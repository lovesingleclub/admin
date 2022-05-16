<?php
    /*****************************************/
    //檔案名稱：springweb_fun7.php
    //後台對應位置：春天網站系統/APP 操作說明
    //改版日期：2022.5.11
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");
    require_once("./include/_top.php");
    require_once("./include/_sidebar_spring.php");

    //程式開始 *****
    if ($_SESSION["MM_Username"] == "") {
        call_alert("請重新登入。", "login.php", 0);
    }

    if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["funtourpm"] != "1"){
        call_alert("您沒有查看此頁的權限。", "login.php", 0);
    }

    // 文章上移
    if($_REQUEST["st"] == "up_line"){
        $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
        $upline = $nowline+1;
        $SQL = "update app_help set t_desc=".$nowline." where t_desc=".$upline."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $SQL = "update app_help set t_desc=".$upline." where auton=".SqlFilter($_REQUEST["t_auto"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }

    // 文章下移
    if($_REQUEST["st"] == "down_line"){
        $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
        $upline = $nowline-1;
        $SQL = "update app_help set t_desc=".$nowline." where t_desc=".$upline."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $SQL = "update app_help set t_desc=".$upline." where auton=".SqlFilter($_REQUEST["t_auto"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }

    // 刪除
    if($_REQUEST["st"] == "del"){
        $SQL = "select * from app_help where auton=".SqlFilter($_REQUEST["a"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $SQL = "delete from app_help where auton=".SqlFilter($_REQUEST["a"],"int")."";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }        

        reURL("win_close.php?m=資料刪除中");
    }
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>春天網站系統</li>
            <li class="active">APP 操作說明</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>APP 操作說明</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p><a href="javascript:Mars_popup('springweb_fun7_add.php','','status=yes,menubar=yes,resizable=yes,scrollbars=yes,width=600,height=250,top=150,left=150');" class="btn btn-info">新增說明</a>
                    <a href="springweb_fun7_form.php" class="btn btn-warning">其他問題</a>
                </p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                <?php 
                    $SQL = "SELECT * FROM app_help order by t_desc desc";
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
                                <td><?php echo $re["quest"]; ?></td>
                                <td width='200'>
                                    <a href="javascript:Mars_popup('springweb_fun7_add.php?st=ed&a=<?php echo $re["auton"]; ?>','','status=yes,menubar=yes,resizable=yes,scrollbars=yes,width=600,height=250,top=150,left=150');">修改</a>　				
                                    <a href="javascript:;" onClick="Mars_popup2('springweb_fun7.php?st=del&a=<?php echo $re["auton"]; ?>','','width=300,height=200,top=30,left=30')">刪除</a>						
                                </td>
                            </tr>
                        <?php $ii = $ii+1; }
                    }else{
                        echo "<tr><td colspan=3>目前無資料</td></tr>";
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