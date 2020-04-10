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
    <link href="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/bootstrap-fileinput/5.0.8/css/fileinput.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="../js/docs.min.js"></script>
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
    <title>每日信息提交</title>
</head>

<body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark ">
        <a class="navbar-brand" href="#">
            <img src="../favicon.ico" alt="Logo" style="width:50px;">
        </a>
        <span class="navbar-text h2">
            <?php echo $message_head?>
        </span>
        <div class="nav-item dropdown ml-auto">
            <a class="nav-link dropdown-toggle h3" href="#" id="navbardrop" data-toggle="dropdown">
                <?php echo  $_SESSION['name'] ?>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#">仅供班级使用</a>
                <a class="dropdown-item" href="../index.php">退出</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-lg-12" id="file-upload" style="margin-top: 10rem">
                <!--                            标题-->
                <div class="card card-dark">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title"> <span>
                            </span><?php echo $message_card?>
                        </h3>
                    </div>
                    <!--                            内容-->
                    <div class="card-body">
                        <div class="row">
                            <!--                  图片上传-->
                            <div class="col-md-12 col-lg-12">
                                <label>文件上传</label>
                                <div class="card-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="file">文件</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exam-upload"
                                                aria-describedby="inputGroupFileAddon01" accept=".xlsx">
                                            <label class="custom-file-label" for="exam-upload" id="filename">
                                                请选择...
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <label for="username">姓名:</label>
                                <div class="input-group mb-3">
                                    <input class="form-control" id="username" type="text"
                                        value="<?php echo $_SESSION['name'];?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="usernumber">学号:</label>
                                <div class="input-group mb-3">
                                    <input class="form-control" id="usernumber" type="text"
                                        value="<?php echo $_SESSION['user'];?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="usernumber">时间:</label>
                                <div class="input-group mb-3">
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
                        </div>
                        <div class="row text-center">
                            <div class="col-6">
                                <button class="btn btn-primary" type="submit" onclick="on_click()">
                                    提交
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-primary" type="submit" onclick="status()">
                                    查看提交情况
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <mark style="margin-left: 1rem;margin-top: 1rem">请各位同学每日记得自行上传信息，认真填写提交信息，方便统计，也省去催促，谢谢配合。</mark>
        </div>
    </div>

    <script type="application/javascript">
        function status() {
            window.location.href = "backpage.php";
        }
        function on_click() {
            var files = $('#exam-upload').prop('files');
            var name = document.getElementById('username').value;
            var num = document.getElementById('usernumber').value;
            var my_select = document.getElementById('choose_class');
            var index = my_select.selectedIndex;
            var date = my_select.options[index].value;
            if (files[0] == null) {
                alert("无文件");
                return;
            }


            var fdata = new FormData();
            fdata.append('file', files[0]);
            // fdata.append('nam', name);
            fdata.append('num', num);
            fdata.append('date', date);

            $.ajax({
                url: 'upload_sql.php',
                type: 'POST',
                cache: false,
                data: fdata,
                processData: false,
                contentType: false
            }).done(function(data) {
                alert(data);
                console.log(JSON.stringify(data))
            }).fail(function(data) {
                alert(data);
            });
            clearAll();
        }
        function clearAll() {
            $("#filename").html("");
        }
    </script>

    <script src="https://cdn.bootcss.com/bootstrap-fileinput/5.0.8/js/fileinput.min.js"></script>
    <script src="https://cdn.bootcss.com/popper.js/1.16.1/esm/popper.min.js"></script>
    <script src="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" color="255,0,0" pointColor="255,0,0" opacity='0.7' zIndex="-2" count="150"
        src="../js/canvas-nest.js"></script>
    <script type="text/javascript" src="../js/canvas-nest.umd.js"></script>
    <script>
        $(document).ready(function() {
            bsCustomFileInput.init()
        });
    </script>
</body>

</html>