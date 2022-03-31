<?php
    /*****************************************/
    //檔案名稱：ad_mem_action_re_list.php
    //後台對應位置：管理系統/排約紀錄功能/活動明細表
    //改版日期：2022.3.17
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
    check_page_power("ad_mem_action_re_list");

    // 接收值
    $vst = SqlFilter($_REQUEST["vst"],"tab");

    // 刪除
    if($_REQUEST["st"] == "del"){
        $SQL = "select * FROM ac_data_re WHERE acre_auto=".SqlFilter($_REQUEST["acre_auto"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $advisory_auton = $result["advisory_auton"];
            $SQL = "DELETE FROM ac_data_re WHERE acre_auto=".SqlFilter($_REQUEST["acre_auto"],"int")."";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }
        reURL("reload_window.php?m=資料刪除中...");
    }

    // 設定為參加
    if($_REQUEST["st"] == "on"){
        $SQL = "update ac_data_re set acre_ck=1 Where acre_auto = ".SqlFilter($_REQUEST["acre_auto"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        reURL("reload_window.php?m=設定為參加...");
    }

    // 設定未參加
    if($_REQUEST["st"] == "off"){
        $SQL = "update ac_data_re set acre_ck=2 Where acre_auto = ".SqlFilter($_REQUEST["acre_auto"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        reURL("reload_window.php?m=設定未參加...");
    }

    // 日期
    if($_REQUEST["times1"] != "" && $_REQUEST["times2"] != ""){
        if($_REQUEST["times1"] > $_REQUEST["times2"]){
            call_alert("日期請由小到大選擇",0,0);            
        }
        $times1 = SqlFilter($_REQUEST["times1"],"tab");
        $times2 = SqlFilter($_REQUEST["times2"],"tab");
        $times1_1 = SqlFilter($_REQUEST["times1"],"tab"). " 00:00";
        $times2_1 = SqlFilter($_REQUEST["times2"],"tab"). " 23:59";
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
            break;
        case "branch":
        case "action":
        case "love":
        case "pay":
            $sqls2 = "SELECT ".$sqlv2." as total_size FROM ac_data_re Where all_branch= '".$_SESSION["branch"]."'";
            break;
    }

    // 回報日期sql
    if(chkDate($times1_1) && chkDate($times2_1)){
        if($times1_1 > $times2_1){
            call_alert("結束日期不能大於起始日期。", 0, 0);
        }
        $sqlss = $sqlss . " and acre_sign between '".$times1_1."' and '".$times2_1."'";
    }

    // 會館sql
    if($_REQUEST["branch"] != ""){
        $branch = SqlFilter($_REQUEST["branch"],"tab");
        $sqlss = $sqlss . " and all_branch like '%" . str_replace("'", "''", $branch) . "%'";
    }

    // 秘書sql
    if($_REQUEST["single"] != ""){
        $single = SqlFilter($_REQUEST["single"],"tab");
        $sqlss = $sqlss . " and all_single like '%" . str_replace("'", "''", $single) . "%'";
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
            break;
        case "branch":
        case "action":
        case "love":
        case "pay":
            $sqls =  "SELECT ".$sqlv." FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM ac_data_re Where all_branch= '".$_SESSION["branch"]."'";
            break;
    }

    // sql
    $sqls = $sqls . $sqlss . " order by acre_auto desc ) t1 order by acre_auto ) t2 order by acre_auto desc";

?>
<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li class="active">活動明細表</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->


        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>活動明細表 - 數量：<?php echo $total_sizen; ?></strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">

                <div class="col-md-12">
                    <input type="text" name="qkword" id="qkword" class="form-control2"> <input type="button" id="member_query_button" class="btn btn-danger" value="查詢會員">
                    <form action="ad_mem_action_re_list.php?vst=full" method="post" name="form1">
                        <p><span>
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
                            回報日期：
                            <input type="text" name="times1" id="times1" class="datepicker" autocomplete="off" placeholder="時間區間" value="<?php echo SqlFilter($_REQUEST["times1"],"tab"); ?>"> ~ <input type="text" name="times2" id="times2" class="datepicker" autocomplete="off" placeholder="時間區間" value="<?php echo SqlFilter($_REQUEST["times2"],"tab"); ?>">
                        </p>
                        <p>
                            會館：
                            <select name="branch" id="branch" style="width:100px;">
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
                            秘書：
                            <select name="single" id="single" style="width:100px;">
                                <option value="">請選擇</option>
                                <?php
                                if ( $branch != "" ){
                                    $SQL = "Select p_user, p_name, p_other_name, lastlogintime From personnel_data Where p_branch = '".$branch."' Order By p_desc2 Desc, lastlogintime Desc";
                                    $rs = $SPConn->prepare($SQL);
                                    $rs->execute();
                                    $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                                    if ( count($result) > 0 ){
                                        foreach($result as $re){?>
                                            <option value="<?php echo $re["p_user"];?>"<?php if ( $single == $re["p_user"] ){?> selected<?php }?>><?php echo $re["p_other_name"]?></option>
                                        <?php }?>
                                    <?php }?>
                                <?php }?>
                            </select>
                            <input type="submit" value="送出" class="btn btn-default">
                        </p>
                    </form>
                </div>

                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                        <tr>
                            <th>報名日期</th>
                            <th>活動日期和地區/活動名稱</th>
                            <th>參加人員</th>
                            <th>收費金額</th>
                            <th width="150">活動備註</th>
                            <th>處理會館</th>
                            <th>處理秘書</th>
                            <th>狀態</th>
                            <th width="100">　</th>
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
                                                $SQL = "SELECT * FROM member_data Where mem_username='".$re["acre_user"]."'";
                                                $rs = $SPConn->prepare($SQL);
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
                                        <td align="center">
                                            <div class="btn-group">
                                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">操作 <span class="caret"></span></button>
                                                <ul class="dropdown-menu pull-right">
                                                    <?php
                                                        $diff = (strtotime("now") - strtotime($re["acre_time"]))/ (60*60*24);
                                                        $diff = floor($diff);                                                     
                                                        if($diff == 0 || $_SESSION["MM_UserAuthorization"] == "admin"){ ?>
                                                            <li><a href="javascript:Mars_popup2('ad_mem_action_re_list.php?st=del&acre_auto=<?php echo $re["acre_auto"]; ?>','','width=300,height=200,top=100,left=100')"><i class="icon-trash"></i> 刪除</a></li>
                                                            <li><a href="javascript:Mars_popup('ad_mem_action_re_list.php?st=on&acre_auto=<?php echo $re["acre_auto"]; ?>','','width=300,height=200,top=100,left=100')"><i class="icon-ok-sign"></i> 參加</a></li>
                                                            <li><a href="javascript:Mars_popup('ad_mem_action_re_list.php?st=off&acre_auto=<?php echo $re["acre_auto"]; ?>','','width=300,height=200,top=100,left=100')"><i class="icon-remove-sign"></i> 未參加</a></li>
                                                        <?php }
                                                    ?>
                                                    <li><a href="javascript:Mars_popup('ad_mem_action_re_print.php?acre_auto=<?php echo $re["acre_auto"]; ?>','','width=700,height=520,top=100,left=100')"><i class="icon-print"></i> 列印收據</a></li>
                                                </ul>
                                            </div>
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

<script type="text/javascript">
    $(function() {
        $("#member_query_button").on("click", function() {
            if (!$("#qkword").val()) {
                alert("請輸入要查詢的會員相關資料，如姓名、電話等。");
                $("#qkword").focus();
                return false;
            }
            Mars_popup('ad_mem_love_re_list.php?st=query_member&qkword=' + $("#qkword").val(), '', 'width=500,height=250,top=250,left=250');
        });
    });
</script>