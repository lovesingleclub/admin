<?php
    /*****************************************/
    //檔案名稱：ad_cheers_check.php
    //後台對應位置：管理系統/Cheers 檢校
    //改版日期：2022.3.16
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
    setlocale(LC_ALL, "zh_CN");
    if($_REQUEST["st"] == "upload"){        
        if ($_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK){            
            $ext = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION); //附檔名
            if($ext != "csv"){
                echo "格式錯誤";
                exit();
            }    
            $urlpath = "temp_excel/";
            $fileName = date("Y").date("n").date("j").date("H").date("i").date("s")."_temp_".rand(1,999).".".$ext; //檔名
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],($urlpath.$fileName)); //儲存檔案
            reURL("ad_cheers_check_excel.php?n=".$fileName);
        }else{
            call_alert("匯入失敗。","ad_cheers_check.php",0);
        }
    }

?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li class="active">Cheers 檢校</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>Cheers 檢校</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">

                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tr>
                        <td>
                            <form action="?st=upload" method="post" enctype="multipart/form-data">
                                <input type="file" name="fileToUpload" id="fileToUpload">
                                <input type="submit" value="匯入 CSV" name="submit">
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!--/span-->

    </div>
    <!--/row-->


    <hr>
    </div>
    <!--/.fluid-container-->
</section>
<!-- /MIDDLE -->

<?php
require_once("./include/_bottom.php");
?>