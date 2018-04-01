<?php
//每次修改图片,就会ajax到这里,这里先保存图片,
//然后找到含有这个图片字段的comment;然后


include_once 'CONST.php';
include_once 'db_operation.php';

//调试使用,php输出错误信息
//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);

//方便取url后的文件名
function getFileName($url){
  $res = substr(strrchr($url, '/'), count(strrchr($url, '/'))-1);
  //echo $res;
  return $res;
}


$src = $_GET['src'];
$src = getFileName($src);

$uuid = $_GET['sid'];

$db = new MyDB(DATA_BAS E_FILE);




//临时假设输入都合法
//还要检测有没有文件!

//先复制图片,再告诉数据库
date_default_timezone_set('PRC');
//设置时区

//保存入数据库

if($db->hasUUID($uuid)){
  //已经有过上传
  //首先从数据库中剔除
  //然后删除文件

  //1.从数据库中挑出字段删除
  //逗号隔开保存
  $before = $db->hasUUID($uuid)['PIC_LOC'];
  $before = split(',',$before);
  $after = "";
  foreach ($before as $key => $value) {
    $value = getFileName($value);
    //echo $src.'=='.$value;
    if($value != "" && $src != $value){
      $after = $after.STORE_NAME. $value.",";
    }
  }

  $sql =
  "UPDATE ".DATA_TABLE_NAME." set PIC_LOC = '".$after."' where UUID = '".$uuid."';";
  $db->exec($sql);
  // echo $sql;

  //2.从文件系统中删除
  unlink(STORE_DIR.$src);


}
else{
  //错误,不存在的文件不能删除

}

//返回图片地址
//echo $destination;
$db->close();
