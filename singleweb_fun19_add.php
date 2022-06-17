<?php

/*****************************************/
//檔案名稱：singleweb_fun19_add.php
//後台對應位置：約會專家系統/部落格>新增修改部落格文章
//改版日期：2022.6.17
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");
// ajax
if($_REQUEST["st"] == "reload_div"){
    $SQL = "select fullpic from si_blog where auton=".SqlFilter($_REQUEST["id"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $fullpic = $result["fullpic"];
        if($fullpic != ""){
            if(stripos($fullpic,",") != false){
                foreach(explode(",",$fullpic) as $pic){
                    echo "<p style='height:22px'>https://blog.singleparty.com.tw/assets/img/".$pic."　<a href='#delpic' onclick=\"del_pic('".$pic."')\">刪</a>　<a href='https://blog.singleparty.com.tw/assets/img/".$pic."' class='fancybox'>圖</a></p>".PHP_EOL;
                }
            }else{
                echo "<p style='height:22px'>https://blog.singleparty.com.tw/assets/img/".$fullpic."　<a href='#delpic' onclick=\"del_pic('".$fullpic."')\">刪</a>　<a href='https://blog.singleparty.com.tw/assets/img/".$fullpic."' class='fancybox'>圖</a></p>".PHP_EOL;
            }
        }
    }
    exit();
}

// ajax上傳圖檔2(待測)
if($_REQUEST["st"] == "upload2"){
    $id = SqlFilter($_REQUEST["id"],"int");
    if ($_FILES['fileupload2']['error'] === UPLOAD_ERR_OK){
        $urlpath = "singleparty_blog/"; //儲存路徑
        $ext = pathinfo($_FILES["fileupload2"]["name"], PATHINFO_EXTENSION); //附檔名      
        $fileName = date("Y").date("n").date("j").date("H").date("i").date("s")."_blog_".rand(1,1000).".".$ext; //檔名
        move_uploaded_file($_FILES["fileupload2"]["tmp_name"],($urlpath.$fileName)); //儲存檔案
        
        $SQL = "select * from si_blog where auton=".$id."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $fullpic = $result["fullpic"];
            if($fullpic != ""){
                // 圖檔名稱用','連接一起
                $SQL = "UPDATE si_blog SET fullpic='".($fullpic.",".$fileName)."' where auton=".$id;
                $rs = $SPConn->prepare($SQL);
                $rs->execute();
            }else{
                $SQL = "UPDATE si_blog SET fullpic='".$fileName."' where auton=".$id;
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
    $pic = SqlFilter($_REQUEST["pic"],"tab");
    if ($_FILES['fileupload']['error'] === UPLOAD_ERR_OK){
        $urlpath = "singleparty_blog/"; //儲存路徑
        $ext = pathinfo($_FILES["fileupload"]["name"], PATHINFO_EXTENSION); //附檔名      
        $fileName = date("Y").date("n").date("j").date("H").date("i").date("s")."_blog_".rand(1,1000).".".$ext; //檔名
        move_uploaded_file($_FILES["fileupload"]["tmp_name"],($urlpath.$fileName)); //儲存檔案
        
        if($fileName != "" && $pic != ""){
            DelFile($urlpath.$pic);
        }

        echo $fileName;        	        
    }
    exit(); 
}
require_once("./include/_top.php");
require_once("./include/_sidebar_single.php");

//程式開始 *****
if ($_SESSION["MM_Username"] == "") {
    call_alert("請重新登入。", "login.php", 0);
}

if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["singleweb"] != "1"){
    call_alert("您沒有查看此頁的權限。", "login.php", 0);
}

// 刪除圖片功能(待測)
if($_REQUEST["st"] == "delpic"){
    $urlpath = "singleparty_blog/";
    DelFile($urlpath.SqlFilter($_REQUEST["p"],"tab"));

    $SQL = "select fullpic from si_blog where auton=".SqlFilter($_REQUEST["id"],"int");
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
                $fullpic = "";
            }
            if(substr($fullpic,-1) == ","){
                $fullpic = substr($fullpic,0,-1);
            }
            $SQL = "UPDATE si_blog SET fullpic='".$fullpic."' where auton=".SqlFilter($_REQUEST["id"],"int");
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }
    }
}

$notes = str_replace("<br>",PHP_EOL,$_REQUEST["notes"]);
$notes = str_replace("<!DOCTYPE html><html><head></head><body><p>", "",$notes);
$notes = str_replace("</p></body></html>", "",$notes);

// 新增
if($_REQUEST["acts"] == "ad"){
    $SQL = "select top 1 t_desc from si_blog order by t_desc desc";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $ltd = $result["t_desc"] + 1;
    }

    if($_REQUEST["tag"] != ""){
        $tag = str_replace(" ","",SqlFilter($_REQUEST["tag"],"tab"));
    }else{
        $tag = "";
    }
    $SQL = "INSERT INTO si_blog (title, author, notes, times, t_desc, showtime, tag, pic) VALUES ('". SqlFilter($_REQUEST["title"],"tab") ."','". SqlFilter($_REQUEST["author"],"tab") ."','". $notes."','".date("Y/m/d H:i:s")."','".$ltd."','".SqlFilter($_REQUEST["showtime"],"tab")."','".$tag."','".SqlFilter($_REQUEST["pic"],"tab")."')";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    
    reURL("singleweb_fun19.php");
}

// 更新
if($_REQUEST["acts"] == "up"){
    if($_REQUEST["tag"] != ""){
        $tag = str_replace(" ","",SqlFilter($_REQUEST["tag"],"tab"));
    }else{
        $tag = "";
    }
    $SQL = "update si_blog set title = '". SqlFilter($_REQUEST["title"],"tab") ."',author = '". SqlFilter($_REQUEST["author"],"tab") ."',notes = '".$notes."', showtime='". SqlFilter($_REQUEST["showtime"],"tab") ."', tag='".$tag."'";
    $SQL = $SQL . ",pic='".SqlFilter($_REQUEST["pic"],"tab")."' where auton = ". SqlFilter($_REQUEST["pid"],"int") ."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    
    reURL("singleweb_fun19.php");
}

$nows = date("Y/m/d H:i:s");

if($_REQUEST["a"] != ""){
    $SQL = "Select * from si_blog where auton = ".SqlFilter($_REQUEST["a"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $tag = $result["tag"];
        $title = $result["title"];     
        $author = $result["author"];     
	    $pic = $result["pic"];	    
        $showtime = $result["showtime"];
	    $nows = $result["times"];
        $notes = $result["notes"];
        if($notes != ""){
            $notes = str_replace("<br>",PHP_EOL,$notes);
        }
        
    }
    $acts = "up";
}else{
    $acts = "ad";
}

$SQL = "SELECT * FROM si_webdata where types='blog_tag' order by t1 desc";
$rs = $SPConn->prepare($SQL);
$rs->execute();
$result = $rs->fetchAll(PDO::FETCH_ASSOC);
if($result){
    foreach($result as $re){
        $alltag = $alltag . "," .$re["d1"];
    }    
}

if(substr($alltag,0,1) == ","){    
    $alltag = substr($alltag,1,(strlen($alltag)-1));
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
            <li>約會專家系統</li>
            <li><a href="singleweb_fun19.php">部落格</a></li>
            <li class="active">新增/修改部落格文章</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>新增/修改部落格文章</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form id="mform" method="post" action="singleweb_fun19_add.php" onSubmit="return chkform()" class="form-inline">
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <tbody>
                            <tr>
                                <td width="150" align="left" valign="middle">分類</td>
                                <td>
                                    <select name="tag" id="tag" style="width:60%" class="select2" multiple>
                                        <option value="">請選擇</option>
                                        <?php 
                                            foreach(explode(",",$alltag) as $onetag){
                                                if($tag == $onetag){
                                                    echo "<option value='".$onetag."' selected>".$onetag."</option>";
                                                }else{
                                                    echo "<option value='".$onetag."'>".$onetag."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                            <tr>
                                <td width="150" align="left" valign="middle">標題</td>
                                <td><input name="title" id="title" value="<?php echo $title; ?>" class="form-control" style="width:60%;" required></td>
                            </tr>
                            <tr>
                                <td width="150" align="left" valign="middle">作者</td>
                                <td><input name="author" id="author" class="form-control" value="<?php echo $author; ?>"></td>
                            </tr>
                            <tr>
                                <td width="150" align="left" valign="middle">日期</td>
                                <td colspan=3><input name="showtime" id="showtime" value="<?php echo Date_EN($showtime,1); ?>" class="datepicker" autocomplete="off" required></td>
                            </tr>
                            <!--<tr>
							<td width="150" align="left" valign="middle"></td>
							<td colspan=3><label><input type="checkbox" name="ads_can_show" value="1"> 前台不顯示</label></td>
							</tr>-->
                            <tr>
                                <td align="left" valign="middle">建立時間</td>
                                <td><?php echo changeDate($nows); ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">上傳圖檔</td>
                                <td>
                                    <?php 
                                        if($pic != ""){
                                            $img = "<a href=\"singleparty_image/salon/". $pic ."\" class='fancybox'><img src='singleparty_blog/". $pic ."' width=250 border=0></a>";
                                        }else{
                                            $img = "";
                                        }
                                    ?>
                                    <div id="img_div">
                                        <?php echo $img; ?>
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
                                <td><textarea name="notes" class="editor" style="width:80%;height:350px;"><?php echo $notes; ?></textarea></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td align="center"><input name="pic" id="pic" type="hidden" value="<?php echo $pic; ?>"><input name="acts" id="acts" type="hidden" value="<?php echo $acts ?>"><input name="pid" type="hidden" id="pid" value="<?php echo SqlFilter($_REQUEST["a"],"int"); ?>">
                                    <input type="submit" value="確認送出" class="btn btn-info" style="width:50%;">
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </form>
                <?php 
                    if($_REQUEST["a"] != ""){ ?>
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
    $mtu = "singleweb_fun19.";
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
            url: 'singleweb_fun19_add.php',
            data: {
                st: "delpic",
                id: '<?php echo SqlFilter($_REQUEST["a"],"int"); ?>',
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
            url: 'singleweb_fun19_add.php',
            data: {
                st: "reload_div",
                id: '<?php echo SqlFilter($_REQUEST["a"],"int"); ?>'
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
                url: "singleweb_fun19_add.php?st=upload&pic=<?php echo $pic; ?>",
                type: "POST",
                dropZone: $file_uploads1,
                dataType: 'html',
                autoUpload: false,
                done: function(e, data) {

                    if (data.result) {
                        $("#pic").val(data.result);
                        $("#img_div").find("img").remove();
                        $("#img_div").append($("<a href='singleparty_blog/" + data.result + "' class='fancybox'><img width=60 src='singleparty_blog/" + data.result + "' border=0></img></a>"));
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
                url: "singleweb_fun19_add.php?st=upload2&id=<?php echo SqlFilter($_REQUEST["a"],"int"); ?>",
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

        toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect styleselect forecolor backcolor | code preview",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | undo redo | link unlink image media | inserttime | table",

        menubar: false,
        language: 'zh_TW',
        height: 600,
    });
</script>