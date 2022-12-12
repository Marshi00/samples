<?php
/**
 * Created by PhpStorm.
 * User: amirjahangiri
 * Date: 2019-05-24
 * Time: 17:49
 */

class request
{
    public static function sendCode(){
        include "dataBase.php";
        $db = new dataBase();
        $CheckedArray = array("mobile","appVersion","device");
        if(
            $db::issetParams($_POST,$CheckedArray)
            &&
            $db::emptyParams($_POST,$CheckedArray)
        ){
            $result = $db::RealString($_POST);
            $mobile = $result['mobile'];
            $appVersion = $result['appVersion'];
            $device = $result['device'];
            if(!$db::checkVersion($device,$appVersion)){
                $version=false;
                $call=array("error"=>true,"version"=>false);
                echo json_encode($call);
                return;
            }
            $select = $db::Query("SELECT * FROM user WHERE userMobile='$mobile'",$db::$NUM_ROW);
            $resultRow = $db::Query("SELECT * FROM user WHERE userMobile='$mobile'",$db::$RESULT_ARRAY);
            if($select==1){
                $userId = $resultRow['userId'];
                $type = "user";
                $code = rand(10000,99999);
                $update = $db::Query("UPDATE user Set userCode='$code' where userId='$userId'");
                $call = array("type"=>$type,"error"=>false,"version"=>true);
                $db::sendSms($code,$mobile);
                echo json_encode($call);
                return;
            }else{
                $select2 = $db::Query("SELECT * FROM location WHERE locationPhoneNumber='$mobile'",$db::$NUM_ROW);
                $resultRow2 = $db::Query("SELECT * FROM location WHERE locationPhoneNumber='$mobile'",$db::$RESULT_ARRAY);
                if($select2==1){
                    $locationId = $resultRow2['locationId'];
                    $type="location";
                    $code = rand(10000,99999);
                    $update = $db::Query("
            UPDATE location Set locationCode='$code' 
            where locationId='$locationId'"
                    );
                    $call = array("type"=>$type,"error"=>false,"version"=>true);
                    echo json_encode($call);
                    $db::sendSms($code,$mobile);
                    return;
                }else{
                    $id = $db::Gid();
                    $date = $db::GetDate();
                    $time = $db::GetTime();
                    $code = rand(10000,99999);
                    $insert = $db::Query(
                        "INSERT INTO user
          (userId,userMobile,userRegDate,userRegTime,userCode)
          VALUES ('$id','$mobile','$date','$time','$code')"
                    );
                    $call = array("type"=>"user","error"=>false,"version"=>true);
                    $db::sendSms($code,$mobile);
                    echo  json_encode($call);
                    return;
                }
            }
        }
    }
    public static function checkCode(){
        include "dataBase.php";
        $db = new dataBase();
        if (
            isset($_POST['mobile']) &&
            isset($_POST['code']) &&
            isset($_POST['appVersion']) &&
            isset($_POST['device']) &&
            $_POST['mobile']!='' &&
            $_POST['code']!='' &&
            $_POST['appVersion']!='' &&
            $_POST['device']!=''
        ) {
            $result = $db::RealString($_POST);
            $mobile = $result['mobile'];
            $code = $result['code'];
            $appVersion = $result['appVersion'];
            $device = $result['device'];
            if(!$db::checkVersion($device,$appVersion)){
                $version=false;
                $call=array("error"=>true,"version"=>false);
                echo json_encode($call);
                return;
            }

                $selectNum = $db::Query("
                        SELECT userId FROM user where 
                              user.userMobile='$mobile'
                          AND 
                              user.userCode='$code'", $db::$NUM_ROW);
            $selectNumArray = $db::Query("
                        SELECT * FROM user where 
                              user.userMobile='$mobile'
                          AND 
                              user.userCode='$code'", $db::$RESULT_ARRAY);
                if ($selectNum == 1) {
                    $password = $db::randomString(10);
                    $encode = hash("md5", $password);
                    //For Client
                    $encode2 = hash("md5", $encode);
                    //For Server
                    $va_password = $db::HashPassword($encode2);
                    //Encode MD5 For security
                    $update = $db::Query(
                        "UPDATE user set 
                        userPassword='$va_password',userCode=''
                        where userCode='$code'
                        AND userMobile='$mobile'"
                    );
                    if($selectNumArray['userName']==""){
                        $GotoSubmitPage = true;
                        $selectCountGem = 0;
                        $name="";
                        $gender="";
                    }else{
                        $GotoSubmitPage = false;
                        $userId = $selectNumArray["userId"];
                        $selectCountGem =  $db::Query("SELECT * FROM gemUser where
                            gemUserUserId='$userId' AND gemStatus='1'",$db::$NUM_ROW);
                        $name = $selectNumArray['userName'];
                        $gender = $selectNumArray['userGender'];
                    }
                    $call =
                    array(
                        "error" => false,
                        "token" => $encode,
                        "version" => true,
                        "goToSubmit"=>$GotoSubmitPage,
                        "countGem"=>$selectCountGem,
                        "name"=>$name,
                        "gender"=>$gender
                    );
                    echo json_encode($call);
                } else {
                    $call = array("error" => true, "MSG" => "کد وارد شده اشتباه است", "version" => true);
                    echo json_encode($call);
            }
        }
    }
    public static function submit(){
        include "dataBase.php";
        $db = new dataBase();
        if(
            isset($_POST['mobile']) &&
            isset($_POST['token']) &&
            isset($_POST['gender']) &&
            isset($_POST['name']) &&
            isset($_POST['invitedMobile']) &&
            isset($_POST['appVersion']) &&
            isset($_POST['device']) &&
            $_POST['device']!='' &&
            $_POST['appVersion']!='' &&
            $_POST['name']!='' &&
            $_POST['gender']!='' &&
            $_POST['token']!='' &&
            $_POST['mobile']!=''
        ){



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
            $gender = $result['gender'];
            $name = $result['name'];
            $invitedMobile = $result['invitedMobile'];
            $selectUser = $db::Query("
              SELECT userId FROM user where user.userMobile='$mobile'
              AND userPassword='$password'
              ");
            if(mysqli_num_rows($selectUser)==1){
                if($invitedMobile!=""){
                    $select = $db::Query("SELECT * FROM user where userMobile='$invitedMobile'");
                    if(mysqli_num_rows($select)==1){
                        $rowSelect = mysqli_fetch_assoc($select);
                        $userId = $rowSelect['userId'];
                        $code = rand(2000,4000);
                        $GemId=$db::GenerateId() ;
                        $date = $db::GetDate();
                        $time = $db::GetTime();
                        $gemUserId = $db::GenerateId();
                        $Insert = $db::Query("
                          INSERT INTO gem
                            (gemId, gemCode, gemGeneratedBy, gemFor, gemRegDate, gemRegTime) 
                          VALUES ('$GemId','$code','admin','دعوت','$date','$time')"
                                );
                        $insert2 = $db::Query("
                        INSERT INTO gemUser (gemUserId, gemUserUserId, gemUserGemId, gemStatus) 
                        VALUES ('$gemUserId','$userId','$GemId','1')"
                         );
                    }else{
                        $call = array("error"=>true,"version"=>true,"login"=>true,"MSG"=>"کاربری با این شماره تلفن در سیستم نیست");
                        echo json_encode($call);
                        return;
                    }
                }
                $update = $db::Query("UPDATE user SET userGender='$gender',userName='$name' where user.userMobile='$mobile'
         AND userPassword='$password'");
                $call = array("error"=>false,"version"=>true,"login"=>true);
                echo json_encode($call);
                return;
            }else{
                $call = array("error"=>true,"version"=>true,"login"=>false);
            }
        }else{
            $call = array("error"=>true,"version"=>true,"login"=>true,"MSG"=>"خطایی رخ داده است.");
            echo json_encode($call);
            return;
        }

    }
    public static function challengeList(){
        $db="";
        $userId="";
        include "../inc/checkUser.php";


        $select = $db::Query("SELECT * from challenge where challengeStatus='1'");
        while ($rowChallenge = mysqli_fetch_assoc($select)){
            $challengeId = $rowChallenge['challengeId'];
            $checkUser = $db::Query("SELECT * FROM challengUser where challengUserUserId='$userId' AND challengUserChallengeId='$challengeId'",$db::$NUM_ROW);
            $checkUser==0?$in=false:$in=true;
            $callArray[] = array(
                "userInSide"=>$in,
                "id"=>$rowChallenge['challengeId'],
                "name"=>$rowChallenge['challengeName'],
                "lat"=>$rowChallenge['challengeLat'],
                "lng"=>$rowChallenge['challengeLng'],
                "address"=>$rowChallenge['challengeAddress'],
                "price"=>$rowChallenge['challengePriceGem'],
                "winner"=>$rowChallenge['challengeWinnerGem'],
                "img"=>$rowChallenge['challengeImg'].'.jpg',
                "desc"=>$rowChallenge['challengeDesc'],
                "time"=>$db::G2J($rowChallenge['challengeStartDate'])

            );
        }
        if (!empty($callArray)) {
            $call = array("error" => false, "version" => true, "login" => true,"result"=>true);
            $call['challenge'] = $callArray;
        } else {
            $call = array("error" => false, "version" => true, "login" => true,"result"=>false,"MSG"=>"موردی یافت نشد.");
            $call['challenge'] = array();
        }
        echo json_encode($call);
    }
    public static function acceptChalleng(){
        $db="";
        $userId="";
        include "../inc/checkUser.php";
        $checkArray = array("challengeId");
        if($userId!=''){
        if($db::issetParams($_POST,$checkArray) && $db::emptyParams($_POST,$checkArray)){
            $result = $db::RealString($_POST);
            $challengeId = $result['challengeId'];
            $selectChallenge = $db::Query("Select * from challenge where challengeId='$challengeId' AND challengeStatus='1'");
            $selectChallengeUser = $db::Query("SELECT * FROM challengUser where challengUserId='$userId' AND challengUserChallengeId='$challengeId'");
            if(mysqli_num_rows($selectChallenge)>0){
                $call = array("error"=>true,"version"=>true,"login"=>true,"MSG"=>'شما قبلا در این چالش شرکت کرده اید');
                echo json_encode($call);
                return;
            }
            if(mysqli_num_rows($selectChallenge)==1){
                $challengeGemRow = mysqli_fetch_assoc($selectChallenge);
                $gemPrice= $challengeGemRow['challengePriceGem'];
                $selectGem = $db::Query("SELECT * FROM gemUser where gemStatus='1' AND gemUserUserId='$userId'",$db::$NUM_ROW);
                if($selectGem >= $gemPrice){
                    $selectGem = $db::Query("SELECT * FROM gemUser where gemStatus='1' AND gemUserUserId='$userId' LIMIT $gemPrice");
                    while ($rowGemExt = mysqli_fetch_assoc($selectGem)) {
                        $gemId = $rowGemExt['gemUserId'];
                        $updatedGame = $db::Query("UPDATE gemUser set gemStatus='0' where gemStatus='1' AND gemUser.gemUserId='$gemId'");
                    }
                        $challengeIdUser = $db::GenerateId();
                        $insert = $db::Query("INSERT INTO challengUser (challengUserId, challengUserUserId, challengUserChallengeId) VALUES ('$challengeIdUser','$userId','$challengeId')");
                        $call = array("error"=>false,"version"=>true,"login"=>true);

                }else{
                    $call = array("error"=>true,"version"=>true,"login"=>true,"MSG"=>'تعداد الماس شما کمتر از تعداد مورد نیاز است');
                }

            }else{
                $call = array("error"=>true,"version"=>true,"login"=>true,"MSG"=>"چالش به درستی انتخاب نشده است");
            }
            echo json_encode($call);
        }
        }

    }
    public static function InsertGem(){

        $db="";
        $userId="";
        include "../inc/checkUser.php";
        if(
            isset($_POST['gemCode'])
        &&
            $_POST['gemCode']!=''
        ){
            $GemResult = $db::RealString($_POST);
            $gemCode = $GemResult['gemCode'];
            $gemCodeArray = $db::Query("SELECT gemId,gemCount FROM gem where gem.gemCode='$gemCode'",$db::$RESULT_ARRAY);
            $gemId = $gemCodeArray['gemId'];
            $gemCount = $gemCodeArray['gemCount'];
            if($gemId!='') {
                $selectGemUser = $db::Query("SELECT * FROM gemUser where gemUserGemId='$gemId'", $db::$NUM_ROW);
                if ($selectGemUser == 0) {
                    for($i=0; $i<$gemCount; $i++){
                        $id= $db::GenerateId();
                        $insert = $db::Query("INSERT INTO gemUser (gemUserId, gemUserUserId, gemUserGemId, gemStatus)
                      VALUES ('$id','$userId','$gemId','1')");
                    }
                    $selectCountGem =  $db::Query("SELECT * FROM gemUser where
                            gemUserUserId='$userId' AND gemStatus='1'",$db::$NUM_ROW);
                    $call =array("error"=>false,"version"=>true,"login"=>true,"countGem"=>$selectCountGem,"test"=>$gemCount);
                    echo json_encode($call);
                } else {
                    $call =array("error"=>true,"version"=>true,"login"=>true,"MSG"=>"این کد قبلا توسط کاربر دیگری وارد شده است");
                    echo json_encode($call);
                }
            }else{
                $call =array("error"=>true,"version"=>true,"login"=>true,"MSG"=>"کد به درستی وارد نشده است");
                echo json_encode($call);
            }




        }




    }
    public static function listLocation()
    {

        $userId = "";
        $db = "";
        include "../inc/checkUser.php";
        if ($userId != '') {
            if (!isset($_POST['searchText']) || $_POST['searchText'] == '') {
                $selectLocation = $db::Query("SELECT locationAddress,locationFullname,locationLat,locationLng
FROM location where location.locationStatus='1' AND locationCountGem>0");
                while ($rowLocation = mysqli_fetch_assoc($selectLocation)) {
                    $callArray[] = array(
                        'address' => $rowLocation['locationAddress'],
                        'name' => $rowLocation['locationFullname'],
                        'lat' => $rowLocation['locationLat'],
                        'lng' => $rowLocation['locationLng']
                    );
                }
                if (!empty($callArray)) {
                    $call = array("error" => false, "version" => true, "login" => true, "result" => true);
                    $call['location'] = $callArray;
                } else {
                    $call = array("error" => false, "version" => true, "login" => true, "result" => false, "MSG" => "موردی یافت نشد.");
                    $call['location'] = array();
                }
                echo json_encode($call);
            } else {
                $text = mysqli_real_escape_string($db::connection(), $_POST['searchText']);
                $selectLocation = $db::Query("SELECT locationAddress,locationFullname,locationLat,locationLng
    FROM location where location.locationStatus='1' AND locationCountGem>0 AND locationFullname LIKE '%$text%'");
                while ($rowLocation = mysqli_fetch_assoc($selectLocation)) {
                    $callArray[] = array(
                        'address' => $rowLocation['locationAddress'],
                        'name' => $rowLocation['locationFullname'],
                        'lat' => $rowLocation['locationLat'],
                        'lng' => $rowLocation['locationLng']
                    );
                }
                if (!empty($callArray)) {
                    $call = array("error" => false, "version" => true, "login" => true, "result" => true);
                    $call['location'] = $callArray;
                } else {
                    $call = array("error" => false, "version" => true, "login" => true, "result" => false, "MSG" => "موردی یافت نشد.");
                    $call['location'] = array();
                }
                echo json_encode($call);
            }
        }
    }
}