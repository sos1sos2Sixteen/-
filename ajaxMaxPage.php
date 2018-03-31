<?php


include_once 'CONST.php';
include_once 'db_operation.php';

$db = new MyDB(DATA_BASE_FILE);

$sql = "select count(*) from ".DATA_TABLE_NAME."
where length(comment) > 0 or length(pic_loc) > 0;";

$query_res = $db -> query($sql);
$res = $query_res->fetchArray(SQLITE3_ASSOC);

echo ceil($res["count(*)"]/10);

$db -> close();

?>
