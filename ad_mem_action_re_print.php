<?php

/*****************************************/
//檔案名稱：ad_mem_action_re_print.php
//後台對應位置：管理系統/活動明細表>活動收據列印
//改版日期：2022.3.24
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");

//程式開始 *****
if ($_SESSION["MM_Username"] == "") {
    call_alert("請重新登入。","ClOsE",0);
}
if($_SESSION["MM_UserAuthorization"] == "single"){
    call_alert("您沒有查看此頁的權限。","ClOsE",0);
}

$SQL = "SELECT * FROM ac_data_re WHERE acre_auto=".SqlFilter($_REQUEST["acre_auto"],"int")."";
$rs = $SPConn->prepare($SQL);
$rs->execute();
$result = $rs->fetch(PDO::FETCH_ASSOC);
if(!$result){
    call_alert("讀取錯誤。","ClOsE",0);
}
?>
<html>

<head>
    <style type="text/css">
        table td {
            font-size: 13px;
            line-height: 16px;
        }
    </style>
</head>

<body leftmargin="0" topmargin="0">
    <table width="660" border="0" align="center">
        <tr>
            <td>
                <fieldset>
                    <legend>春天會館資料</legend>
                    <table width="660" border="0" align="center" cellpadding="5">
                        <tr bgcolor="#FFF0E1">
                            <td colspan="2" bgcolor="#336699">
                                <div align="center"><strong>
                                        <font color="#FFFFFF">活動收據列印</font>
                                    </strong></div>
                            </td>
                        </tr>
                        <tr bgcolor="#F0F0F0">
                            <td colspan="2" bgcolor="#F0F0F0" style="word-break:break-all">單據編號：<?php echo $result["acre_auto"]; ?>　　　列印日期：<?php echo changeDate(date("Y/m/d H:i:s")); ?></td>
                        </tr>
                        <tr bgcolor="#F0F0F0">
                            <td colspan="2" bgcolor="#F0F0F0" style="word-break:break-all">
                                報名日期：<font size="2"><?php echo changeDate($result["acre_sign"]); ?></font>
                            </td>
                        </tr>
                        <tr bgcolor="#F0F0F0">
                            <td colspan="2" bgcolor="#F0F0F0" style="word-break:break-all">
                                <font size="2">地區：<?php echo $result["acre_branch"]; ?></font>
                            </td>
                        </tr>
                        <tr bgcolor="#F0F0F0">
                            <td colspan="2" bgcolor="#F0F0F0" style="word-break:break-all">
                                <font size="2">
                                    活動日期：<?php echo changeDate($result["acre_time2"]); ?>
                                    活動標題：<?php echo $result["acre_content"]; ?>
                                </font>　
                            </td>
                        </tr>
                        <tr bgcolor="#F0F0F0">

                            <td colspan="2" bgcolor="#F0F0F0" style="word-break:break-all">
                                <?php 
                                    $SQL = "SELECT mem_name FROM member_data Where mem_username='".$result["acre_user"]."'";
                                    $rs = $SPConn->prepare($SQL);
                                    $rs->execute();
                                    $result2 = $rs->fetch(PDO::FETCH_ASSOC);
                                    if($result2){
                                        $mem_name = $result2["mem_name"];
                                    }
                                ?>
                                參加人員：<font size="2"><?php echo $mem_name; ?></font>
                            </td>
                        </tr>
                        <tr bgcolor="#F0F0F0">
                            <td colspan="2" bgcolor="#F0F0F0" style="word-break:break-all">
                                <font size="2">收費金額：(<?php echo $result["acre_pay"]; ?>)<?php echo $result["acre_pay2"]; ?> 元 </font>
                            </td>
                        </tr>
                        <tr bgcolor="#F0F0F0">
                            <td colspan="2" bgcolor="#F0F0F0" style="word-break:break-all">
                                <font size="2">活動備註：
                                <?php echo $result["acre_note"]; ?> </font>
                            </td>
                        </tr>
                        <tr bgcolor="#F0F0F0">
                            <td colspan="2" bgcolor="#F0F0F0" style="word-break:break-all">
                                <font size="2">受理</font>
                                <font size="2">會館：<?php echo $result["all_branch"]; ?></font>
                            </td>
                        </tr>
                        <tr bgcolor="#F0F0F0">
                            <td colspan="2" bgcolor="#F0F0F0" style="word-break:break-all">
                                <div align="center">
                                    <p align="left">
                                        <font size="2"> 受理秘書：
                                            <?php 
                                                if($result["all_single"] != ""){
                                                    echo SingleName($result["all_single"],"normal");
                                                }
                                            ?>
                                        </font>
                                    </p>
                                </div>
                            </td>
                        </tr>
                        <tr bgcolor="#FFF0E1">
                            <td colspan="2" bgcolor="#F0F0F0">活動注意事項<br>
                                (1) 收據需加蓋本公司印章使生效。<br>
                                (2) 參加活動請事先報名，不接受當天報名。<br>
                                (3) 活動報名後請準時參加，若報名後不克參加，請事先告知。<br>
                                ＊戶外活動-1星期前；室內活動-3天前。超過活動取消截止日，活動費用、活動保證金不退還<br>
                                (4) 活動保證金：活動結束後，憑本聯申請退還。<br>
                                (5) 要有好姻緣，先有好人緣；主動的人才有選擇權。</td>
                        </tr>
                        <tr bgcolor="#FFF0E1">
                            <td colspan="2" bgcolor="#336699">
                                <div align="center">
                                    <input name="Submit" type="button" id="Submit2" style="font-size: 9pt" onClick="varitext()" value="列印收據">
                                </div>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
</body>

</html>




<script type="text/javascript" language="JavaScript">
    function varitext(text) {
        text = document
        print(text)
    }
</script>