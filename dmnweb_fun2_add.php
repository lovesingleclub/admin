<?php
/*****************************************/
//檔案名稱：dmnweb_fun2_add.php
//後台對應位置：DateMeNow網站系統/新增修改頁面TDK
//改版日期：2022.8.15
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");

// 程式開始
if($_SESSION["MM_Username"] == ""){
    call_alert("請重新登入。","login.php",0);
}

if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["dmnweb"] != "1"){
    call_alert("您沒有查看此頁的權限。","login.php",0);
}

if($_REQUEST["st"] == "save"){
    $page = strtolower(SqlFilter($_REQUEST["page"],"tab"));
    $title = SqlFilter($_REQUEST["title"],"tab");
    $description = SqlFilter($_REQUEST["description"],"tab");
    $keywords = SqlFilter($_REQUEST["keywords"],"tab");
    if($_REQUEST["t"] == "ed"){
        $SQL = "select * from pagehead where auton=".SqlFilter($_REQUEST["an"],"int")."";
        $rs = $DMNConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $SQL = "UPDATE pagehead SET page='".$page."', title='".$title."', description='".$description."', keywords='".$keywords."' where auton=".SqlFilter($_REQUEST["an"],"int")."";
            $rs = $DMNConn->prepare($SQL);
            $rs->execute();
        }
    }else{
        $SQL = "INSERT INTO pagehead (page,title,description,keywords) VALUES ('".$page."','".$title."','".$description."','".$keywords."')";
        $rs = $DMNConn->prepare($SQL);
        $rs->execute();
    }
    reURL("win_close.php?m=儲存中....");
}

if($_REQUEST["st"] == "ed"){
    $SQL = "select * from pagehead where auton=".SqlFilter($_REQUEST["a"],"int")."";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $page = $result["page"];
        $title = $result["title"];
        $description = $result["description"];
        $keywords = $result["keywords"];
    }
}

?>
<meta charset="utf-8" />

<script src="js/jquery-1.8.3.js"></script>
<form action="?st=save" method="post" target="_self" onsubmit="return chk_form()">
    <input type="hidden" name="t" value="<?php echo SqlFilter($_REQUEST["st"],"tab"); ?>">
    <input type="hidden" name="an" value="<?php echo SqlFilter($_REQUEST["a"],"int"); ?>">
    <div>頁面檔名：<input type="text" name="page" id="page" style="width:60%;" value="<?php echo $page; ?>">(如 index.php)</div>
    <br>
    <div style="line-height:30px;">標題(title)：<input type="text" name="title" id="title" style="width:60%;" value="<?php echo $title; ?>"></div>
    <div style="line-height:30px;" style="line-height:30px;">描述(description)：<input type="text" name="description" id="description" style="width:60%;" value="<?php echo $description; ?>"></div>
    <div style="line-height:30px;">關鍵字(keywords)：<input type="text" name="keywords" id="keywords" style="width:60%;" value="<?php echo $keywords; ?>"></div>
    <div style="text-align:center;margin: 0 auto;"><input type="submit" value="送出"></div>
</form>
<script type="text/javascript">
    function chk_form() {
        if (!$("#page").val()) {
            alert("plz input page");
            $("#page").focus();
            return false;
        }
        if (!$("#title").val()) {
            alert("plz input title");
            $("#title").focus();
            return false;
        }
        if (!$("#description").val()) {
            alert("plz input description");
            $("#description").focus();
            return false;
        }
        if (!$("#keywords").val()) {
            alert("plz input keywords");
            $("#keywords").focus();
            return false;
        }
        return true;
    }
</script>