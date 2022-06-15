<?php

/*****************************************/
//檔案名稱：singleweb_fun22.php
//後台對應位置：約會專家系統/GT活動管理>新增GT活動
//改版日期：2022.6.1
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");
require_once("./include/_top.php");
require_once("./include/_sidebar_single.php");

//程式開始 *****
if ($_SESSION["MM_Username"] == "") {
    call_alert("請重新登入。", "login.php", 0);
}

if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["singleweb"] != "1"){
    call_alert("您沒有查看此頁的權限。", "login.php", 0);
}

// 上傳圖檔(待測)
if($_REQUEST["st"] == "upload"){
    if($_REQUEST["an"] == ""){
        echo "error";
    }

    $an = SqlFilter($_REQUEST["an"],"int");
    if ($_FILES['fileupload']['error'] === UPLOAD_ERR_OK){
        $urlpath = "singleparty_image/event_201220/"; //儲存路徑
        $ext = pathinfo($_FILES["fileupload"]["name"], PATHINFO_EXTENSION); //附檔名      
        $fileName = date("Y").date("n").date("j").date("H").date("i").date("s")."_event_201220_".rand(1,1000).".".$ext; //檔名
        move_uploaded_file($_FILES["fileupload"]["tmp_name"],($urlpath.$fileName)); //儲存檔案

        // 更新圖檔名稱
        $SQL = "SELECT d5 FROM si_webdata where auton='".$an."'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $d5 = $result["d5"];
            if($d5 != ""){
                $d5 = $d5 . "," . $fileName;
            }else{
                $d5 = $fileName;
            }
            
            $SQL = "UPDATE si_webdata SET d5 = '".$d5."' where auton='".$an."'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }
        
        echo $fileName;
        exit();
    }
}

//刪除圖檔(待測)
if($_REQUEST["st"] == "delimg"){
    $SQL = "SELECT * FROM si_webdata where auton='".SqlFilter($_REQUEST["an"],"int")."'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $d5 = $result["d5"];
        $ff = SqlFilter($_REQUEST["f"],"tab");
        $newd5 = "";

        foreach(explode(",",$d5) as $dd5){
            if($dd5 == $ff){
                DelFile("singleparty_image/event_201220/".$ff);
            }else{
                $newd5 = $newd5.",".$dd5;
            }
        }

        $newd5 = clear_left_par($newd5,",");
        if($newd5 == ""){
            $SQL = "UPDATE si_webdata SET d5=NULL where auton='".SqlFilter($_REQUEST["an"],"int")."'";
        }else{
            $SQL = "UPDATE si_webdata SET d5='".$newd5."' where auton='".SqlFilter($_REQUEST["an"],"int")."'";
        }
        $rs = $SPConn->prepare($SQL);
        $rs->execute();

    }else{
        call_alert("資料讀取錯誤。","ClOsE",0);
    }

    reURL("singleweb_fun22_add.php?an=".SqlFilter($_REQUEST["an"],"int"));
}

//更新或新增
if($_REQUEST["st"] == "add"){
    $n1 = SqlFilter($_REQUEST["n1"],"tab");
    if($n1 != ""){
        $n1 = str_replace(PHP_EOL,"<br>",$n1);
    }
    $n2 = SqlFilter($_REQUEST["n1"],"tab");
    if($n2 != ""){
        $n2 = str_replace(PHP_EOL,"<br>",$n2);
    }
    $n3 = SqlFilter($_REQUEST["n1"],"tab");
    if($n3 != ""){
        $n3 = str_replace(PHP_EOL,"<br>",$n3);
    }
    if($_REQUEST["an"] != ""){        
        //更新
        $SQL = "SELECT * FROM si_webdata where auton='".SqlFilter($_REQUEST["an"],"int")."'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){  
            $SQL = "UPDATE si_webdata SET d1='".SqlFilter($_REQUEST["d1"],"tab")."', d2='".SqlFilter($_REQUEST["d2"],"tab")."', d3='".SqlFilter($_REQUEST["d3"],"tab")."', d4='".SqlFilter($_REQUEST["d4"],"tab")."', t1='".SqlFilter($_REQUEST["t1"],"tab")."', types='event_201220', times='".date("Y/m/d H:i:s")."', n1='".$n1."', n2='".$n2."', n3='".$n3."' where auton='".SqlFilter($_REQUEST["an"],"int")."'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();            

        }else{
            call_alert("資料讀取錯誤。","ClOsE",0);
        }
    }else{
        //新增
        $SQL = "SELECT top 1 i1 FROM si_webdata where types='event_201220' order by i1 desc";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $i1 = $result["i1"] + 1;
        }else{
            $i1 = 1;
        }
        $SQL = "INSERT INTO si_webdata (d1,d2,d3,d4,t1,i1,types,times,n1,n2,n3) VALUES ('".SqlFilter($_REQUEST["d1"],"tab")."','".SqlFilter($_REQUEST["d2"],"tab")."','".SqlFilter($_REQUEST["d3"],"tab")."','".SqlFilter($_REQUEST["d4"],"tab")."','".SqlFilter($_REQUEST["t1"],"tab")."','".$i1."','event_201220','".date("Y/m/d H:i:s")."','".$n1."','".$n2."','".$n3."')";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }

    reURL("singleweb_fun22.php");
}

//讀取資料
if($_REQUEST["an"] != ""){
    $SQL = "SELECT * FROM si_webdata where auton='".SqlFilter($_REQUEST["an"],"int")."'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){ 
        $d1 = $result["d1"];  
        $d2 = $result["d2"];
        $d3 = $result["d3"];
        $d4 = $result["d4"];
        $d5 = $result["d5"];	  	  
        $t1 = $result["t1"];
        $n1 = $result["n1"];
        $n2 = $result["n2"];
        $n3 = $result["n3"];
        if($n1 != ""){
            $n1 = str_replace("<br>",PHP_EOL,$n1);
        }
        if($n2 != ""){
            $n2 = str_replace("<br>",PHP_EOL,$n2);
        }
        if($n3 != ""){
            $n3 = str_replace("<br>",PHP_EOL,$n3);
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
            <li><a href="singleweb_fun22.php">GT活動管理</a></li>
            <li class="active"><?php echo $fsb; ?>GT活動</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong><?php echo $fsb; ?>GT活動</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form action="?st=add<?php echo $fsq; ?>" method="post" id="form1" class="form-inline">
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <tbody>
                            <tr>
                                <td>
                                    活動名稱：<input name="d1" type="text" id="d1" value="<?php echo $d1; ?>" style="width:80%" class="form-control" required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    時間：<input type="text" name="t1" id="t1" value="<?php echo Date_EN($t1,1); ?>" class="datepicker" autocomplete="off">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    地點：<input name="d3" type="text" id="d3" value="<?php echo $d3; ?>" style="width:80%" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    類型：<input name="d2" type="text" id="d2" value="<?php echo $d2; ?>" style="width:80%" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    價錢：<input name="d4" type="text" id="d4" value="<?php echo $d4; ?>" style="width:80%" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    主旨： <textarea type="text" name="n3" id="n3" class="form-control" style="width:80%;height:100px;font-size:13px;" rows=4 required><?php echo $n3; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    簡介： <textarea type="text" name="n1" id="n1" class="form-control" style="width:80%;height:100px;font-size:13px;" rows=4 required><?php echo $n1; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    特色： <textarea type="text" name="n2" id="n2" class="form-control" style="width:80%;height:100px;font-size:13px;" rows=4 required><?php echo $n2; ?></textarea>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="col-md-12 margin-bottom-10">圖片：
                                        <?php 
                                            if($_REQUEST["an"] != ""){ ?>
                                                <span class="btn btn-danger fileinput-button"><span>選擇檔案</span><input data-no-uniform="true" id="file_uploads" type="file" class="fileupload" name="fileupload"></span>
		                                        <div id="progress" class="progress progress-striped" style="display:none"><div class="bar progress-bar progress-bar-lovepy"></div></div>
                                            <?php }
                                        ?>
                                    </div>
                                    <?php 
                                        if($_REQUEST["an"] != ""){ ?>
                                            <span id="img_div">
                                                <?php 
                                                    if($d5 != ""){
                                                        foreach(explode(",",$d5) as $dd5){
                                                            echo "<div class='col-md-2'><img height=200 src='singleparty_image/event_201220/".$dd5."' border=0><br><a href='?st=delimg&an=".SqlFilter($_REQUEST["an"],"int")."&f=".$dd5."'><i class='fa fa-remove text-danger'></i>刪圖</a></div>";
                                                        }
                                                    }
                                                ?>
                                            </span>
                                        <?php }else{
                                            echo "建立活動後方可上傳圖片";
                                        } ?>                                   

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
    $mtu = "singleweb_fun22.";

    $(function() {

        $("#file_uploads").fileupload({
                url: "singleweb_fun22_add.php?st=upload&an=<?php echo SqlFilter($_REQUEST["an"],"int"); ?>",
                type: "POST",
                dropZone: $(this),
                dataType: 'html',
                autoUpload: false,
                done: function(e, data) {
                    //	console.log(data);
                    if (data.result) {
                        //	console.log(data.result);	
                        location.reload();
                    }
                },
                fail: function(e, data) {
                    console.log(data);
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
                        data.submit();
                    }
                }
            }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');

    });
</script>