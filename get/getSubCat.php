<?php
include "../../class/dataBase.php";
$db=new dataBase();
session_start();
if(isset($_SESSION['loginAdmin']) && $_SESSION['loginAdmin']=true) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (
            isset($_POST['catId']) && $_POST['catId'] != ''
        ) {
            $result = $db::RealString($_POST);
            $catId = $result['catId'];
            $select = $db::Query("SELECT * FROM shopping.category where catId='$catId'",$db::$NUM_ROW);
            if($select==1){
                $getSubCat = $db::Query("SELECT * FROM shopping.subcategory where subCatId='$catId'");
                while ($rowCat = mysqli_fetch_assoc($getSubCat)){
                    $resulter[] = array(
                        'id'=>$rowCat['subId'],
                        'name'=>$rowCat['subName']
                    );
                }

                $call= array("error"=>false);
                $call['subCat']=$resulter;
                echo json_encode($call);
            }
        }
    }
}