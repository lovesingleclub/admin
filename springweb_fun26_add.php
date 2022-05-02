<?php
    /*****************************************/
    //檔案名稱：springweb_fun18_add.php
    //後台對應位置：春天網站系統/服務據點-環境照>新增/修改服務據點
    //改版日期：2022.4.25
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");

    if($_REQUEST["st"] == "add"){
        $branch = SqlFilter($_REQUEST["b"],"tab");
        if($branch == ""){
            $SQL = "SELECT top 1 de FROM web_photo where types='place' and branch='".$branch."' order by de desc";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
            $result = $rs->fetch(PDO::FETCH_ASSOC);
            if($result){
                $i1 = $result["de"] + 1;
            }else{
                $i1 = 1;
            }

            if($_REQUEST["d2"] != ""){
                $photo_name = SqlFilter($_REQUEST["d2"],"tab");
            }else{
                $photo_name = NULL;
            }
            $SQL = "INSERT INTO web_photo (photo_name, vurl, de, types, branch) VALUES ('".$photo_name."','".SqlFilter($_REQUEST["alt"],"tab")."','".$i1."', 'place', '".$branch."')";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();

            reURL("win_close.php");
        }
    }

    //編輯，有刪除圖檔功能(待測)
    if($_REQUEST["st"] == "edit"){
        $SQL = "SELECT * FROM web_photo where auton='".SqlFilter($_REQUEST["an"],"int")."' and types='place'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            //刪除圖檔
            if($result["photo_name"] == $_REQUEST["d2"]){
                DelFile("upload_image/".$result["photo_name"]);
            }

            if($_REQUEST["d2"] != ""){
                $photo_name = SqlFilter($_REQUEST["d2"],"tab");
            }else{
                $photo_name = NULL;
            }
            $SQL = "UPDATE web_photo SET photo_name='".$photo_name."', vurl='".SqlFilter($_REQUEST["alt"],"tab")."', times='".date("Y/m/d H:i:s")."' where auton='".SqlFilter($_REQUEST["an"],"int")."' and types='place'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }
        reURL("win_close.php");
    }

    // 上傳圖檔(待測)
	if($_REQUEST["st"] == "upload"){
        // 儲存圖片
        if ($_FILES['fileupload']['error'] === UPLOAD_ERR_OK){
            $urlpath = "upload_image/"; //儲存路徑
            $ext = pathinfo($_FILES["fileupload"]["name"], PATHINFO_EXTENSION); //附檔名      
            $fileName = "place_".date("Y").date("n").date("j").date("H").date("i").date("s").rand(1,99).".".$ext; //檔名  
			move_uploaded_file($_FILES["fileupload"]["tmp_name"],($urlpath.$fileName)); //儲存檔案
			
            // 回傳檔名
			echo $fileName;
        	exit();         
        }
    }

    if($_REQUEST["an"] != ""){
        $vst = "edit&an=".SqlFilter($_REQUEST["an"],"int");
        $SQL = "SELECT * FROM web_photo where auton=".SqlFilter($_REQUEST["an"],"int")." and types='place'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $img = $result["photo_name"];
            $alt = $result["vurl"];
        }else{
            $branch = SqlFilter($_REQUEST["b"],"tab");
            if($branch == ""){
                call_alert("會館讀取錯誤。","ClOsE",0);
            }
            $vst = "add";
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
<p></p>
<form id="fun_form" method="post" action="?st=<?php echo $vst; ?>" onsubmit="return chk_form()">
    <p>
        <label>ALT：
            <input type="text" id="alt" name="alt" value="" style="width:40%;height:26px;"></label>
    </p>
    <p>
        <label>展示圖檔(690x330)：</label>
        <?php 
            if($img != ""){
                $imgsrc = "upload_image/".$img."?t=".rand(1,9999);
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
    <input type="hidden" name="b" id="b" value="<?php echo $branch; ?>">
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
                    url: "springweb_fun26_add.php?st=upload&an=",
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




