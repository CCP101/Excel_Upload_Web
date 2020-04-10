<?php

/**
 * Created by PhpStorm.
 * User : CCP101
 * Date : 2020/2/15
 * Time : 15:50
 */

require_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';
header("content-type:text/html; charset=utf-8");

if (!isset($_SESSION)) {
    session_start();
}

/**
 * 此函数为实际登陆页面 请忽略编辑器地址警告 实际在上一层运行
 * @param $state
 */
function login($state)
{
    ?>
<html lang="zn">

<head>
    <meta charset="utf-8">
    <title>用户登录</title>
    <link rel="apple-touch-icon" href="../src/icon.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="stylesheet" href="../src/css/font-awesome.css">
    <link href="../src/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
        }

        body {
            display: -ms-flexbox;
            display: -webkit-box;
            display: flex;
            -ms-flex-align: center;
            -ms-flex-pack: center;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }

        .form-signin .checkbox {
            font-weight: 400;
        }

        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }

        .form-signin .form-control:focus {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>

<body class="text-center">
    <div class="container">
        <form class="form-signin" action="php/login.php" method="post">
            <img class="mb-4" src="../src/favicon.ico" alt="" width="100" height="100">
            <h1 class="h3 mb-3 font-weight-normal">
                账号登录
            </h1>
            <label for="inputEmail" class="sr-only">

            </label>
            <label for="input">学号</label>
            <input type="text" id="input" name="input" class="form-control" placeholder="学号/工号" required="required">
            <label for="inputPassword" class="sr-only">密码</label>
            <input type="password" id="inputPassword" name="password" class="form-control" placeholder="密码"
                required="required">
            <div class="checkbox mb-3">
                <label>
                </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">登录</button>
            <p class="mt-5 mb-2 text-muted">© CCP101 © doublezhuang101</p>
        </form>
    </div>
    <script type="text/javascript" color="255,0,0" pointColor="255,0,0" opacity='0.7' zIndex="-2" count="150"
        src="../src/js/canvas-nest.js"></script>
    <script type="text/javascript" src="../src/js/canvas-nest.umd.js"></script>
    <script src="../src/js/jquery.min.js"></script>
    <script src="../src/js/popper.min.js"></script>
    <script src="../src/js/bootstrap.min.js"></script>

    <?php if ($state == 1) { ?>
    <script type="text/javascript">
        alert("账户不存在");
    </script>
    <?php }
    if ($state == 3) { ?>
    <script type="text/javascript">
        alert("密码错误");
    </script><?php }
    $_SESSION['state'] = 0; ?>
</body>

</html>
<?php
}

/**
 * 此函数用于封装连接MySQL的函数，可以修改IP连远程MySQL数据库
 * @param $sql
 * @return bool|mysqli_result
 */
function connect($sql)
{
    //支持MYSQL 8
    $hostname = "127.0.0.1";
    $database = "2020_competion";
    $username = "root";
    $password = "";
    //请在PHP.ini里面打开extension=mysqli
    $connect = mysqli_connect($hostname, $username, $password, $database);
    if (!$connect) {
        die("连接失败: " . mysqli_connect_error());
    }
    $sql_set_coding = "SET NAMES 'UTF8'";
    //设置中文，不然姓名全是乱码
    //$connect->query($sql_set_coding);
    $res = mysqli_query($connect, $sql_set_coding) or die("命令出错");
    if ($res = mysqli_query($connect, $sql)) {
//        echo "新记录插入成功";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connect);
    }
    mysqli_close($connect);
    //不关会爆线程
    return $res;
}

/**
 * 此函数用于将选项转换成数据库tinyint类型
 * @param $ans
 * @return int
 */
function char_to_int($ans)
{
    switch ($ans) {
    case 'A':
        return 1;
        break;
    case 'B':
        return 2;
        break;
    case 'C':
        return 4;
        break;
    case 'D':
        return 8;
        break;
    }
}

/**
 * 此函数用于将tinyint类型逆转换
 * @param $ans
 * @return string
 */
function int_to_char($ans)
{
    switch ($ans) {
        case 1:
            return 'A';
            break;
        case 2:
            return 'B';
            break;
        case 4:
            return 'C';
            break;
        case 8:
            return 'D';
            break;
    }
}

/**
 * 此函数用于递归将多选项转换成数据库tinyint类型
 * @param $ans
 * @param $n
 * @param $mk
 * @return mixed
 */
function multiple_to_int($ans, $n, $mk)
{ // 生成多选题答案掩码
    if ($n == strlen($ans)) {
        return $mk;
    }
    switch ($ans[$n]) {
        case 'A':
            return multiple_to_int($ans, $n + 1, 1 + $mk);
            break;

        case 'B':
            return multiple_to_int($ans, $n + 1, 2 + $mk);
            break;

        case 'C':
            return multiple_to_int($ans, $n + 1, 4 + $mk);
            break;

        case 'D':
            return multiple_to_int($ans, $n + 1, 8 + $mk);
            break;
    }
}
function judge_ans($text)
{
    if ($text == null) {
        $text = "未作答";
    } elseif ($text == 1) {
        $text = "正确";
    } elseif ($text == 2) {
        $text = "错误";
    } elseif ($text == 0) {
        $text = "未作答";
    }
    return $text;
}
/**
 * 此函数用于递归将多选项tinyint类型逆转换
 * @param $mask
 * @return string
 */
function int_to_multiple($mask)
{
    $i = 8;
    $ans = '';
    while ($mask != 0) {
        $mask -= $i;
        if ($mask < 0) {
            $mask += $i;
        } else {
            $ans = $ans . int_to_char($i);
        }
        $i /= 2;
    }
    $ans = strrev($ans);
    return $ans;
}

/**
 * 此函数用于生成随机数
 * @param $num
 * @return mixed
 */
function generate_random_numbers($num)
{
    $vi = array_fill(0, $num, 0);
    $cnt = 0;
    while ($cnt != $num) {
        $i = mt_rand() % $num;
        if (!$vi[$i]) {
            $vi[$i] = 1;
            $sqe[$cnt] = $i + 1;
            $cnt++;
        } else {
            $i = ($i + 1) % $num;
        }
    }
    return $sqe;
}

/**
 * sheet1单选存入数据库
 * @param $cid
 * @param $sheet
 */
function exam_single_choose($cid, $sheet)
{
    $highestRow = $sheet->getHighestRow();
    $exam_table_name = $cid."-exam-question";
    $exam_answer_table_name = $cid."-exam-answer";
    $sq = "1";

    for ($row = 1; $row <= $highestRow; ++$row) {
        $ans = char_to_int($sheet->getCell("F" . $row)->getValue());
        $sql_input = "INSERT INTO `2020_competion`.`".$exam_table_name."` 
                ( `tyid`, `pid`, `content`, `ans` )
                VALUES
                ( 'sc" . $row . "'," . $sq . ",'" .
                    $sheet->getCell("A" . $row)->getValue() . "'," . $ans . ")";
        connect($sql_input);
        $col = 'A';
        for ($i = 1; $i != 5; ++$i) {
            $msk = 1 << ($i - 1);
            $col++;
            $sql_input_answer = "INSERT INTO `2020_competion`.`".$exam_answer_table_name."` 
                ( `tyid`, `cid`, `pid`, `content`, `mask` )
                VALUES
                ( 'sc" . $row . "'," . $i . "," . $sq . ",'" .
                $sheet->getCell($col . $row)->getValue() . "'," . $msk . ")";
            connect($sql_input_answer);
        }
    }
    return $highestRow;
}

/**
 * sheet2多选存入数据库
 * @param $cid
 * @param $sheet
 */
function exam_multiple_choose($cid, $sheet)
{
    $highestRow = $sheet->getHighestRow();
    $exam_table_name = $cid."-exam-question";
    $exam_answer_table_name = $cid."-exam-answer";
    $sq = "1";

    for ($row = 1; $row <= $highestRow; ++$row) {
        $ans = multiple_to_int($sheet->getCell("F" . $row)->getValue(), 0, 0);
        $sql_input = "INSERT INTO `2020_competion`.`".$exam_table_name."`
            ( `tyid`, `pid`, `content`, `ans` )
            VALUES
            ( 'mc" . $row . "'," . $sq . ",'" .
            $sheet->getCell("A" . $row)->getValue() . "'," . $ans . ")";
        connect($sql_input);
        $col = 'A';
        for ($i = 1; $i != 5; ++$i) {
            $msk = 1 << ($i - 1);
            $col++;
            $sql_input_answer = "INSERT INTO `2020_competion`.`".$exam_answer_table_name."`
                ( `tyid`, `cid`, `pid`, `content`, `mask` )
                VALUES
                ( 'mc" . $row . "'," . $i . "," . $sq . ",'" .
                $sheet->getCell($col . $row)->getValue() . "'," . $msk . ")";
            connect($sql_input_answer);
        }
    }
    return $highestRow;
}

/**
 * sheet3判断存入数据库
 * @param $cid
 * @param $sheet
 */
function exam_judge_choose($cid, $sheet)
{
    $highestRow = $sheet->getHighestRow();
    $exam_table_name = $cid."-exam-question";
    $exam_answer_table_name = $cid."-exam-answer";
    $sq = "1";
    for ($row = 1; $row <= $highestRow; ++$row) {
        $ans = char_to_int($sheet->getCell("B" . $row)->getValue());
        $sql_input = "INSERT INTO `2020_competion`.`".$exam_table_name."`
            ( `tyid`, `pid`, `content`, `ans` )
            VALUES
            ( 'jd" . $row . "'," . $sq . ",'" .
            $sheet->getCell("A" . $row)->getValue() . "'," . $ans . ")";
        connect($sql_input);
    }
    return $highestRow;
}

/**
 * 读取excel表格内容并存如数据库
 * @param $course_id
 * @throws PHPExcel_Exception
 * @throws PHPExcel_Reader_Exception
 */
function exam_create($course_id)
{
    $cid = $course_id;
    error_reporting(E_ALL);
    date_default_timezone_set('Asia/ShangHai');
    if (!file_exists("../../upload/quiz/" . $course_id . ".xlsx")) {
        echo("not found quiz" . $course_id . ".xlsx");
    }
    $reader = PHPExcel_IOFactory::createReader('Excel2007');
    $PHPExcel = $reader->load("../../upload/quiz/" . $course_id . ".xlsx"); // 载入excel文件
    $exam_table_name = $cid."-exam-question";
    $exam_answer_table_name = $cid."-exam-answer";
    $sql_check = "DROP TABLE IF EXISTS `".$exam_table_name."`";
    connect($sql_check);
    $sql_create_table = "CREATE TABLE `".$exam_table_name."` 
        (`tyid` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        `pid` tinyint(3) unsigned NOT NULL,
        `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        `ans` tinyint(3) unsigned NOT NULL,
        PRIMARY KEY (`tyid`,`pid`)) 
        ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE= utf8_unicode_ci;";
    connect($sql_create_table);
    $sql_check_answer = "DROP TABLE IF EXISTS `".$exam_answer_table_name."`";
    connect($sql_check_answer);
    $sql_create_table_answer = "CREATE TABLE `".$exam_answer_table_name."`  
        (`tyid` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        `cid` tinyint(3) unsigned NOT NULL,
        `pid` tinyint(3) unsigned NOT NULL,
        `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        `mask` tinyint(3) unsigned NOT NULL,
        PRIMARY KEY (`tyid`,`cid`,`pid`)) 
        ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    connect($sql_create_table_answer);
    $sheet = $PHPExcel->getSheet(0); // 读取第一个工作表
    $sc = exam_single_choose($cid, $sheet);
    $sheet = $PHPExcel->getSheet(1); // 读取第二个工作表
    $mc = exam_multiple_choose($cid, $sheet);
    $sheet = $PHPExcel->getSheet(2); // 读取第三个工作表
    $jd = exam_judge_choose($cid, $sheet);
    $sql_add_num = "UPDATE `course-exam` 
                    SET `sc` = ".$sc.",
                        `mc` = ".$mc.",
                        `jd` = ".$jd." 
                    WHERE `course_id` = ".$cid." ";
    connect($sql_add_num);
}

function convert($str)
{
    $l = fun_count($str);
    $res = 0;
    for ($i = 0; $i != $l; ++$i) {
        $res += (int) $str[$i];
    }
    return $res;
}
function fun_count($array_or_countable, $mode = COUNT_NORMAL)
{
    $res = 0;
    if (is_array($array_or_countable) || is_object($array_or_countable)) {
        $res = count($array_or_countable, $mode);
    }
    return $res;
}
function _POST($str)
{
    $val = !empty($_POST[$str]) ? $_POST[$str] : null;
    return $val;
}

function exam_result_analysis($teacher_id, $cid)
{
    $analysis_table_name = $cid."-exam-analysis";
    $exam_table_record = $cid."-exam-record";
    $student_count = 0;
    $sql_check = "DROP TABLE IF EXISTS `".$analysis_table_name."`";
    connect($sql_check);
    $sql_create_table = "CREATE TABLE `".$analysis_table_name."` (
        `tyid` varchar(12) NOT NULL,
        `submit_num` int(6) DEFAULT NULL,
        `answer_A` int(6) DEFAULT NULL,
        `answer_B` int(6) DEFAULT NULL,
        `answer_C` int(6) DEFAULT NULL,
        `answer_D` int(6) DEFAULT NULL,
        `correct_rate` double(20,0) DEFAULT NULL,
        PRIMARY KEY (`tyid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;";
    connect($sql_create_table);
    //FIXME : ABCD分析之后写 目前只做正确率
    $sql_count = "SELECT
        count( `exam-result`.student_id ) count
        FROM
            `exam-result` 
        WHERE
            `exam-result`.exam_id = ".$cid."";
    $res = connect($sql_count);
    $row = mysqli_fetch_assoc($res);
    if (mysqli_num_rows($res) > 0) {
        $student_count = $row['count'];
    }
    for ($i = 1; $i != 21; ++$i) {
        $sql = "SELECT
                count(user_id) num 
                FROM
                `".$exam_table_record."`
                WHERE
                accurate = 1 
                AND tyid = 'sc" . $i . "'";
        $res = connect($sql);
        $row = mysqli_fetch_assoc($res);
        $acc_num = $row['num'];
        if ($acc_num == 0) {
            $acc_per = 0;
        } else {
            $acc_per = round($acc_num/$student_count*100, 2);
        }
        
        $sql = "INSERT INTO `".$analysis_table_name."` 
                ( `tyid`, `submit_num`, `correct_rate` )
                VALUES
                ( 'sc" . $i . "', '".$acc_num."', '".$acc_per."' )";
        connect($sql);
    }
    for ($i = 1; $i != 21; ++$i) {
        $sql = "SELECT
                count(user_id) num 
                FROM
                `".$exam_table_record."`
                WHERE
                accurate = 1 
                AND tyid = 'mc" . $i . "'";
        $res = connect($sql);
        $row = mysqli_fetch_assoc($res);
        $acc_num = $row['num'];
        if ($acc_num == 0) {
            $acc_per = 0;
        } else {
            $acc_per = round($acc_num/$student_count*100, 2);
        }
        
        $sql = "INSERT INTO `".$analysis_table_name."` 
                ( `tyid`, `submit_num`, `correct_rate` )
                VALUES
                ( 'mc" . $i . "', '".$student_count."', '".$acc_per."' )";
        connect($sql);
    }
    for ($i = 1; $i != 21; ++$i) {
        $sql = "SELECT
            count(user_id) num 
            FROM
            `".$exam_table_record."`
            WHERE
            accurate = 1 
            AND tyid = 'jd" . $i . "'";
        $res = connect($sql);
        $row = mysqli_fetch_assoc($res);
        $acc_num = $row['num'];
        if ($acc_num == 0) {
            $acc_per = 0;
        } else {
            $acc_per = round($acc_num/$student_count*100, 2);
        }
        
        $sql = "INSERT INTO `".$analysis_table_name."` 
                ( `tyid`, `submit_num`, `correct_rate` )
                VALUES
                ( 'jd" . $i . "', '".$student_count."', '".$acc_per."' )";
        connect($sql);
    }
}

function teacher_UI_left()
{?>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container-fluid">
        <!--    图标LOGO-->
        <a class="navbar-brand" href="#">
            <img src="../image/bootstrap-solid.svg" width="30" height="30" class="d-inline-block align-top" alt="">
            在线学习中心</a>
        <!--    手机端按钮-->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a href="../index.php" class="nav-link">
                        主页
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $_SESSION['name']; ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="../index.php">主页</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../../index.php">退出</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row" id="row-main">
        <!--        左侧部分-->
        <div class="col-md-2 col-sm-2" id="sidebar">
            <!--            用户信息块-->
            <div class="user-panel">
                <figure class="figure">
                    <img src="../image/cover.jpg" class="figure-img img-fluid rounded" alt="...">
                    <figcaption class="figure-caption text-center"><?php echo $_SESSION['name']; ?>
                    </figcaption>
                </figure>
            </div>
            <!--            界面功能菜单-->
            <div class="function">
                <nav class="function">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    课程管理
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="course_creat.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>课程创建</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="course_delete.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>课程删除</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    考试管理
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="exam_create.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>考试创建</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="exam_delete.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>考试删除</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>
                                    结果分析
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="result_analysis.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>考试题目分析</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="exam_check.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>考试成绩分析</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>
                                    教师信息
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="infoconfig.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>教师信息确认</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../../php/pwd_change.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>登录密码修改</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <?}


function student_page_UI()
{
    ?>

        <div class="row no-gutters">
            <div class="col-md-2 " style="background-color: rgb(218,218,218);height: 625px ;margin-top: 80px">
                <!--左部导航栏-->
                <br>
                <img src="../img/test_img.jpg" alt="" class="img-fluid img-thumbnail rounded-circle d-block mx-auto"
                    style="width: 150px;height: 150px">
                <figcaption class="figure-caption text-center">
                    <?php echo $_SESSION['name']; ?>
                </figcaption>
                <hr>
                <div class="container text-center">
                    <div class="list-group">
                        <br>
                        <a href="curriculum.php"
                            class="list-group-item list-group-item-action list-group-item-light rounded ">课程学习</a>
                        <br>
                        <a href="exam_arrangement.php"
                            class="list-group-item list-group-item-action list-group-item-light rounded">考试安排查询</a>
                        <br>
                        <a href="../web/homework.html"
                            class="list-group-item list-group-item-action list-group-item-light rounded">题库练习</a>
                        <br>
                        <a href="exam_score.php"
                            class="list-group-item list-group-item-action list-group-item-light rounded">考试成绩查询</a>
                        <br>
                        <a href="personalpage.php"
                            class="list-group-item list-group-item-action list-group-item-light rounded">个人中心</a>
                        <br>
                    </div>
                </div>
            </div>
            <!--左部导航栏-->
            <?php
}
