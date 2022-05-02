<?php
    /*****************************************/
    //檔案名稱：springweb_fun13_add.php
    //後台對應位置：春天網站系統/企業內訓>新增/修改企業內訓
    //改版日期：2022.4.17
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");
    // AJAX上傳圖檔(待測)
	if($_REQUEST["st"] == "upload"){
		$adc_auto = SqlFilter($_REQUEST["adc_auto"],"int");
		if ($_FILES['fileupload2']['error'] === UPLOAD_ERR_OK){
            $urlpath = "upload_image/"; //儲存路徑
            $ext = pathinfo($_FILES["fileupload2"]["name"], PATHINFO_EXTENSION); //附檔名      
            $fileName = date("Y").date("n").date("j").date("H").date("i").date("s")."_springweb_fun13_".rand(1,99).".".$ext; //檔名
			move_uploaded_file($_FILES["fileupload2"]["tmp_name"],($urlpath.$fileName)); //儲存檔案
			
			$SQL = "INSERT INTO web_photo (photo_name, num, types) VALUES ('".$fileName."','".$adc_auto."','enterprise')";
			$rs = $SPConn->prepare($SQL);
			$rs->execute();

			echo $fileName;
        	exit();         
        }		
	}
    // AJAX
	if($_REQUEST["st"] == "pic_div"){
		$SQL = "select * from web_photo where types='enterprise' and num = ".SqlFilter($_REQUEST["adc_auto"],"int")." order by de desc";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		$result = $rs->fetchAll(PDO::FETCH_ASSOC);
		if($result){
			foreach($result as $re){
				if($re["de"] == 1){
					$first = "設為封面";
				}else{
					$first = "<a href='#s1' onclick=\"set_first('".$re["auton"]."')\">設為封面</a>";
				}
				echo "<div style='float:left;padding-left:10px;'>".$first."　<a href='#s1' onclick=\"set_del('".$re["auton"]."')\">刪除</a>";
				echo "<br><a href='upload_image/".$re["photo_name"]."' class='fancybox'><img width=130 src='upload_image/".$re["photo_name"]."'></a>";
				echo "<br><input type='text' class='vurl_link' id='vurl' style='width:100px;' placeholder='影片連結' data-auton='".$re["auton"]."' value='".$re["vurl"]."' data-val='".$re["vurl"]."'></div>";
			}
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

    if($_REQUEST["st"] == "vurl"){
		$SQL = "select photo_name, vurl from web_photo where auton = ".SqlFilter($_REQUEST["an"],"int")."";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		$result = $rs->fetch(PDO::FETCH_ASSOC);
		if($result){
			$pname = $result["photo_name"];
			$SQL = "UPDATE web_photo SET vurl='".SqlFilter($_REQUEST["val"],"tab")."' where auton = ".SqlFilter($_REQUEST["an"],"int")."";
			$rs = $SPConn->prepare($SQL);
			$rs->execute();
		}
	}

    if($_REQUEST["val"] == ""){
		$SQL = "update ad_enterprise set vurl=NULL where adc_auto = ".SqlFilter($_REQUEST["ac"],"int")."";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
	}else{
		$SQL = "select adc_pic, vurl from ad_enterprise where adc_auto = ".SqlFilter($_REQUEST["ac"],"int")."";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		$result = $rs->fetch(PDO::FETCH_ASSOC);
		if($result){
			if($result["adc_pic"] == $pname){
				$SQL = "UPDATE ad_enterprise SET vurl='".SqlFilter($_REQUEST["val"],"tab")."' where adc_auto = ".SqlFilter($_REQUEST["ac"],"int")."";
				$rs = $SPConn->prepare($SQL);
				$rs->execute();
			}
		}
	}

    if($_REQUEST["st"] == "pic_first"){
		$SQL = "update web_photo set de = 0 where types='enterprise' and num = ".SqlFilter($_REQUEST["adc_auto"],"int")."";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		$isvurl = NULL;

		$SQL = "select * from web_photo where types='enterprise' and num = ".SqlFilter($_REQUEST["adc_auto"],"int")." and auton=".SqlFilter($_REQUEST["pa"],"int")."";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		$result = $rs->fetch(PDO::FETCH_ASSOC);
		if($result){
			$pname = $result["photo_name"];
			if($result["vurl"] != ""){
				$isvurl = $result["vurl"];
			}
			$SQL = "UPDATE web_photo SET de=1 where types='enterprise' and num = ".SqlFilter($_REQUEST["adc_auto"],"int")." and auton=".SqlFilter($_REQUEST["pa"],"int")."";
			$rs = $SPConn->prepare($SQL);
			$rs->execute();

			$SQL = "update ad_enterprise set adc_pic = '".$pname."', vurl='".$isvurl."' where adc_auto=".SqlFilter($_REQUEST["adc_auto"],"int")."";
			$rs = $SPConn->prepare($SQL);
			$rs->execute();
		}
	}

    //刪除圖檔(待測)
	if($_REQUEST["st"] == "pic_del"){
		$upw = 0;
		$SQL = "select photo_name, de from web_photo where types='enterprise' and num = ".SqlFilter($_REQUEST["adc_auto"],"int")." and auton=".SqlFilter($_REQUEST["pa"],"int")."";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		$result = $rs->fetch(PDO::FETCH_ASSOC);
		if($result){
			DelFile("upload_image/".$result["photo_name"]);
			if($result["de"] == 1){
				$upw = 1;
			}
			$SQL = "DELETE from web_photo where types='enterprise' and num = ".SqlFilter($_REQUEST["adc_auto"],"int")." and auton=".SqlFilter($_REQUEST["pa"],"int")."";
			$rs = $SPConn->prepare($SQL);
			$rs->execute();
		}
		if($upw == 1){
			$SQL = "update ad_enterprise set ac_pic = NULL where adc_auto=".SqlFilter($_REQUEST["adc_auto"],"int")."";
			$rs = $SPConn->prepare($SQL);
			$rs->execute();
		}
	}

    if($_REQUEST["acts"] == "ad"){
		$SQL = "select top 1 adc_desc from ad_enterprise order by adc_desc desc";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		$result = $rs->fetch(PDO::FETCH_ASSOC);
		if($result){
			$ltd = $result["adc_desc"] + 1;
		}else{
            $ltd = 1;
        }

		$SQL = "INSERT INTO ad_enterprise (adc_title,adc_note,adc_desc,adc_showtime) VALUES ('".SqlFilter($_REQUEST["adc_title"],"tab")."','".SqlFilter($_REQUEST["adc_note"],"tab")."','".$ltd."','".SqlFilter($_REQUEST["adc_showtime"],"tab")."')";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		reURL("springweb_fun13.php");
	}

	if($_REQUEST["acts"] == "up"){
		$SQL = "update ad_enterprise set adc_title = '".SqlFilter($_REQUEST["adc_title"],"tab")."' , adc_note = '".SqlFilter($_REQUEST["adc_note"],"tab")."' , adc_showtime = '".SqlFilter($_REQUEST["adc_showtime"],"tab")."' where adc_auto = ".SqlFilter($_REQUEST["pid"],"int")."";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		reURL("springweb_fun13.php");
	}

    if($_REQUEST["act"] == "up" && $_REQUEST["id"] != 0){
		$sqlstr = "select * from ad_enterprise where adc_auto = ".SqlFilter($_REQUEST["id"],"int")."";
		$rs = $SPConn->prepare($sqlstr);
		$rs->execute();
		$result = $rs->fetch(PDO::FETCH_ASSOC);
		if($result){
			$adc_auto = $result["adc_auto"];
            $adc_title = $result["adc_title"];	  
            $adc_time = $result["adc_time"];
            $adc_note = $result["adc_note"];
            $adc_showtime = $result["adc_showtime"];	
		}
	}else{
		$adc_time = date("Y/m/d H:i:s");
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
            <li><a href="springweb_fun13.php">企業內訓</a></li>
            <li class="active">新增/修改企業內訓</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>新增/修改企業內訓</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form name="mform" method="post" action="springweb_fun13_add.php" class="form-inline" onSubmit="return chkform()">
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <tbody>

                            <tr>
                                <td width="150" align="left" valign="middle">標題</td>
                                <td colspan=3><input style="width:80%;" name="adc_title" id="adc_title" value="<?php echo $adc_title; ?>" class="form-control" required></td>
                            </tr>
                            <tr>
                                <td width="150" align="left" valign="middle">舉辦日期</td>
                                <td colspan=3><input style="width:120px" name="adc_showtime" id="adc_showtime" value="<?php echo Date_EN($adc_showtime,1); ?>" class="datepicker" autocomplete="off"></td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">內容</td>
                                <td colspan=3><textarea name="adc_note" id="adc_note" style="width:80%;height:150px;" class="form-control"><?php echo $adc_note; ?></textarea></td>
                            </tr>
                            <?php 
                                if($_REQUEST["act"] == "up"){ ?>
                                    <tr>
                                        <td align="left" valign="middle">圖片</td>
                                        <td colspan=3>
                                            <div>
                                                <span class="btn btn-danger fileinput-button"><span>圖片上傳</span><input id="file_uploads2" type="file" class="fileupload" name="fileupload2"></span>
                                                <div id="progress" class="progress progress-striped" style="display:none"><div class="bar progress-bar progress-bar-lovepy"></div></div>
                                            </div>
                                            <div id="pic_div"></div>
                                        </td>
                                    </tr>
                                <?php }
                            ?>
                            <tr>
                                <td align="left" valign="middle">建立時間</td>
                                <td colspan=3><?php echo changeDate($adc_time); ?></td>
                            </tr>
                            <tr>
                                <td colspan=3><input name="acts" id="acts" type="hidden" value="<?php echo SqlFilter($_REQUEST["act"],"tab"); ?>"><input name="pid" type="hidden" id="pid" value="<?php echo SqlFilter($_REQUEST["id"],"int"); ?>">
                                    <input type="submit" value="確認送出" class="btn btn-info" style="width:50%">
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
<script language="JavaScript">
    $mtu = "springweb_fun13.";
    $(function() {
        reload_pic_div();
        var $file_uploads2 = $("#file_uploads2"),
            $thisid = $file_uploads2.attr("id"),
            $progress2 = $file_uploads2.closest("div").find(".progress");
        var $imgs = $file_uploads2.closest("span").find("#cimg").val();

        $file_uploads2.fileupload({
                url: "springweb_fun13_add.php?st=upload&adc_auto=<?php echo $adc_auto; ?>",
                type: "POST",
                dropZone: $file_uploads2,
                dataType: 'html',
                autoUpload: true,
                done: function(e, data) {

                    if (data.result) {
                        $progress2.find(".progress-bar").css("width", "0").parent().hide();
                        reload_pic_div();
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


    function reload_pic_div() {
        var $pic_div = $("#pic_div");
        $pic_div.html("讀取中...");
        $.ajax({
            type: "POST",
            url: "springweb_fun13_add.php",
            data: {
                st: "pic_div",
                adc_auto: "<?php echo $adc_auto; ?>"
            },
            error: function(xhr) {},
            success: function(response) {
                $pic_div.html(response);

                $(".vurl_link").bind("keypress", function(e) {
                    if (e.which == 13) {
                        var $an = $(this).data("auton");
                        vurl_save($an, $(this));
                    }
                });

            }
        });
    }

    function vurl_save(an, ob) {
        if (!an) return false;
        var $val = ob.val();
        if ($val == ob.data("val")) return false;
        ob.val("儲存中...");
        ob.prop("disabled", true);
        $.ajax({
            url: "springweb_fun13_add.php",
            data: {
                st: "vurl",
                an: an,
                val: $val,
                ac: "<?php echo $adc_auto; ?>"
            },
            type: "POST",
            dataType: 'text',
            success: function(msg) {
                var $dd = $("<div>儲存成功。</div>");
                ob.parent().append($dd);
                setTimeout(function() {
                    $dd.remove();
                    ob.val($val);
                    ob.data("val", $val);
                    ob.prop("disabled", false);
                }, 600);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }

    function set_first(a) {
        $.ajax({
            type: "POST",
            url: "springweb_fun13_add.php",
            data: {
                st: "pic_first",
                adc_auto: "<?php echo $adc_auto; ?>",
                pa: a
            },
            error: function(xhr) {},
            success: function(response) {
                reload_pic_div();
            }
        });
    }

    function set_del(a) {
        if (confirm("是否確定刪除此圖片？")) {
            $.ajax({
                type: "POST",
                url: "springweb_fun13_add.php",
                data: {
                    st: "pic_del",
                    adc_auto: "<?php echo $adc_auto; ?>",
                    pa: a
                },
                error: function(xhr) {},
                success: function(response) {
                    reload_pic_div();
                }
            });
        }
    }
</script>