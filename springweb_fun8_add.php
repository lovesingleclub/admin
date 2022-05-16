
<?php 
    require_once("_inc.php");
    require_once("./include/_function.php");

    //程式開始 *****
    if ($_SESSION["MM_Username"] == "") {
        call_alert("請重新登入。", "login.php", 0);
    }

    if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["funtourpm"] != "1"){
        call_alert("您沒有查看此頁的權限。", "login.php", 0);
    }

    //修改OR新增
    if($_REQUEST["st"] == "save"){
        if($_REQUEST["t"] == "ed"){
            //修改
            $SQL = "select * from pagehead where auton=".SqlFilter($_REQUEST["an"],"int")." and types='springclub'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
            $result = $rs->fetch(PDO::FETCH_ASSOC);
            if($result){
                $SQL = "UPDATE pagehead SET page='".strtolower(SqlFilter($_REQUEST["page"],"tab"))."',title='".SqlFilter($_REQUEST["title"],"tab")."', description='".SqlFilter($_REQUEST["description"],"tab")."', keywords='".SqlFilter($_REQUEST["keywords"],"tab")."', types='springclub' where auton=".SqlFilter($_REQUEST["an"],"int")." and types='springclub'";
                $rs = $SPConn->prepare($SQL);
                $rs->execute();
            }
        }else{
            //新增
            $SQL = "INSERT INTO pagehead (page,title,description,keywords,types) VALUES ('".strtolower(SqlFilter($_REQUEST["page"],"tab"))."','".SqlFilter($_REQUEST["title"],"tab")."','".SqlFilter($_REQUEST["description"],"tab")."','".SqlFilter($_REQUEST["keywords"],"tab")."','springclub')";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }
        reURL("win_close.php?m=儲存中....");
    }

    if($_REQUEST["st"] == "ed"){
        $SQL = "select * from pagehead where auton=".SqlFilter($_REQUEST["a"],"int")." and types='springclub'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $page = $result["page"];
            $title = $result["title"];
            $description = $result["description"];
            $keyword = $result["keyword"];
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>春天網站系統</title>
</head>
<body>
<script src="js/jquery-1.8.3.js"></script>
<form action="?st=save" method="post" target="_self" onsubmit="return chk_form()">
    <input type="hidden" name="t" value="<?php echo SqlFilter($_REQUEST["st"],"tab"); ?>">
    <input type="hidden" name="an" value="<?php echo SqlFilter($_REQUEST["a"],"int"); ?>">
    <div>頁面檔名：<input type="text" name="page" id="page" style="width:60%;" value="<?php echo $page; ?>">(如 index.php)</div>
    <br>
    <div style="line-height:30px;">標題(title)：<input type="text" name="title" id="title" style="width:60%;" value="<?php echo $title; ?>"></div>
    <div style="line-height:30px;" style="line-height:30px;">描述(description)：<input type="text" name="description" id="description" style="width:60%;" value="<?php echo $description; ?>"></div>
    <div style="line-height:30px;">關鍵字(keywords)：<input type="text" name="keywords" id="keywords" style="width:60%;" value="<?php echo $keyword; ?>"></div>
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
</body>
</html>

