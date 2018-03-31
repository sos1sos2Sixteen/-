<?php
//!在运行db_init.php前调整
//!注意确保对生成的db文件有读写权限
//修改生成的数据库文件和表的名称
define('DATA_BASE_FILE', 'upload_info.db');
define('DATA_TABLE_NAME', 'upload');

//定义保存图片命名的起始(后面会加上uniqid)
define('FILE_NAME_BEGIN', 'grad_');
//定义保存在数据库中时间戳的格式(字符串)
define('TIME_STORE_FORM', 'Y-m-d H:i:s');

//定义保存图片的地址
//!注意确保对这个目录有读写权限
define('STORE_DIR', './uPics/');
define('STORE_NAME','./uPics');
 ?>
