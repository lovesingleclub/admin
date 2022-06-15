<?php

/*****************************************/
//檔案名稱：springweb_fun6.php
//後台對應位置：約會專家系統/戀愛學院-豪華講師>新增/修改講師資料
//改版日期：2022.5.23
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");
//ajax上傳圖檔(待測)
if($_REQUEST["st"] == "upload"){
    $id = SqlFilter($_REQUEST["id"],"int");
    if ($_FILES['fileupload']['error'] === UPLOAD_ERR_OK){
        $urlpath = "singleparty_image/salon/"; //儲存路徑
        $ext = pathinfo($_FILES["fileupload"]["name"], PATHINFO_EXTENSION); //附檔名      
        $fileName = date("Y").date("n").date("j").date("H").date("i").date("s")."_singleweb_fun6_".rand(1,1000).".".$ext; //檔名
        move_uploaded_file($_FILES["fileupload"]["tmp_name"],($urlpath.$fileName)); //儲存檔案
        
        echo $fileName;        	        
    }
    exit(); 
}
require_once("./include/_top.php");

//程式開始 *****
if ($_SESSION["MM_Username"] == "") {
    call_alert("請重新登入。", "login.php", 0);
}

// if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["singleweb"] != "1"){
//     call_alert("您沒有查看此頁的權限。", "login.php", 0);
// }

$notes = str_replace("<br>",PHP_EOL,$_REQUEST["notes"]);
$notes = str_replace("<!DOCTYPE html><html><head></head><body>", "",$notes);
$notes = str_replace("</body></html>", "",$notes);

$app_notes = $_REQUEST["app_notes"];

//新增
if($_REQUEST["acts"] == "ad"){
    $SQL = "select top 1 descs from si_salon_teacher order by descs desc";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $ltd = $result["descs"] + 1;
    }else{
        $ltd = 1;
    }

    if($_REQUEST["consult_open"] == "1"){
        $consult_open = 1;
    }else{
        $consult_open = 0;
    }

    if($_REQUEST["shows"] == "1"){
        $shows = 1;
    }else{
        $shows = 0;
    }

    if($_REQUEST["review"] == "1"){
        $review = 1;
    }else{
        $review = 0;
    }

    $SQL = "INSERT INTO si_salon_teacher (title,name,notes,times,descs,pics,p_auto,b2b_uid, consult_open, app_notes, show, single, singlename,review,tag) VALUES ('".SqlFilter($_REQUEST["title"],"tab")."','".SqlFilter($_REQUEST["name"],"tab")."',";
    $SQL = $SQL . "'".$notes."',GetDate(),".$ltd.",'".SqlFilter($_REQUEST["pics"],"tab")."','".SqlFilter($_REQUEST["p_auto"],"int")."','".SqlFilter($_REQUEST["b2b_uid"],"tab")."', ".$consult_open.", '".$app_notes."', '".$shows."','".$_SESSION["MM_Username"]."','".$_SESSION["pname"]."','".$review."','".SqlFilter($_REQUEST["tag"],"tab")."')";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    
    if($_SESSION["MM_UserAuthorization"] == "admin"){
        reURL("singleweb_fun6.php");
    }else{
        call_alert("新增完成，請通知總公司審核。 ", "singleweb_fun6.php", 0);
    }
}

//更新
if($_REQUEST["acts"] == "up"){
    if($_REQUEST["consult_open"] == "1"){
        $consult_open = 1;
    }else{
        $consult_open = 0;
    }

    if($_REQUEST["shows"] == "1"){
        $shows = 1;
    }else{
        $shows = 0;
    }

    if($_REQUEST["review"] == "1"){
        $review = 1;
    }else{
        $review = 0;
    }
    $SQL = "update si_salon_teacher set title = '".SqlFilter($_REQUEST["title"],"tab")."',name = '".SqlFilter($_REQUEST["name"],"tab")."'";
	$SQL = $SQL . ",notes='".$notes."' , pics='".SqlFilter($_REQUEST["pics"],"tab")."', p_auto='".SqlFilter($_REQUEST["p_auto"],"int")."',b2b_uid='".SqlFilter($_REQUEST["b2b_uid"],"tab")."', consult_open=".$consult_open.", app_notes = '".$app_notes."', show = '".$shows."',review='".$review."',tag='".SqlFilter($_REQUEST["tag"],"tab")."'  where auton = ".SqlFilter($_REQUEST["pid"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();

    reURL("singleweb_fun6.php");
}

//讀取
if($_REQUEST["act"] == "up" && $_REQUEST["id"] != 0){
    $SQL = "Select * from si_salon_teacher where auton = ".SqlFilter($_REQUEST["id"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $title = $result["title"];
        $names = $result["name"];
        $pics = $result["pics"];
        $notes = $result["notes"];       
        $p_auto = $result["p_auto"];
        $b2b_uid = $result["b2b_uid"];
	    $consult_open = $result["consult_open"];
	    $app_notes = $result["app_notes"];
        $shows = $result["show"];
        $pname = $result["singlename"];
        $review = $result["review"];
        $tag = $result["tag"];
        if($notes != ""){
            $notes = str_replace("<br>",PHP_EOL,$notes);
        }
        if($app_notes != ""){
            $app_notes = str_replace("<br>",PHP_EOL,$app_notes);
        }
    }
}

switch($_SESSION["MM_UserAuthorization"]){
    case "admin":
        $power_edit = 1;       
        include_once("./include/_sidebar_single.php");
        break;
    default:
        $power_edit = 0;
        include_once("./include/_sidebar.php");
}

if($_REQUEST["act"] == "up"){
    $act = "up";
}else{
    $act = "ad";
}

?>

<!-- MIDDLE -->
<link rel="stylesheet" href="css/jquery.fileupload.css">
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
<noscript><link rel="stylesheet" href="css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="css/jquery.fileupload-ui-noscript.css"></noscript>
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>約會專家系統</li>
            <li>戀愛學院-豪華講師</li>
            <li class="active">新增/修改講師資料</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>新增/修改講師資料</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form id="mform" method="post" action="singleweb_fun6_add.php" onSubmit="return chkform()" class="form-inline">
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <tbody>
                            <tr>
                                <td width="150" align="left" valign="middle">職稱</td>
                                <td><input name="title" id="title" value="<?php echo $title; ?>" class="form-control" required></td>
                            </tr>
                            <tr>
                            <tr>
                                <td width="150" align="left" valign="middle">姓名</td>
                                <td><input name="name" id="name" value="<?php echo $names; ?>" class="form-control" required></td>
                            </tr>
                            <?php 
                                if($power_edit == 1){ ?>
                                    <tr>
                                        <td width="150" align="left" valign="middle">對應秘書編號</td>
                                        <td><input name="p_auto" id="p_auto" value="<?php echo $p_auto; ?>" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td width="150" align="left" valign="middle">委外廠商系統帳號對應</td>
                                        <td><input name="b2b_uid" id="b2b_uid" value="<?php echo $b2b_uid; ?>" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td width="150" align="left" valign="middle"></td>
                                        <td><label class="checkbox"><input type="checkbox" name="consult_open" id="consult_open" value="1"<?php if($consult_open == 1) echo " checked"; ?>><i></i> 開放情感諮詢</label>
                                            <label class="checkbox"><input type="checkbox" name="shows" id="shows" value="1"<?php if($shows == 1) echo " checked"; ?>><i></i> 顯示</label>
                                            <label class="checkbox"><input type="checkbox" name="review" id="review" value="1"<?php if($review == 1) echo " checked"; ?>><i></i> 審核通過</label>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td width="150" align="left" valign="middle">TAG</td>
                                        <td><input name="tag" id="tag" value="<?php echo $tag; ?>" class="form-control" style="width:60%;"><small>(逗號分隔)</small></td>
                                    </tr>
                                <?php }                            
                            ?>
                            <tr>
                                <td align="left" valign="middle">建立時間</td>
                                <td><?php echo changeDate(date("Y/m/d H:i:s")); ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">建立人</td>
                                <td><?php echo $pname; ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">講師照片</td>
                                <td>
                                    <?php
                                        if($pics != ""){
                                            $img = "<a href='singleparty_image/salon/".$pics."' class='fancybox'><img src='singleparty_image/salon/".$pics."' width=250 border=0></a>";
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

                            </tr>
                            <tr>
                                <td align="left" valign="middle">內文</td>
                                <td><textarea name="notes" class="editor" style="width:80%;height:150px;"><?php echo $notes; ?></textarea></td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">內文-APP用</td>
                                <td><textarea name="app_notes" style="width:80%;height:250px;"><?php echo $app_notes; ?></textarea></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td align="center"><input name="pics" id="pics" type="hidden" value="<?php echo $pics; ?>"><input name="acts" id="acts" type="hidden" value="<?php echo $act; ?>"><input name="pid" type="hidden" id="pid" value="<?php echo SqlFilter($_REQUEST["id"],"int"); ?>">
                                    <input type="submit" value="確認送出" class="btn btn-info" style="width:50%;">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
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
require("./include/_bottom.php");
?>

<script type="text/javascript" src="assets/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.fileupload.js"></script>
<script src="js/tinymce/tinymce.min.js"></script>
<script language="JavaScript">
    $mtu = "singleweb_fun6.";
    var $fsend = 0;
    var $ff;

    function chkform() {
        if ($ff && !$fsend) {
            $ff.submit();
            return false;
        }
        return true;
    }
    $(function() {

        var $file_uploads1 = $("#file_uploads1"),
            $thisid = $file_uploads1.attr("id"),
            $progress = $file_uploads1.closest("div").find(".progress");
        var $imgs = $file_uploads1.closest("span").find("#cimg").val();

        $file_uploads1.fileupload({
                url: "singleweb_fun6_add.php?st=upload&pics=<?php echo $pics; ?>",
                type: "POST",
                dropZone: $file_uploads1,
                dataType: 'html',
                autoUpload: false,
                done: function(e, data) {

                    if (data.result) {
                        $("#pics").val(data.result);
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
    });
    tinymce.init({
        selector: ".editor",
        plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons template textcolor paste fullpage textcolor"
        ],

        toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | fontsizeselect forecolor backcolor | code preview",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | undo redo | link unlink image media | inserttime | table",

        menubar: false,
        toolbar_items_size: 'small',
        language: 'zh_TW',
        height: 300
    });
</script>