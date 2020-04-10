<?php
require_once("func.php");
date_default_timezone_set('PRC');
$year = date("Y"); //年
$month = intval(date("m")); //月
$date = date("d"); //日答
$message_head = $month."月".$date."日"."信息上传";
$message_card = $month."月".$date."日"."文件提交";
$today = $month.$date;
$today_text = $month."月".$date."日";

$cid = $_SESSION['user'];
$class = substr($cid , 0 , 8);

$ymonth = intval(date("m", strtotime("-1 day")));
$ydate = date("d", strtotime("-1 day"));
$yestday = $ymonth.$ydate;
$yestday_text = $ymonth."月".$ydate."日";
?>
<!DOCTYPE html>
<html lang="zn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
            width: 100%;
        }
    </style>
    <title>后台数据</title>
</head>

<body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark ">
        <a class="navbar-brand" href="#">
            <img src="../favicon.ico" alt="Logo" style="width:50px;">
        </a>
        <span class="navbar-text h2">
            后台
        </span>
        <div class="nav-item dropdown ml-auto">
            <a class="nav-link dropdown-toggle h3" href="#" id="navbardrop" data-toggle="dropdown">
            <?php echo  $_SESSION['name'] ?>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#">刷新</a>
                <a class="dropdown-item" href="../index.php">退出</a>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-3">
            </div>
            <div class="col-xl-6">
                <div class="row" style="margin-top: 5rem">
                    <div class="col-lg-9 col-md-6">
                        <div class="input-group">
                        <select class="form-control" id="choose_class">
                            <option
                                value="<?php echo $today;?>">
                                <?php echo $today_text;?>
                            </option>
                            <option
                                value="<?php echo $yestday;?>">
                                <?php echo $yestday_text;?>
                            </option>
                        </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <script>
                            function jump(){
                            var class_num = "<?php echo $class ?>";
                            var my_select = document.getElementById('choose_class');
                            var index = my_select.selectedIndex;
                            var date = my_select.options[index].value;
                            window.location.href = "backpage.php?date="+date+"&class="+class_num;
                        }
                        </script>
                        <button class="btn btn-secondary" type="submit" onclick="jump()">查看</button>
                    </div>
                </div>
                <?php 
                if ($_GET['date'] != null) {
                    $class_num = $_GET['class']."%";
                    $sql = "SELECT
                            count( nuaajc.user_id ) stu_num 
                            FROM
                                nuaajc 
                            WHERE
                                user_id LIKE '" . $class_num . "'";
                    $res = connect($sql);
                    $row = mysqli_fetch_assoc($res);
                    $stu_num = $row['stu_num'];
                    $sql_submit = "SELECT
                                    count( DISTINCT `nuaajc-record`.user_id ) stu_sub
                                    FROM
                                        `nuaajc-record`
                                    WHERE
                                        user_id LIKE '" . $class_num . "' 
                                    AND
                                        date = '" . $_GET['date'] . "'";
                    $res_submit = connect($sql_submit);
                    $row_submit = mysqli_fetch_assoc($res_submit);
                    $stu_sub = $row_submit['stu_sub'];
                    $month = substr($_GET['date'] , 0 , 1);
                    $date = substr($_GET['date'] , 1 , 2);
                }
                ?>
                <div class="row" style="margin-top: 1rem">
                    <span class="h4"><?echo $month ?> 月 <?echo $date ?>日 共 <b><? echo $stu_num ?></b>位同学，提交 <span class="text-success"><? echo $stu_sub ?></span>位，剩余 <span
                            class="text-danger"><? echo $stu_num-$stu_sub ?></span>位未提交</span>
                </div>
                <div class="row" style="margin-top: 1rem">
                    <table class="table table-hover table-bordered table-responsive-xl text-center vertical-alignment"
                        style="background-color: white;margin-top: 1rem">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>姓名</th>
                                <th>学号</th>
                                <th>是否提交</th>
                                <th>姓名</th>
                                <th>学号</th>
                                <th>是否提交</th>
                            </tr>
                        </thead>
                        <tbody>
                            <? 
                            $count = 0;
                            $sql = "SELECT
                                    DISTINCT `nuaajc`.user_id,
                                    `nuaajc`.user_name
                                    FROM
                                    `nuaajc`
                                    WHERE
                                    user_id LIKE '" . $class_num . "'";
                            $res = connect($sql);
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $count++;
                                    $name = $row['user_name'];
                                    $id = $row['user_id'];
                                    $sql_check = "SELECT
                                                    `nuaajc-record`.submit
                                                    FROM
                                                    `nuaajc-record`
                                                    WHERE
                                                    `nuaajc-record`.user_id =  '" . $id . "'
                                                    AND
                                                    `nuaajc-record`.date = '" . $_GET['date'] . "'";
                                    $res_check = connect($sql_check);
                                    $row_check = mysqli_fetch_assoc($res_check);
                                    $status = $row_check['submit'];
                                    if ($count % 2 == 1) {
                                        ?><tr><?
                                    }?>
                                    
                                <td><?php echo $name?></td>
                                <td><?php echo $id?></td>
                                <?
                                if ($status!=1) {?>
                                <td class ="text-danger">
                                    <?echo "未提交";
                                }else {?>
                                <td class ="text-success">
                                    <?echo "提交";
                                }
                                ?></td>
                                      <?if ($count % 2 == 0) {
                                        ?></tr><?
                                    }?>
                                <?}
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>


    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" color="255,0,0" pointColor="255,0,0" opacity='0.7' zIndex="-2" count="150"
        src="../js/canvas-nest.js"></script>
    <script type="text/javascript" src="../js/canvas-nest.umd.js"></script>
</body>

</html>