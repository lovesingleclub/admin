<?php

/*****************************************/
//檔案名稱：singleweb_fun17.php
//後台對應位置：約會專家系統/首頁推薦會員
//改版日期：2022.6.13
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");
// ajax操作
if($_REQUEST["st"] == "agree" && $_REQUEST["t"] != ""){
    $SQL = "update member_data set singleparty_hot=1 where mem_num='".SqlFilter($_REQUEST["t"],"tab")."'";
    $rs = $SPConn->prepare($SQL);
	$rs->execute();
    echo "fix";
    exit();
}

if($_REQUEST["st"] == "cancel" && $_REQUEST["t"] != ""){
    $SQL = "update member_data set singleparty_hot=0 where mem_num='".SqlFilter($_REQUEST["t"],"tab")."'";
    $rs = $SPConn->prepare($SQL);
	$rs->execute();
    echo "fix";
    exit();
}

if($_REQUEST["st"] == "remove" && $_REQUEST["t"] != ""){
    $SQL = "update member_data set singleparty_hot=0, singleparty_hot_check=0 where mem_num='".SqlFilter($_REQUEST["t"],"tab")."'";
    $rs = $SPConn->prepare($SQL);
	$rs->execute();
    echo "fix";
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

// 上移
if($_REQUEST["st"] == "mup"){
	$nowline = round(SqlFilter($_REQUEST["i1"],"int"));
	$upline = $nowline+1;
	$SQL = "update si_webdata set i1=".$nowline." where i1='".$upline."'";
	$rs = $SPConn->prepare($SQL);
	$rs->execute();
	$SQL = "update si_webdata set i1=".$upline." where auton=".SqlFilter($_REQUEST["an"],"int")."";
	$rs = $SPConn->prepare($SQL);
	$rs->execute();

	reURL("singleweb_fun17.php");
}

// 下移
if($_REQUEST["st"] == "mdo"){
	$nowline = round(SqlFilter($_REQUEST["i1"],"int"));
	$upline = $nowline-1;
	$SQL = "update si_webdata set i1=".$nowline." where i1=".$upline."";
	$rs = $SPConn->prepare($SQL);
	$rs->execute();
	$SQL = "update si_webdata set i1=".$upline." where auton=".SqlFilter($_REQUEST["an"],"int")."";
	$rs = $SPConn->prepare($SQL);
	$rs->execute();

	reURL("singleweb_fun17.php");
}

// 刪除圖片(待測試)
if($_REQUEST["st"] == "del"){
	$SQL = "select d2, d3 from si_webdata where auton=".SqlFilter($_REQUEST["an"],"int")." and types='coupon'";
	$rs = $SPConn->prepare($SQL);
	$rs->execute();
	$result = $rs->fetch(PDO::FETCH_ASSOC);
	if($result){
        DelFile(("singleparty_image/coupon/".$result["d2"]));
        DelFile(("singleparty_image/coupon/".$result["d3"]));
		
		$SQL = "delete from si_webdata where auton=".SqlFilter($_REQUEST["an"],"int")." and types='coupon'";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		if($rs){
			reURL("singleweb_fun17.php");
		}
	}        
}

?>
<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>約會專家系統</li>
            <li class="active">首頁推薦會員</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>首頁推薦會員</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">

                <div id="content" class="span10">
                    <!-- content starts -->
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <tbody>
                            <tr>
                                <th>編號</th>
                                <th>姓名</th>
                                <th>性別</th>
                                <th>生日</th>
                                <th>學歷</th>
                                <th width=160>秘書</th>
                                <th width=60>照片</th>
                                <th width=200></th>
                            </tr>
                            <?php 
                                $nopic = "";
                                
                                //取得總筆數
                                $SQL = "Select count(mem_auto) As total_size FROM member_data where mem_level='mem' and singleparty_hot_check=1";
                                $rs = $SPConn->prepare($SQL);
                                $rs->execute();
                                $result=$rs->fetchAll(PDO::FETCH_ASSOC);
                                foreach($result as $re);
                                if ( count($result) == 0 || $re["total_size"] == 0 ) {
                                    $total_size = 0;
                                }else{
                                    $total_size = $re["total_size"];
                                }                                

                                // //取得分頁資料
                                // $tPageSize = 50; //每頁幾筆
                                // $tPage = 1; //目前頁數
                                // if ( $_REQUEST["tPage"] > 1 ){ $tPage = $_REQUEST["tPage"];}
                                // $tPageTotal = ceil(($total_size/$tPageSize)); //總頁數
                                // if ( $tPageSize*$tPage < $total_size ){
                                //     $page2 = 50;
                                // }else{
                                //     $page2 = (50-(($tPageSize*$tPage)-$total_size));
                                // }

                                //取得分頁資料
                                $tPageSize = 50; //每頁幾筆
                                $tPage = 1; //目前頁數
                                $tPage_list = 0;
                                if ( $_REQUEST["tPage"] > 1 ){ 
                                    $tPage = $_REQUEST["tPage"];
                                    $tPage_list = ($tPage-1);
                                }
                                $tPageTotal = ceil(($total_size/$tPageSize)); //總頁數

                                //分頁語法
                                $SQL_list  = "Select Top ".$tPageSize." * ";
                                $SQL_list .= "From (Select row_number() ";
                                $SQL_list .= "over(order by singleparty_hot_check desc, singleparty_hot asc) As rownumber, * ";
                                $SQL_list .= "From member_data Where mem_level='mem' and singleparty_hot_check=1 ) temp_row ";
                                $SQL_list .= "Where rownumber > ".($tPageSize*$tPage_list);
                                
                                // $SQL = "SELECT * FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM member_data WHERE mem_level='mem' and singleparty_hot_check=1 order by singleparty_hot_check desc, singleparty_hot asc ) t1 order by singleparty_hot_check, singleparty_hot) t2 order by singleparty_hot_check desc, singleparty_hot asc";
                                $rs = $SPConn->prepare($SQL_list);
                                $rs->execute();
                                $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                                if($result){
                                    foreach($result as $re){ ?>
                                        <tr id="row_<?php echo $re["mem_num"]; ?>">
                                            <td><?php echo $re["mem_num"]; ?></td>
                                            <td class="center"><a href="ad_mem_detail.php?mem_num=<?php echo $re["mem_num"]; ?>" target="_blank"><?php echo $re["mem_name"]; ?></a>
                                                <div style="float:right">
                                                <?php
                                                    if($re["si_account"] != "" && $re["si_account"] != "0"){
                                                        echo "&nbsp;<span class='label' style='background:#c22c7d'><a href='#' style='color:white;' data-toggle='tooltip' data-original-title='約會專家主帳號'>專</a></span>";
                                                    }
                                                    if($re["si_enterprise"] == 1){
                                                        echo "&nbsp;<span class='label' style='background:blue'><a href='#' style='color:white;' data-toggle='tooltip' data-original-title='企業會員-".$re["company"]."'>企</a></span>";
                                                    }                                               
                                                ?>                                                
                                                </div>
                                            </td>
                                            <td class="center"><?php echo $re["mem_sex"]; ?></td>
                                            <td class="center">
                                                <?php echo $re["mem_by"]; ?>/<?php echo $re["mem_bm"]; ?>/<?php echo $re["mem_bd"]; ?>
                                                <?php 
                                                    if($re["mem_by"] != ""){
                                                        echo "　　".(date("Y")-$re["mem_by"])." 歲";
                                                    }
                                                ?>                                               
                                            </td>
								            <td class="center"><?php echo $re["mem_school"]; ?></td>
                                            <td class="center">
                                                <?php 
                                                    if($re["mem_branch"] != ""){
                                                        $mem_single = "<font color=green>受理：</font>".$re["mem_branch"]. " - " . SingleName($re["mem_single"],"normal");
                                                    }else{
                                                        $mem_single = "";
                                                    }
                                                    if($re["love_single"] != ""){
                                                        $love_single = "<br><font color=green>排約：</font>". SingleName($re["love_single"],"normal");
                                                    }else{
                                                        $love_single = "";
                                                    }
                                                    if($re["call_branch"] != ""){
                                                        $call_single = "<br><font color=green>邀約：</font>".$re["call_branch"]. " - ". SingleName($re["call_single"],"normal");
                                                    }else{
                                                        $call_single = "";
                                                    }
                                                    if($re["mem_come3"] != ""){
                                                        $sup_single = "<br><font color=green>推薦：</font>".$re["mem_come3"]. " - ". SingleName($re["mem_come4"],"normal");
                                                    }else{
                                                        $sup_single = "";
                                                    }
                                                    if($re["keyin_single"] != ""){
                                                        $keyin_single = "<br><font color=blue>輸入：</font>". SingleName($re["keyin_single"],"normal");
                                                    }else{
                                                        $keyin_single = "";
                                                    }
                                                    if($re["del_mask"] != ""){
                                                        $del_single = "<br><font color=red>刪除：</font>". SingleName($re["del_mask"],"normal")."<small>(".Date_EN($re["del_mask_time"],9).")</small>";
                                                    }else{
                                                        $del_single = "";
                                                    }
                                                    echo $mem_single.$love_single.$call_single.$sup_single.$keyin_single.$del_single;   
                                                ?>
                                            </td>
                                            <td class="center">
                                                <?php 
                                                    $mem_photo = $re["mem_photo"];
                                                    if($mem_photo != "" && strpos($mem_photo,"boy.") == false && strpos($mem_photo,"girl.") == false){
                                                        if(strpos($mem_photo,"photo/") > 0){
                                                            echo "<a href='dphoto/".$mem_photo."?t=".rand(1,9999)."' class='fancybox'><img src='dphoto/".$mem_photo."?t=".rand(1,9999)."' width='100'></a>";
                                                        }else{
                                                            echo "<a href='../photo/".$mem_photo."?t=".rand(1,9999)."' class='fancybox'><img src='../photo/".$mem_photo."?t=".rand(1,9999)."' width='100'></a>";
                                                        }
                                                    }else{
                                                        $nopic = 1;
                                                        echo "無";
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if($re["singleparty_hot"] == "1"){
                                                        echo "<a href='#r' onclick=\"cancel($(this), '".$re["mem_num"]."')\" class='btn btn-info'>取消展示</a>";
                                                    }elseif($nopic == 1){
                                                        echo "<a href='#' class='btn btn-primary disabled'>無照片</a>";
                                                    }else{
                                                        echo "<a href='#r' onclick=\"agree($(this), '".$re["mem_num"]."')\" class='btn btn-success'>通過</a>";
                                                    }
                                                    echo "<a href='#r' onclick=\"remove($(this), '".$re["mem_num"]."')\" class='btn btn-danger'>移除</a>";
                                                ?>
                                            </td>
                                        </tr>
                                    <?php }
                                }else{
                                    echo "<tr><td colspan=5>目前無資料</td></tr>";                                    
                                }
                            ?>                           
                        </tbody>
                    </table>
                </div>
                <!-- 頁碼 -->
                <?php require_once("./include/_page.php"); ?>
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

<script language="JavaScript">
    $(function() {

    });

    function agree($this, $num) {
        $.ajax({
            url: "singleweb_fun17.php",
            data: {
                st: "agree",
                t: $num
            },
            type: "POST",
            dataType: 'text',
            success: function(msg) {
                switch (msg) {
                    case "fix":
                        $this.removeClass("btn-success").addClass("btn-info").html("取消展示").off("click").on("click", function() {
                            cancel($this, $num);
                        });
                        break;
                    default:
                        alert("error:" + msg);
                        break;
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }

    function cancel($this, $num) {
        $.ajax({
            url: "singleweb_fun17.php",
            data: {
                st: "cancel",
                t: $num
            },
            type: "POST",
            dataType: 'text',
            success: function(msg) {
                switch (msg) {
                    case "fix":
                        $this.removeClass("btn-info").addClass("btn-success").html("通過").off("click").on("click", function() {
                            agree($this, $num);
                        });
                        break;
                    default:
                        alert("error:" + msg);
                        break;
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }

    function remove($this, $num) {
        $.ajax({
            url: "singleweb_fun17.php",
            data: {
                st: "remove",
                t: $num
            },
            type: "POST",
            dataType: 'text',
            success: function(msg) {
                switch (msg) {
                    case "fix":
                        $("#row_" + $num).fadeOut();
                        break;
                    default:
                        alert("error:" + msg);
                        break;
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }
</script>