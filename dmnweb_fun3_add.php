<?php 
/*****************************************/
//檔案名稱：dmnweb_fun3_add.php
//後台對應位置：DateMeNow網站系統/新增修改活動頁面-Banner
//改版日期：2022.8.17
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

// 更新
if ($_REQUEST["st"] == "ups") {
    $an = SqlFilter($_REQUEST["an"],"int");
    $d1 = SqlFilter($_REQUEST["d1"],"tab");
    $SQL = "UPDATE webdata SET d1='" . $d1 . "' where auton='" . $an . "' and types='index_banner'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
}

// 新增、更新及上傳圖檔、刪除圖檔(待測)
if ($_REQUEST["st"] == "upload") {
    $an = SqlFilter($_REQUEST["an"],"int");
    $d1 = SqlFilter($_REQUEST["d1"],"tab");
    if($an == ""){
        $an = 0;
    }
    $SQL = "SELECT top 1 i1 FROM webdata where types='index_banner' order by i1 desc";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $i1 = $result["i1"] + 1;
    } else {
        $i1 = 1;
    }
    $SQL = "SELECT * FROM webdata where auton=".$an." and types='index_banner'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $old_photo_name = $result["d2"];
        if($old_photo_name != ""){
            DelFile("datemenow_image/upload/".$old_photo_name);
        }
    }else{
        $SQL = "INSERT INTO webdata (t1, i1, types) VALUES ('" . date("Y/m/d H:i:s") . "','" . $i1 . "','index_banner')";
        $rs = $DMNConn->prepare($SQL);
        $rs->execute();
        $an = $DMNConn->lastInsertId();
    } 

    if ($_FILES['fileupload']['error'] === UPLOAD_ERR_OK) {
        $urlpath = "datemenow_image/upload/"; //儲存路徑
        $ext = pathinfo($_FILES["fileupload"]["name"], PATHINFO_EXTENSION); //附檔名      
        $fileName = "index_banner_" . $an . $ext; //檔名
        move_uploaded_file($_FILES["fileupload"]["tmp_name"], ($urlpath . $fileName)); //儲存檔案
        
        //更新圖檔檔名及連結位置
        $SQL = "update webdata set d1='".$d1."', d2='".$fileName."' where auton=".$an."";
        $rs = $DMNConn->prepare($SQL);
        $rs->execute();
    }
    exit();
}

if ($_REQUEST["an"] != "") {
    $vst = "edit&an=" . SqlFilter($_REQUEST["an"], "int");
    $SQL = "SELECT * FROM webdata where auton=" . SqlFilter($_REQUEST["an"], "int") . " and types='index_banner'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $d1 = $result["d1"];
        $img = $result["d2"];
    }
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
    <form method="post" action="dmnweb_fun3_add.php?st=edit">
        <p>
            <label>指向連結位置：</label>
            <input type="text" id="d1" name="d1" value="<?php echo $d1 ?>" size=60 style="height:30px;"><?php if($_REQUEST["an"] != "") {?> <button id="edit_link_button" type="button" class="button">修改連結</button><input type="hidden" id="bd1" value="<?php echo $d1; ?>"/> <?php }?>
        </p>
        <p>
            <label>展示圖檔(1920x360)：</label>
        <dl>
            <div>
                <span class="btn btn-info fileinput-button"><span>選擇檔案</span><input data-no-uniform="true" id="fileupload" type="file" class="fileupload" name="fileupload"></span>
                <div id="progress" class="progress progress-striped" style="display:none">
                    <div class="bar progress-bar progress-bar-lovepy"></div>
                </div>
            </div>
        </dl>
        </p>

        <p><?php echo $img; ?><br><img height=80 src="datemenow_image/upload/<?php echo $img; ?>?t=<?php echo rand(1,9999); ?>" id="showimg"></p>

        </div>
        <center><button type="submit" class="btn btn-danger" onclick="window.close()" style="width:40%;height:32px;">關閉</button></center>
    </form>

    <script type="text/javascript">
        $(function() {
            $(".fileupload").each(function() {

                var $this = $(this),
                    $thisid = $this.attr("id"),
                    $progress = $this.closest("div").find(".progress");
                var $imgs = $(this).closest("span").find("#cimg").val(),
                    $d1v = $("#d1").val();

                $this.fileupload({
                        url: "dmnweb_fun3_add.php?st=upload&an=<?php echo SqlFilter($_REQUEST["an"],"int"); ?>",
                        type: "POST",
                        dropZone: $this,
                        dataType: 'html',
                        autoUpload: false,
                        done: function(e, data) {

                            if (data.result) {
                                location.href = 'win_close.php?m=上傳完成';
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
                                if ($("#d1").val().replace(/\s/g, '') == '') {
                                    alert("請輸入連結位置。");
                                    return false;
                                } else {
                                    data.url = "dmnweb_fun3_add.php?st=upload&d1=" + $("#d1").val() + "&an=<?php echo SqlFilter($_REQUEST["an"],"int"); ?>";
                                    data.submit();
                                }
                            }
                        }
                    }).prop('disabled', !$.support.fileInput)
                    .parent().addClass($.support.fileInput ? undefined : 'disabled');

            });

            $("#edit_link_button").click(function() {
                var $d1 = $("#d1").val();
                var $bd1 = $("#bd1").val();
                if ($d1 == $bd1) {
                    alert("連結位置與目前的資料沒有不同，請確認後再修改，目前連結位置為：" + $bd1);
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: "dmnweb_fun3_add.php",
                    data: {
                        st: "ups",
                        d1: $d1,
                        an: "<?php echo SqlFilter($_REQUEST["an"],"int"); ?>"
                    }
                }).done(function() {
                    location.reload();
                });
            });
        });
    </script>
</body>

</html>