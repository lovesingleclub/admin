<?php

/*****************************************/
//檔案名稱：springweb_fun4.php
//後台對應位置：約會專家系統/戀愛講堂-文章分類
//改版日期：2022.5.20
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

//新增分類
if($_REQUEST["st"] == "addtag"){
    $SQL = "INSERT INTO si_webdata (d1, types, t1) VALUES ('".SqlFilter($_REQUEST["tagv"],"tab")."','lovesalon_tag','".date("Y/m/d H:i:s")."')";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    reURL("singleweb_fun4.php");
}

//刪除分類
if($_REQUEST["st"] == "deltag"){
    $SQL = "delete from si_webdata where auton=".SqlFilter($_REQUEST["an"],"int")." and types='lovesalon_tag'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    reURL("singleweb_fun4.php");
}

$nowty = $_REQUEST["ty"];
if($nowty == ""){
    $nowty = "1";
}

switch($nowty){
    case "2":
        $showb = "廣告一";
        $btn2 = "disabled";
        break;
    case "2":
        $showb = "廣告二";
        $btn2 = "disabled";
        break;
    default:
        $showb = "戀愛講堂 Banner";
        $btn1 = "disabled";
}
$showb = "";

?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>約會專家系統</li>
            <li class="active">戀愛講堂-文章分類</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>戀愛講堂-文章分類</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <?php 
                            $ii = 0;
                            $SQL = "SELECT * FROM si_webdata where types='lovesalon_tag' order by t1 desc";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                foreach($result as $re){ ?>
                                    <tr>
                                        <td><?php echo $re["d1"]; ?></td>
                                        <td><?php echo changeDate($re["t1"]); ?></td>
                                        <td>				
                                            <a title="刪除" href="singleweb_fun4.php?st=deltag&an=<?php echo $re["auton"]; ?>">刪除</a>						
                                        </td>
                                    </tr>
                                <?php $ii = $ii + 1 ;}
                            }
                        ?>
                        <tr>
                            <td colspan=3>
                                <form name="forms" id="f1" method="post" action="?st=addtag" onsubmit="return check_form();">新增分類：<input type="text" name="tagv" id="tagv"> <input class="btn btn-default" style="margin-top:-8px" type="submit" value="新增"></form>
                            </td>
                        </tr>
                    </tbody>
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