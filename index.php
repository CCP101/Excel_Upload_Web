<?php
/**
 * Created by PhpStorm.
 * User : CCP101
 * Date : 2020/2/15
 * Time : 16:05
 */

require_once("php/func.php");

if (isset($_SESSION['state'])) {
  login($_SESSION['state']);
} else
  login(0);

