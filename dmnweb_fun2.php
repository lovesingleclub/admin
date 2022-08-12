<?php
/*****************************************/
//檔案名稱：dmnweb_fun2.php
//後台對應位置：DateMeNow網站系統/頁面TDK
//改版日期：2022.8.12
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");
require_once("./include/_top.php");
require_once("./include/_sidebar_dmn.php");

// 程式開始
if($_SESSION["MM_Username"] == ""){
    call_alert("請重新登入。","login.php",0);
}

if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["dmnweb"] != "1"){
    call_alert("您沒有查看此頁的權限。","login.php",0);
}

//刪除
if($_REQUEST["st"] == "del"){
    $SQL = "select * from pagehead where auton=".SqlFilter($_REQUEST["a"],"int")."";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $SQL = "DELETE FROM pagehead where auton=".SqlFilter($_REQUEST["a"],"int")."";
        $rs = $DMNConn->prepare($SQL);
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
            <li>DateMeNow網站系統</li>
            <li class="active">頁面TDK</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>頁面TDK</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p><a class="btn btn-info" href="javascript:Mars_popup('dmnweb_fun2_add.php','','status=yes,menubar=yes,resizable=yes,scrollbars=yes,width=600,height=250,top=150,left=150');">新增TDK</a></p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <?php 
                        $SQL = "select * from pagehead order by page desc";
                        $rs = $DMNConn->prepare($SQL);
                        $rs->execute();
                        $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                        if($result){
                            foreach($result as $re){ ?>
                                <tr>
                                    <td><?php echo $re["page"]; ?></td>
                                    <td><?php echo $re["title"]; ?></td>
                                    <td><?php echo $re["description"]; ?></td>
                                    <td><?php echo $re["keywords"]; ?></td>
                                    <td width="200"><a href="javascript:Mars_popup('dmnweb_fun2_add.php?st=ed&a=<?php echo $re["auton"]; ?>','','status=yes,menubar=yes,resizable=yes,scrollbars=yes,width=600,height=250,top=150,left=150');">修改</a>　<a href="javascript:;" onClick="Mars_popup2('?st=del&a=<?php echo $re["auton"]; ?>','','width=300,height=200,top=30,left=30')">刪除</a></td>
                                </tr>
                            <? }
                        }else{
                            echo "暫無頁面";
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
require_once("./include/_bottom.php")
?>