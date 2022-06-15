<?php

/*****************************************/
//檔案名稱：singleweb_fun14.php
//後台對應位置：約會專家系統/企業專區
//改版日期：2022.5.30
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

//刪除
if($_REQUEST["st"] == "del"){
    $SQL = "delete FROM si_business_contact where auton='".SqlFilter($_REQUEST["an"],"int")."'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    reURL("win_close.php?m=刪除中...");
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

$sqls2 = "SELECT ".$sqlv2." as total_size FROM si_business_contact WHERE 1=1";

//查詢條件
if($_REQUEST["s99"] != "1"){
    $sqls2 = $sqls2 . " and (all_note IS NULL)";
    $all_type = "未處理";
}else{
    $sqls2 = $sqls2 . " and not (all_note IS NULL)";
    $all_type = "已處理";
}

// 計算總筆數
$rs = $SPConn->prepare($sqls2);
$rs->execute();
$result = $rs->fetch(PDO::FETCH_ASSOC);
if (!$result){
    $total_size = 0;
}else{
    if( $_REQUEST["vst"] == "full" ){
        $total_size = $result["total_size"]; //總筆數
    }else{
        if($result["total_size"] > 500 ) {
            $total_size =  500; //限制到500筆
        }else{
            $total_size =  $result["total_size"];
        }   
    }
}

$tPage = 1; //目前頁數
$tPageSize = 50; //每頁幾筆
if ( $_REQUEST["tPage"] > 1 ){ $tPage = $_REQUEST["tPage"];}
$tPageTotal = ceil(($total_size/$tPageSize)); //總頁數
if ( $tPageSize*$tPage < $total_size ){
    $page2 = 50;
}else{
    $page2 = (50-(($tPageSize*$tPage)-$total_size));
}

//查詢條件
if($_REQUEST["s99"] != "1"){
    $sqlss = " and (all_note IS NULL)";
}else{
    $sqlss = " and not (all_note IS NULL)";
}

$sqls = "SELECT " .$sqlv. " FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM si_business_contact WHERE 1 = 1";
$sqls = $sqls . $sqlss ." order by auton desc ) t1 order by auton) t2 order by auton desc";

?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>約會專家系統</li>
            <li class="active">企業專區</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>企業專區　未處理 - 數量：<?php echo $total_size; ?></strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <div class="col-md-12">
                    <p>
                    <div class="btn-group">
                        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">功能 <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <?php 
                                if($all_type == "未處理"){ ?>
                                    <li><a href="?s99=1" target="_self"><i class="icon-resize-horizontal"></i> 切換已處理</a></li>
                                <?php }
                                if($all_type == "已處理"){ ?>
                                    <li><a href="singleweb_fun14.php" target="_self"><i class="icon-resize-horizontal"></i> 切換未處理</a></li>
                                <?php }
                            ?>  
                        </ul>
                    </div>
                    </p>
                </div>

                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                        <tr>
                            <th>公司名稱</th>
                            <th>公司部門</th>
                            <th>職稱</th>
                            <th>Email</th>
                            <th width=60>聯絡人</th>
                            <th>聯絡電話</th>
                            <th>公司網址</th>
                            <th width=120>留言時間</th>
                            <th></th>
                            <th></th>
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
                                        <td class="center"><?php echo $re["company"]; ?></td>
                                        <td class="center"><?php echo $re["department"]; ?></td>
                                        <td class="center"><?php echo $re["job"]; ?></td>
                                        <td class="center"><?php echo $re["email"]; ?></td>
                                        <td class="center"><?php echo $re["name"]; ?></td>
                                        <td class="center"><?php echo $re["phone"]; ?></td>
                                        <td class="center"><?php echo $re["url"]; ?></td>
                                        <td class="center"><?php echo changeDate($re["times"]); ?></td>      
                                        <td>
                                            <font color="#FF0000" size="2">處理情形：
                                            <?php 
                                                if($re["all_type"] != "未處理"){
                                                    echo "(".$re["all_type"].")";
                                                }
                                                echo $re["all_note"];
                                            ?>
                                            </font>
                                        </td>
                                        <td width=80 class="center">
                                            <div class="btn-group">
                                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">操作 <span class="caret"></span></button>
                                                <ul class="dropdown-menu pull-right">
                                                    <li><a href="javascript:Mars_popup('singleweb_fun14_fix.php?an=<?php echo $re["auton"]; ?>','','status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=500,height=320,top=100,left=100');"><i class="icon-pencil"></i> 處理</a></li>
                                                    <?php if($_SESSION["MM_UserAuthorization"] == "admin"){ ?>                                                                
                                                        <li><a href="javascript:Mars_popup2('singleweb_fun14.php?st=del&an=<?php echo $re["auton"]; ?>','','status=yes,menubar=yes,resizable=yes,scrollbars=yes,width=300,height=200,top=150,left=150');"><i class="icon-trash"></i> 刪除</a></li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            }else{
                                echo "<tr><td colspan=10 height=200>目前沒有資料</td></tr>";
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