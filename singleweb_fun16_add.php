<?php

/*****************************************/
//檔案名稱：singleweb_fun16_add.php
//後台對應位置：約會專家系統/主題活動管理>新增主題活動
//改版日期：2022.5.31
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");
// 上傳&刪除圖檔(待測)
if($_REQUEST["st"] == "upload"){
    $d5 = SqlFilter($_REQUEST["d5b"],"tab");
    if ($_FILES['fileupload']['error'] === UPLOAD_ERR_OK){
        $urlpath = "singleparty_image/event_custom/"; //儲存路徑
        $ext = pathinfo($_FILES["fileupload"]["name"], PATHINFO_EXTENSION); //附檔名      
        $fileName = date("Y").date("n").date("j").date("H").date("i").date("s")."_event_custom_".rand(1,1000).".".$ext; //檔名
        move_uploaded_file($_FILES["fileupload"]["tmp_name"],($urlpath.$fileName)); //儲存檔案

        //如果D5不為空則刪圖檔
        if($d5 != "" && $fileName != ""){
            DelFile($urlpath.$d5);
        }
        echo $fileName;
        exit();
    }
}
require_once("./include/_top.php");
require_once("./include/_sidebar_single.php");

// 新增
if($_REQUEST["st"] == "add"){
    if($_REQUEST["d2"] != ""){
        $d2 = SqlFilter($_REQUEST["d2"],"tab");
    }else{
        $d2 = "";
    }
    if($_REQUEST["d3"] != ""){
        $d3 = SqlFilter($_REQUEST["d3"],"tab");
    }else{
        $d3 = "";
    }
    if($_REQUEST["d5"] != ""){
        $d5 = SqlFilter($_REQUEST["d5"],"tab");
    }else{
        $d5 = "";
    }
    if($_REQUEST["t1"] != ""){
        $t1 = SqlFilter($_REQUEST["t1"],"tab");
    }else{
        $t1 = "";
    }

    if($_REQUEST["an"] != ""){
        $SQL = "SELECT * FROM si_webdata where auton='".SqlFilter($_REQUEST["an"],"int")."'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if(!$result){
            call_alert("資料讀取錯誤。","ClOsE",0);
        }else{
            //修改
            $SQL = "UPDATE si_webdata SET d1='".SqlFilter($_REQUEST["d1"],"tab")."', d2='".$d2."', d3='".$d3."', d4='".SqlFilter($_REQUEST["d4"],"tab")."', d5='".$d5."', t1='".$t1."', n1='".str_replace(PHP_EOL,"<br>",$_REQUEST["n1"])."', types='event_custom', times='".date("Y/m/d H:i:s")."' WHERE auton='".SqlFilter($_REQUEST["an"],"int")."'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }        
    }else{
        // 新增
        $SQL = "SELECT top 1 i1 FROM si_webdata where types='event_custom' order by i1 desc";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $i1 = $result["i1"] + 1;
        }else{
            $i1 = 1;
        }        
        
        $SQL = "INSERT INTO si_webdata (d1, d2, d3, d4, d5, t1, i1, n1, types, times) VALUES ('".SqlFilter($_REQUEST["d1"],"tab")."','".$d2."','".$d3."','".SqlFilter($_REQUEST["d4"],"tab")."','".$d5."','".$t1."','".$i1."','".str_replace(PHP_EOL,"<br>",$_REQUEST["n1"])."','event_custom','".date("Y/m/d H:i:s")."')";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }

    reURL("singleweb_fun16.php");        
}

//讀取
if($_REQUEST["an"] != ""){
    $SQL = "SELECT * FROM si_webdata where auton=".SqlFilter($_REQUEST["an"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $t1 = $result["t1"];
        $n1 = str_replace(PHP_EOL,"<br>",$result["n1"]);
        $d1 = $result["d1"];    
        $d2 = $result["d2"];
        $d3 = $result["d3"];
        $d4 = $result["d4"];	  
        $d5 = $result["d5"];
        if($d5 != ""){
            $d5b = "&d5b=".$d5;
        }
        $fsq = "&an=".SqlFilter($_REQUEST["an"],"int");
	    $fsb = "修改";
    }else{
        call_alert("資料讀取錯誤。","ClOsE",0);
    }
}else{
    $fsb = "新增";
	$fsq = "";
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
            <li><a href="singleweb_fun16.php">主題活動管理</a></li>
            <li class="active"><?php echo $fsb; ?>主題活動</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong><?php echo $fsb; ?>主題活動</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form action="?st=add<?php echo $fsq; ?>" method="post" id="form1" onSubmit="return chk_form()" class="form-inline">
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <tbody>
                            <tr>
                                <td>
                                    活動標題：<input name="d1" type="text" id="d1" value="<?php echo $d1; ?>" style="width:80%" class="form-control" required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    愛心小標：<input name="d2" type="text" id="d2" value="<?php echo $d2; ?>" style="width:80%" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    時間小標：<input type="text" name="t1" id="t1" value="<?php echo Date_EN($t1,1); ?>" class="datepicker" autocomplete="off">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    房屋小標：<input name="d3" type="text" id="d3" value="<?php echo $d3; ?>" style="width:80%" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    活動說明： <textarea type="text" name="n1" id="n1" class="form-control" style="width:80%;height:100px;font-size:13px;" rows=4 required><?php echo str_replace("<br>",PHP_EOL,$n1); ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    活動連結：<input name="d4" type="text" id="d4" class="form-control" value="<?php echo $d4; ?>" style="width:80%" required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="width:40%;">列表圖：
                                        <span id="img_div">
                                            <?php 
                                                if($d5 != ""){ ?>
                                                    <img height=200 src="singleparty_image/event_custom/<?php echo $d5; ?>" border=0>
                                                <?php }
                                            ?>
                                        </span>
                                        <p></p>
                                        <span class="btn btn-danger fileinput-button"><span>選擇檔案</span><input data-no-uniform="true" id="file_uploads" type="file" class="fileupload" name="fileupload"></span>
                                        <div id="progress" class="progress progress-striped" style="display:none">
                                            <div class="bar progress-bar progress-bar-lovepy"></div>
                                        </div>
                                    </div>
                                    <div id="fileupload_show"></div>
                                    <input type="hidden" name="d5" id="d5" value="<?php echo $d5; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div align="center">

                                        <input id="submit3" type="submit" value="確定<?php echo $fsb; ?>" class="btn btn-info" style="width:50%;">
                                    </div>
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
require_once("./include/_bottom.php");
?>

<script type="text/javascript" src="assets/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.fileupload.js"></script>
<script type="text/javascript">
    $mtu = "singleweb_fun16.";
    var $fissend = 0;
    var $ff;
    var $ff2;

    function chk_form() {
        if ($fissend) return true;
        if (!$ff && !$ff2) return true;
        else if ($ff) {
            $ff.submit();
            return false;
        } else if ($ff2) {
            $ff2.submit();
            return false;
        }

        return false;
    }


    $(function() {

        $("#file_uploads").fileupload({
                url: "singleweb_fun16_add.php?st=upload<?php echo $d5b; ?>",
                type: "POST",
                dropZone: $(this),
                dataType: 'html',
                autoUpload: false,
                done: function(e, data) {

                    if (data.result) {
                        $("#d5").val(data.result);
                        $("#img_div").find("img").remove();
                        $("#img_div").append($("<img width=60 src='singleparty_image/event_custom/" + data.result + "' border=0></img>"));
                        if ($ff2) {
                            $ff2.submit();
                        } else {
                            $fissend = 1;
                            $("#form1").submit();
                        }
                    }
                },
                fail: function(e, data) {

                },
                progressall: function(e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $(this).closest("div").find(".progress").show().find(".progress-bar").css(
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
                        $("#d5").val(data.files[data.files.length - 1].name);
                        $ff = data;
                    }
                }
            }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');


        $("#file_uploads2").fileupload({
                url: "singleweb_fun16_add.php?st=upload<?php echo $d3b; ?>",
                type: "POST",
                dropZone: $(this),
                dataType: 'html',
                autoUpload: false,
                done: function(e, data) {

                    if (data.result) {
                        $("#d3").val(data.result);
                        $("#img_div2").find("img").remove();
                        $("#img_div2").append($("<img width=60 src='singleparty_image/event_custom/" + data.result + "' border=0></img>"));
                        $fissend = 1;
                        $("#form1").submit();
                    }
                },
                fail: function(e, data) {

                },
                progressall: function(e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $(this).closest("div").find(".progress").show().find(".progress-bar").css(
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
                        $("#fileupload_show2").html(data.files[data.files.length - 1].name);
                        $("#d3").val(data.files[data.files.length - 1].name);
                        $ff2 = data;
                    }
                }
            }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');

    });
</script>