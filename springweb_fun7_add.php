<?php 
    /*****************************************/
    //檔案名稱：springweb_fun7.php
    //後台對應位置：春天網站系統/APP 操作說明>新增說明
    //改版日期：2022.5.12
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");

    //程式開始 *****
    if ($_SESSION["MM_Username"] == "") {
        call_alert("請重新登入。", "login.php", 0);
    }

    if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["funtourpm"] != "1"){
        call_alert("您沒有查看此頁的權限。", "login.php", 0);
    }

    if($_REQUEST["st"] == "save"){
        if($_REQUEST["t"] == "ed"){
            //更新
            $SQL = "select * from app_help where auton=".SqlFilter($_REQUEST["an"],"int");
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
            $result = $rs->fetch(PDO::FETCH_ASSOC);
            if($result){                
                $SQL = "UPDATE app_help SET quest='".SqlFilter($_REQUEST["quest"],"tab")."', ans='".str_replace(PHP_EOL,"<br>",SqlFilter($_REQUEST["ans"],"tab"))."', times='".date("Y/m/d H:i:s")."' WHERE auton=".SqlFilter($_REQUEST["an"],"int")."";
                $rs = $SPConn->prepare($SQL);
                $rs->execute();
            }
        }else{
            //新增
            $SQL = "select top 1 t_desc from app_help order by t_desc desc";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
            $result = $rs->fetch(PDO::FETCH_ASSOC);
            if($result){
                $ltd = intval($result["t_desc"]) + 1;
            }else{
                $ltd = 1;
            }
            $SQL = "INSERT INTO app_help (quest, ans, t_desc, times) VALUES ('".SqlFilter($_REQUEST["quest"],"tab")."','".str_replace(PHP_EOL,"<br>",SqlFilter($_REQUEST["ans"],"tab"))."','".$ltd."' ,'".date("Y/m/d H:i:s")."')";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }
        reURL("win_close.php?m=儲存中....");
    }

    if($_REQUEST["st"] == "ed"){
        $SQL = "select * from app_help where auton=".SqlFilter($_REQUEST["a"],"int");
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $quest = $result["quest"];
            $ans = str_replace("<br>",PHP_EOL,$result["ans"]);
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
    <div>問題：<input type="text" name="quest" id="quest" style="width:80%;" value="<?php echo $quest; ?>"></div>
    <br>
    <div>答案：<textarea name="ans" id="ans" style="width:80%;height:100px;"><?php echo $ans; ?></textarea></div>
    <div style="text-align:center;margin: 0 auto;"><input type="submit" value="送出"></div>
</form>
<script type="text/javascript">
    function chk_form() {
        if (!$("#quest").val()) {
            alert("plz input question");
            $("#quest").focus();
            return false;
        }
        if (!$("#ans").val()) {
            alert("plz input answer");
            $("#ans").focus();
            return false;
        }

        return true;
    }
</script>
</body>
</html>