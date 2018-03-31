<?php
//每次修改图片,就会ajax到这里,这里先保存图片,
//然后找到含有这个图片字段的comment;然后


//调试使用,php输出错误信息
//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);


include_once 'CONST.php';
include_once 'db_operation.php';

$file = $_FILES['img'];
$uuid = $_GET['sid'];


$db = new MyDB(DATA_BASE_FILE);

//临时假设输入都合法
//还要检测有没有文件!

//先复制图片,再告诉数据库
date_default_timezone_set('PRC');


$destination =
  STORE_DIR.
  FILE_NAME_BEGIN.
  uniqid().".".
  substr(strrchr($file['name'], '.'), 1);;

  //移动文件
  move_uploaded_file(
    $file['tmp_name'],
    $destination
  );

//保存入数据库

if($db->hasUUID($uuid)){
  //已经有过上传
  $before = $db->hasUUID($uuid)['PIC_LOC'];

  $sql =
  "UPDATE ".DATA_TABLE_NAME." set PIC_LOC = '".$before.",".$destination."' where UUID = '".$uuid."';";
  $db->exec($sql);
  //echo $sql;
}
else{
  //新的用户上传
  $db->pushData('','',$uuid,'','',$destination);
}

//返回图片地址
echo $destination;

$db->close();

