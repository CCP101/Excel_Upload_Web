<?php
require_once("func.php");
$date = $_POST['date'];
$cid = $_POST['num'];
$class = substr($cid , 0 , 8);
$date_root = "../"."upload/"."$class"."/".$date."/";


$temp = explode(".", $_FILES['file']["name"]);
$allowedExts = array("xls", "xlsx");
$extension = end($temp);
if ($_FILES['file']["size"] < 1000000000) {
    if ($_FILES['file']["error"] > 0) {
        echo "错误：: " . $_FILES['file']["error"] . "<br>";
        exit();
    } else {
        if (file_exists($date_root . $cid . "." . $extension)) {
            unlink($date_root . $cid . "." . $extension);
        }
        // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
        // echo $_FILES['file']["tmp_name"];
        move_uploaded_file($_FILES['file']["tmp_name"], $date_root . $cid . "." . $extension);
    }
} else {
    echo "文件过大";
    exit();
}

$sql = "INSERT INTO `nuaajc-record` 
        ( `user_id`, `date`, `submit` )
        VALUES
        ( '" . $cid . "', '" . $date . "', 1 )";
$res = connect($sql);
echo "上传成功";
