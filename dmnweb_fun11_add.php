<?php
/*****************************************/
//檔案名稱：dmnweb_fun11_add.php
//後台對應位置：DateMeNow網站系統/新增&修改幸福故事
//改版日期：2022.8.24
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

    if($_REQUEST["an"] != "" && $p != ""){
        $SQL = "select pic1, pic2, pic3 from happiness where auton='".SqlFilter($_REQUEST["an"],"int")."'";
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

// 程式開始
if ($_SESSION["MM_Username"] == "") {
    call_alert("請重新登入。", "login.php", 0);
}

if ($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["dmnweb"] != "1") {
    call_alert("您沒有查看此頁的權限。", "login.php", 0);
}

//刪除(待測)
if($_REQUEST["st"] == "delpic" && $_REQUEST["a"] != ""){
    $SQL = "select pic1, pic2, pic3 from happiness where auton='".SqlFilter($_REQUEST["an"],"int")."'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $ppic = $result[("pic".$_REQUEST["a"])];
        if($ppic != ""){
            DelFile("datemenow_image/upload/".$ppic); //刪除圖檔
        }
        $SQL = "UPDATE happiness SET pic".$_REQUEST["a"]." = NULL WHERE auton='".SqlFilter($_REQUEST["an"],"int")."'";
        $rs = $DMNConn->prepare($SQL);
        $rs->execute();
    }
    reURL("dmnweb_fun11_add.php?act=up&an=".SqlFilter($_REQUEST["an"],"int"));
}

$content = str_replace(PHP_EOL,"",$_REQUEST["content"]);
$content = str_replace("<!DOCTYPE html><html><head></head><body>","",$content);
$content = str_replace("</body></html>","",$content);

//新增
if($_REQUEST["acts"] == "ad"){
    $SQL = "select top 1 des from happiness order by des desc";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $ltd = $result["des"] + 1; 
    }else{
        $ltd = 1;
    }
    if($_REQUEST["pic1"]!=""){
        $pic1 = SqlFilter($_REQUEST["pic1"],"tab");
    }else{
        $pic1 = "";
    }
    if($_REQUEST["pic2"]!=""){
        $pic2 = SqlFilter($_REQUEST["pic2"],"tab");
    }else{
        $pic2 = "";
    }
    if($_REQUEST["pic3"]!=""){
        $pic3 = SqlFilter($_REQUEST["pic3"],"tab");
    }else{
        $pic3 = "";
    }
    $SQL = "INSERT INTO happiness (title,n1,tag,pic1,pic2,pic3,content,times,des) VALUES ('".SqlFilter($_REQUEST["title"],"tab")."', '".SqlFilter($_REQUEST["n1"],"tab")."', '".SqlFilter($_REQUEST["tag"],"tab")."', '".$pic1."', '".$pic2."', '".$pic3."', '".$content."', '".date("Y/m/d H:i:s")."', '".$ltd."')";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();;
    reURL("dmnweb_fun11.php");    
}

//更新
if($_REQUEST["acts"] == "up"){
    if($_REQUEST["tag"] == "none"){
        $tag = "";
    }else{
        $tag = SqlFilter($_REQUEST["tag"],"tab");
    }
    $SQL = "UPDATE happiness SET title='".SqlFilter($_REQUEST["title"],"tab")."', n1='".htmlspecialchars($_REQUEST["n1"],ENT_QUOTES)."', tag='".$tag."', pic1='".SqlFilter($_REQUEST["pic1"],"tab")."', pic2='".SqlFilter($_REQUEST["pic2"],"tab")."', pic3='".SqlFilter($_REQUEST["pic3"],"tab")."', content='".$content."' WHERE auton = '".SqlFilter($_REQUEST["an"],"int")."'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    reURL("dmnweb_fun11.php");
}

$nows = date("Y/m/d H:i:s");
if($_REQUEST["act"] == "up" && $_REQUEST["an"] != 0){
    $SQL = "Select * from happiness where auton = '".SqlFilter($_REQUEST["an"],"int")."'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $an = $result["an"];
        $title = $result["title"];
        $n1 = $result["n1"];
        $content = $result["content"];
        if($content != ""){
            $content = str_replace("<br>",PHP_EOL,$content);
        }
        $tag = $result["tag"];
        $nows = Date_EN($result["times"],6);
        $pic1 = $result["pic1"];
        $pic2 = $result["pic2"];
        $pic3 = $result["pic3"];
        $upurl = "&an=".$an;
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
            <li><a href="dmnweb_fun11.php">幸福故事</a></li>
            <li class="active">新增/修改幸福故事</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>新增/修改幸福故事</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form id="mform" method="post" action="dmnweb_fun11_add.php" onSubmit="return chk_form()" class="form-inline">
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <tbody>
                            <tr>
                            <tr>
                                <td width="150" align="left" valign="middle">標題</td>
                                <td><input name="title" id="title" value="<?php echo $title; ?>" class="form-control" style="width:60%;" required></td>
                            </tr>
                            <tr>
                                <td width="150" align="left" valign="middle">小字</td>
                                <td><input name="n1" id="n1" value="<?php echo $n1; ?>" class="form-control"></td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">建立時間</td>
                                <td><?php echo $nows; ?></td>
                            </tr>
                            <tr>
                                <td width="150" align="left" valign="middle">標籤(TAG)</td>
                                <td><label><input type="radio" name="tag" id="tag" value="結婚啦"<?php if($tag == "結婚啦") echo " checked"; ?>> 結婚啦</label>
                                    <label><input type="radio" name="tag" id="tag" value="熱戀中"<?php if($tag == "熱戀中") echo " checked"; ?>> 熱戀中</label>
                                    <label><input type="radio" name="tag" id="tag" value="心得"<?php if($tag == "心得") echo " checked"; ?>> 心得</label>
                                    <label><input type="radio" name="tag" id="tag" value="none"> 取消TAG</label>
                                </td>
                            </tr>

                            <tr>
                                <td align="left" valign="middle">內文</td>
                                <td><textarea name="content" class="editor" style="width:80%;height:350px;"><?php echo $content; ?></textarea></td>
                            </tr>

                            <tr>
                                <td></td>
                                <td>
                                    <table>
                                        <tr>
                                            <td>
                                                示意圖1<br>
                                                <span id="img_div">
                                                    <?php 
                                                        if($pic1 != ""){ ?>
                                                            <a href="?st=delpic&a=1&an=<?php echo $_REQUEST["an"]; ?>">刪除</a><br>
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
                                            <td>
                                                示意圖2<br>
                                                <span id="img_div">
                                                    <?php 
                                                        if($pic2 != ""){ ?>
                                                            <a href="?st=delpic&a=2&an=<?php echo $_REQUEST["an"]; ?>">刪除</a><br>
                                                            <img height=200 src="datemenow_image/upload/<?php echo $pic2; ?>?t=<?php echo rand(1,9999) ?>" border=0>
                                                        <?php }
                                                    ?>
                                                </span>
                                                <p></p>
                                                <span class="btn btn-danger fileinput-button"><span>上傳檔案</span><input data-p="2" id="file_uploads2" type="file" class="fileupload" name="fileupload"></span>
                                                <div id="progress" class="progress progress-striped" style="display:none">
                                                    <div class="bar progress-bar progress-bar-lovepy"></div>
                                                </div>
                                                <div id="file_uploads2_show"></div>
                                                <input type="hidden" name="pic2" id="pic2" value="<?php echo $pic2; ?>">
                                            </td>
                                            <td>
                                                示意圖3<br>
                                                <span id="img_div">
                                                    <?php 
                                                        if($pic3 != ""){ ?>
                                                            <a href="?st=delpic&a=3&an=<?php echo $_REQUEST["an"]; ?>">刪除</a><br>
                                                            <img height=200 src="datemenow_image/upload/<?php echo $pic3; ?>?t=<?php echo rand(1,9999) ?>" border=0>
                                                        <?php }
                                                    ?>
                                                </span>
                                                <p></p>
                                                <span class="btn btn-danger fileinput-button"><span>上傳檔案</span><input data-p="3" id="file_uploads3" type="file" class="fileupload" name="fileupload"></span>
                                                <div id="progress" class="progress progress-striped" style="display:none">
                                                    <div class="bar progress-bar progress-bar-lovepy"></div>
                                                </div>
                                                <div id="file_uploads3_show"></div>
                                                <input type="hidden" name="pic3" id="pic3" value="<?php echo $pic3; ?>">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2 align="center"><input name="acts" id="acts" type="hidden" value="<?php echo $_REQUEST["act"]; ?>"><input name="an" type="hidden" id="an" value="<?php echo $_REQUEST["an"]; ?>">
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
    $mtu = "dmnweb_fun11.";
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
                    url: "dmnweb_fun11_add.php?st=upload<?php echo $upurl; ?>&p=" + $p,
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