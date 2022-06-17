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

//修改或新增
if($_REQUEST["st"] == "save"){
    $ans = $_REQUEST["ans"];
    if($ans != ""){
        $ans = str_ireplace("<!DOCTYPE html><html><head></head><body>", "",$ans);
        $ans = str_ireplace("</body></html>", "",$ans);
    }    

    if($_REQUEST["t"] == "ed"){
        //修改
        $SQL = "select * from si_help where	auton=".SqlFilter($_REQUEST["an"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $SQL = "UPDATE si_help SET quest='".SqlFilter($_REQUEST["quest"],"tab")."', ans='".$ans."', types='".SqlFilter($_REQUEST["types"],"tab")."', times='".date("Y/m/d H:i:s")."' where auton=".SqlFilter($_REQUEST["an"],"int")."";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }
    }else{
        //新增
        $SQL = "select top 1 t_desc from si_help order by t_desc desc";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $ltd = $result["t_desc"] + 1;
        }else{
            $ltd = 1;
        }

        $SQL = "INSERT INTO si_help (quest,ans,types,t_desc,times) VALUES ('".SqlFilter($_REQUEST["quest"],"tab")."','".$ans."','".SqlFilter($_REQUEST["types"],"tab")."','".$ltd."','".date("Y/m/d H:i:s")."')";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }

    reURL("singleweb_fun2.php");
}

//讀取資料
if($_REQUEST["st"] == "ed"){
    $SQL = "select * from si_help where	auton=".SqlFilter($_REQUEST["a"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $quest = $result["quest"];
        $ans = str_ireplace("<br>",PHP_EOL,$result["ans"]);
        $types = $result["types"];
    }
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

                <table class="table table-striped table-bordered bootstrap-datatable">

                    <form action="?st=save" method="post" target="_self" onsubmit="return chk_form()" class="form-inline">
                        <input type="hidden" name="t" value="<?php echo SqlFilter($_REQUEST["st"],"tab"); ?>">
                        <input type="hidden" name="an" value="<?php echo SqlFilter($_REQUEST["a"],"int"); ?>">
                        <tr>
                            <td>問題：<input type="text" name="quest" id="quest" style="width:80%;" value="<?php echo $quest; ?>" class="form-control" required></td>
                        </tr>
                        <tr>
                            <td>分類：<input type="text" name="types" id="types" style="width:80%;" value="<?php echo $types; ?>" class="form-control" required></td>
                        </tr>
                        <tr>
                            <td>答案：<textarea class="editor" name="ans" id="ans" style="width:80%;height:100px;"><?php echo $ans; ?></textarea></td>
                        </tr>
                        <tr>
                            <td align="center"><input type="submit" value="送出" class="btn btn-info" style="width:50%"></td>
                        </tr>
                    </form>
                    </td>
                    </tr>
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

<script src="js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    $mtu = "singleweb_fun2.";
    $(function() {
        tinymce.init({
            selector: ".editor",
            plugins: [
                "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons template textcolor paste fullpage textcolor"
            ],

            toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | fontsizeselect forecolor backcolor | code preview",
            toolbar2: "cut copy paste | searchreplace | bullist numlist | undo redo | link unlink image media | inserttime | table",

            menubar: false,
            language: 'zh_TW',
            height: 200,
            force_br_newlines: true,
            force_p_newlines: false,
            forced_root_block: false
        });

    });

    function chk_form() {
        if (!$("#quest").val()) {
            alert("plz input question");
            $("#quest").focus();
            return false;
        }


        return true;
    }
</script>