<?php
    /*****************************************/
    //檔案名稱：springweb_fun11.php
    //後台對應位置：春天網站系統/徵人頁面
    //改版日期：2022.4.18
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
;
    if($_REQUEST["st"] == "send"){
        $SQL = "select * FROM webdata where types='opportunity'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if(!$result){
            $SQL = "INSERT INTO webdata (n1,types) VALUES ('".SqlFilter($_REQUEST["n1"],"tab")."','opportunity')";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }else{
            $SQL = "UPDATE webdata SET n1 = '".SqlFilter($_REQUEST["n1"],"tab")."' where types='opportunity'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }
        reURL("springweb_fun11.php");
    }

    $SQL = "select * FROM webdata where types='opportunity'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $n1 = $result["n1"];
    }
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>春天網站系統</li>
            <li class="active">徵人頁面</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>徵人頁面</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">

                <form name="form1" method="post" action="?st=send" style="border:0;margin:0;padding:0">
                    <table class="table table-bordered bootstrap-datatable">
                        <tr>
                            <td><textarea style="width:100%;" class="editor" id="n1" name="n1"><?php echo $n1; ?></textarea></td>
                        </tr>
                        <tr>
                            <td align="center"><input style="width:20%;height:30px;" type="submit" value="更新"></td>
                        </tr>
                    </table>
                </form>
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
<script language="JavaScript">
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
            height: 500
        });
    });
</script>