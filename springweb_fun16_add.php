<?php
    /*****************************************/
    //檔案名稱：springweb_fun8.php
    //後台對應位置：春天網站系統/會員通知訊息>新增/修改通知訊息
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

    if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["funtourpm"] != "1"){
        call_alert("您沒有查看此頁的權限。", "login.php", 0);
    }

    $notes = str_ireplace(PHP_EOL,"",$_REQUEST["notes"]);
    $notes = str_ireplace("<!DOCTYPE html><html><head></head><body>","",$_REQUEST["notes"]);
    $notes = str_ireplace("</body></html>","",$_REQUEST["notes"]);

    if($_REQUEST["acts"] == "ad"){
        if($_REQUEST["title_old"] == "1" && $_REQUEST["ac_auto"] != ""){
            $SQL = "select ac_note from action_data where ac_auto=".SqlFilter($_REQUEST["ac_auto"],"int")."";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
            $result = $rs->fetch(PDO::FETCH_ASSOC);
            if($result){
                $title = $result["title"];
            }else{
                $title = SqlFilter($_REQUEST["title"],"tab");
            }
        }else{
            $title = SqlFilter($_REQUEST["title"],"tab");
        }
        $SQL = "INSERT INTO member_announce (title,notes,ac_auto,times,types) VALUES ('".$title."','".$notes."','".SqlFilter($_REQUEST["ac_auto"],"int")."',getdate(),'".SqlFilter($_REQUEST["types"],"tab")."')";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();

        reURL("springweb_fun16.php");
    }

    if($_REQUEST["acts"] == "up"){
        if($_REQUEST["title_old"] == "1" && $_REQUEST["ac_auto"] != ""){
            $SQL = "select ac_note from action_data where ac_auto=".SqlFilter($_REQUEST["ac_auto"],"int")."";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
            $result = $rs->fetch(PDO::FETCH_ASSOC);
            if($result){
                $title = $result["title"];
            }else{
                $title = SqlFilter($_REQUEST["title"],"tab");
            }
        }else{
            $title = SqlFilter($_REQUEST["title"],"tab");
        }
        $SQL = "update member_announce set title = '".$title."',notes='".$notes."',ac_auto='".SqlFilter($_REQUEST["ac_auto"],"int")."',types='".SqlFilter($_REQUEST["types"],"tab")."' where auton = ".SqlFilter($_REQUEST["pid"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();

        reURL("springweb_fun16.php");
    }

    $nows = date("Y/m/d H:i:s");
    if($_REQUEST["act"] == "up" && $_REQUEST["id"] != 0){
        $SQL = "Select * from member_announce where auton = ".SqlFilter($_REQUEST["id"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $types = $result["types"];
            $title = $result["title"];
            $ac_auto = $result["ac_auto"];
            $notes = $result["notes"];
            if($notes != ""){
                $notes = str_replace("<br>",PHP_EOL,$notes);
            }
            $times = $result["times"];
        }
    }

?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>春天網站系統</li>
            <li><a href="springweb_fun16.php">會員通知訊息</a></li>
            <li class="active">新增/修改通知訊息</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>新增/修改通知訊息</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form name="mform" method="post" action="springweb_fun16_add.php" onSubmit="return chkform()">
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <tbody>
                            <tr>
                                <td width="150" align="left" valign="middle">分類</td>
                                <td><select name="types" id="types">
                                        <option value="重要通知"<?php if($types == "重要通知") echo " selected"; ?>>重要通知</option>
                                        <option value="活動通知"<?php if($types == "活動通知") echo " selected"; ?>>活動通知</option>
                                    </select></td>
                            </tr>
                            <tr id="ac_auto_tr" style="display:none">
                                <td align="left" valign="middle">活動編號</td>
                                <td><input name="ac_auto" id="ac_auto" value="<?php echo $ac_auto; ?>"></td>
                            </tr>
                            <tr>
                                <td width="150" align="left" valign="middle">標題</td>
                                <td>
                                    <span id="title_use" style="display:none;padding-right:30px;"><input data-no-uniform="true" type="checkbox" name="title_old" id="title_old" value="1" checked> 使用活動標題</span>
                                    <input name="title" id="title" value="<?php echo $title; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">建立時間</td>
                                <td><?php echo changeTime($nows); ?></td>
                            </tr>
                            <tr id="notes_tr">
                                <td align="left" valign="middle">內文</td>
                                <td><textarea name="notes" id="notes" class="editor" style="width:80%;height:350px;"><?php echo $notes ?></textarea></td>
                            </tr>
                            <tr>
                                <td colspan=2 align="center"><input name="acts" id="acts" type="hidden" value="<?php echo SqlFilter($_REQUEST["act"],"tab"); ?>"><input name="pid" type="hidden" id="pid" value="<?php echo SqlFilter($_REQUEST["id"],"int"); ?>">
                                    <input type="submit" value="確認送出" class="btn btn-info" style="width:50%">
                                </td>
                            </tr>

                        </tbody>
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
    $mtu = "springweb_fun16.";

    function chkform() {
        if ($("#ac_auto").is(":visible") && !$("#ac_auto").val()) {
            alert("請輸入活動編號。");
            $("#ac_auto").focus();
            return false;
        }
        if ($("#ac_auto").is(":visible") && !$.isNumeric($("#ac_auto").val())) {
            alert("活動編號只能是數字。");
            $("#ac_auto").val("");
            $("#ac_auto").focus();
            return false;
        }
        if ($("#title").is(":visible") && !$("#title").val()) {
            alert("請輸入標題。");
            $("#title").focus();
            return false;
        }
        return true;
    }
    $(function() {

        $("#types").on("change", function() {
            if ($(this).val() == "活動通知") {
                $("#title_old").prop("checked", true);
                $("#title").hide();
                $("#title_use").show();
                $("#ac_auto_tr").show();
                $("#notes_tr").hide();
            } else {
                $("#title").show();
                $("#title_use").hide();
                $("#title").attr("placeholder", "");
                $("#title").val("");
                $("#ac_auto_tr").hide();
                $("#notes_tr").show();
            }
        });
        $("#title_old").on("click", function() {
            if ($(this).prop("checked")) {
                $("#title").val("");
                $("#title").hide();
                $("#title_use").show();
            } else {
                $("#title").show();
                $("#title").attr("placeholder", "請輸入自訂標題");
            }
        });
        <?php 
            if($types == "活動通知"){ ?>
                $("#ac_auto_tr").show();
                $("#notes_tr").hide();
                if($("#title").val()) {
                    $("#title_old").prop("checked", false);
                    $("#title_use").show();
                } else {
                    $("#title_old").prop("checked", true);
                    $("#title_use").show();
                    $("#title").hide();
                }
            <?php }
        ?>
    });
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
        toolbar_items_size: 'small',
        language: 'zh_TW',
        height: 600
    });
</script>