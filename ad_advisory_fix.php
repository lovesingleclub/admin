<?php
/***************************************/
//檔案名稱：ad_advisory_fix.php
//後台對應位置：排約/記錄功能 → 諮詢記錄表(修改)
//改版日期：2022.2.16
//改版設計人員：Jack
//改版程式人員：Queena
/***************************************/
require_once("_inc.php");
require_once("./include/_function.php");

//接收值
$an = SqlFilter($_REQUEST["an"],"tab");
$st = SqlFilter($_REQUEST["st"],"tab");
$n11y = SqlFilter($_REQUEST["n11y"],"tab");
$n11m = SqlFilter($_REQUEST["n11m"],"tab");
$n11d = SqlFilter($_REQUEST["n11d"],"tab");
$n11h = SqlFilter($_REQUEST["n11h"],"tab");
$n11mm = SqlFilter($_REQUEST["n11mm"],"tab");
$mem_name = SqlFilter($_REQUEST["mem_name"],"tab");
$mem_sex = SqlFilter($_REQUEST["mem_sex"],"tab");
$pay_money = SqlFilter($_REQUEST["pay_money"],"tab");
$pay_money2 = SqlFilter($_REQUEST["pay_money2"],"tab");
$pay_money3 = SqlFilter($_REQUEST["pay_money3"],"tab");
$pay_money4 = SqlFilter($_REQUEST["pay_money4"],"tab");
$types = SqlFilter($_REQUEST["types"],"tab");
$itimes = SqlFilter($_REQUEST["itimes"],"tab");
$notes = SqlFilter($_REQUEST["notes"],"tab");
$mem_phone = SqlFilter($_REQUEST["mem_phone"],"tab");
$mem_mobile = SqlFilter($_REQUEST["mem_mobile"],"tab");

//判斷編號
if ( $an == "" ){ call_alert("編號錯誤。","ClOsE",0);}

//新增記錄
if ( $st == "add" ){
    $n11 = $n11y."/".$n11m."/".$n11d." ".$n11h.":".$n11mm;
    if ( ! chkDate($n11) ){
        call_alert("諮詢時間有誤。",0,0);
    }

    $SQL = "Select Top 1 * From ad_advisory Where auton='".$an."'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $re);
    if ( count($result) == 0 ){
        call_alert("資料錯誤。",0,0);
    }else{
        $SQL_u  = "Update ad_advisory Set ";
        $SQL_u .= "mem_name = '".$mem_name."',";
        $SQL_u .= "mem_sex = '".$mem_sex."',";
        if ( $pay_money != "" ){
            $SQL_u .= "pay_money = '".$pay_money."',";
        }
        if ( $pay_money2 != "" ){
            $SQL_u .= "pay_money2 = '".$pay_money2."',";
        }
        if ( $pay_money3 != "" ){
            $SQL_u .= "pay_money3 = '".$pay_money3."',";
        }
        if ( $pay_money4 != "" ){
            $SQL_u .= "pay_money4 = '".$pay_money4."',";
        }
        $SQL_u .= "types = '".$types."',";
        $SQL_u .= "itimes = '".$n11."',";
        $SQL_u .= "notes = '".$notes."',";
        $SQL_u .= "mem_phone = '".$mem_phone."',";
        $SQL_u .= "mem_mobile = '".$mem_mobile."'";
        $SQL_u .= " Where auton='".$an."'";
        $rs_u = $SPConn->prepare($SQL_u);
        $rs_u->execute();
        reURL("win_close.php?m=修改完成.......");
        exit;
    }
}

//資料語法
$SQL = "Select Top 1 * From ad_advisory Where auton='".$an."'";
$rs = $SPConn->prepare($SQL);
$rs->execute();
$result = $rs->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $re);
if ( count($result) == 0 ){
    call_alert("資料錯誤。",0,0);
}else{ ?>
    <style type="text/css">
        table td {
            font-size: 12px;
        }
    </style>
    <html>
        <script type="text/javascript" src="js/jquery-1.8.3.js"></script>
        <script type="text/javascript" src="js/util.js"></script>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <title>春天會館</title>
        </head>
        <body leftmargin="0" topmargin="0">
            <table width="660" border="0" align="center">
                <tr>
                    <td>
                        <br>
                        <fieldset>
                            <legend style="color:#fc3bf5;font-weight:bold;">春天會館資料</legend>
                            <table width="650" border="0" align="center" cellpadding="3">
                                <tr> 
                                    <td colspan="2" bgcolor="#d9dbfd"><strong style="color: #1a218e;">▼ 請輸入修改資料</strong></td>
                                </tr>
                                <form action="?st=add" method="post" id="form2" onSubmit="return chk_form()">
                                    <tr>
                                        <td bgcolor="#F0F0F0">處理會館/秘書：<?php echo $re["mem_branch"];?> - <?php echo SingleName($re["mem_single"],"normal");?></td>
                                        <td bgcolor="#F0F0F0">講師：<?php echo $re["mem_wbranch"];?> - <?php echo SingleName($re["mem_who"],"normal");?></td>
                                    </tr>
                                    <?php $types = $re["types"];?>
                                    <tr>
                                        <td bgcolor="#F0F0F0" colspan=2>類型：
                                            <select name="types" id="types">
                                                <option value="一對一諮詢"<?php if ( $types == "一對一諮詢" ){ echo " selected";}?>>一對一諮詢</option>
                                                <option value="一對一造型諮詢"<?php if ( $types == "一對一造型諮詢" ){ echo " selected";}?>>一對一造型諮詢</option>
                                                <option value="一對一愛情諮詢"<?php if ( $types == "一對一愛情諮詢" ){ echo " selected";}?>>一對一愛情諮詢</option>
                                                <option value="魅力解析"<?php if ( $types == "魅力解析" ){ echo " selected";}?>>魅力解析</option>      
                                                <option value="新會員先修"<?php if ( $types == "新會員先修" ){ echo " selected";}?>>新會員先修</option>          	          	
                                                <option value="戀愛選修1"<?php if ( $types == "戀愛選修1" ){ echo " selected";}?>>戀愛選修1</option>      
                                                <option value="戀愛選修2"<?php if ( $types == "戀愛選修2" ){ echo " selected";}?>>戀愛選修2</option>      
                                                <option value="戀愛選修3"<?php if ( $types == "戀愛選修3" ){ echo " selected";}?>>戀愛選修3</option>      
                                                <option value="戀愛選修4"<?php if ( $types == "戀愛選修4" ){ echo " selected";}?>>戀愛選修4</option>      
                                                <option value="戀愛選修5"<?php if ( $types == "戀愛選修5" ){ echo " selected";}?>>戀愛選修5</option>      
                                                <option value="戀愛選修6"<?php if ( $types == "戀愛選修6" ){ echo " selected";}?>>戀愛選修6</option>      
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#F0F0F0" width="50%">姓名： <input name="mem_name" type="text" id="mem_name" value="<?php echo $re["mem_name"];?>" size="20"></td>
                                        <td bgcolor="#F0F0F0">性別： 
                                            <select name="mem_sex" id="mem_sex">
                                                <option value="男"<?php if ( $re["mem_sex"] == "男" ){ echo " selected";}?>>男</option>
                                                <option value="女"<?php if ( $re["mem_sex"] == "女" ){ echo " selected";}?>>女</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#F0F0F0">電話： <input name="mem_phone" type="text" id="mem_phone" value="<?php echo $re["mem_phone"];?>" size="20"></td>
                                        <td bgcolor="#F0F0F0">手機： <input name="mem_mobile" type="text" id="mem_mobile" value="<?php echo $re["mem_mobile"];?>" size="20"></td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#F0F0F0" colspan="2">諮詢時間：
                                            <select name="n11y" id="n11y">
                                                <?php
                                                $n11 = $re["itimes"];
                                                for ( $i=date("Y");$i<=(date("Y")+2);$i++ ){
                                                    echo "<option value='".$i."'";
                                                    if ( $i == date("Y",$n11) ){ echo " selected";}
                                                    echo ">".$i."</option>";
                                                }
                                                echo "<option value='".(date("Y")-1)."'>".(date("Y")-1)."</option>";
                                                ?>
                                            </select> 年
                                            <select name="n11m" id="n11m"> 
                                                <?php
                                                for ( $i=1;$i<=12;$i++ ){
                                                    echo "<option value='".$i."'";
                                                    if ( $i == date("m",$n11) ){ echo " selected"; }
                                                    echo ">".$i."</option>";
                                                }
                                                ?>
                                            </select> 月
                                            <select name="n11d" id="n11d">
                                                <?php
                                                for ( $i=1;$i<=31;$i++ ){
                                                    echo "<option value='".$i."'";
                                                    if ( $i == date("d",$n11) ){ echo " selected"; }
                                                    echo ">".$i."</option>";
                                                }?>
                                            </select> 日 
                                            
                                            <select name="n11h" id="n11h">
                                                <option value="">請選擇</option>
                                                <?php
                                                for ( $i=10;$i<=22;$i++ ){
                                                    echo "<option value='".$i."'";
                                                    if ( $i == date("h",$n11 ) ){ echo " selected"; }
                                                    echo ">".$i."</option>";
                                                }?>
                                            </select> 時 
                                            <select name="n11mm" id="n11mm">
                                                <option value="00"<?php if ( date("m",$n11) == 0 ){ echo " selected";}?>>00</option>
                                                <option value="15"<?php if ( date("m",$n11) == 15 ){ echo " selected";}?>>15</option>
                                                <option value="30"<?php if ( date("m",$n11) == 30 ){ echo " selected";}?>>30</option>
                                                <option value="45"<?php if ( date("m",$n11) == 45 ){ echo " selected";}?>>45</option>
                                            </select> 分<?php echo $n11;?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#F0F0F0" colspan="2">備註：<br>
                                            <textarea name="notes" id="notes" style="width:95%" rows="3">
            	                                <?php $notes = $re["notes"];?>
                                            </textarea><?php echo $notes;?>
                                        </td>			
                                    </tr>
                                    <tr bgcolor="#FFF0E1">
                                        <td bgcolor="#336699" colspan=2 align="center">
                                            <input name="an" type="hidden" id="an" value="<?php echo $an;?>">
                                            <input name="Submit" type="submit" id="Submit2" value="確定送出">
                                        </td>
                                    </tr>
                                </form>
                            </table>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </body>
    </html>
<?php }?>