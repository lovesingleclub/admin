<?php
/*****************************************/
//檔案名稱：dmnweb_fun4_add.php
//後台對應位置：DateMeNow網站系統/新增&修改談情說愛
//改版日期：2022.8.23
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/
require_once("_inc.php");
require_once("./include/_function.php");
//ajax非同步上傳圖檔(待測)
if($_REQUEST["st"] == "upload"){
    $p = $_REQUEST["p"];
    if($p == ""){
        echo "err";
        exit();
    }

    if($_REQUEST["id"] != "" && $p != ""){
        $SQL = "select pic1 from lovestory where id='".SqlFilter($_REQUEST["id"],"int")."'";
        $rs = $DMNConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $ppic = $result["pic".$p];
            if($ppic != ""){
                DelFile("datemenow_image/upload/".$ppic); //刪除圖檔
            }
        }
    }

    if ($_FILES['fileupload']['error'] === UPLOAD_ERR_OK) {
        $urlpath = "datemenow_image/upload/"; //儲存路徑
        $ext = pathinfo($_FILES["fileupload"]["name"], PATHINFO_EXTENSION); //附檔名      
        $fileName = date("Y").date("n").date("j").date("H").date("i").date("s")."_".$p."_".rand(1,5000).".".$ext; //檔名
        move_uploaded_file($_FILES["fileupload"]["tmp_name"], ($urlpath . $fileName)); //儲存檔案
        
        echo $fileName;
        exit();
    }else{
        echo "err";
        exit();
    }
}
require_once("./include/_top.php");
require_once("./include/_sidebar_dmn.php");

function lovestory_name($n){
    switch($n){
        case "1":
        case "跟我說愛情":
            if($n==1){
                $lovestory_name = "跟我說愛情";
            }else{
                $lovestory_name = "1";
            }
            break;
        case "2":
        case "跟我做心測":
                if($n==2){
                    $lovestory_name = "跟我做心測";
                }else{
                    $lovestory_name = "2";
                }
                break;
        case "3":
        case "跟我變Beauty":
                if($n==3){
                    $lovestory_name = "跟我變Beauty";
                }else{
                    $lovestory_name = "3";
                }
                break;
        case "4":
        case "跟我看花絮":
                if($n==4){
                    $lovestory_name = "跟我看花絮";
                }else{
                    $lovestory_name = "4";
                }
                break;
        case "5":
        case "跟我挖新聞":
                if($n==5){
                    $lovestory_name = "跟我挖新聞";
                }else{
                    $lovestory_name = "5";
                }
                break;
        case "6":
        case "跟我玩樂趣":
                if($n==6){
                    $lovestory_name = "跟我玩樂趣";
                }else{
                    $lovestory_name = "6";
                }
                break;
        case "7":
        case "跟我談兩性":
                if($n==7){
                    $lovestory_name = "跟我談兩性";
                }else{
                    $lovestory_name = "7";
                }
                break;
        case "8":
        case "跟我享好康":
                if($n==8){
                    $lovestory_name = "跟我享好康";
                }else{
                    $lovestory_name = "8";
                }
                break;
        case "9":
        case "跟我聊星座":
                if($n==9){
                    $lovestory_name = "跟我聊星座";
                }else{
                    $lovestory_name = "9";
                }
                break;        
    }
    return $lovestory_name;
}

// 程式開始
if($_SESSION["MM_Username"] == ""){
    call_alert("請重新登入。","login.php",0);
}

if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["dmnweb"] != "1"){
    call_alert("您沒有查看此頁的權限。","login.php",0);
}

//刪除(待測)
if($_REQUEST["st"] == "delpic" && $_REQUEST["id"] != ""){
    $SQL = "select pic1 from lovestory where id='".SqlFilter($_REQUEST["id"],"int")."'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $ppic = $result[("pic".$_REQUEST["a"])];
        if($ppic != ""){
            DelFile("datemenow_image/upload/".$ppic); //刪除圖檔
        }
        $SQL = "UPDATE lovestory SET pic".$_REQUEST["a"]." = NULL WHERE id='".SqlFilter($_REQUEST["id"],"int")."'";
        $rs = $DMNConn->prepare($SQL);
        $rs->execute();
    }
    reURL("dmnweb_fun4_add.php?act=up&id=".SqlFilter($_REQUEST["id"],"int"));
}

$content = str_replace(PHP_EOL,"",$_REQUEST["content"]);
$content = str_replace("<!DOCTYPE html><html><head></head><body>","",$content);
$content = str_replace("</body></html>","",$content);

//新增
if($_REQUEST["acts"] == "ad"){
    if(!is_numeric($_REQUEST["hits"])){
        call_alert("人氣只能輸入數字。", 0, 0);
    }
    $SQL = "select top 1 des from lovestory where category_id='".SqlFilter($_REQUEST["category_id"],"int")."' order by des desc";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $ltd = $result["des"] + 1; 
    }else{
        $ltd = "";
    }
    if($_REQUEST["pic1"]!=""){
        $pic1 = SqlFilter($_REQUEST["pic1"],"tab");
    }else{
        $pic1 = "";
    }
    $SQL = "INSERT INTO lovestory (category_id, name, writer, hits, create_time, content, pic1, times, des) VALUES ('".SqlFilter($_REQUEST["category_id"],"int")."', '".SqlFilter($_REQUEST["name"],"tab")."', '".SqlFilter($_REQUEST["writer"],"tab")."', '".SqlFilter($_REQUEST["hits"],"int")."', '".SqlFilter($_REQUEST["create_time"],"tab")."', '".$content."', '".$pic1."', '".date("Y/m/d H:i:s")."', '".$ltd."')";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();;
    reURL("dmnweb_fun4.php");
}

//更新
if($_REQUEST["acts"] == "up"){
    if(!is_numeric($_REQUEST["hits"])){
        call_alert("人氣只能輸入數字。", 0, 0);
    }
    $SQL = "UPDATE lovestory SET category_id='".SqlFilter($_REQUEST["category_id"],"int")."', name='".SqlFilter($_REQUEST["name"],"tab")."', writer='".SqlFilter($_REQUEST["writer"],"tab")."', hits='".SqlFilter($_REQUEST["hits"],"int")."', create_time='".SqlFilter($_REQUEST["create_time"],"tab")."', content='".$content."', pic1='".SqlFilter($_REQUEST["pic1"],"tab")."' WHERE id = '".SqlFilter($_REQUEST["pid"],"int")."'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    reURL("dmnweb_fun4.php");
}

$nows = date("Y/m/d H:i:s");
$hits = 0;
if($_REQUEST["act"] == "up" && $_REQUEST["id"] != 0){
    $SQL = "Select * from lovestory where id = '".SqlFilter($_REQUEST["id"],"int")."'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $id = $result["id"];
        $category_id = $result["category_id"];
        $name = $result["name"];
        $writer = $result["writer"];    
        $hits = $result["hits"];     
        $create_time = Date_EN($result["create_time"],6);
        $content = $result["content"];
        if($content != ""){
            $content = str_replace("<br>",PHP_EOL,$content);
        }
        $nows = Date_EN($result["times"],6);
        $pic1 = $result["pic1"];
        $upurl = "&id=".$id;
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
            <li>DateMeNow網站系統</li>
            <li><a href="dmnweb_fun4.php">談情說愛</a></li>
            <li class="active">新增/修改談情說愛</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>新增/修改談情說愛</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form id="mform" method="post" action="dmnweb_fun4_add.php" onSubmit="return chk_form()" class="form-inline">
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <tbody>
                            <tr>
                                <td width="150" align="left" valign="middle">分類</td>
                                <td>
                                    <select name="category_id" id="category_id">
                                        <?php 
                                            for($i=1;$i<=8;$i++){
                                                if($category_id == $i){
                                                    echo "<option value=".$i." selected>".lovestory_name($i)."</option>";
                                                }else{
                                                    echo "<option value=".$i.">".lovestory_name($i)."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                            <tr>
                                <td width="150" align="left" valign="middle">標題</td>
                                <td><input name="name" id="name" value="<?php echo $name; ?>" class="form-control" style="width:60%;" required></td>
                            </tr>
                            <tr>
                                <td width="150" align="left" valign="middle">作者</td>
                                <td><input name="writer" id="writer" value="<?php echo $writer; ?>" class="form-control"></td>
                            </tr>
                            <tr>
                                <td width="150" align="left" valign="middle">日期</td>
                                <td colspan=3><input name="create_time" id="create_time" value="<?php echo $create_time; ?>" class="datepicker" autocomplete="off"></td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">建立時間</td>
                                <td><?php echo $nows; ?></td>
                            </tr>
                            <tr>
                                <td width="150" align="left" valign="middle">人氣</td>
                                <td><input name="hits" id="hits" value="<?php echo $hits; ?>" class="form-control">(數字)</td>
                            </tr>

                            <tr>
                                <td align="left" valign="middle">內文</td>
                                <td><textarea name="content" class="editor" style="width:80%;height:350px;"><?php echo $content; ?></textarea></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    示意圖<br>
                                    <span id="img_div">
                                        <?php 
                                            if($pic1 != ""){ ?>
                                                <a href="?st=delpic&a=1&id=<?php echo $_REQUEST["id"]; ?>">刪除</a><br>
			                                    <img height=200 src="datemenow_image/upload/<?php echo $pic1; ?>?t=<?php echo rand(1,9999) ?>" border=0>
                                            <?php }
                                        ?>
                                    </span>
                                    <p></p>
                                    <span class="btn btn-danger fileinput-button"><span>上傳檔案</span><input data-p="1" id="file_uploads1" type="file" class="fileupload" name="fileupload"></span>
                                    <div id="progress" class="progress progress-striped" style="display:none">
                                        <div class="bar progress-bar progress-bar-lovepy"></div>
                                    </div>
                                    <div id="file_uploads1_show"></div>
                                    <input type="hidden" name="pic1" id="pic1" value="<?php echo $pic1; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2 align="center"><input name="acts" id="acts" type="hidden" value="<?php echo $_REQUEST["act"]; ?>"><input name="pid" type="hidden" id="pid" value="<?php echo $_REQUEST["id"]; ?>">
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
require_once("./include/_bottom.php")
?>

<script type="text/javascript" src="assets/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.fileupload.js"></script>
<script src="js/tinymce/tinymce.min.js"></script>

<script language="JavaScript">
    $mtu = "dmnweb_fun4.";
    var $fsend = 0;
    var $ff = [];

    function chk_form() {
        if ($ff.length > 0) {
            $.each($ff, function() {
                $(this).submit();
            });
            return false;
        }
        return true;
    }
    $(function() {
        $(".fileupload").each(function() {

            var $this = $(this),
                $thisid = $this.attr("id"),
                $progress = $this.closest("td").find(".progress"),
                $p = $this.data("p");

            $this.fileupload({
                    url: "dmnweb_fun4_add.php?st=upload<?php echo $upurl; ?>&p=" + $p,
                    type: "POST",
                    dropZone: $this,
                    dataType: 'html',
                    autoUpload: false,
                    done: function(e, data) {

                        switch (data.result) {
                            case "err":
                                $progress.find(".progress-bar").css("width", "0px").stop().parent().hide();
                                alert("上傳出錯。");
                                break;
                            default:
                                $("#pic" + $p).val(data.result);
                                if ($fsend + 1 >= $ff.length) {
                                    $ff.length = 0;
                                    $("#mform").submit();
                                } else $fsend++;
                                break;
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
                            $("#" + $thisid + "_show").html(data.files[data.files.length - 1].name);
                            $ff.push(data);
                        }
                    }
                }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');

        });
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
        height: 600
    });
</script>