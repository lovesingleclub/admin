<?php 
/*****************************************/
//檔案名稱：singleweb_fun15_add2.php
//後台對應位置：約會專家系統/禮物管理>新增罐頭訊息
//改版日期：2022.5.27
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");

//編輯或新增
if($_REQUEST["st"] == "edit"){
    $an = SqlFilter($_REQUEST["an"],"int");
    $SQL = "select top 1 * from si_gift_list where auton='".$an."'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $SQL = "UPDATE si_gift_list SET name='".SqlFilter($_REQUEST["names"],"tab")."' where auton='".$an."'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }else{
        $SQL = "INSERT INTO si_gift_list (name,types) VALUES ('".SqlFilter($_REQUEST["names"],"tab")."','text')";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }
    reURL("win_close.php?m=儲存中..");
}

//讀取
if($_REQUEST["an"] != ""){
    $SQL = "SELECT * FROM si_gift_list where auton='".SqlFilter($_REQUEST["an"],"int")."'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $names = $result["name"];
        $url = $result["url"];
        $an = $result["auton"];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>約會專家系統</title>
</head>
<body>
<script src="js/jquery-1.8.3.js"></script>
<p>罐頭訊息</p>
	<form method="post" action="singleweb_fun15_add2.php?st=edit">
        <p>
            <label>訊息文字：</label>
            <input type="text" id="names" name="names" value="<?php echo $names; ?>" size=60/>
        </p>			

		</div>
		<input type="hidden" name="an" value="<?php echo $an; ?>">
		<div class="button"><input type="submit" value="送出"></div>
	</form>

<script type="text/javascript">
$(function() {
	$("#names").focus();
});
</script>
</body>
</html>

