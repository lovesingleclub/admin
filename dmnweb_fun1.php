<?php
    /*****************************************/
    //檔案名稱：dmnweb_fun1.php
    //後台對應位置：DateMeNow網站系統/自訂ABOUT
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

    if($_REQUEST["st"] == "del"){
        $getpath = "../datemenow";
        $fullpath = $getpath."/".$_REQUEST["fname"];
        DelFile($fullpath);
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
            <li class="active">自訂ABOUT</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>自訂ABOUT</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p><a href="dmnweb_fun1_add.php" class="btn btn-info">新增一頁</a></p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <?php 
                        if (is_dir($getpath)) {
                            if ($dh = opendir($getpath)) {                                
                                while (($file = readdir($dh)) !== false) {
                                    if(substr($file,0,6) == "about_" && $file != "about_include.php"){
                                        echo "<tr><td><a href='http://www.datemenow.com.tw/".$file."' target='_blank'>".$file."</a></td><td width='200'><a href='dmnweb_fun1_add.php?t=ed&f=".$file."'>修改</a>　<a href='javascript:;' onClick=\"Mars_popup2('?st=del&fname=".$file."','','width=300,height=200,top=30,left=30')\">刪除</a></td></tr>";
                                    }
                                } 
                                closedir($dh);
                            }
                        }else{
                            echo "讀取資料夾時發生錯誤。";
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