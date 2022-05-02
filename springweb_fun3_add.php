<?php
    /*****************************************/
    //檔案名稱：springweb_fun3_add.php
    //後台對應位置：春天網站系統/愛情見證>新增/修改愛情見證
    //改版日期：2022.4.7
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");
	// 非同步
	if($_REQUEST["st"] == "pic_div"){
		$SQL = "select * from witness_photo where ac_auto = ".SqlFilter($_REQUEST["ac_auto"],"int")." order by de desc";
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
				echo "<br><a href='upload_image/".$re["pname"]."' class='fancybox'><img width=130 src='upload_image/".$re["pname"]."'></a>";
				echo "<br><input type='text' class='vurl_link' id='vurl' style='width:100px;' placeholder='影片連結' data-auton='".$re["auton"]."' value='".$re["vurl"]."' data-val='".$re["vurl"]."'></div>";
			}
		}
		exit();
	}
	// 上傳圖檔&旋轉圖片(待測)
	if($_REQUEST["st"] == "upload"){
		$ac_auto = SqlFilter($_REQUEST["ac_auto"],"int");
		if ($_FILES['fileupload']['error'] === UPLOAD_ERR_OK){
            $urlpath = "upload_image/"; //儲存路徑
            $ext = pathinfo($_FILES["fileupload"]["name"], PATHINFO_EXTENSION); //附檔名      
            $fileName = date("Y").date("n").date("j").date("H").date("i").date("s")."_springweb_fun3_".rand(1,99).".".$ext; //檔名
            
			// 檢查圖片是否被旋轉過，並轉回來(待功能開啟)
			$image = imagecreatefromstring(file_get_contents($_FILES['fileupload']['tmp_name']));
			$exif = exif_read_data($_FILES['fileupload']['tmp_name']);    
			if (!empty($exif['Orientation'])) {
				switch ($exif['Orientation']) {
					case 8:
						$image = imagerotate($image, 90, 0);
						break;
					case 3:
						$image = imagerotate($image, 180, 0);
						break;
					case 6:
						$image = imagerotate($image, -90, 0);
						break;
				}
			}
			// 儲存圖片
			move_uploaded_file($_FILES["fileupload"]["tmp_name"],($urlpath.$fileName)); //儲存檔案
			
			$SQL = "select top 1 * from witness_photo where ac_auto = ".$ac_auto."";
			$rs = $SPConn->prepare($SQL);
			$rs->execute();
			$result = $rs->fetch(PDO::FETCH_ASSOC);
			if($result){
				$thefirst = 0;
			}else{
				$thefirst = 1;
			}
			$SQL = "INSERT INTO witness_photo (pname, ac_auto) VALUES ('".$fileName."','".$ac_auto."')";
			$rs = $SPConn->prepare($SQL);
			$rs->execute();

			if($thefirst == 1){
				$SQL = "update witness_photo set de=1 where ac_auto=".$ac_auto."";
				$rs = $SPConn->prepare($SQL);
				$rs->execute();
				$SQL = "update witness set ac_pic='".$fileName."' where ac_auto=".$ac_auto."";
				$rs = $SPConn->prepare($SQL);
				$rs->execute();
			}
			echo $fileName;
        	exit();         
        }		
	}
    require_once("./include/_top.php");
    switch($_SESSION["MM_UserAuthorization"]){
        case "admin":
            include_once("./include/_sidebar_spring.php");
            $power_edit = 1;
            break;
        default:
            include_once("./include/_sidebar.php");
            $power_edit = 0;  
    }

	//程式開始 *****
    if ($_SESSION["MM_Username"] == "") {
        call_alert("請重新登入。", "login.php", 0);
    }

	if($_REQUEST["st"] == "vurl"){
		$SQL = "select pname, vurl from witness_photo where auton = ".SqlFilter($_REQUEST["an"],"int")."";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		$result = $rs->fetch(PDO::FETCH_ASSOC);
		if($result){
			$pname = $result["pname"];
			$SQL = "UPDATE witness_photo SET vurl='".SqlFilter($_REQUEST["val"],"tab")."' where auto = ".SqlFilter($_REQUEST["an"],"int")."";
			$rs = $SPConn->prepare($SQL);
			$rs->execute();
		}
	}

	if($_REQUEST["val"] == ""){
		$SQL = "update witness set vurl=NULL where ac_auto = ".SqlFilter($_REQUEST["ac"],"int")."";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
	}else{
		$SQL = "select ac_pic, vurl from witness where ac_auto = ".SqlFilter($_REQUEST["ac"],"int")."";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		$result = $rs->fetch(PDO::FETCH_ASSOC);
		if($result){
			if($result["ac_pic"] == $pname){
				$SQL = "UPDATE witness SET vurl='".SqlFilter($_REQUEST["val"],"tab")."' where ac_auto = ".SqlFilter($_REQUEST["ac"],"int")."";
				$rs = $SPConn->prepare($SQL);
				$rs->execute();
			}
		}
	}	

	if($_REQUEST["st"] == "pic_first"){
		$SQL = "update witness_photo set de = 0 where ac_auto = ".SqlFilter($_REQUEST["ac_auto"],"int")."";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		$isvurl = NULL;

		$SQL = "select * from witness_photo where ac_auto = ".SqlFilter($_REQUEST["ac_auto"],"int")." and auton=".SqlFilter($_REQUEST["pa"],"int")."";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		$result = $rs->fetch(PDO::FETCH_ASSOC);
		if($result){
			$pname = $result["pname"];
			if($result["vurl"] != ""){
				$isvurl = $result["vurl"];
			}
			$SQL = "UPDATE witness_photo SET de=1 where ac_auto = ".SqlFilter($_REQUEST["ac_auto"],"int")." and auton=".SqlFilter($_REQUEST["pa"],"int")."";
			$rs = $SPConn->prepare($SQL);
			$rs->execute();

			$SQL = "update witness set ac_pic = '".$pname."', vurl='".$isvurl."' where ac_auto=".SqlFilter($_REQUEST["ac_auto"],"int")."";
			$rs = $SPConn->prepare($SQL);
			$rs->execute();
		}
	}

	//刪除圖檔(待測)
	if($_REQUEST["st"] == "pic_del"){
		$upw = 0;
		$SQL = "select pname, de from witness_photo where ac_auto = ".SqlFilter($_REQUEST["ac_auto"],"int")." and auton=".SqlFilter($_REQUEST["pa"],"int")."";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		$result = $rs->fetch(PDO::FETCH_ASSOC);
		if($result){
			DelFile("upload_image/".$result["pname"]);
			if($result["de"] == 1){
				$upw = 1;
			}
			$SQL = "DELETE from witness_photo where ac_auto = ".SqlFilter($_REQUEST["ac_auto"],"int")." and auton=".SqlFilter($_REQUEST["pa"],"int")."";
			$rs = $SPConn->prepare($SQL);
			$rs->execute();
		}
		if($upw == 1){
			$SQL = "update witness set ac_pic = NULL where ac_auto=".SqlFilter($_REQUEST["ac_auto"],"int")."";
			$rs = $SPConn->prepare($SQL);
			$rs->execute();
		}
	}

	if($_REQUEST["acts"] == "ad"){
		$SQL = "select top 1 t_desc from witness order by t_desc desc";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		$result = $rs->fetch(PDO::FETCH_ASSOC);
		if($result){
			$ltd = $result["t_desc"] + 1;
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

		if($_REQUEST["sign"] == "1"){
			$sign = 1;
		}else{
			$sign = 0;
		}

		$SQL = "INSERT INTO witness (ac_time,ac_title,ac_note1,t_desc,n1,n2,n3,n4,show, single, singlename,review,sign) VALUES (GetDate(),'".SqlFilter($_REQUEST["ac_title"],"tab")."','";
		$SQL = $SQL.SqlFilter($_REQUEST["ac_note1"],"tab")."',".$ltd.",'".SqlFilter($_REQUEST["n1"],"tab")."','".SqlFilter($_REQUEST["n2"],"tab")."','".SqlFilter($_REQUEST["n3"],"tab")."','".SqlFilter($_REQUEST["n4"],"tab")."','".$shows."','".$_SESSION["MM_Username"]."','".$_SESSION["pname"]."','".$review."','".$sign."')";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		reURL("springweb_fun3.php");
	}

	if($_REQUEST["acts"] == "up"){
		if($_REQUEST["shows"] == "1"){
			$shows = 1;
		}else{
			$shows = 0;
		}

		if($_REQUEST["shows"] == "2"){
			$shows2 = 1;
		}else{
			$shows2 = 0;
		}

		if($_REQUEST["review"] == "1"){
			$review = 1;
		}else{
			$review = 0;
		}

		if($_REQUEST["sign"] == "1"){
			$sign = 1;
		}else{
			$sign = 0;
		}

		$SQL = "select * from witness where ac_auto = ".SqlFilter($_REQUEST["pid"],"int")."";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		$result = $rs->fetch(PDO::FETCH_ASSOC);
		if($result){
			if($_SESSION["MM_UserAuthorization"] == "admin"){
				$SQL = "UPDATE witness SET n1='".SqlFilter($_REQUEST["n1"],"tab")."', n2='".SqlFilter($_REQUEST["n2"],"tab")."', n3='".SqlFilter($_REQUEST["n3"],"tab")."', n4='".SqlFilter($_REQUEST["n4"],"tab")."', ac_title='".SqlFilter($_REQUEST["ac_title"],"tab")."', ac_note1='".SqlFilter($_REQUEST["ac_note1"],"tab")."', show='".$shows."', show2='".$show2."' where ac_auto = ".SqlFilter($_REQUEST["pid"],"int")."";
			}else{
				$SQL = "UPDATE witness SET n1='".SqlFilter($_REQUEST["n1"],"tab")."', n2='".SqlFilter($_REQUEST["n2"],"tab")."', n3='".SqlFilter($_REQUEST["n3"],"tab")."', n4='".SqlFilter($_REQUEST["n4"],"tab")."', ac_title='".SqlFilter($_REQUEST["ac_title"],"tab")."', ac_note1='".SqlFilter($_REQUEST["ac_note1"],"tab")."', show='".$shows."', show2='".$show2."', review='".$review."', sign='".$sign."' where ac_auto = ".SqlFilter($_REQUEST["pid"],"int")."";
			}
			$rs = $SPConn->prepare($SQL);
			$rs->execute();			
		}
		reURL("springweb_fun3.php");
	}

	if($_REQUEST["act"] == "up" && $_REQUEST["id"] != 0){
		$sqlstr = "select * from witness where ac_auto = ".SqlFilter($_REQUEST["id"],"int")."";
		$rs = $SPConn->prepare($sqlstr);
		$rs->execute();
		$result = $rs->fetch(PDO::FETCH_ASSOC);
		if($result){
			$ac_auto = $result["ac_auto"];
			$ac_title = $result["ac_title"];	  
			$ac_time = $result["ac_time"];
			$n1 = $result["n1"];
			$n2 = $result["n2"];
			$n3 = $result["n3"];
			$n4 = $result["n4"];
			$ac_note1 = $result["ac_note1"];
			$shows = $result["show"];
			$shows2 = $result["show2"];
			$pname = $result["singlename"];
			$review = $result["review"];
			$sign = $result["sign"];
			$nnow = $result["ac_time"];	
		}
	}else{
		$nnow = date("Y/m/d H:i:s");
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
			<li><a href="springweb_fun3.php">愛情見證</a></li>
			<li class="active">新增/修改愛情見證</li>
		</ol>
	</header>
	<!-- /page title -->

	<div id="content" class="padding-20">
		<!-- content starts -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="title elipsis">
					<strong>新增/修改愛情見證</strong> <!-- panel title -->
				</span>
			</div>

			<div class="panel-body">
				<form name="mform" method="post" action="springweb_fun3_add.php" class="form-inline" onSubmit="return chkform()">
					<table class="table table-striped table-bordered bootstrap-datatable">
						<tbody>
							<tr>
								<td width="150" align="left" valign="middle">相識時間</td>
								<td><input name="n1" id="n1" value="<?php echo $n1; ?>" class="form-control"></td>
								<td width="150" align="left" valign="middle">結婚時間</td>
								<td><input name="n2" id="n2" value="<?php echo $n2; ?>" class="form-control"></td>
							</tr>
							<tr>
								<td width="150" align="left" valign="middle">新郎</td>
								<td><input name="n3" id="n3" value="<?php echo $n3; ?>" class="form-control"></td>
								<td width="150" align="left" valign="middle">新娘</td>
								<td><input name="n4" id="n4" value="<?php echo $n4; ?>" class="form-control"></td>
							</tr>
							<tr>
								<td width="150" align="left" valign="middle">愛情見證標題</td>
								<td colspan=3><input style="width:80%;" class="form-control" name="ac_title" id="ac_title" value="<?php echo $ac_title; ?>"></td>
							</tr>
							<tr>
								<td align="left" valign="middle">內容</td>
								<td colspan=3><textarea name="ac_note1" id="ac_note1" class="form-control" style="width:80%;height:150px;"><?php echo $ac_note1; ?></textarea></td>
							</tr>
							<?php 
								if($_REQUEST["act"] == "up"){ ?>
									<tr>
										<td align="left" valign="middle">圖片</td>
										<td colspan=3>
											<div>
											<span class="btn btn-danger fileinput-button"><span>圖片上傳</span><input id="file_uploads2" type="file" class="fileupload" name="fileupload"></span>
											<div id="progress" class="progress progress-striped" style="display:none"><div class="bar progress-bar progress-bar-lovepy"></div></div>
											</div>
											<div id="pic_div"></div>
										</td>
									</tr>
								<?php }
							?>
							<tr>
								<td width="150" align="left" valign="middle"></td>
								<td colspan=4>
									<label class="checkbox"><input type="checkbox" name="shows" id="shows" value="1"<?php if($shows == 1) echo " checked"; ?>><i></i> 春網前台顯示</label>
									<label class="checkbox"><input type="checkbox" name="shows2" id="shows2" value="1"<?php if($show2 == 1) echo " checked"; ?>><i></i> 業務展示系統顯示</label>
									<?php 
										if($_REQUEST["id"] == "" || $_SESSION["MM_UserAuthorization"] == "admin"){ ?>
											<label class="checkbox"><input type="checkbox" name="sign" id="sign" value="1"<?php if($sign == 1) echo " checked"; ?>><i></i> 簽約</label>
										<?php }
										if( $power_edit == 1){ ?>
											<label class="checkbox"><input type="checkbox" name="review" id="review" value="1"<?php if($review == 1) echo " checked"; ?>><i></i> 審核通過</label>
										<?php }
									?>
								</td>
							</tr>

							<tr>
								<td align="left" valign="middle">建立時間</td>
								<td colspan=4><?php echo changeDate($nnow); ?></td>
							</tr>
							<tr>
								<td align="left" valign="middle">建立人</td>
								<td colspan=4><?php echo $pname; ?></td>
							</tr>
							<tr>
								<td colspan=3 align="center"><input name="acts" id="acts" type="hidden" value="<?php echo SqlFilter($_REQUEST["act"],"tab"); ?>"><input name="pid" type="hidden" id="pid" value="<?php echo SqlFilter($_REQUEST["id"],"int"); ?>">
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
<script language="JavaScript">
	$mtu = "springweb_fun3.";
	$(function() {
		reload_pic_div();

		var $file_uploads2 = $("#file_uploads2"),
			$thisid = $file_uploads2.attr("id"),
			$progress2 = $file_uploads2.closest("div").find(".progress");
		var $imgs = $file_uploads2.closest("span").find("#cimg").val();

		$file_uploads2.fileupload({
				url: "springweb_fun3_add.php?st=upload&ac_auto=<?php echo $ac_auto; ?>",
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
			url: "springweb_fun3_add.php",
			data: {
				st: "pic_div",
				ac_auto: "<?php echo $ac_auto; ?>"
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
			url: "springweb_fun3_add.php",
			data: {
				st: "vurl",
				an: an,
				val: $val,
				ac: "<?php echo $ac_auto; ?>"
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
			url: "springweb_fun3_add.php",
			data: {
				st: "pic_first",
				ac_auto: "<?php echo $ac_auto; ?>",
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
				url: "springweb_fun3_add.php",
				data: {
					st: "pic_del",
					ac_auto: "<?php echo $ac_auto; ?>",
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