<?php

/*****************************************/
//檔案名稱：singleweb_fun23.php
//後台對應位置：約會專家系統/交友大廳會員
//改版日期：2022.6.14
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


if($_REQUEST["st"] == "remove" && $_REQUEST["t"] != ""){
    $SQL = "update member_data set singleparty_hot=0, singleparty_hot_check=0 where mem_num='".SqlFilter($_REQUEST["t"],"tab")."'";
    $rs = $SPConn->prepare($SQL);
	$rs->execute();
    echo "fix";
    exit();
}

if($_REQUEST["st"] == "add" && $_REQUEST["num"] != ""){
    $SQL = "update member_data set singleparty_hot2=1 where mem_num='".SqlFilter($_REQUEST["num"],"int")."'";
    $rs = $SPConn->prepare($SQL);
	$rs->execute();
    call_alert("", "singleweb_fun23.php", 0);
}
?>
<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>約會專家系統</li>
            <li class="active">交友大廳會員</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>交友大廳會員</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p>
                <form action="?st=add" method="post" target="_self" onsubmit="return chk_add_form()" class="form-inline" style="display:inline-block;margin:0px;">
                    <input type="text" name="num" id="num" class="form-control" placeholder="請輸入要新增的會員編號" required>&nbsp;<input type="submit" class="btn btn-info" value="新增">
                </form>
                </p>
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
                                $SQL = "Select count(mem_auto) As total_size FROM member_data where singleparty_hot2=1";
                                $rs = $SPConn->prepare($SQL);
                                $rs->execute();
                                $result=$rs->fetchAll(PDO::FETCH_ASSOC);
                                foreach($result as $re);
                                if ( count($result) == 0 || $re["total_size"] == 0 ) {
                                    $total_size = 0;
                                }else{
                                    $total_size = $re["total_size"];
                                }
                                
                                //取得分頁資料
                                $tPageSize = 50; //每頁幾筆
                                $tPage = 1; //目前頁數
                                if ( $_REQUEST["tPage"] > 1 ){ $tPage = $_REQUEST["tPage"];}
                                $tPageTotal = ceil(($total_size/$tPageSize)); //總頁數
                                if ( $tPageSize*$tPage < $total_size ){
                                    $page2 = 50;
                                }else{
                                    $page2 = (50-(($tPageSize*$tPage)-$total_size));
                                }
                                
                                $SQL = "SELECT * FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM member_data WHERE singleparty_hot2=1 order by singleparty_hot2 desc ) t1 order by singleparty_hot2) t2 order by singleparty_hot2 desc";
                                $rs = $SPConn->prepare($SQL);
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
                                                    echo "<a href='#r' onclick='remove($(this), '".$re["mem_num"]."')' class='btn btn-danger'>移除</a>";
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
            url: "singleweb_fun23.php",
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
            url: "singleweb_fun23.php",
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
            url: "singleweb_fun23.php",
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