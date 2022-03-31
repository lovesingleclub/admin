<?php
/*****************************************/
//檔案名稱：ad_mem_action_re_ac1_print.php
//後台對應位置：管理系統/活動明細表/單一活動紀錄>列印本頁
//改版日期：2022.3.24
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");

//程式開始 *****
if ($_SESSION["MM_Username"] == "") {
    call_alert("請重新登入。", "login.php", 0);
}

if($_REQUEST["times1"] != "" && chkDate($_REQUEST["times1"])){
    $times1 = SqlFilter($_REQUEST["times1"],"tab");
}else{
    call_alert("請先選擇起始日期後按查詢再列印。", "ClOsE", 0);
}

if($_REQUEST["times2"] != "" && chkDate($_REQUEST["times2"])){
    $times2 = SqlFilter($_REQUEST["times2"],"tab");
}else{
    call_alert("請先選擇結束日期後按查詢再列印。", "ClOsE", 0);
}

$diff = (strtotime($times2) - strtotime($times1))/ (60*60*24);
if($diff > 60){
    call_alert("日期範圍不能超過兩個月。", "ClOsE", 0);
}

$sqlv = "*";
$sqlv2 = "count(ac_auto)";

switch($_SESSION["MM_UserAuthorization"]){
    case "admin":
        $sqls2 = "SELECT ".$sqlv2." as total_size FROM action_data Where 1=1";
        break;
    default:
        $sqls2 = "SELECT ".$sqlv2." as total_size FROM action_data Where ac_branch='".$_SESSION["branch"]."'";
}

// 日期SQL
if (chkDate($times1) && chkDate($times2)) {
    if ($times1 > $times2) {
        call_alert("結束日期不能大於起始日期。", 0, 0);
    }
    $acre_sign = $times1." ~ ".$times2;
    $sqlss = $sqlss . " and ac_time between '" . $times1 . "' and '" . $times2 . "'";
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

<html>
    <head>
        <style>
            table {
            font-size:12px;
            }
        </style>
    </head>
    <body>
    <table width="100%" border="1" cellpadding="4" style="border-collapse:collapse;" borderColor="black">
		<tbody>
            <tr><td colspan=8><i class="icon-user"></i> 單一活動紀錄 - <?php echo $acre_sign; ?></td></tr>
            <tr>
                <th>會館</th>
                <th>活動時間</th>
                <th>活動標題</th>
                <th>活動內容</th>
                <th>總金額</th>
                <th></th>
            </tr>
            <?php 
                $rs = $SPConn->prepare($sqls);
                $rs->execute();
                $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                if($result){
                    foreach($result as $re){ ?>
                        <tr>
                            <td> 
                                <div align="left"><?php echo $re["ac_branch"]; ?></div>
                            </td>
                            <td> 
                                <div align="left"><?php echo changeDate($re["ac_time"]).$ac_stat; ?></div>
                            </td>
                            <td> 
                                <div align="left"><?php echo $re["ac_title"]; ?></div>
                            </td>
                            <td>                                 
                                <?php
                                    $notes = $re["ac_note"];
                                    if(mb_strlen($notes,"utf-8") > 10){
                                        $notes = mb_substr($notes,0,10,"utf-8");
                                    }
                                    echo $notes."...";
                                ?>
                            </td>
                            <td>
                                <?php 
                                    $ac_auto = $re["ac_auto"];
                                    $SQL = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_num = '".$ac_auto."'";
                                    $rs = $SPConn->prepare($SQL);
                                    $rs->execute();
                                    $result2 = $rs->fetch(PDO::FETCH_ASSOC);
                                    if($result2){
                                        $sumr1 = round($result2["acre_pay2_total"]);
                                    }else{
                                        $sumr1 = 0;
                                    } 
                                    
                                    $SQL = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '保證金' and acre_num = '".$ac_auto."'";
                                    $rs = $SPConn->prepare($SQL);
                                    $rs->execute();
                                    $result2 = $rs->fetch(PDO::FETCH_ASSOC);
                                    if($result2){
                                        $sumr2 = round($result2["acre_pay2_total"]);
                                    }else{
                                        $sumr2 = 0;
                                    }

                                    $SQL = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '活動卷' and acre_num = '".$ac_auto."'";
                                    $rs = $SPConn->prepare($SQL);
                                    $rs->execute();
                                    $result2 = $rs->fetch(PDO::FETCH_ASSOC);
                                    if($result2){
                                        $sumr3 = round($result2["acre_pay2_total"]);
                                    }else{
                                        $sumr3 = 0;
                                    }
                                    echo ($sumr1 - $sumr2 - $sumr3)." 元";
                                ?>
                            </td>
                            <td>
                                <div align="left"><?php echo ac_stats($re["ac_stat"]); ?></div>                            
                            </td>
                        </tr>
                    <?php }
                }else{
                    echo "<tr><td colspan=9 height=200>目前沒有資料</td></tr>";
                }
            ?>
        </tbody>
    </table>
    </body>
</html>

<script type="text/javascript">
    window.print();
    //window.close();
</script>