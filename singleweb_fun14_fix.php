<?php 
/*****************************************/
//檔案名稱：singleweb_fun14_fix.php
//後台對應位置：約會專家系統/企業專區>處理
//改版日期：2022.5.30
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");

//更新
if($_REQUEST["st"] == "add"){
    $an = SqlFilter($_REQUEST["an"],"int");
    $SQL = "SELECT * FROM si_business_contact Where auton = '".$an."'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        if(trim($_REQUEST["all_note"]) == ""){
            $SQL =  "UPDATE si_business_contact SET all_note=NULL WHERE auton = '".$an."'";
        }else{
            $SQL =  "UPDATE si_business_contact SET all_note='".SqlFilter($_REQUEST["all_note"],"tab")."' WHERE auton = '".$an."'";            
        }
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }
    reURL("win_close.php");
}

$SQL = "SELECT * from si_business_contact where auton='".SqlFilter($_REQUEST["an"],"int")."'";
$rs = $SPConn->prepare($SQL);
$rs->execute();
$result = $rs->fetch(PDO::FETCH_ASSOC);
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>約會專家</title>
</head>
<body leftmargin="0" topmargin="0">

    <form action="?st=add" method="post" name="form1">
        <table width="400" border="0" align="center">
            <tr>
                <td>
                    <fieldset>
                        <legend>企業專區處理</legend>
                        <table width="390" border="0" align="center" cellpadding="3">
                            <tr bgcolor="#FFF0E1">
                                <td colspan="2" bgcolor="#336699">
                                    <div align="center">
                                        <font color="#FFFFFF" size="3">請填寫處理情形</font>
                                    </div>
                                </td>
                            </tr>
                            <tr bgcolor="#F0F0F0">
                                <td colspan="2" bgcolor="#F0F0F0" style="word-break:break-all">
                                    <font color="#990066">
                                        <strong>
                                            <?php 
                                                if($result["all_type"] != "未處理"){
                                                    echo $result["all_type"];
                                                }
                                            ?>
                                        </strong>
                                    </font>
                                </td>
                            </tr>
                            <tr bgcolor="#F0F0F0">
                                <td colspan="2" bgcolor="#F0F0F0" style="word-break:break-all">
                                    <font color="#990066">
                                        <textarea name="all_note" cols="50" rows="7" id="all_note"><?php echo $result["all_note"]; ?></textarea>
                                    </font>
                                </td>
                            </tr>
                            <tr bgcolor="#FFF0E1">
                                <td colspan="2" bgcolor="#336699">
                                    <div align="center">
                                        <input name="Submit" type="submit" id="Submit" style="font-size: 9pt" value="確定送出">
                                        <font color="#990066"><strong>
                                                <input name="an" type="hidden" id="an" value="<?php echo SqlFilter($_REQUEST["an"],"int"); ?>">
                                            </strong></font>
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