<?php

/*****************************************/
//檔案名稱：dmnweb_fun5_add.php
//後台對應位置：DateMeNow網站系統/新增首頁-Banner
//改版日期：2022.8.16
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");

// 程式開始
if ($_SESSION["MM_Username"] == "") {
    call_alert("請重新登入。", "login.php", 0);
}

if ($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["dmnweb"] != "1") {
    call_alert("您沒有查看此頁的權限。", "login.php", 0);
}

// 新增
if ($_REQUEST["st"] == "add") {
    $SQL = "SELECT top 1 i1 FROM webdata where types='newindex_banner' order by i1 desc";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $i1 = $result["i1"] + 1;
    } else {
        $i1 = 1;
    }
    if ($_REQUEST["d2"] != "") {
        $d2 = SqlFilter($_REQUEST["d2"], "tab");
    } else {
        $d2 = "";
    }
    $SQL = "INSERT INTO webdata (d1, d2, alt, d3, d4, t1, i1, types) VALUES ('" . SqlFilter($_REQUEST["d1"], "tab") . "','" . $d2 . "','" . SqlFilter($_REQUEST["alt"], "tab") . "','" . SqlFilter($_REQUEST["d3"], "tab") . "','" . SqlFilter($_REQUEST["d4"], "tab") . "','" . date("Y/m/d H:i:s") . "','" . $i1 . "','newindex_banner')";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();

    reURL("win_close.php");
}

// 更新(有刪除圖檔功能待測)
if ($_REQUEST["st"] == "edit") {
    $SQL = "SELECT * FROM webdata where auton='" . SqlFilter($_REQUEST["an"], "int") . "' and types='newindex_banner'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        if ($result["d2"] != $_REQUEST["d2"]) {
            DelFile("datemenow_image/upload/" . $result["d2"]);
        }
        if ($_REQUEST["d2"] != "") {
            $d2 = SqlFilter($_REQUEST["d2"], "tab");
        } else {
            $d2 = NULL;
        }

        $SQL = "UPDATE webdata SET d1='" . SqlFilter($_REQUEST["d1"], "tab") . "', d2='" . $d2 . "', alt='" . SqlFilter($_REQUEST["alt"], "tab") . "', d3='" . SqlFilter($_REQUEST["d3"], "tab") . "', d4='" . SqlFilter($_REQUEST["d4"], "tab") . "', t1='" . date("Y/m/d H:i:s") . "' where auton='" . SqlFilter($_REQUEST["an"], "int") . "' and types='newindex_banner'";
        $rs = $DMNConn->prepare($SQL);
        $rs->execute();
    }
    reURL("win_close.php");
}

// 上傳圖檔(待測)
if ($_REQUEST["st"] == "upload") {
    if ($_FILES['fileupload']['error'] === UPLOAD_ERR_OK) {
        $urlpath = "datemenow_image/upload/"; //儲存路徑
        $ext = pathinfo($_FILES["fileupload"]["name"], PATHINFO_EXTENSION); //附檔名      
        $fileName = "newindex_banner_" . date("Y") . date("n") . date("j") . date("H") . date("i") . date("s") . rand(1, 99) . "." . $ext; //檔名
        move_uploaded_file($_FILES["fileupload"]["tmp_name"], ($urlpath . $fileName)); //儲存檔案
        echo $fileName;
        exit();
    }
}

if ($_REQUEST["an"] != "") {
    $vst = "edit&an=" . SqlFilter($_REQUEST["an"], "int");
    $SQL = "SELECT * FROM webdata where auton=" . SqlFilter($_REQUEST["an"], "int") . " and types='newindex_banner'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $d1 = $result["d1"];
        $img = $result["d2"];
        $d3 = $result["d3"];
        $d4 = $result["d4"];
        $alt = $result["alt"];
    }
} else {
    $vst = "add";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <script src="js/jquery-1.8.3.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/jquery.fileupload.js"></script>
    <link href="css/jquery-ui-1.8.21.custom.css" rel="stylesheet">
    <link id="bs-css" href="css/bootstrap-cerulean.css" rel="stylesheet">

    <link rel="stylesheet" href="css/jquery.fileupload.css">
    <link rel="stylesheet" href="css/jquery.fileupload-ui.css">
    <noscript>
        <link rel="stylesheet" href="css/jquery.fileupload-noscript.css">
    </noscript>
    <noscript>
        <link rel="stylesheet" href="css/jquery.fileupload-ui-noscript.css">
    </noscript>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DateMeNow網站系統</title>
</head>

<body>
    <p></p>
    <form id="fun_form" method="post" action="?st=<?php echo $vst; ?>" onsubmit="return chk_form()">
        <p>
            <label>連結位置：
                <input type="text" id="d1" name="d1" value="<?php echo $d1; ?>" style="width:40%;height:26px;" required></label>
            <label>ALT：
                <input type="text" id="alt" name="alt" value="<?php echo $alt; ?>" style="width:40%;height:26px;"></label>
        </p>
        <p>
            <label>展示圖檔(690x330)：</label>
            <?php 
                if($img != ""){
                    $imgsrc = "datemenow_image/upload/".$img."?t=".rand(1,9999);
                }else{
                    $imgsrc = "img/lovepy_noimg.jpg";
                }
            ?>    
        <div>
            <img id="show_img" src="<?php echo $imgsrc; ?>" height=80>
            <span class="btn btn-info fileinput-button"><span>選擇檔案</span><input data-no-uniform="true" id="fileupload" type="file" class="fileupload" name="fileupload"></span>
            <div id="progress" class="progress progress-striped" style="display:none">
                <div class="bar progress-bar progress-bar-lovepy"></div>
            </div>
        </div>
        <div id="fileupload_show"></div>
        <input type="hidden" name="d2" id="d2" value="<?php echo $img; ?>">
        </p>

        </div>
        <center><button type="submit" class="btn btn-danger" style="width:40%;height:28px;">確認送出</button></center>
    </form>

    <script type="text/javascript">
        var $fsend = 0;

        function chk_form() {
            if (!$("#d2").val() || $fsend) return true;
            if ($("#d2").val() && !$fsend) return true;
            return false;
        }


        $(function() {
            $(".fileupload").each(function() {

                var $this = $(this),
                    $thisid = $this.attr("id"),
                    $progress = $this.closest("div").find(".progress");
                var $imgs = $(this).closest("span").find("#cimg").val();

                $this.fileupload({
                        url: "dmnweb_fun5_add.php?st=upload&an=<?php echo SqlFilter($_REQUEST["an"],"int"); ?>",
                        type: "POST",
                        dropZone: $this,
                        dataType: 'html',
                        autoUpload: false,
                        done: function(e, data) {

                            if (data.result) {
                                $("#d2").val(data.result);
                                $("#show_img").attr("src", "upload_image/" + data.result);
                                $fsend = 1;
                                $("#fun_form").submit();
                            }
                        },
                        fail: function(e, data) {

                        },
                        progressall: function(e, data) {
                            var progress = parseInt(data.loaded / data.total * 100, 10);
                            $progress.show().find(".progress-bar").css(
                                'width',
                                progress + '%'
                            );
                        },
                        add: function(e, data) {
                            var uploadErrors = [];
                            var acceptFileTypes = /^image\/(gif|jpe?g|png)$/i;
                            if (data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
                                uploadErrors.push('目前僅開放上傳 gif, jpg, jpeg, png 檔案。');
                            }
                            if (data.originalFiles[0]['size'] > 5000000) {
                                uploadErrors.push('檔案大小超過限制。');
                            }
                            if (uploadErrors.length > 0) {
                                alert(uploadErrors.join("\n"));
                            }
                            if (data.files) {
                                $("#fileupload_show").html(data.files[data.files.length - 1].name);
                                $("#d2").val(data.files[data.files.length - 1].name);
                                data.submit();
                            }
                        }
                    }).prop('disabled', !$.support.fileInput)
                    .parent().addClass($.support.fileInput ? undefined : 'disabled');

            });

        });
    </script>
</body>

</html>