<?php

/*****************************************/
//檔案名稱：ad_autoedm.php
//後台對應位置：春天網站系統/配對信產生器>下載EDM
//改版日期：2022.5.16
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

// require_once("_inc.php");
// require_once("./include/_function.php");

//產生配對EDM
if ($_REQUEST["st"] == "make") {
    $utmcode = $_REQUEST["utmcode"];

    $rm = "<!DOCTYPE html>" . PHP_EOL;
    $rm = $rm . "<html lang='zh-TW'>" . PHP_EOL;
    $rm = $rm . "<meta http-equiv='content-type' content='text/html'; charset=UTF-8/>" . PHP_EOL;
    $rm = $rm . "<body>" . PHP_EOL;
    $rm = $rm . "<center>" . PHP_EOL;
    $rm = $rm . "<table width=800 cellspacing=0 cellpadding=0 style='width:800;border:0;margin:0;padding:0'>" . PHP_EOL;
    $rm = $rm . "<tr><td align='right'>若您無法正常閱讀，請點此處連結至<a href='http://www.springclub.com.tw/lovepy.asp?" . $utmcode . "'>春天會館線上電子報</a></td></tr>" . PHP_EOL;
    $rm = $rm . "<tr><td align='left'><a href='http://www.springclub.com.tw/?" . $utmcode . "'><img src='http://www.springclub.com.tw/images/edms/logo.png' width=200 border=0 style='display:block;'></a></td></tr>" . PHP_EOL;
    $rm = $rm . "<tr><td align='left' height='50'>" . $_REQUEST["t1"] . "</td></tr>" . PHP_EOL;
    $rm = $rm . "<tr><td align='left' height='40' style='background:url(http://www.springclub.com.tw/images/edms/tag1.png) no-repeat;font-size:15px;color:#fff;line-height:40px;height:40px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $_REQUEST["t2"] . "</td></tr>" . PHP_EOL;
    $rm = $rm . "<tr><td align='left' style='padding-top:10px;padding-bottom:10px;background:#f5d7df'>" . PHP_EOL;
    $rm = $rm . "<table width=760 cellspacing=0 cellpadding=0 style='width:760;border:0;margin:0;padding:0'>" . PHP_EOL;
    $rm = $rm . "	<tr>" . PHP_EOL;
    $rm = $rm . "		<td width=30></td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg01.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v1num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v1photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v1num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v1name"] . " (" . $_REQUEST["v1school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg02.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v2num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v2photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v2num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v2name"] . " (" . $_REQUEST["v2school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg03.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v3num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v3photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v3num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v3name"] . " (" . $_REQUEST["v3school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg04.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v4num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v4photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v4num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v4name"] . " (" . $_REQUEST["v4school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg05.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v5num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v5photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v5num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v5name"] . " (" . $_REQUEST["v5school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "	</tr>" . PHP_EOL;
    $rm = $rm . "	<tr>" . PHP_EOL;
    $rm = $rm . "		<td width=30></td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg05.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v6num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v6photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v6num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v6name"] . " (" . $_REQUEST["v6school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg04.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v7num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v7photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v7num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v7name"] . " (" . $_REQUEST["v7school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg02.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v8num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v8photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v8num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v8name"] . " (" . $_REQUEST["v8school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg03.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v9num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v9photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v9num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v9name"] . " (" . $_REQUEST["v9school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg01.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v10num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v10photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v10num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v10name"] . " (" . $_REQUEST["v10school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "	</tr>" . PHP_EOL;
    $rm = $rm . "</table>" . PHP_EOL;
    $rm = $rm . "</td></tr>" . PHP_EOL;

    $rm = $rm . "<tr><td height=20></td></tr>" . PHP_EOL;

    $rm = $rm . "<tr><td align='left' height='40' style='background:url(http://www.springclub.com.tw/images/edms/tag2.png) no-repeat;font-size:15px;color:#fff;line-height:40px;height:40px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $_REQUEST["t3"] . "</td></tr>" . PHP_EOL;
    $rm = $rm . "<tr><td align='left' style='padding-top:10px;padding-bottom:10px;background:#f5d7df'>" . PHP_EOL;
    $rm = $rm . "<table width=760 cellspacing=0 cellpadding=0 style='width:760;border:0;margin:0;padding:0'>" . PHP_EOL;
    $rm = $rm . "	<tr>" . PHP_EOL;
    $rm = $rm . "		<td width=30></td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg03.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v11num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v11photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v11num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v11name"] . " (" . $_REQUEST["v11school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg02.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v12num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v12photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v12num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v12name"] . " (" . $_REQUEST["v12school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg05.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v13num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v13photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v13num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v13name"] . " (" . $_REQUEST["v13school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg01.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v14num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v14photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v14num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v14name"] . " (" . $_REQUEST["v14school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg04.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v15num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v15photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v15num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v15name"] . " (" . $_REQUEST["v15school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "	</tr>" . PHP_EOL;
    $rm = $rm . "	<tr>" . PHP_EOL;
    $rm = $rm . "		<td width=30></td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg05.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v16num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v16photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v16num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v16name"] . " (" . $_REQUEST["v16school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg04.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v17num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v17photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v17num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v17name"] . " (" . $_REQUEST["v17school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg02.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v18num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v18photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v18num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v18name"] . " (" . $_REQUEST["v18school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg03.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v19num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v19photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v19num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v19name"] . " (" . $_REQUEST["v19school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "		<td style='background:url(http://www.springclub.com.tw/images/edms/mbg01.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;'>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v20num"] . "&" . $utmcode . "'><img src='http://www.springclub.com.tw/photo/" . $_REQUEST["v20photo"] . "' width=104 height=126 border=0 style='display:block;'></a>" . PHP_EOL;
    $rm = $rm . "		  <a href='http://www.springclub.com.tw/lovepy_profile.asp?m=" . $_REQUEST["v20num"] . "&" . $utmcode . "' style='text-decoration: none;'>" . $_REQUEST["v20name"] . " (" . $_REQUEST["v20school"] . ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . PHP_EOL;
    $rm = $rm . "		</td>" . PHP_EOL;
    $rm = $rm . "	</tr>" . PHP_EOL;
    $rm = $rm . "</table>" . PHP_EOL;
    $rm = $rm . "</td></tr>" . PHP_EOL;

    $rm = $rm . "<tr><td align='center' height=80><a href='http://www.springclub.com.tw/lovepy.asp?" . $utmcode . "' style='padding:15px 30px;background:#E84A83;text-decoration: none;color:#fff'>" . $_REQUEST["t4"] . "</a></td></tr>" . PHP_EOL;
    $rm = $rm . "<tr><td align='left'>" . PHP_EOL;
    $rm = $rm . "春天會館開放時間&nbsp;&nbsp;週一~週六 pm 13:30~pm 21:30	<br>" . PHP_EOL;
    $rm = $rm . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;週日 am 10:00~pm 18:00<br><br>" . PHP_EOL;
    $rm = $rm . "</td></tr>" . PHP_EOL;
    $rm = $rm . "<tr><td>" . PHP_EOL;
    $rm = $rm . "	<table width=800 cellspacing=0 cellpadding=0 style='width:800;border:0;margin:0;padding:0'>" . PHP_EOL;
    $rm = $rm . "		<tr>" . PHP_EOL;
    $rm = $rm . "			<td>台北會館 (02)2381-1348 台北市重慶南路一段49號8樓<br>" . PHP_EOL;
    $rm = $rm . "桃園會館 (03)347-5825 桃園市復興路205號18樓之三<br>" . PHP_EOL;
    $rm = $rm . "新竹會館 (03)535-6676 新竹市北區北大路307號14樓之1<br></td>" . PHP_EOL;
    $rm = $rm . "			<td>台中會館 (04)2326-5300 台中市台灣大道2段307號11樓之一<br>" . PHP_EOL;
    $rm = $rm . "台南會館 (06)250-6900 台南市成功路515號8樓<br>" . PHP_EOL;
    $rm = $rm . "高雄會館 (07)216-1988 高雄市中山二路507號5樓<br></td>" . PHP_EOL;
    $rm = $rm . "		</tr>" . PHP_EOL;
    $rm = $rm . "	</table>" . PHP_EOL;
    $rm = $rm . "</td></tr>" . PHP_EOL;

    $rm = $rm . "<tr><td>" . PHP_EOL;
    $rm = $rm . "<br>" . PHP_EOL;
    $rm = $rm . "單身聯誼、婚友聯誼社 Copyright &copy 春天會館 版權所有&nbsp;&nbsp;&nbsp;<a style='text-decoration: none;color:#999' href='http://www.springclub.com.tw/?service_post=1'>取消訂閱</a>" . PHP_EOL;
    $rm = $rm . "</td></tr>" . PHP_EOL;

    $rm = $rm . "</table>" . PHP_EOL;
    $rm = $rm . "</center>" . PHP_EOL;
    $rm = $rm . "</body>" . PHP_EOL;
    $rm = $rm . "</html>";

    //檔案路徑
    $fname = "temp_excel/autoedm.html";
    
    //存入暫存路徑
    file_put_contents($fname, $rm);
    //下載檔案
    $filename = str_replace("/", "", date("Y/m/d")) . $_REQUEST["sextype"] . ".html";
    header('content-disposition:attachment;filename=' . $filename); //告訴瀏覽器通過何種方式處理檔案
    header('content-length:' . filesize($fname)); //下載檔案的大小
    readfile($fname); //讀取檔案
    unlink($fname); //下載後刪除
}

