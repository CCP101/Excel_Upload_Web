<?php

/**
 * Created by PhpStorm.
 * User : CCP101
 * Date : 2020/2/15
 * Time : 15:49
 */

//$user = $_POST['input'];
//$pwd = $_POST['password'];
//echo $user;

require_once("func.php");
$user = $_POST['input'];
$pwd = $_POST['password'];
$sql = "select * from `nuaajc` where user_id='" . $user . "';";
echo $sql;
$res = connect($sql);
$row = mysqli_fetch_assoc($res);

if (mysqli_num_rows($res) > 0) {

  if ($row['user_pwd'] == $pwd) {
    $_SESSION['user'] = $row['user_id'];
    $id = $row['user_id'];
    $_SESSION['name'] = $row['user_name'];
    $name = $row['user_name'];
    header('Location:upload.php');
  } else {
    $_SESSION['state'] = 3; //密码错误
    header('Location:../index.php');
  }
} else {
  $_SESSION['state'] = 1; //账户不存在
  header('Location:../index.php');
}
echo $_SESSION['name'];
