<?php

/*****************************************/
//檔案名稱：singleweb_fun2.php
//後台對應位置：約會專家系統/操作說明
//改版日期：2022.6.17
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

// 文章上移
if($_REQUEST["st"] == "up_line"){
    $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
    $upline = $nowline+1;
    $SQL = "update si_help set t_desc=".$nowline." where t_desc='".$upline."'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $SQL = "update si_help set t_desc=".$upline." where auton=".SqlFilter($_REQUEST["t_auto"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
}

// 文章下移
if($_REQUEST["st"] == "down_line"){
    $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
    $upline = $nowline-1;
    $SQL = "update si_help set t_desc=".$nowline." where t_desc=".$upline."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $SQL = "update si_help set t_desc=".$upline." where auton=".SqlFilter($_REQUEST["t_auto"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
}

// 刪除文章
if($_REQUEST["st"] == "del"){   
    $SQL = "delete from si_help where auton=".SqlFilter($_REQUEST["a"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();

    reURL("win_close.php?m=刪除中.....");
}

?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>約會專家系統</li>
            <li class="active">操作說明</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>操作說明</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p><a href="singleweb_fun2_add.php" class="btn btn-info">新增說明</a>
                </p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <?php 
                        $SQL = "SELECT * FROM si_help order by t_desc desc";
                        $rs = $SPConn->prepare($SQL);
                        $rs->execute();
                        $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                        if($result){
                            $ii = 0;
                            foreach($result as $re){
                                if($ii == 0){
                                    $uahref = "#nu\" onclick=\"alert('無法向上');\"";
                                }else{
                                    $uahref = "?st=up_line&ad=".$re["t_desc"]."&t_auto=".$re["auton"];
                                }                                   
                                if($ii == count($result)-1){
                                    $dahref = "#nu\" onclick=\"alert('無法向下');\"";
                                }else{
                                    $dahref = "?st=down_line&ad=".$re["t_desc"]."&t_auto=".$re["auton"];
                                } ?>
                                <tr>
                                    <td width=80><a href="<?php echo $uahref; ?>"><span class="fa fa-arrow-up margin-left-10 margin-right-10"></span></a><a href="<?php echo $dahref; ?>"><span class="fa fa-arrow-down"></span></a></td>			    
                                    <td width=200><?php echo $re["types"]; ?></td>
                                    <td><?php echo $re["quest"]; ?></td>
                                    <td width=200>
                                        <a href="singleweb_fun2_add.php?st=ed&a=<?php echo $re["auton"]; ?>">修改</a>　				
                                        <a href="javascript:;" onClick="Mars_popup2('?st=del&a=<?php echo $re["auton"]; ?>','','width=300,height=200,top=30,left=30')">刪除</a></td>
                                    </td>
                                </tr>
                            <?php $ii = $ii+1; }
                        }else{
                            echo "<tr><td colspan=4>暫無文章</td></tr>";
                        }
                    ?>
                </table>
            </div>
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