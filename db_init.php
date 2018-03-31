<?php
//这是初始化数据库文件的脚本;在运行前可以在CONST.php中设置数据库文件名
//!!!生成的数据库文件可能没有写入权限,
//!!!如果那样,需要手动允许服务器对文件的写入权限

include_once 'CONST.php';
include_once 'db_operation.php';

$db = new MyDB(DATA_BASE_FILE);

if($db){
  echo 'ceate database '.DATA_BASE_FILE.' successfully.';
  echo PHP_EOL;

  $sql_create = 'CREATE TABLE '.DATA_TABLE_NAME.'
    (
      UUID TEXT PRIMARY KEY,
      ACC_NAME TEXT,
      TELE TEXT,
      COMMENT TEXT,
      PIC_LOC TEXT,
      TIME_COMMIT TEXT,
      NICKNAME TEXT,
      GRAD_YEAR TEXT,
      ZCODE TEXT,
      ADDR TEXT
    );';

  $db->exec($sql_create);
  if(true){
    echo 'create table: '.DATA_TABLE_NAME.' successfully.\n';
    echo PHP_EOL;
  }
  else{
    echo 'table failed';
    echo PHP_EOL;
  }

  $db->close();
}
else{
  echo 'data base creation failed.';echo PHP_EOL;
}


 ?>
