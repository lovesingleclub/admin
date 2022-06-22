<?php

/*****************************************/
//檔案名稱：ad_autoedm2.php
//後台對應位置：春天網站系統/當月活動信更換
//改版日期：2022.5.16
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

if ($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["funtourpm"] != "1") {
    call_alert("您沒有查看此頁的權限。", "login.php", 0);
}

// 刪除資料夾內所有檔案
function deldir($path){
    //如果是目錄則繼續
    if (is_dir($path)) {
        //掃描一個資料夾內的所有資料夾和檔案並返回陣列
        $p = scandir($path);
        foreach ($p as $val) {
            //排除目錄中的.和..
            if ($val != "." && $val != "..") {
                //如果是目錄則遞迴子目錄，繼續操作
                if (is_dir($path . $val)) {
                    //子目錄中操作刪除資料夾和檔案
                    deldir($path . $val . '/');
                    //目錄清空後刪除空資料夾
                    @rmdir($path . $val . '/');
                } else {
                    //如果是檔案直接刪除
                    unlink($path . $val);
                }
            }
        }
    }
}

$getpath = "Upload/edm/action_edm/"; //檔案夾路徑
//上傳及刪除檔案(待測)
if ($_REQUEST["st"] == "add") {
    //刪除檔案夾內所有資料(待測)
    deldir($getpath);

    //上傳檔案(待測)
    if ($_FILES['photofile']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES["photofile"]["name"], PATHINFO_EXTENSION); //附檔名      
        $fileName = date("Y") . date("m") . date("d") . "_" . date("H") . date("i") . date("s") . "_action_edm". "." . $ext; //檔名  
        move_uploaded_file($_FILES["photofile"]["tmp_name"], ($getpath . $fileName)); //儲存檔案
    } else {
        call_alert("上傳出錯", 0, 0);
    }
    reURL("ad_autoedm2.php");
}

?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>春天網站系統</li>
            <li class="active">當月活動信更換</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>當月活動信更換</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <?php
                    if (is_dir($getpath)) {
                        $p = scandir($getpath);
                        foreach ($p as $val) {
                            //排除目錄中的.和..
                            if ($val != "." && $val != "..") {
                                echo "<tr><td><a href='Upload/edm/action_edm/".$val."' target='_blank'>".$val."</a></td></tr>";
                            }
                        }                        
                    } else {
                        echo "讀取資料夾時發生錯誤。";
                    }
                    ?>
                </table>
                <p>
                <form id="picform" method="post" action="?st=add" onsubmit="return chk_form()" enctype="multipart/form-data">

                    <!--中央表格——灰表格結束-->
                    <!--中央表格——按鈕開始-->
                    <input type="file" name="photofile" id="photofile" placeholder="請選擇檔案">

                    <p><input type="submit" value="上傳"></p>

                </form>
                </p>
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

<script type="text/javascript">
    $(function() {
        $("#photofile").on("change", function() {
            var type = /(.htm|.html)$/i;
            if (!type.test($(this).val())) {
                alert("只能上傳 .htm, .html 為副檔名的檔案。");
                $(this).val("");
                return false;
            }
            return true;
        });
    });

    function chk_form() {
        if (!$("#photofile").val()) {
            alert("請選擇要上傳的檔案。");
            return false;
        }
        return true;
    }
</script>