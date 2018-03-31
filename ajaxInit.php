<?php


//调试使用,php输出错误信息
// ini_set("display_errors", "On");
// error_reporting(E_ALL | E_STRICT);


include_once 'CONST.php';
include_once 'db_operation.php';


$uuid = $_GET['sid'];
$requireZcode = $_GET['requirezcode'];

$db = new MyDB(DATA_BASE_FILE);


//echo json_encode(array('commit'=>"加法减肥",'dog'=>40));
//保存入数据库

if($db->hasUUID($uuid)){
  //已经有过上传
  //更新文本
  $result = $db->hasUUID($uuid);

  echo json_encode($result);

}
else{
  //新的用户首次登陆
  //讲道理不应该可以这么传
  // echo {}

}

$db->close();


 ?>
