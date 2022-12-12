<?php

//include "active3.php";
//$a = new Active3();
//$conn=$a->Connect();
//if(
//    isset($_POST['phone']) &&
//    isset($_POST['password'])
//){
//
//    if(!$conn){
//        $Note['conn']=false;
//        echo json_encode($call);
//        return;
//    }
//    else{
//        $Note['conn']=true;
//    }
//
//    $phoneNumber = mysqli_real_escape_string($conn,$_POST['phone']);
//    $password = $a->hash1(mysqli_real_escape_string($conn,$_POST['password']));
//
//    $selectUser = mysqli_query($conn,"SELECT * FROM user WHERE
//         PhoneNumber= '$username' AND password = '$password'");
//
//    if(mysqli_num_rows($selectUser)==0){
//        $Note['login']=false;
//
//    }else{
//        $getUserId = mysqli_fetch_assoc($selectUser);
//        $phoneNumberKey = $getUserId['PhoneNumber'];
//        $nameUser = $getUserId['name'];
//        $Note['login']=true;
//        $Note['name']=$getUserId['name'];
//    }
//}else{
//    $Note['login']=false;
//}
//nqtioncoee3='aweqw'.$a
//AAAAAAAAAAAAAAAAAAAAAAAA
//$phoneNumberKey = '';
//include "checkLoginUser.php";

//if ($Note['login'] == true) {
//        $queryGetPreUserInfo = $A->Query("SELECT  * from user where PhoneNumber='$phoneNumberKey'");

//        $fetchQueryGetPreUserInfo = mysqli_fetch_assoc($queryGetPreUserInfo);


//        if (isset($_POST['phoneNumber']) && $_POST['phoneNumber'] !=''){
//            $phoneNumber = mysqli_real_escape_string($conn,$_POST['phoneNumber']);
//        }else{
//            $phoneNumber = $fetchQueryGetPreUserInfo['PhoneNumber'];
//        }
