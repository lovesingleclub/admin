<?php
    /*****************************************/
    //檔案名稱：springweb_fun15_add.php
    //後台對應位置：春天網站系統/戀愛講堂>新增/修改戀愛講堂
    //改版日期：2022.4.11
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");
    // ajax
    if($_REQUEST["st"] == "reload_div"){
        $SQL = "select fullpic from ad_salon where ads_auto=".SqlFilter($_REQUEST["id"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $fullpic = $result["fullpic"];
            if($fullpic != ""){
                if(stripos($fullpic,",") != false){
                    foreach(explode(",",$fullpic) as $pic){
                        echo "<p style='height:22px'>http://www.springclub.com.tw/upload_image/".$pic."　<a href='#delpic' onclick=\"del_pic('".$pic."')\">刪</a>　<a href='http://www.springclub.com.tw/upload_image/".$pic."' class='fancybox'>圖</a></p>".PHP_EOL;
                    }
                }else{
                    echo "<p style='height:22px'>http://www.springclub.com.tw/upload_image/".$fullpic."　<a href='#delpic' onclick=\"del_pic('".$fullpic."')\">刪</a>　<a href='http://www.springclub.com.tw/upload_image/".$fullpic."' class='fancybox'>圖</a></p>".PHP_EOL;
                }
            }
        }
        exit();
    }
    
    // ajax上傳圖檔2(待測)
    if($_REQUEST["st"] == "upload2"){
        $id = SqlFilter($_REQUEST["id"],"int");
        if ($_FILES['fileupload2']['error'] === UPLOAD_ERR_OK){
            $urlpath = "upload_image/"; //儲存路徑
            $ext = pathinfo($_FILES["fileupload2"]["name"], PATHINFO_EXTENSION); //附檔名      
            $fileName = date("Y").date("n").date("j").date("H").date("i").date("s")."_lovesalon_".rand(1,1000).".".$ext; //檔名
            move_uploaded_file($_FILES["fileupload2"]["tmp_name"],($urlpath.$fileName)); //儲存檔案
			
			$SQL = "select * from ad_salon where ads_auto=".$id."";
			$rs = $SPConn->prepare($SQL);
			$rs->execute();
			$result = $rs->fetch(PDO::FETCH_ASSOC);
			if($result){
				$fullpic = $result["fullpic"];
                if($fullpic != ""){
                    // 圖檔名稱用','連接一起
                    $SQL = "UPDATE ad_salon SET fullpic='".($fullpic.",".$fileName)."' where ads_auto=".$id;
                    $rs = $SPConn->prepare($SQL);
			        $rs->execute();
                }else{
                    $SQL = "UPDATE ad_salon SET fullpic='".$fileName."' where ads_auto=".$id;
                    $rs = $SPConn->prepare($SQL);
			        $rs->execute();
                }
            }

			echo $id;        	        
        }
        exit(); 
    }

    // ajax上傳跟刪除圖檔(待測)
    if($_REQUEST["st"] == "upload"){
        $ads_pic = SqlFilter($_REQUEST["ads_pic"],"tab");
        if ($_FILES['fileupload']['error'] === UPLOAD_ERR_OK){
            $urlpath = "upload_image/"; //儲存路徑
            $ext = pathinfo($_FILES["fileupload"]["name"], PATHINFO_EXTENSION); //附檔名      
            $fileName = date("Y").date("n").date("j").date("H").date("i").date("s")."_springweb_fun15_".rand(1,1000).".".$ext; //檔名
            move_uploaded_file($_FILES["fileupload"]["tmp_name"],($urlpath.$fileName)); //儲存檔案
			
			if($fileName != "" && $ads_pic != ""){
                DelFile("upload_image/".$ads_pic);
            }

			echo $fileName;        	        
        }
        exit(); 
    }
    require_once("./include/_top.php");
    require_once("./include/_sidebar_spring.php");

    //程式開始 *****
    if ($_SESSION["MM_Username"] == "") {
        call_alert("請重新登入。", "login.php", 0);
    }

    if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["funtourpm"] != "1"){
        call_alert("您沒有查看此頁的權限。", "login.php", 0);
    }

    // 刪除圖片功能(待測)
    if($_REQUEST["st"] == "delpic"){
        $urlpath = "upload_image/";
        DelFile($urlpath.SqlFilter($_REQUEST["p"],"tab"));

        $SQL = "select fullpic from ad_salon where ads_auto=".SqlFilter($_REQUEST["id"],"int");
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $fullpic = $result["fullpic"];
            if($fullpic != ""){
                if(stripos($fullpic,",") != false){
                   $fullpic = $fullpic .",";
                   $fullpic = str_replace(($_REQUEST["p"].","),"",$fullpic);
                }else{
                    $fullpic = NULL;
                }
                if(substr($fullpic,-1) == ","){
                    $fullpic = substr($fullpic,0,-1);
                }
                $SQL = "UPDATE ad_salon SET fullpic='".$fullpic."' where ads_auto=".SqlFilter($_REQUEST["id"],"int");
                $rs = $SPConn->prepare($SQL);
                $rs->execute();
            }
        }
    }

    $ads_note = str_replace("<br>",PHP_EOL,$_REQUEST["ads_note"]);
    $ads_note = str_replace("<!DOCTYPE html><html><head></head><body>", "",$ads_note);
    $ads_note = str_replace("</body></html>", "",$ads_note);

    // 新增
    if($_REQUEST["acts"] == "ad"){
        $SQL = "select top 1 ads_desc from ad_salon order by ads_desc desc";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $ltd = $result["ads_desc"] + 1;
        }
        $SQL = "INSERT INTO ad_salon (ads_kind,ads_title,ads_tag,ads_teacher,ads_note,ads_pic1,ads_desc,ads_showtime,upload) VALUES ('". SqlFilter($_REQUEST["ads_kind"],"tab") ."','". SqlFilter($_REQUEST["ads_title"],"tab") ."','". SqlFilter($_REQUEST["ads_tag"],"tab") ."','". SqlFilter($_REQUEST["ads_teacher"],"tab") ."','". $ads_note."','".SqlFilter($_REQUEST["ads_pic"],"tab")."',".$ltd.",'".SqlFilter($_REQUEST["ads_showtime"],"tab")."','".SqlFilter($_REQUEST["upload"],"tab")."')";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        
        reURL("springweb_fun15.php");
    }

    // 更新
    if($_REQUEST["acts"] == "up"){
        $SQL = "update ad_salon set ads_kind = '". SqlFilter($_REQUEST["ads_kind"],"tab") ."',ads_title = '". SqlFilter($_REQUEST["ads_title"],"tab") ."',ads_tag = '". SqlFilter($_REQUEST["ads_tag"],"tab") ."',ads_teacher = '". SqlFilter($_REQUEST["ads_teacher"],"tab") ."', ads_pic1='". SqlFilter($_REQUEST["ads_pic"],"tab") ."', upload='". SqlFilter($_REQUEST["upload"],"tab") ."'";
        $SQL = $SQL . ",ads_note='".$ads_note ."',ads_showtime='".SqlFilter($_REQUEST["ads_showtime"],"tab")."' where ads_auto = ". SqlFilter($_REQUEST["pid"],"int") ."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        
        reURL("springweb_fun15.php");
    }

    $nows = date("Y/m/d H:i:s");
    if($_REQUEST["act"] == "up" && $_REQUEST["id"] != 0){
        $SQL = "Select * from ad_salon where ads_auto = ".SqlFilter($_REQUEST["id"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $ads_kind = $result["ads_kind"];
            $ads_title = $result["ads_title"];
            $ads_tag = $result["ads_tag"];
            $ads_teacher = $result["ads_teacher"];     
            $ads_pic = $result["ads_pic1"];     
            $ads_note = $result["ads_note"];
            $ads_showtime = $result["ads_showtime"];
	        $nows = $result["ads_time"];
            $upload = $result["upload"];
            if($ads_note != ""){
                $ads_note = str_replace("<br>",PHP_EOL,$ads_note);
            }
        }
    }
?>

<link rel="stylesheet" href="css/jquery.fileupload.css">
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
<noscript><link rel="stylesheet" href="css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="css/jquery.fileupload-ui-noscript.css"></noscript>
<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>春天網站系統</li>
            <li><a href="springweb_fun15.php">戀愛講堂</a></li>
            <li class="active">新增/修改戀愛講堂</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>新增/修改戀愛講堂</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form id="mform" method="post" action="springweb_fun15_add.php" class="form-inline" onSubmit="return chkform()">
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <tbody>
                            <tr>
                                <td width="150" align="left" valign="middle">分類</td>
                                <td>
                                    <select name="ads_kind" id="ads_kind">
                                        <option value="愛情先修班"<?php if($ads_kind == "愛情先修班") echo " selected"; ?>>愛情先修班</option>
                                        <option value="戀愛補習班"<?php if($ads_kind == "戀愛補習班") echo " selected"; ?>>戀愛補習班</option>
                                        <option value="好想談戀愛"<?php if($ads_kind == "好想談戀愛") echo " selected"; ?>>好想談戀愛</option>
                                        <option value="男女大不同"<?php if($ads_kind == "男女大不同") echo " selected"; ?>>男女大不同</option>
                                        <option value="專家學者說"<?php if($ads_kind == "專家學者說") echo " selected"; ?>>專家學者說</option>
                                        <option value="幸福幫幫忙"<?php if($ads_kind == "幸福幫幫忙") echo " selected"; ?>>幸福幫幫忙</option>
                                        <option value="就是要幸福"<?php if($ads_kind == "就是要幸福") echo " selected"; ?>>就是要幸福</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                            <tr>
                                <td width="150" align="left" valign="middle">標題</td>
                                <td><input name="ads_title" id="ads_title" value="<?php echo $ads_title; ?>" style="width:60%;" class="form-control" required></td>
                            </tr>
                            <tr>
                                <td width="150" align="left" valign="middle">TAG(分隔符號|)</td>
                                <td><input name="ads_tag" id="ads_tag" value="<?php echo $ads_tag; ?>" class="form-control" style="width:60%;"></td>
                            </tr>
                            <tr>
                                <td width="150" align="left" valign="middle">教師</td>
                                <td><input name="ads_teacher" id="ads_teacher" class="form-control" value="<?php echo $ads_teacher; ?>"></td>
                            </tr>
                            <tr>
                                <td width="150" align="left" valign="middle">日期</td>
                                <td colspan=3><input name="ads_showtime" id="ads_showtime" value="<?php echo Date_EN($ads_showtime,1); ?>" class="datepicker" autocomplete="off"></td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">建立時間</td>
                                <td><?php echo changeDate($nows); ?></td>
                            </tr>
                            <td align="left" valign="middle">上傳圖檔</td>
                            <td>
                                <div id="img_div">
                                    <?php 
                                        if($ads_pic != ""){
                                            echo "<a href='upload_image/". $ads_pic ."' class='fancybox'><img src='upload_image/". $ads_pic ."' width=250 border=0></a>";
                                        }else{
                                            echo "";
                                        }
                                    ?>
                                </div>
                                <div>
                                    <span class="btn btn-danger fileinput-button"><span>上傳檔案</span><input id="file_uploads1" type="file" class="fileupload" name="fileupload"></span>
                                    <div class="progress progress-striped" style="display:none">
                                        <div class="bar progress-bar progress-bar-lovepy"></div>
                                    </div>
                                </div>
                                <div id="fileupload_show"></div>
                            </td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">內文</td>
                                <td><textarea name="ads_note" class="editor" style="width:80%;height:350px;"><?php echo $ads_note; ?></textarea></td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">上下架</td>
								<td>
									<select name="upload" id="upload">	
										<option value="上架"<?php if($upload == "上架") echo " selected"; ?>>上架</option>
										<option value="下架"<?php if($upload == "下架") echo " selected"; ?>>下架</option>
									</select>
								</td>
                            </tr>
                            <tr>
                                <td colspan=2 align="center"><input name="ads_pic" id="ads_pic" type="hidden" value="<?php echo $ads_pic; ?>"><input name="acts" id="acts" type="hidden" value="<?php echo SqlFilter($_REQUEST["act"],"tab"); ?>"><input name="pid" type="hidden" id="pid" value="<?php echo SqlFilter($_REQUEST["id"],"int"); ?>">
                                    <input type="submit" value="確認送出" class="btn btn-info" style="width:50%;">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
                <?php 
                    if($_REQUEST["id"] != ""){ ?>
                        <div>
                            <div id="reload_showtopdiv"></div>
                            <div>
                                <span class="btn btn-danger fileinput-button"><span>內文用圖片上傳</span><input id="file_uploads2" type="file" class="fileupload" name="fileupload2"></span>
                                <div id="progress" class="progress progress-striped" style="display:none"><div class="bar progress-bar progress-bar-lovepy"></div></div>
                            </div>
                        </div>
                    <?php }
                ?>
            </div>
        </div>
        <!--/span-->
    </div>
    <!--/row-->

    </div>
    <!--/.fluid-container-->
</section>
<!-- /MIDDLE -->

<?php
require_once("./include/_bottom.php");
?>

<script type="text/javascript" src="assets/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.fileupload.js"></script>
<script src="js/tinymce/tinymce.min.js"></script>
<script language="JavaScript">
    $mtu = "springweb_fun15.";
    var $fsend = 0;
    var $ff;

    function chkform() {
        if ($ff && !$fsend) {
            $ff.submit();
            return false;
        }
        return true;
    }

    function del_pic(p) {
        $.ajax({
            url: 'springweb_fun15_add.php',
            data: {
                st: "delpic",
                id: '<?php echo SqlFilter($_REQUEST["id"],"int"); ?>',
                p: p
            },
            error: function(xhr) {
                alert('Ajax request 發生錯誤1');
            },
            success: function(response) {
                reload_upload("reload_showtopdiv");
            }
        });
    }

    function reload_upload(dd) {
        var $sdiv = $("#" + dd);
        if (!$sdiv.length) return false;
        $.ajax({
            url: 'springweb_fun15_add.php',
            data: {
                st: "reload_div",
                id: '<?php echo SqlFilter($_REQUEST["id"],"int"); ?>'
            },
            error: function(xhr) {
                alert('Ajax request 發生錯誤1x');
            },
            success: function(response) {
                $sdiv.html(response);
            }
        });
    }
    $(function() {

        reload_upload("reload_showtopdiv");

        var $file_uploads1 = $("#file_uploads1"),
            $thisid = $file_uploads1.attr("id"),
            $progress = $file_uploads1.closest("div").find(".progress");
        var $imgs = $file_uploads1.closest("span").find("#cimg").val();

        $file_uploads1.fileupload({
                url: "springweb_fun15_add.php?st=upload&ads_pic=<?php echo $ads_pic; ?>",
                type: "POST",
                dropZone: $file_uploads1,
                dataType: 'html',
                autoUpload: false,
                done: function(e, data) {

                    if (data.result) {
                        $("#ads_pic").val(data.result);
                        $("#img_div").find("img").remove();
                        $("#img_div").append($("<a href='singleparty_image/salon/" + data.result + "' class='fancybox'><img width=60 src='singleparty_image/salon/" + data.result + "' border=0></img></a>"));
                        $fsend = 1;
                        $("#mform").submit();
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
                        $ff = data;
                    }
                }
            }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');

        var $file_uploads2 = $("#file_uploads2"),
            $thisid = $file_uploads2.attr("id"),
            $progress2 = $file_uploads2.closest("div").find(".progress");
        var $imgs = $file_uploads2.closest("span").find("#cimg").val();

        $file_uploads2.fileupload({
                url: "springweb_fun15_add.php?st=upload2&id=<?php echo SqlFilter($_REQUEST["id"],"int"); ?>",
                type: "POST",
                dropZone: $file_uploads2,
                dataType: 'html',
                autoUpload: true,
                done: function(e, data) {

                    if (data.result) {
                        $progress2.find(".progress-bar").css("width", "0").parent().hide();
                        reload_upload("reload_showtopdiv");
                    }
                },
                fail: function(e, data) {

                },
                progressall: function(e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $progress2.show().find(".progress-bar").css(
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
                    data.submit();
                }
            }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');

    });
    tinymce.init({
        selector: ".editor",
        plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons template textcolor paste fullpage textcolor"
        ],

        toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | fontsizeselect styleselect removeformat forecolor backcolor | code preview",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | undo redo | link unlink image media | inserttime | table",

        menubar: false,
        toolbar_items_size: 'small',
        language: 'zh_TW',
        height: 600
    });
</script>