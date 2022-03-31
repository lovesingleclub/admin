<?php

/*****************************************/
//檔案名稱：ad_mem_action_re_ac1.php
//後台對應位置：管理系統/活動明細表/單一活動紀錄
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

if ($_REQUEST["times1"] != "" && $_REQUEST["times2"] != "") {
    if ($_REQUEST["times1"] > $_REQUEST["times2"]) {
        call_alert("日期請由小到大選擇", 0, 0);
    }
    $times1 = SqlFilter($_REQUEST["times1"], "tab");
    $times1_1 = SqlFilter($_REQUEST["times1"], "tab") . " 00:00";
    $times2 = SqlFilter($_REQUEST["times2"], "tab");
    $times2_1 = SqlFilter($_REQUEST["times2"], "tab") . " 23:59";
}

$default_sql_num = 500;
if ($_REQUEST["vst"] == "full") {
    $sqlv = "*";
    $sqlv2 = "count(ac_auto)";
} else {
    $sqlv = "top " . $default_sql_num . " *";
    $sqlv2 = "count(ac_auto)";
}

switch ($_SESSION["MM_UserAuthorization"]) {
    case "admin":
        $sqls2 = "SELECT " . $sqlv2 . " as total_size FROM action_data Where 1=1";
        break;
    default:
        $sqls2 = "SELECT " . $sqlv2 . " as total_size FROM action_data Where (ac_branch='" . $_SESSION["branch"] . "' or ac_branch='總管理處')";
}

// 日期SQL
if (chkDate($times1_1) && chkDate($times2_1)) {
    if ($times1_1 > $times2_1) {
        call_alert("結束日期不能大於起始日期。", 0, 0);
    }
    $sqlss = $sqlss . " and ac_time between '" . $times1_1 . "' and '" . $times2_1 . "'";
}

// 會館sql
if($_REQUEST["branch"] != ""){
    $branch = SqlFilter($_REQUEST["branch"],"tab");
    $sqlss = $sqlss . " and ac_branch='" . str_replace("'","''",$branch) . "'";    
}

//計算總筆數
$sqls2 = $sqls2 . $sqlss;
$rs = $SPConn->prepare($sqls2);
$rs->execute();
$result = $rs->fetch(PDO::FETCH_ASSOC);          
if($result){
    $total_size =  $result["total_size"];
    if($_REQUEST["vst"] == "full"){            
        $total_sizen = $total_size . "　<a href='?vst=n'>[查看前五百筆]</a>";
    }else{            
        if($total_size > 500){
            $total_size = 500;
        }
        $total_sizen = $total_size . "　<a href='?vst=full'>[查看完整清單]</a>";
    }
}else{
    $total_size = 0;
    $total_sizen = 0;
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

switch($_SESSION["MM_UserAuthorization"]) {
    case "admin":
        $sqls = "SELECT " . $sqlv . " FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM action_data Where 1=1";
        break;
    default:
        $sqls = "SELECT " . $sqlv . " FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM action_data Where (ac_branch='" . $_SESSION["branch"] . "' or ac_branch='總管理處')";
}

// sql
$sqls = $sqls . $sqlss . " order by ac_time desc ) t1 order by ac_time ) t2 order by ac_time desc";

?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li><a href="ad_mem_action_re_list.php">活動明細表</a></li>
            <li class="active">單一活動紀錄</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>單一活動紀錄 - 數量：<?php echo $total_sizen; ?></strong> <!-- panel title -->
                    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="btn btn-info" onclick="Mars_popup('ad_mem_action_re_ac1_print.php?times1=<?php echo $times1_1; ?>&times2=<?php echo $times2_1; ?>&branch=<?php echo $branch; ?>','','scrollbars=yes,location=yes,status=yes,menubar=yes,resizable=yes,width=690,height=600,top=10,left=10');" value="列印本頁">
                </span>
            </div>

            <div class="panel-body">
                <div class="col-md-12">
                    <form name="form1" method="post" action="ad_mem_action_re_ac1.php">
                        <p>
                            <span>
                                <div class="btn-group pull-left margin-right-10">
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">功能 <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="ad_mem_action_re.php"><i class="fa fa-edit"></i> 新增活動明細</a></li>
                                        <li><a href="ad_mem_action_re_list.php"><i class="fa fa-th-large"></i> 活動明細表</a></li>
                                        <li><a href="ad_mem_action_re_day.php"><i class="fa fa-th-list"></i> 每日活動記錄</a></li>
                                        <li><a href="ad_mem_action_re_ac1.php"><i class="fa fa-th-list"></i> 單一活動記錄</a></li>
                                        <li><a href="ad_mem_action_re_list_turn.php"><i class="fa fa-share"></i> 待轉資料查詢</a></li>
                                        <li><a href="ad_mem_action_re_list_turn2.php"><i class="fa fa-arrow-circle-right"></i> 退費資料查詢</a></li>

                                    </ul>
                                </div>　
                            </span>　活動時間：
                            <input type="text" name="times1" id="times1" class="datepicker" autocomplete="off" placeholder="時間區間" value="<?php echo $times1; ?>"> ~ <input type="text" name="times2" id="times2" class="datepicker" autocomplete="off" placeholder="時間區間" value="<?php echo $times2; ?>">　
                            會館：
                            <select name="branch" id="branch">
                                <option value="">請選擇</option>
                                <?php
                                    $SQL = "Select * From branch_data Where admin_name<>'線上諮詢' Order By admin_SOrt";
                                    $rs = $SPConn->prepare($SQL);
                                    $rs->execute();
                                    $result=$rs->fetchAll(PDO::FETCH_ASSOC);                                
                                    foreach($result as $re){
                                        if($branch == $re["admin_name"]){
                                            echo "<option value='".$re["admin_name"]."' selected>".$re["admin_name"]."</option>";  
                                        }else{
                                            echo "<option value='".$re["admin_name"]."'>".$re["admin_name"]."</option>";  
                                        }                                                                                        
                                    }                                           
                                ?>
                            </select>
                            <input type="submit" value="送出" class="btn btn-default">
                        </p>
                    </form>
                </div>

                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                        <tr>
                            <th>會館</th>
                            <th>活動時間</th>
                            <th>活動標題</th>
                            <th width="40%">活動內容</th>
                            <th>總金額</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $rs = $SPConn->prepare($sqls);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                foreach($result as $re){
                                    if($re["ac_stat"] == 1){
                                        $ac_stat = "<br><font color=red>活動取消(".$re["ac_stat_time"].")</font>";
                                    }elseif($re["ac_stat"] == 2){
                                        $ac_stat = "<br><font color=blue>活動新增(".$re["ac_stat_time"].")</font>";
                                    }else{
                                        $ac_stat = "";
                                    } ?>
                                    <tr>
                                        <td> 
                                            <div align="left"><?php echo $re["ac_branch"]; ?></div>
                                        </td>
                                        <td> 
                                            <div align="left"><?php echo changeDate($re["ac_time"]).$ac_stat; ?></div>
                                        </td>
                                        <td>
                                            <div align="left"><a href="ad_mem_action_re_ac2.php?ac_auto=<?php echo $re["ac_auto"]; ?>"><?php echo $re["ac_title"]; ?></a></div>
                                        </td>
                                        <td> 
                                            <a href="ad_mem_action_re_ac2.php?ac_auto=<?php echo $re["ac_auto"]; ?>">
                                                <?php
                                                    $notes = $re["ac_note"];
                                                    if(mb_strlen($notes,"utf-8") > 100){
                                                        $notes = mb_substr($notes,0,100,"utf-8");
                                                    }
                                                    echo $notes;
                                                ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php 
                                                $ac_auto = $re["ac_auto"];
                                                $SQL = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_num = '".$ac_auto."' and acre_ck2 = 0";
                                                $rs = $SPConn->prepare($SQL);
                                                $rs->execute();
                                                $result2 = $rs->fetchAll(PDO::FETCH_ASSOC);
                                                if($result2){
                                                    $sumr1 = round($result2["acre_pay2_total"]);
                                                }else{
                                                    $sumr1 = 0;
                                                } 
                                                
                                                $SQL = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '保證金' and acre_num = '".$ac_auto."'";
                                                $rs = $SPConn->prepare($SQL);
                                                $rs->execute();
                                                $result2 = $rs->fetchAll(PDO::FETCH_ASSOC);
                                                if($result2){
                                                    $sumr2 = round($result2["acre_pay2_total"]);
                                                }else{
                                                    $sumr2 = 0;
                                                }

                                                $SQL = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '活動卷' and acre_num = '".$ac_auto."'";
                                                $rs = $SPConn->prepare($SQL);
                                                $rs->execute();
                                                $result2 = $rs->fetchAll(PDO::FETCH_ASSOC);
                                                if($result2){
                                                    $sumr3 = round($result2["acre_pay2_total"]);
                                                }else{
                                                    $sumr3 = 0;
                                                }
                                                echo ($sumr1 - $sumr2 - $sumr3)." 元";
                                            ?>
                                        </td>
                                    </tr>
                                <?php }
                            }else{
                                echo "<tr><td colspan=9 height=200>目前沒有資料</td></tr>";
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
    $mtu = "ad_mem_action_re_list.";
</script>