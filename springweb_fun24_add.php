<?php 
    /*****************************************/
    //檔案名稱：springweb_fun24_add.php
    //後台對應位置：春天網站系統/活動表下載/修改活動表
    //改版日期：2022.4.23
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");

    // 新增
    if($_REQUEST["st"] == "ups"){
        $an = SqlFilter($_REQUEST["an"],"int");
        $d1 = SqlFilter($_REQUEST["d1"],"tab");
        if($an == "" || $d1 == ""){
            exit();
        }

        $SQL = "update webdata set d1='".$d1."' where auton=".$an." and types='actiondownload'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        exit();  
    }

    // 上傳圖檔跟刪除圖檔(待測)
    if($_REQUEST["st"] == "upload"){
        $an = SqlFilter($_REQUEST["an"],"int");
        if($an == ""){
            $an = 0;
        }

        $SQL = "SELECT * FROM webdata where auton=".$an." and types='actiondownload'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $old_photo_name = $result["d2"];
            if($old_photo_name != ""){
                DelFile("upload_image/".$old_photo_name); //刪除圖檔
            }
        }

        if ($_FILES['fileupload']['error'] === UPLOAD_ERR_OK){
            $urlpath = "upload_image/"; //儲存路徑
            $ext = pathinfo($_FILES["fileupload"]["name"], PATHINFO_EXTENSION); //附檔名      
            $fileName = "event_".$an.".".$ext; //檔名
            move_uploaded_file($_FILES["fileupload"]["tmp_name"],($urlpath.$fileName)); //儲存檔案
            // 更新連結位置跟圖檔名稱
            $SQL = "update webdata set d2='".$fileName."' where auton=".$an."";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
            echo $an;
            exit();
        }
    }

    $img = "../img/lovepy_noimg.jpg";
    if($_REQUEST["an"] != ""){
        $SQL = "SELECT * FROM webdata where auton=".SqlFilter($_REQUEST["an"],"int")." and types='actiondownload'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $d1 = $result["d1"];
            $img = $result["d2"];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>春天網站系統</title>
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
</head>
<body>
    
<form method="post" action="springweb_fun24_add.php?st=edit">
    <p>
        <label><?php echo $d1; ?> 活動表下載圖檔：</label>
    <dl>
        <div>
            <span class="btn btn-info fileinput-button"><span>選擇檔案</span><input data-no-uniform="true" id="fileupload" type="file" class="fileupload" name="fileupload"></span>
            <div id="progress" class="progress progress-striped" style="display:none">
                <div class="bar progress-bar progress-bar-lovepy"></div>
            </div>
        </div>
    </dl>
    </p>

    <p><?php echo $img; ?><br><img height=80 src="upload_image/<?php echo $img; ?>?t=<?php echo rand(1,9999); ?>" id="showimg"></p>

    </div>
    <center><button type="submit" class="btn btn-danger" onclick="window.close()" style="width:40%;height:32px;">關閉</button></center>
</form>

<script type="text/javascript">
    $(function() {
        $(".fileupload").each(function() {

            var $this = $(this),
                $thisid = $this.attr("id"),
                $progress = $this.closest("div").find(".progress");
            var $imgs = $(this).closest("span").find("#cimg").val();

            $this.fileupload({
                    url: "springweb_fun24_add.php?st=upload&an=<?php echo SqlFilter($_REQUEST["an"],"int"); ?>",
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