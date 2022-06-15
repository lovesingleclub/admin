<?php
    /*****************************************/
    //檔案名稱：singleweb_fun1.php
    //後台對應位置：約會專家系統/頁面TDK
    //改版日期：2022.5.17
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
        $SQL = "select * from pagehead where auton=".SqlFilter($_REQUEST["a"],"int")." and types='singleparty'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $SQL = "DELETE FROM pagehead where auton=".SqlFilter($_REQUEST["a"],"int")." and types='singleparty'";
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
            <li>約會專家系統</li>
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
                <p><a href="javascript:Mars_popup('singleweb_fun1_add.php','','status=yes,menubar=yes,resizable=yes,scrollbars=yes,width=600,height=250,top=150,left=150');" class="btn btn-info">新增TDK</a>
                </p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <?php 
                        $SQL = "select * from pagehead where types='singleparty' order by page desc";
                        $rs = $SPConn->prepare($SQL);
                        $rs->execute();
                        $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                        if($result){
                            foreach($result as $re){
                                echo "<tr>";
                                echo "<td>".$re["page"]."</td><td>".$re["title"]."</td><td>".$re["description"]."</td><td>".$re["keywords"]."</td><td width='200'><a href=\"javascript:Mars_popup('singleweb_fun1_add.php?st=ed&a=".$re["auton"]."','','status=yes,menubar=yes,resizable=yes,scrollbars=yes,width=600,height=250,top=150,left=150');\">修改</a>　<a href=\"javascript:;\" onClick=\"Mars_popup2('?st=del&a=".$re["auton"]."','','width=300,height=200,top=30,left=30')\">刪除</a></td>";
                                echo "</tr>";
                            }
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