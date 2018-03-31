<?php
//调试使用,php输出错误信息
//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);

include_once 'CONST.php';
//继承的操作sqlite数据库文件的类;
//针对db_init中建的表提供写入操作
class MyDB extends SQLite3
{
  function __construct($db_nm)
  {
    $this->open($db_nm);
  }

  function pushData(
    $acc_str,
    $nickName,
    $uuid,
    $comment_str,
    $tele_str,
    $location_str,
    $gradYear = '2017',
    $zcode = '',
    $addr = '')
    {
    date_default_timezone_set('PRC');
    $time_stamp_form = TIME_STORE_FORM;
    $sql = "INSERT INTO ".DATA_TABLE_NAME." (UUID,ACC_NAME,TELE,COMMENT,PIC_LOC,TIME_COMMIT,NICKNAME,GRAD_YEAR,ZCODE,ADDR)
    VALUES (
      '".$uuid."',
      '".$acc_str."',
      '".$tele_str."',
      '".$comment_str."',
      '".$location_str."',
      '".time()."',
      '".$nickName."',
      '".$gradYear."',
      '".$zcode."',
      '".$addr."'
    );";
    // echo date($time_stamp_form,$now)."<br />";
    // echo $sql.'<br />';
    $this->exec($sql);
  }

  // //得到离flag_id最近的一条
  // function retriveData($flag_id){
  //   $sql = "SELECT * FROM upload WHERE ID <= ".$flag_id." order by ID desc limit 1;";
  //   //echo $sql."<br />";
  //
  //   $query_res = $this->query($sql);
  //
  //   $res = $query_res->fetchArray(SQLITE3_ASSOC);
  //
  //   return $res;
  //
  // }

  function hasUUID($uuid){
    $sql = "SELECT * FROM ".DATA_TABLE_NAME." WHERE UUID = '".$uuid."' ;";
    //echo $sql;
    $query_res = $this->query($sql);
    $res = $query_res->fetchArray(SQLITE3_ASSOC);
    return $res;
  }
}




 ?>
