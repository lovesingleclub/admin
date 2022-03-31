<?php
    /*****************************************/
    //檔案名稱：ad_mem_action_re_day.php
    //後台對應位置：管理系統/活動明細表/每日活動紀錄
    //改版日期：2022.3.23
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
    // 接收值
    $vst = SqlFilter($_REQUEST["vst"],"tab");

    // 日期
    if($_REQUEST["times1"] != "" && $_REQUEST["times2"] != ""){
        if($_REQUEST["times1"] > $_REQUEST["times2"]){
            call_alert("日期請由小到大選擇",0,0);
        }
        $times1 = $_REQUEST["times1"];
        $times1_1 = $_REQUEST["times1"]." 00:00";
        $times2 = $_REQUEST["times2"];
        $times2_1 = $_REQUEST["times2"]." 23:59";
    }

    $default_sql_num = 500;
    if($_REQUEST["vst"] == "full"){
        $sqlv = "*";
        $sqlv2 = "count(acre_auto)";
    }else{
        $sqlv = "top ".$default_sql_num." *";
        $sqlv2 = "count(acre_auto)";
    }

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

    if(chkDate($times1_1) && chkDate($times2_1)){
        if($times1_1 > $times2_1){
            call_alert("結束日期不能大於起始日期。", 0, 0);
        }
        $sqlss = $sqlss . " and acre_sign between '".$times1_1."' and '".$times2_1."'";
        $sqlss2 = $sqlss2 . " and acre_time_del between '".$times1_1."' and '".$times2_1."'";
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

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li><a href="ad_mem_action_re_list.php">活動明細表</a></li>
            <li class="active">每日活動紀錄</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>每日活動紀錄 - 數量：<?php echo $total_sizen; ?></strong> <!-- panel title -->
                    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="btn btn-info" onclick="Mars_popup('ad_mem_action_re_day_print.php?branch=<?php echo SqlFilter($_REQUEST['branch'],'tab'); ?>&times1=<?php echo SqlFilter($_REQUEST['times1'],'tab'); ?>&times2=<?php echo SqlFilter($_REQUEST['times2'],'tab'); ?>','','scrollbars=yes,location=yes,status=yes,menubar=yes,resizable=yes,width=690,height=600,top=10,left=10');" value="列印本頁">
                </span>
            </div>

            <div class="panel-body">
                <div class="col-md-12">
                    <form action="ad_mem_action_re_day.php?vst=full" method="post" name="form1">
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
                            </span>　
                            <select name="branch" id="branch" style="width:140px;">
                                <option value="">請選擇會館</option>
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
                            報名日期：
                            <input type="text" name="times1" id="times1" class="datepicker" autocomplete="off" placeholder="時間區間" value="<?php echo $times1 ?>"> ~ <input type="text" name="times2" id="times2" class="datepicker" autocomplete="off" placeholder="時間區間" value="<?php echo $times2 ?>">

                            <input type="submit" value="送出" class="btn btn-default">
                        </p>
                        <p>
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
                            （收入：<?php echo $sumresu2; ?>元）（支出：<?php echo $sumresu3; ?>元）
                        </p>
                    </form>
                </div>

                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
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
                    </thead>
                    <tbody>
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
                                                    echo "待轉時間(".Date_EN($re["acre_time_turn"],1).")";
                                                }
                                                if($re["acre_ck2"] == 2){
                                                    echo "退費時間(".Date_EN($re["acre_time_del"],1).")";
                                                }
                                                if($re["acre_time_turn2"] != ""){
                                                    echo "轉入時間(".Date_EN($re["acre_time_turn2"],1).")";
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
                <table class="table table-striped table-bordered bootstrap-datatable">
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
                        echo "<table class='table table-striped table-bordered bootstrap-datatable'>";
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
                            </tr>
                        <?php }
                        echo "</table>";
                    }else{
                        echo "<table class='table table-striped table-bordered bootstrap-datatable'>";
                        echo "<tr><td colspan=9 height=200>目前沒有資料</td></tr>";
                        echo "</table>";
                    }
                ?>
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
require("./include/_bottom.php");
?>

<script type="text/javascript">
    $mtu = "ad_mem_action_re_list.";

</script>