<?php
/**
 * Created by PhpStorm.
 * User: amirjahangiri
 * Date: 2019-05-10
 * Time: 21:40
 */
include "../class/dataBase.php";
$db = new dataBase();
$CheckedArray = array("mobile","token","appVersion","device");
if(
    $db::issetParams($_POST,$CheckedArray)
    &&
    $db::emptyParams($_POST,$CheckedArray)
) {
    $result = $db::RealString($_POST);
    $appVersion = $result['appVersion'];
    $device = $result['device'];
    if(!$db::checkVersion($device,$appVersion)){
        $version = false;
        $call = array("error"=>true,"version"=>false);
        echo json_encode($call);
        return;
    }
    $mobile = $result['mobile'];
    $token = $result['token'];
    $token = substr($token,0,-6);
    $password = $db::HashPassword($token);
    $selectUser = $db::Query("
      SELECT userId FROM user where user.userMobile='$mobile'
      AND userPassword='$password'
    ");
    if(mysqli_num_rows($selectUser)==1){
        $row = mysqli_fetch_assoc($selectUser);
        $userId = $row['userId'];
    }else{
        $call = array("error"=>true,"version"=>true,"login"=>false);
        echo json_encode($call);
        return;
    }
}
?>