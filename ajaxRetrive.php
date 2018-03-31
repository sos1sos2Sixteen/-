<?php

include_once 'CONST.php';
include_once 'db_operation.php';



class RetrDB extends MyDB{
  function RetrData($flag){
    //返回时间在$flag之前（不包括flag）的
    $sql =
    "SELECT COMMENT,PIC_LOC,TIME_COMMIT FROM ".DATA_TABLE_NAME." WHERE (TIME_COMMIT < ".$flag.
    ") AND (length(COMMENT) > 0 OR length(PIC_LOC) > 0) ORDER BY TIME_COMMIT DESC LIMIT 10;";
    // $sql =
    // "SELECT COMMENT,PIC_LOC,TIME_COMMIT FROM ".DATA_TABLE_NAME." WHERE (TIME_COMMIT <= ".$flag.
    // ")  ORDER BY TIME_COMMIT DESC LIMIT 10;";

    $query_res = $this -> query($sql);
    $res = [];
    while($res[] = $row = $query_res->fetchArray(SQLITE3_ASSOC));
    return $res;
  }

  function retrDataPerPage($page){
    $page_size = 5;
    $sql =
    "SELECT COMMENT,PIC_LOC,TIME_COMMIT,UUID FROM ".DATA_TABLE_NAME." WHERE (length(COMMENT) > 0 OR length(PIC_LOC) > 0)
    ORDER BY TIME_COMMIT DESC
    LIMIT ".$page_size." OFFSET ". ($page - 1) * $page_size .";";

    $query_res = $this -> query($sql);
    $res = [];
    while($res[] = $row = $query_res->fetchArray(SQLITE3_ASSOC));
    return $res;
  }
}

$db = new RetrDB(DATA_BASE_FILE);

$lastStamp = $_GET['lastStamp'];

// $result = $db -> RetrData($lastStamp);
$result = $db -> retrDataPerPage($lastStamp);

$db -> close();

echo json_encode($result);




?>
