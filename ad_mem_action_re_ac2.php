<?php
/*****************************************/
//檔案名稱：ad_mem_action_re_ac1.php
//後台對應位置：管理系統/活動明細表/單一活動紀錄/活動人員
//改版日期：2022.3.24
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");
require_once("./include/_top.php");
require_once("./include/_sidebar.php");

//程式開始 *****
if ($_SESSION["MM_Username"] == "") {
    call_alert("請重新登入。", "login.php", 0);
}

$ac_auto = SqlFilter($_REQUEST["ac_auto"],"int");

if($_REQUEST["st"] == "on"){
    $SQL = "SELECT * FROM ac_data_re Where acre_auto = ".SqlFilter($_REQUEST["acre_auto"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);          
    if($result){
        $SQL = "UPDATE ac_data_re SET acre_ck2=1, acre_time_turn='".date("Y/m/d")."' Where acre_auto = ".SqlFilter($_REQUEST["acre_auto"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }
    reURL("ad_mem_action_re_ac2.php?ac_auto=".SqlFilter($_REQUEST["acre_num"],"int")."");
}

$SQL = "select ac_branch, crossarea from action_data where ac_auto='".$ac_auto."'";
$rs = $SPConn->prepare($SQL);
$rs->execute();
$result = $rs->fetch(PDO::FETCH_ASSOC);
if($result){
    $crossarea = $result["crossarea"];
	$ac_branch = $result["ac_branch"];
}else{
    call_alert("活動資料錯誤。", 0,0);
}

switch($_SESSION["MM_UserAuthorization"]){
    case "admin":
        $sqls2 = "SELECT count(acre_auto) as total_size FROM ac_data_re Where acre_ck2 = 0 and acre_num = '".$ac_auto."'";
        break;
    default:
        if($ac_branch == $_SESSION["branch"] && $crossarea == 1){
            $sqls2 = "SELECT count(acre_auto) as total_size FROM ac_data_re Where acre_ck2 = 0 and acre_num = '".$ac_auto."'";
        }else{
            $sqls2 = "SELECT count(acre_auto) as total_size FROM ac_data_re Where acre_ck2 = 0 and acre_num = '".$ac_auto."' and all_branch='".$_SESSION["branch"]."'";
        }
        
}
//計算總筆數
$rs = $SPConn->prepare($sqls2);
$rs->execute();
$result = $rs->fetch(PDO::FETCH_ASSOC);          
if($result){
    $total_size =  $result["total_size"];
}else{
    $total_size = 0;
}

$tPage = 1; //目前頁數
$tPageSize = 20; //每頁幾筆
if ( $_REQUEST["tPage"] > 1 ){ $tPage = $_REQUEST["tPage"];}
$tPageTotal = ceil(($total_size/$tPageSize)); //總頁數
if ( $tPageSize*$tPage < $total_size ){
    $page2 = 20;
}else{
    $page2 = (20-(($tPageSize*$tPage)-$total_size));
}

switch($_SESSION["MM_UserAuthorization"]){
    case "admin":
        $SQL2 = "SELECT * FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM ac_data_re Where acre_ck2 = 0 and acre_num = '".$ac_auto."' order by acre_auto desc ) t1 order by acre_auto ) t2 order by acre_auto desc";
        break;
    default:
        if($ac_branch == $_SESSION["branch"] && $crossarea == 1){
            $SQL2 = "SELECT * FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM ac_data_re Where acre_ck2 = 0 and acre_num = '".$ac_auto."' order by acre_auto desc ) t1 order by acre_auto ) t2 order by acre_auto desc";
        }else{
            $SQL2 = "SELECT * FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM ac_data_re Where acre_ck2 = 0 and acre_num = '".$ac_auto."' and all_branch='".$_SESSION["branch"]."' order by acre_auto desc ) t1 order by acre_auto ) t2 order by acre_auto desc";
        }
       
}

$rs = $SPConn->prepare($SQL2);
$rs->execute();
$result = $rs->fetchAll(PDO::FETCH_ASSOC);
if(!$result){    
    call_alert("目前本場活動沒有明細。",0,0);
}
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li><a href="ad_mem_action_re_list.php">活動明細表</a></li>
            <li><a href="ad_mem_action_re_ac1.php">單一活動紀錄</a></li>
            <li><?php echo $result[0]["acre_branch"] ?> - <?php echo $result[0]["acre_title"] ?></li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>單一活動紀錄 - <?php echo $result[0]["acre_branch"] ?> - <?php echo $result[0]["acre_title"] ?>　</strong> <!-- panel title -->
                    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="btn btn-info btn-xs" onclick="Mars_popup('ad_mem_action_re_ac2_print.php?ac_auto=<?php echo $ac_auto; ?>','','scrollbars=yes,location=yes,status=yes,menubar=yes,resizable=yes,width=690,height=600,top=10,left=10');" value="列印本頁">
                </span>
            </div>

            <div class="panel-body">
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                        <tr>
                            <th>報名日期</th>
                            <th>活動日期</th>
                            <th>參加人員</th>
                            <th>收費金額</th>
                            <th>活動備註</th>
                            <th>處理會館</th>
                            <th>處理秘書</th>
                            <th width=100>狀態</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach($result as $re){ ?>
                                <tr>
                                    <td align="left"><?php echo Date_EN($re["acre_sign"],9); ?></td>
                                    <td align="left"><?php echo Date_EN($re["acre_time2"],9); ?></td>
                                    <td align="left">
                                        <?php 
                                            $SQL = "SELECT mem_name, mem_username, mem_by, mem_bm, mem_bd, mem_mobile FROM member_data Where mem_username='".$re["acre_user"]."' order by mem_auto desc";
                                            $rs = $SPConn->prepare($SQL);
                                            $rs->execute();
                                            $result2 = $rs->fetch(PDO::FETCH_ASSOC);
                                            if($result2){ ?>
                                                <?php echo $result2["mem_name"]; ?>(<?php echo $result2["mem_username"]; ?>) (<?php echo $result2["mem_by"]; ?>/<?php echo $result2["mem_bm"]; ?>/<?php echo $result2["mem_bd"]; ?> , <?php echo $result2["mem_mobile"]; ?>)<br>
                                                <?php if($re["acre_time_turn2"] != "") echo "轉入時間(".Date_EN($re["acre_time_turn2"],9).")"; ?>
                                            <?php }
                                        ?>
                                    </td>
                                    <td align="left">（<?php echo $re["acre_pay"]; ?>）<font color="#FF0000" size="3"><?php echo $re["acre_pay2"]; ?></font></td>
                                    <td align="left"><?php echo $re["acre_note"]; ?></td>
                                    <td align="left"><?php echo $re["all_branch"]; ?></td>
                                    <td align="left">
                                        <?php 
                                            if($re["all_single"] != ""){
                                                echo SingleName($re["all_single"],"normal");
                                            }
                                        ?>
                                    </td>
                                    <td align="left">
                                        <?php 
                                            if($re["acre_ck"] == 0){
                                                echo "已報名";
                                            }else{
                                                if($re["acre_ck"] == 1){
                                                    echo "參加";
                                                }else{
                                                    echo "未參加";
                                                }
                                            }
                                        ?>
                                    </td>
                                    <td align="left">
                                        <div class="btn-group">
                                            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">操作 <span class="caret"></span></button>
                                            <ul class="dropdown-menu pull-right">
                                                <?php 
                                                    if($re["acre_time2"] > (date("Y/m/d H:i:s")-7) || $_SESSION["MM_UserAuthorization"] == "admin"){ ?>
                                                        <li><a href="javascript:Mars_popup('ad_mem_action_re_list.php?st=on&acre_auto=<?php echo $re["acre_auto"]; ?>','','width=300,height=200,top=100,left=100')"><i class="icon-ok-sign"></i> 參加</a></li>
							                            <li><a href="javascript:Mars_popup('ad_mem_action_re_list.php?st=off&acre_auto=<?php echo $re["acre_auto"]; ?>','','width=300,height=200,top=100,left=100')"><i class="icon-remove-sign"></i> 未參加</a></li>
                                                    <?php }
                                                ?>
                                                <li><a href="javascript:Mars_popup('ad_mem_action_re_print.php?acre_auto=<?php echo $re["acre_auto"]; ?>','','width=700,height=520,top=100,left=100')"><i class="icon-print"></i> 列印收據</a></li>
                                                <?php 
                                                    if($_SESSION["MM_UserAuthorization"] == "admin" || $_SESSION["MM_UserAuthorization"] == "pay" && $re["acre_time2"] > date("Y/m/d",strtotime("-800 day"))){ ?>
                                                        <li><a href="ad_mem_action_re_ac2.php?acre_auto=<?php echo $re["acre_auto"]; ?>&acre_num=<?php echo $re["acre_num"]; ?>&st=on"><i class="icon-print"></i> 移至待轉</a></li>
                                                    <?php }
                                                ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                        ?>
                    </tbody>
                </table>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <?php
                        $SQL = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_ck2 = 0 and acre_num = '".$ac_auto."'";
                        $rs = $SPConn->prepare($SQL);
                        $rs->execute();
                        $result = $rs->fetch(PDO::FETCH_ASSOC);
                        if($result){
                            $sumr1 = round($result["acre_pay2_total"]);
                        }

                        $SQL = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_ck2 = 0 and acre_pay = '保證金' and acre_num = '".$ac_auto."'";
                        $rs = $SPConn->prepare($SQL);
                        $rs->execute();
                        $result = $rs->fetch(PDO::FETCH_ASSOC);
                        if($result){
                            $sumr2 = round($result["acre_pay2_total"]);
                        }

                        $SQL = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_ck2 = 0 and acre_pay = '刷卡' and acre_num = '".$ac_auto."'";
                        $rs = $SPConn->prepare($SQL);
                        $rs->execute();
                        $result = $rs->fetch(PDO::FETCH_ASSOC);
                        if($result){
                            $sumr3 = round($result["acre_pay2_total"]);
                        }

                        $SQL = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_ck2 = 0 and acre_pay = '匯款' and acre_num = '".$ac_auto."'";
                        $rs = $SPConn->prepare($SQL);
                        $rs->execute();
                        $result = $rs->fetch(PDO::FETCH_ASSOC);
                        if($result){
                            $sumr4 = round($result["acre_pay2_total"]);
                        }

                        $SQL = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_ck2 = 0 and acre_pay = '活動卷' and acre_num = '".$ac_auto."'";
                        $rs = $SPConn->prepare($SQL);
                        $rs->execute();
                        $result = $rs->fetch(PDO::FETCH_ASSOC);
                        if($result){
                            $sumr5 = round($result["acre_pay2_total"]);
                        }

                        $SQL = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_ck2 = 0 and acre_pay = '現金' and acre_num = '".$ac_auto."'";
                        $rs = $SPConn->prepare($SQL);
                        $rs->execute();
                        $result = $rs->fetch(PDO::FETCH_ASSOC);
                        if($result){
                            $sumr6 = round($result["acre_pay2_total"]);
                        }

                        $SQL = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_ck2 = 0 and acre_pay = '抵用卷' and acre_num = '".$ac_auto."'";
                        $rs = $SPConn->prepare($SQL);
                        $rs->execute();
                        $result = $rs->fetch(PDO::FETCH_ASSOC);
                        if($result){
                            $sumr7 = round($result["acre_pay2_total"]);
                        }

                        $SQL = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_ck2 = 0 and acre_pay = '新抵用卷' and acre_num = '".$ac_auto."'";
                        $rs = $SPConn->prepare($SQL);
                        $rs->execute();
                        $result = $rs->fetch(PDO::FETCH_ASSOC);
                        if($result){
                            $sumr8 = round($result["acre_pay2_total"]);
                        }

                        $allsum = $sumr1-$sumr2-$sumr5-$sumr7-$sumr8;

                    ?>
                    <tbody>
                        <tr>
                            <td>保証金：<?php echo $sumr2; ?>元</td>
                            <td>刷卡：<?php echo $sumr3; ?>元</td>
                            <td>匯款：<?php echo $sumr4; ?>元</td>
                            <td>活動卷：<?php echo $sumr5; ?>元</td>
                            <td>現金：<?php echo $sumr6; ?>元</td>
                            <td>抵用卷：<?php echo $sumr7; ?>元</td>
                            <td>新抵用卷：<?php echo $sumr8; ?>元</td>
                            <td>總收入：<?php echo $allsum; ?>元</td>
                        </tr>
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
    $mtu = "ad_mem_action_re_list.";
</script>