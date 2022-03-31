<?php
    /*****************************************/
    //檔案名稱：ad_mem_action_re_day_print.php
    //後台對應位置：管理系統/活動明細表/每日活動紀錄>列印本頁
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

    // 起始日
    if($_REQUEST["times1"] != ""){
        if(!chkDate($_REQUEST["times1"])){
            call_alert("起始日期有誤。", "ClOsE", 0);
        }
        $times1 = SqlFilter($_REQUEST["times1"],"tab");
    }else{
        call_alert("請先選擇起始日期後按查詢再列印。", "ClOsE", 0);
    }

    // 結束日
    if($_REQUEST["times2"] != ""){
        if(!chkDate($_REQUEST["times2"])){
            call_alert("起始日期有誤。", "ClOsE", 0);
        }
        $times2 = SqlFilter($_REQUEST["times2"],"tab");
    }else{
        call_alert("請先選擇結束日期後按查詢再列印。", "ClOsE", 0);
    }

    $diff = (strtotime($times2) - strtotime($times1))/ (60*60*24);

    if($diff > 60){
        call_alert("日期範圍不能超過兩個月。", "ClOsE", 0);
    }

    $sqlv = "*";
    $sqlv2 = "count(acre_auto)";

    switch($_SESSION["MM_UserAuthorization"]){
        case "admin":
            $sqls2 = "SELECT ".$sqlv2." as total_size FROM ac_data_re Where 1=1";
            if($_REQUEST["branch"] != ""){
                $branch = SqlFilter($_REQUEST["branch"],"tab");
                $sqlss = $sqlss . " and all_branch like '%" . str_replace("'", "''",$branch) . "%'";
                $sqlss2 = $sqlss2 . " and all_branch like '%" . str_replace("'", "''",$branch) . "%'";
            }
            break;
        case "branch":
        case "action":
        case "love":
        case "pay":
            $p_branch = $_SESSION["branch"];
            $sqls2 = "SELECT ".$sqlv2." as total_size FROM ac_data_re Where all_branch= '".$p_branch."'";
            break;
    }

    if(chkDate($times1) && chkDate($times2)){
        if($times1 > $times2){
            call_alert("結束日期不能大於起始日期。", 0, 0);
        }
        $sqlss = $sqlss . " and acre_sign between '".$times1."' and '".$times2."'";
        $sqlss2 = $sqlss2 . " and acre_time_del between '".$times1."' and '".$times2."'";
        $acre_sign = $times1 . " ~ " . $times2;
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
            $sqls = "SELECT ".$sqlv." FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM ac_data_re Where 1=1";
            $sqls3 = "SELECT * FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM ac_data_re Where acre_ck2 = 2";
            $sumsql1 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where 1=1";
            $sumsql2 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay2 > 0";
            $sumsql3 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay2 < 0";
            $sumsql4 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '保證金'";
            $sumsql5 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '刷卡'";
            $sumsql6 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '匯款'";
            $sumsql7 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '活動卷'";
            $sumsql8 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '現金'";
            $sumsql9 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_ck2 = 2";
            $sumsql10 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '抵用卷'";
            $sumsql11 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '新抵用卷'";
            break;
        case "branch":
        case "action":
        case "love":
        case "pay":
            $p_branch = $_SESSION["branch"];
            $sqls = "SELECT ".$sqlv." FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM ac_data_re Where all_branch= '".$p_branch."'";
            $sqls3 = "SELECT * FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM ac_data_re Where acre_ck2 = 2 and all_branch = '".$p_branch."'";
            $sumsql1 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where all_branch = '".$p_branch."'";
            $sumsql2 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay2 > 0 and all_branch = '".$p_branch."'";
            $sumsql3 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay2 < 0 and all_branch = '".$p_branch."'";
            $sumsql4 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '保證金' and all_branch = '".$p_branch."'";
            $sumsql5 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '刷卡' and all_branch = '".$p_branch."'";
            $sumsql6 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '匯款' and all_branch = '".$p_branch."'";
            $sumsql7 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '活動卷' and all_branch = '".$p_branch."'";
            $sumsql8 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '現金' and all_branch = '".$p_branch."'";
            $sumsql9 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_ck2 = 2 and all_branch = '".$p_branch."'";
            $sumsql10 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '抵用卷' and all_branch = '".$p_branch."'";
            $sumsql11 = "SELECT sum(acre_pay2) as acre_pay2_total FROM ac_data_re Where acre_pay = '新抵用卷' and all_branch = '".$p_branch."'";
            break;
    }

    // sql
    $sqls = $sqls . $sqlss . " order by acre_auto desc ) t1 order by acre_auto ) t2 order by acre_auto desc";
    $sqls3 = $sqls3 . $sqlss2 . " order by acre_auto desc ) t1 order by acre_auto ) t2 order by acre_auto desc";
    $sumsql1 = $sumsql1 . $sqlss;
    $sumsql2 = $sumsql2 . $sqlss;
    $sumsql3 = $sumsql3 . $sqlss;
    $sumsql4 = $sumsql4 . $sqlss;
    $sumsql5 = $sumsql5 . $sqlss;
    $sumsql6 = $sumsql6 . $sqlss;
    $sumsql7 = $sumsql7 . $sqlss;
    $sumsql8 = $sumsql8 . $sqlss;
    $sumsql9 = $sumsql9 . $sqlss2;
    $sumsql10 = $sumsql10 . $sqlss;
    $sumsql11 = $sumsql11 . $sqlss;
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
                <tr>
                    <td colspan=8><i class="icon-user"></i> 每日活動紀錄 - <?php echo $branch; ?> - <?php echo $acre_sign; ?></td>
                </tr>
                <tr>
                    <?php
                        $rs = $SPConn->prepare($sumsql2);
                        $rs->execute();
                        $result = $rs->fetch(PDO::FETCH_ASSOC);
                        if($result){
                            $sumresu2 = round($result["acre_pay2_total"]);
                        }

                        $rs = $SPConn->prepare($sumsql3);
                        $rs->execute();
                        $result = $rs->fetch(PDO::FETCH_ASSOC);
                        if($result){
                            $sumresu3 = round($result["acre_pay2_total"]);
                        }
                    ?>
                    <td colspan=8>（收入：<?php echo $sumresu2; ?>元）（支出：<?php echo $sumresu3; ?>元）</td>
                </tr>
                <tr> 
                    <td width="8%">報名日期</td>
                    <td width="25%">活動日期和地區/活動名稱</td>
                    <td width="15%">參加人員</td>
                    <td width="13%">收費金額</td>
                    <td width="10%">活動備註</td>
                    <td width="5%">處理會館</td>
                    <td width="7%">處理秘書</td>
                    <td width="5%">狀態</td>
                </tr>
                <?php 
                    $rs = $SPConn->prepare($sqls);
                    $rs->execute();
                    $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                    if($result){
                        foreach($result as $re){ ?>
                            <tr>
                                <td align="left"><?php echo changeDate($re["acre_sign"]); ?></td>
                                <td align="left">
                                    <?php echo changeDate($re["acre_time2"]); ?>(<?php echo $re["acre_branch"]; ?>)<?php echo $re["acre_title"]; ?><br>
                                    <?php 
                                        if($re["acre_ck2"] == 1){
                                            echo "待轉時間(".$re["acre_time_turn"].")";
                                        }
                                        if($re["acre_ck2"] == 2){
                                            echo "退費時間(".$re["acre_time_del"].")";
                                        }
                                        if($re["acre_time_turn2"] != ""){
                                            echo "轉入時間(".$re["acre_time_turn2"].")";
                                        }
                                    ?>
                                </td>
                                <td align="left">
                                    <?php 
                                        $rs = $SPConn->prepare("SELECT * FROM member_data Where mem_username='".$re["acre_user"]."'");
                                        $rs->execute();
                                        $result2 = $rs->fetch(PDO::FETCH_ASSOC);
                                        if($result2){ 
                                            echo $result2["mem_name"]."(".$result2["mem_username"].")<br>(".$result2["mem_by"]."/".$result2["mem_bm"]."/".$result2["mem_bd"]." , ".$result2["mem_mobile"].")";
                                        }
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
                            </tr>
                        <?php }
                    }else{
                        echo "<tr><td colspan=9 height=200>目前沒有資料</td></tr>";
                    }
                ?>  
            </tbody>
        </table>
        <table width='100%' border='1' cellpadding="4" style='border-collapse:collapse;' borderColor='black'>
            <?php 
                $rs = $SPConn->prepare($sumsql1);
                $rs->execute();
                $result = $rs->fetch(PDO::FETCH_ASSOC);
                if($result){
                    $sumresu1 = round($result["acre_pay2_total"]);
                }

                $rs = $SPConn->prepare($sumsql4);
                $rs->execute();
                $result = $rs->fetch(PDO::FETCH_ASSOC);
                if($result){
                    $sumresu4 = round($result["acre_pay2_total"]);
                }

                $rs = $SPConn->prepare($sumsql5);
                $rs->execute();
                $result = $rs->fetch(PDO::FETCH_ASSOC);
                if($result){
                    $sumresu5 = round($result["acre_pay2_total"]);
                }

                $rs = $SPConn->prepare($sumsql6);
                $rs->execute();
                $result = $rs->fetch(PDO::FETCH_ASSOC);
                if($result){
                    $sumresu6 = round($result["acre_pay2_total"]);
                }

                $rs = $SPConn->prepare($sumsql7);
                $rs->execute();
                $result = $rs->fetch(PDO::FETCH_ASSOC);
                if($result){
                    $sumresu7 = round($result["acre_pay2_total"]);
                }

                $rs = $SPConn->prepare($sumsql8);
                $rs->execute();
                $result = $rs->fetch(PDO::FETCH_ASSOC);
                if($result){
                    $sumresu8 = round($result["acre_pay2_total"]);
                }

                $rs = $SPConn->prepare($sumsql9);
                $rs->execute();
                $result = $rs->fetch(PDO::FETCH_ASSOC);
                if($result){
                    $sumresu9 = round($result["acre_pay2_total"]);
                }

                $rs = $SPConn->prepare($sumsql10);
                $rs->execute();
                $result = $rs->fetch(PDO::FETCH_ASSOC);
                if($result){
                    $sumresu10 = round($result["acre_pay2_total"]);
                }

                $rs = $SPConn->prepare($sumsql11);
                $rs->execute();
                $result = $rs->fetch(PDO::FETCH_ASSOC);
                if($result){
                    $sumresu11 = round($result["acre_pay2_total"]);
                }
            ?>
            <tr bgcolor="#DA5893">
                <td width="15%">保証金：</font>
                    <font size="2"><?php echo $sumresu4; ?>元</font>
                    </font>
                </td>
                <td width="10%">刷卡：</font>
                    <font size="2"><?php echo $sumresu5; ?>元</font>
                    </font>
                </td>
                <td width="10%">匯款：</font>
                    <font size="2"><?php echo $sumresu6; ?>元</font>
                    </font>
                </td>
                <td width="10%">活動卷：</font>
                    <font size="2"><?php echo $sumresu7; ?>元</font>
                    </font>
                </td>
                <td width="10%">現金：<font size="2"><?php echo $sumresu8; ?>元</font>
                    </font>
                </td>
                <td width="10%">抵用卷：</font>
                    <font size="2"><?php echo $sumresu10; ?>元</font>
                    </font>
                </td>
                <td width="10%">新抵用卷：</font>
                    <font size="2"><?php echo $sumresu11; ?>元</font>
                    </font>
                </td>
                <td width="25%">總收入：</font>
                    <font size="2"><?php echo ($sumresu1 - $sumresu7 - $sumresu4 + $sumresu9); ?>元</font>
                    </font>
                </td>
            </tr>
        </table>
        <?php 
            $rs = $SPConn->prepare($sqls3);
            $rs->execute();
            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
            if($result){
                echo "<table width='100%' border='1' cellpadding='4' style='border-collapse:collapse;' borderColor='black'>";
                foreach($result as $re){ ?>
                    <tr>
                        <td align="left"><?php echo changeDate($re["acre_sign"]); ?></td>
                        <td align="left">
                            <?php echo $re["acre_time2"]; ?>(<?php echo $re["acre_branch"]; ?>)<?php echo $re["acre_title"]; ?><br>
                            <span class="c1">退費時間：<?php echo Date_EN($re["acre_time_del"],1); ?></span>
                        </td>
                        <td width=200  align="left">
                            <?php 
                                $rs = $SPConn->prepare("SELECT * FROM member_data Where mem_username='".$re["acre_user"]."'");
                                $rs->execute();
                                $result2 = $rs->fetch(PDO::FETCH_ASSOC);
                                if($result2){ 
                                    echo $result2["mem_name"]."(".$result2["mem_username"].")<br>(".$result2["mem_by"]."/".$result2["mem_bm"]."/".$result2["mem_bd"]." , ".$result2["mem_mobile"].")";
                                }
                            ?>
                        </td>
                        <td align="left">（<?php echo $re["acre_pay"]; ?>）<?php echo $re["acre_pay2"]; ?></td>
                        <td align="left"><?php echo $re["acre_note"]; ?></td>
                        <td align="left"><?php echo $re["all_branch"]; ?></td>
                        <td align="left">
                            <?php 
                                if($re["all_single"] != ""){
                                    echo SingleName($re["all_single"],"normal");
                                }
                            ?>
                        </td>
                    </tr>
                <?php }
                echo "</table>";
            }else{
                echo "<table width='100%' border='1' cellpadding='4' style='border-collapse:collapse;' borderColor='black'>";
                echo "<tr><td colspan=9 height=30>目前沒有資料</td></tr>";
                echo "</table>";
            }
        ?>
    </body>
</html>

<script type="text/javascript">
    window.print();
    //window.close();
</script>