<?php 
/*****************************************/
//檔案名稱：singleweb_fun15_add.php
//後台對應位置：約會專家系統/禮物管理>新增禮物
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
        $SQL = "UPDATE si_gift_list SET name='".SqlFilter($_REQUEST["names"],"tab")."', url='".SqlFilter($_REQUEST["url"],"tab")."' where auton='".$an."'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }else{
        $SQL = "select top 1 des from si_gift_list where types='gift' order by des desc";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result2 = $rs->fetch(PDO::FETCH_ASSOC);
        if($result2){
            $ltd = $result2["des"] + 1;
        }else{
            $ltd = 1;
        }
        $SQL = "INSERT INTO si_gift_list (name,url,types,des) VALUES ('".SqlFilter($_REQUEST["names"],"tab")."','".SqlFilter($_REQUEST["url"],"tab")."','gift','".$ltd."')";
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
<p>禮物管理</p>
<form method="post" action="singleweb_fun15_add.php?st=edit">
    <p>
        <label>禮物名稱：</label>
        <input type="text" id="names" name="names" value="<?php echo $names; ?>" size=60 />
    </p>
    <p>
        <label>檔名：</label>
        <input type="text" id="url" name="url" value="<?php echo $url; ?>" size=60 />
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

