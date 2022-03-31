<?php

/*****************************************/
//檔案名稱：ad_mem_action_re_list_turn.php
//後台對應位置：管理系統/活動明細表/待轉資料查詢>轉入活動
//改版日期：2022.3.28
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");

// 轉入活動
if($_REQUEST["st"] == "add"){
    $acre_num = SqlFilter($_REQUEST["acre_num"],"int");
    $SQL = "SELECT * FROM action_data Where ac_auto = ".$acre_num."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);                    
    if($result){
        $ac_time = $result["ac_time"];
        $ac_title = $result["ac_title"];
        $ac_kind = $result["ac_kind"];
        $ac_kind2 = $result["ac_kind2"];
        $ac_branch = $result["ac_branch"];
        $ac_note = $result["ac_note"];
        $ac_auto = $result["ac_auto"];
    }

    if($_REQUEST["acre_note"] != ""){
        $acre_note = SqlFilter($_REQUEST["acre_note"],"tab");
    }else{
        $acre_note = "";
    }
    $SQL = "SELECT * FROM ac_data_re Where acre_auto = ".SqlFilter($_REQUEST["acre_auto"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);                    
    if($result){
        $SQL = "UPDATE ac_data_re SET acre_time_turn2='".date("Y/m/d")."', acre_time2='".$ac_time."', acre_title='".$ac_title."', acre_kind='".$ac_kind."', acre_kind2='".$ac_kind2."', acre_branch='".$ac_branch."', acre_content='".$ac_note."', acre_note='".$acre_note."', acre_num='".$ac_auto."', acre_ck2='0' Where acre_auto = ".SqlFilter($_REQUEST["acre_auto"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }

    reURL("reload_window.php?m=轉入活動中...");
}

$SQL = "SELECT * FROM ac_data_re Where acre_auto = ".SqlFilter($_REQUEST["acre_auto"],"int")."";
$rs = $SPConn->prepare($SQL);
$rs->execute();
$result = $rs->fetch(PDO::FETCH_ASSOC);                    
if(!$result){
    call_alert("讀取錯誤。","ClOsE",0);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>春天會館</title>
    <style type="text/css">
        table td {
            font-size:12px;
        }
    </style>
</head>

<body>
    <form action="ad_mem_action_re_turn.php?st=add" method="post" name="form1">
        <table width="460" border="0" align="center">
            <tr>
                <td>
                    <fieldset>
                        <legend>春天會館資料</legend>
                        <table width="460" border="0" align="center" cellpadding="3">
                            <tr bgcolor="#F0F0F0">
                                <td colspan="2" bgcolor="#F0F0F0">活動標題：
                                    <select name="acre_num" id="select4" style="width:90%;">
                                        <?php 
                                            $SQL = "SELECT * FROM action_data Where ac_time between '".date("Y/m/d",strtotime("-300 day"))."' and '".date("Y/m/d",strtotime("+1 year"))."' ORDER BY ac_time DESC";
                                            $rs = $SPConn->prepare($SQL);
                                            $rs->execute();
                                            $result2 = $rs->fetchAll(PDO::FETCH_ASSOC);                    
                                            if($result2){
                                                foreach($result2 as $re2){
                                                    echo "<option value=".$re2["ac_auto"].">".$re2["ac_auto"]."-".$re2["ac_branch"]." - ".$re2["ac_time"]." - ".$re2["ac_title"]."</option>";
                                                }
                                            }else{
                                                echo "<option value='error'>讀取錯誤</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tr>
                            <tr bgcolor="#F0F0F0">
                                <td align="left" colspan="2" bgcolor="#F0F0F0">參加人員身分証：<?php echo $result["acre_user"]; ?></td>
                            </tr>
                            <tr bgcolor="#F0F0F0">
                                <td align="left" colspan="2" bgcolor="#F0F0F0">收費金額：(<?php echo $result["acre_pay"]; ?>) <?php echo $result["acre_pay2"]; ?>元 </td>
                            </tr>
                            <tr bgcolor="#F0F0F0">
                                <td align="left" colspan="2" bgcolor="#F0F0F0">活動備註：
                                    <input name="acre_note" type="text" id="acre_note" style="width:90%;">
                                </td>
                            </tr>
                            <tr bgcolor="#F0F0F0">
                                <td align="left" colspan="2" bgcolor="#F0F0F0">
                                    受理會館：<?php echo $result["all_branch"]; ?> 　受理秘書：<?php echo SingleName($result["all_single"],"normal"); ?></td>
                            </tr>
                            <tr bgcolor="#FFF0E1">
                                <td align="left" colspan="2" bgcolor="#336699">
                                    <div align="center">
                                        <input name="Submit" type="submit" id="Submit2" value="確定送出">
                                        <input name="acre_auto" type="hidden" id="acre_auto" value="<?php echo SqlFilter($_REQUEST["acre_auto"],"int"); ?>">
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
        </table>
    </form>
</body>

</html>