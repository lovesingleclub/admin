<?php

/*****************************************/
//檔案名稱：springweb_fun7_form.php
//後台對應位置：春天網站系統/APP 操作說明>其他問題
//改版日期：2022.5.13
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

// 刪除
if($_REQUEST["st"] == "del"){
    $SQL = "select * from app_help_form where auton=".SqlFilter($_REQUEST["a"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $SQL = "DELETE from app_help_form where auton=".SqlFilter($_REQUEST["a"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }
    reURL("win_close.php?m=刪除中...");
}

?>

<!-- MIDDLE -->
<section id="middle">
    <div id="content" class="span10">
        <!-- content starts -->
        <div>
            <ul class="breadcrumb">
                <li>
                    �K�Ѻ����޲z�t�� <span class="divider">/</span>
                </li>
                <li>
                    <a href="springweb_fun7.asp">��L���D</a>
                </li>
            </ul>
        </div>

        <div class="row-fluid">
            <div class="box span12">
                <div class="box-header well" data-original-title>
                    <h2><i class="icon-exclamation-sign"></i> ��L���D</h2>
                </div>
                <div class="box-content">
                    <p><a href="javascript:Mars_popup('springweb_fun7_add.asp','','status=yes,menubar=yes,resizable=yes,scrollbars=yes,width=600,height=250,top=150,left=150');" class="btn btn-info">�s�W����</a>
                        <a href="springweb_fun7.asp"><i class="icon-question-sign"></i> APP �ާ@����</a>
                    </p>
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <?php 
                            $SQL = "select count(auton) as total_size from app_help_form WHERE 1 = 1";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetch(PDO::FETCH_ASSOC);
                            if($result){
                                $total_size = $result["total_size"];
                            }else{
                                $total_size = 0;
                            }
                            $tPage = 1; //目前頁數
                            $tPageSize = 0; //每頁幾筆
                            if ( $_REQUEST["tPage"] > 1 ){ $tPage = $_REQUEST["tPage"];}
                            $tPageTotal = ceil(($total_size/$tPageSize)); //總頁數
                            if ( $tPageSize*$tPage < $total_size ){
                                $page2 = 0;
                            }else{
                                $page2 = (50-(($tPageSize*$tPage)-$total_size));
                            }
                            $SQL = "select * FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM app_help_form order by times desc ) t1 order by times) t2 order by times desc";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                foreach($result as $re){
                                    echo "<tr>";
                                    echo "<td width='200'>".$re["name"]."</td><td>".str_replace("&lt;br&gt;","<br>",$re["notes"])."</td><td width='200'><a href='javascript:;' onClick=\"Mars_popup2('?st=del&a=".$re["auton"]."','','width=300,height=200,top=30,left=30')\">�R��</a></td>";
                                    echo "</tr>";
                                }                                
                            }
                        ?>
                    </table>
                </div>
            </div>
            <!-- 頁碼 -->
            <?php require_once("./include/_page.php"); ?>

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