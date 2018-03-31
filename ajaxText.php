<?php


//调试使用,php输出错误信息
// ini_set("display_errors", "On");
// error_reporting(E_ALL | E_STRICT);


include_once 'CONST.php';
include_once 'db_operation.php';


$uuid = $_GET['sid'];
$requireZcode = $_GET['requirezcode'];
$comment = $_POST['comment'];
$telephone = $_POST['telephone'];



foreach ($_POST as $key => $value) {
  echo $key.'->'.$value.'   ';
}


// 方便测试
require_once('interface.php');
$nickName = getNickName();
$account = getAccount();
//这个两个函数如果返回null则会直接把null值填入数据库
//$nickName = 'place_holder_nick_name';
//$account = "place_holder_account";

$db = new MyDB(DATA_BASE_FILE);

//保存入数据库

if($db->hasUUID($uuid)){
  //已经有过上传
  //更新文本
  $sql = "";
  date_default_timezone_set('PRC');
  if($requireZcode){
    $sql =
    "UPDATE ".DATA_TABLE_NAME." set
    COMMENT = '".$comment."',
    TELE = '".$telephone."',
    NICKNAME = '".$nickName."',
    ACC_NAME = '".$account."',
    ZCODE = '".$_POST['zcode']."',
    ADDR = '".$_POST['addr']."',
    TIME_COMMIT = '".time()."'
     where UUID = '".$uuid."';";
  }
  else{
    $sql =
    "UPDATE ".DATA_TABLE_NAME." set
    COMMENT = '".$comment."',
    TELE = '".$telephone."',
    NICKNAME = '".$nickName."',
    ACC_NAME = '".$account."',
    TIME_COMMIT = '".time()."'
     where UUID = '".$uuid."';";
  }

  //echo $sql;

   $db->exec($sql);

}
else{
  //新的用户上传
  //讲道理不应该可以这么传
  $db->pushData($account,$nickName,$uuid,$comment,$telephone,"");
}

$db->close();

?>

