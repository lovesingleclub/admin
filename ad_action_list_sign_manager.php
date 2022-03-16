<?php
    /*****************************************/
    //檔案名稱：ad_action_list_sign_manager.php
    //後台對應位置：管理系統/活動異動單列表
    //改版日期：2022.3.14
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
    
    $default_sql_num = 500;
    if($_REQUEST["vst"] == "full"){
        $sqlv = "*";
        $sqlv2 = "count(auton)";
    }else{
        $sqlv = "top ".$default_sql_num." *";
        $sqlv2 = "count(auton)";
    }

    switch($_SESSION["MM_UserAuthorization"]){
        case "admin":
            $sqls2 = "SELECT ".$sqlv2." as total_size FROM system_sign WHERE types='活動異動單'";
            break;
        case "branch":
        case "manager":
        case "love_manager":
	        $sqls2 = "SELECT ".$sqlv2." as total_size FROM system_sign WHERE types='活動異動單' and branch='".$_SESSION["branch"]."'";
            break;
        default:
            if($_SESSION["action_level"] == 1){ //南區企劃
	            $sqls2 = "SELECT ".$sqlv2." as total_size FROM system_sign WHERE types='活動異動單' and (branch = '台南' or branch = '高雄' or branch = '台北')";
            }elseif($_SESSION["action_level"] == 2){ //北區企劃
	            $sqls2 = "SELECT ".$sqlv2." as total_size FROM system_sign WHERE types='活動異動單' and single= '".strtoupper($_SESSION["MM_username"])."'";
            }elseif($_SESSION["action_level"] == 3){ //企劃總監
                $sqls2 = "SELECT ".$sqlv2." as total_size FROM system_sign WHERE types='活動異動單'";
            }else{
	            $sqls2 = "SELECT ".$sqlv2." as total_size FROM system_sign WHERE types='活動異動單' and single= '".strtoupper($_SESSION["MM_username"])."'";
            } 
    }

    // 關鍵字搜尋
    if($_REQUEST["keyword"] != ""){
        $sqlss = $sqlss." and (notes like '%".SqlFilter($_REQUEST["keyword"],"tab")."%' or statnote like '%".SqlFilter($_REQUEST["keyword"],"tab")."%')";
    }

    // 申請日期(起始日期)
    if($_REQUEST["start_time"] != ""){
        $start_time = SqlFilter($_REQUEST["start_time"],"tab")." 00:00";
        $start_time2 = SqlFilter($_REQUEST["start_time"],"tab");
        if(!chkDate($start_time)){
            call_alert("申請日期有誤。", 0, 0);
        }
    }

    // 申請日期(結束日期)
    if($_REQUEST["end_time"] != ""){
        $end_time = SqlFilter($_REQUEST["end_time"],"tab")." 23:59";
        $end_time2 = SqlFilter($_REQUEST["end_time"],"tab");
        if(!chkDate($end_time)){
            call_alert("申請日期有誤。", 0, 0);
        }
    }

    // 申請日期
    if(chkDate($start_time) && chkDate($end_time)){
        $sqlss = $sqlss . " and times between '".$start_time."' and '".$end_time."'";
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
            $sqls = "SELECT ".$sqlv." FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM system_sign WHERE types='活動異動單'";
            break;
        case "branch":
        case "manager":
        case "love_manager":
            $sqls = "SELECT ".$sqlv." FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM system_sign WHERE types='活動異動單' and branch='".$_SESSION["branch"]."'";
            break;
        default:
            if($_SESSION["action_level"] == 1){ //南區企劃
                $sqls = "SELECT ".$sqlv." FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM system_sign WHERE types='活動異動單' and (branch = '台南' or branch = '高雄' or branch = '台北')";
            }elseif($_SESSION["action_level"] == 2){ //北區企劃
                $sqls = "SELECT ".$sqlv." FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM system_sign WHERE types='活動異動單' and (branch = '桃園' or branch = '新竹' or branch = '台中' or branch = '台北')";
            }elseif($_SESSION["action_level"] == 3){ //企劃總監
                $sqls = "SELECT ".$sqlv." FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM system_sign WHERE types='活動異動單'";
            }else{
                $sqls = "SELECT ".$sqlv." FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM system_sign WHERE types='活動異動單' and single= '".strtoupper($_SESSION["MM_username"])."'";
            } 
    }
    // SQL  
    $sqls = $sqls . $sqlss . " order by times desc ) t1 order by times ) t2 order by times desc";

?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li class="active">活動異動單列表</li>
        </ol>
    </header>
    <!-- /page title -->


    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>活動異動單列表 - 數量：<?php echo $total_sizen; ?></strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p>
                <form id="searchform" action="ad_action_list_sign_manager.php" method="post" target="_self" class="form-inline">
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
                    <input type="text" name="start_time" id="start_time" class="datepicker" autocomplete="off" value="<?php echo $start_time2; ?>" placeholder="申請日期開始">　～　<input type="text" name="end_time" id="end_time" class="datepicker" autocomplete="off" value="<?php echo $end_time2; ?>" placeholder="申請日期結束">
                    <input id="keyword" name="keyword" id="keyword" class="form-control" type="text" placeholder="搜尋內容" value="<?php echo SqlFilter($_REQUEST["keyword"],"tab"); ?>">
                    <input type="submit" id="search_send" class="btn btn-default" value="查詢">
                </form>
                </p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                        <tr>
                            <th width=140>申請時間</th>
                            <th width=180>申請人</th>
                            <th width=160>類型</th>
                            <th>內容</th>
                            <th width=120>狀態</th>
                            <th>過程</th>
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
                                        <td class="center"><?php echo Date_EN($re["times"],9); ?></td>    	
                                        <td class="center"><?php echo $re["branch"]; ?>-<?php echo $re["singlename"]; ?></td>      
                                        <td class="center"><?php echo $re["types"]; ?><?php if($re["types2"] != "") echo "<br>".$re["types2"]; ?></td>
                                        <td class="center"><?php echo $re["notes"]; ?></td>
                                        <td class="center">
                                            <?php                                                
                                                switch($re["stat"]){
                                                    case 1:
                                                        if($re["branchfix"] == 1){
                                                            echo "督導/經理核准";
                                                        }else{
                                                            echo "督導/經理駁回";
                                                        }
                                                        break;
                                                    case 2:
                                                        if($re["managerfix"] == 1){
                                                            echo "總經理核准";
                                                        }else{
                                                            echo "總經理駁回";
                                                        }
                                                        break;
                                                    case 3:
                                                        if($re["adminfix"] == 1){
                                                            echo "管理者核准";
                                                        }else{
                                                            echo "管理者駁回";
                                                        }
                                                        break;
                                                    case -1:
                                                        echo "等待處理";
                                                        break;
                                                    case -2:
                                                        echo "待核准";
                                                        break;
                                                    case 7:
                                                        echo "不需處理";
                                                        break;
                                                    case 8:
                                                        echo "已處理";
                                                        break;
                                                    default:
                                                        if($re["needbranch"] == 1){
                                                            echo "待核准";
                                                        }else{
                                                            echo "待處理";
                                                        }
                                                }
                                            ?>
                                        </td>
                                        <td class="center"><?php echo $re["statnote"]; ?></td>  
                                    </tr>
                                <?php }
                            }else{
                                echo "<tr><td colspan=8>暫無資料</td></tr>";
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
    $mtu = "ad_action_list_sign_manager.";
    $(function() {
    });
</script>