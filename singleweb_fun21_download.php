<?php

/*****************************************/
//檔案名稱：singleweb_fun21_download.php
//後台對應位置：約會專家網站系統/配對信產生器>下載EDM
//改版日期：2022.6.15
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

// require_once("_inc.php");
// require_once("./include/_function.php");

//產生配對EDM
if ($_REQUEST["st"] == "make") {
    if($_REQUEST["v1data"] != ""){
        $v1data = str_replace(PHP_EOL,"<br>",$v1data);
    }
    if($_REQUEST["v2data"] != ""){
        $v2data = str_replace(PHP_EOL,"<br>",$v2data);
    }
    if($_REQUEST["v3data"] != ""){
        $v3data = str_replace(PHP_EOL,"<br>",$v3data);
    }
    if($_REQUEST["v4data"] != ""){
        $v4data = str_replace(PHP_EOL,"<br>",$v4data);
    }
    if($_REQUEST["v5data"] != ""){
        $v5data = str_replace(PHP_EOL,"<br>",$v5data);
    }
    if($_REQUEST["v6data"] != ""){
        $v16data = str_replace(PHP_EOL,"<br>",$v6data);
    }

    $rm = "<!DOCTYPE html>".PHP_EOL;
    $rm = $rm ."<html>".PHP_EOL;
    $rm = $rm ."<head>".PHP_EOL;
    $rm = $rm ."</head>".PHP_EOL;
    $rm = $rm ."<body>".PHP_EOL;
    $rm = $rm ."<table style='margin: 0 auto; text-align: left; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;' border='0' cellspacing='0' cellpadding='0' align='center'>".PHP_EOL;
    $rm = $rm ."<tbody>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td><span style='color: #b6b6b6; font-size: 90%; display: block; margin: 10px auto 20px auto; text-align: center;'> 若您無法正常閱讀此封電子報，請點此處連結至 <a style='color: #b6b6b6;' href='https://www.singleparty.com.tw/?cc=singleparty_EDM_E' target='_blank' rel='noopener'> 約會專家官方網站 </a> </span></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td><a href='https://www.singleparty.com.tw/?cc=singleparty_EDM_E'><img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_logo.png' alt='約會專家logo' border='0' /></a></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td>".PHP_EOL;
    $rm = $rm ."<table style='font-size: 0;' border='0' width='800' cellspacing='0' cellpadding='0'>".PHP_EOL;
    $rm = $rm ."<tbody>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td><a href='https://www.singleparty.com.tw/login.asp?cc=singleparty_EDM_E'> <img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_header_1.png' alt='交友大廳' border='0' /> </a></td>".PHP_EOL;
    $rm = $rm ."<td><a href='https://www.singleparty.com.tw/event.asp?cc=singleparty_EDM_E'> <img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_header_2.png' alt='主題約會' border='0' /> </a></td>".PHP_EOL;
    $rm = $rm ."<td><a href='https://www.singleparty.com.tw/loveclass_teacher.asp?cc=singleparty_EDM_E'> <img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_header_3.png' alt='戀愛達人' border='0' /> </a></td>".PHP_EOL;
    $rm = $rm ."<td><a href='https://www.singleparty.com.tw/lovesalon.asp?cc=singleparty_EDM_E'> <img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_header_4.png' alt='戀愛專欄' border='0' /> </a></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."</tbody>".PHP_EOL;
    $rm = $rm ."</table>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td><img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/20200727_e/images/sp_body01.png' alt='會專家配對系統為您找出速配指數極高的對象!' border='0' /></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding: 40px 25px;'>".PHP_EOL;
    $rm = $rm ."<table border='0' cellspacing='0' cellpadding='0'>".PHP_EOL;
    $rm = $rm ."<tbody>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td>".PHP_EOL;
    $rm = $rm ."<p style='color: #000; font-size: 24px; font-weight: bold; margin-bottom: 20px;'>".$_REQUEST["t1"]."</p>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr><!-- 會員照 -->".PHP_EOL;
    $rm = $rm ."<td width='230'>".PHP_EOL;
    $rm = $rm ."<table border='0' width='230' cellspacing='0' cellpadding='0'>".PHP_EOL;
    $rm = $rm ."<tbody>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 15px;' width='230'><img style='max-width: 230px; height: 180px; vertical-align: top; display: block;' src='".$_REQUEST["v1photov"]."' alt='會員照片' /></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 8px;' width='230'><span style='color: #000; font-size: 20px; font-weight: bold;'>".$_REQUEST["v1area"]."</span></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 10px;' width='230'>".PHP_EOL;
    $rm = $rm ."<p style='margin: 0; font-size: 16px; color: #000; line-height: 1.4;'>".$v1data."</p>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td width='230'><a style='display: inline-block; background-color: #c2267d; padding: 6px 12px; color: #fff; font-size: 16px; text-decoration: none;' href='https://www.singleparty.com.tw/profile.asp?m=".$_REQUEST["v1num"]."' target='_blank' rel='noopener'> 打招呼去！ </a></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."</tbody>".PHP_EOL;
    $rm = $rm ."</table>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."<td width='30'>&nbsp;</td>".PHP_EOL;
    $rm = $rm ."<!-- 會員照 -->".PHP_EOL;
    $rm = $rm ."<td width='230'>".PHP_EOL;
    $rm = $rm ."<table border='0' width='230' cellspacing='0' cellpadding='0'>".PHP_EOL;
    $rm = $rm ."<tbody>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 15px;' width='230'><img style='max-width: 230px; height: 180px; vertical-align: top; display: block;' src='".$_REQUEST["v2photov"]."' alt='會員照片' /></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 8px;' width='230'><span style='color: #000; font-size: 20px; font-weight: bold;'>".$_REQUEST["v2area"]."</span></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 10px;' width='230'>".PHP_EOL;
    $rm = $rm ."<p style='margin: 0; font-size: 16px; color: #000; line-height: 1.4;'>".$v2data."</p>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td width='230'><a style='display: inline-block; background-color: #c2267d; padding: 6px 12px; color: #fff; font-size: 14px; text-decoration: none;' href='https://www.singleparty.com.tw/profile.asp?m=".$_REQUEST["v2num"]."' target='_blank' rel='noopener'> 打招呼去！ </a></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."</tbody>".PHP_EOL;
    $rm = $rm ."</table>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."<td width='30'>&nbsp;</td>".PHP_EOL;
    $rm = $rm ."<!-- 會員照 -->".PHP_EOL;
    $rm = $rm ."<td width='230'>".PHP_EOL;
    $rm = $rm ."<table border='0' width='230' cellspacing='0' cellpadding='0'>".PHP_EOL;
    $rm = $rm ."<tbody>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 15px;' width='230'><img style='max-width: 230px; height: 180px; vertical-align: top; display: block;' src='".$_REQUEST["v3photov"]."' alt='會員照片' /></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 8px;' width='230'><span style='color: #000; font-size: 20px; font-weight: bold;'>".$_REQUEST["v3area"]."</span></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 10px;' width='230'>".PHP_EOL;
    $rm = $rm ."<p style='margin: 0; font-size: 16px; color: #000; line-height: 1.4;'>".$v3data."</p>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td width='230'><a style='display: inline-block; background-color: #c2267d; padding: 6px 12px; color: #fff; font-size: 16px; text-decoration: none;' href='https://www.singleparty.com.tw/profile.asp?m=".$_REQUEST["v3num"]."' target='_blank' rel='noopener'> 打招呼去！ </a></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."</tbody>".PHP_EOL;
    $rm = $rm ."</table>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td colspan='5' height='30'>&nbsp;</td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr><!-- 會員照 -->".PHP_EOL;
    $rm = $rm ."<td width='230'>".PHP_EOL;
    $rm = $rm ."<table border='0' width='230' cellspacing='0' cellpadding='0'>".PHP_EOL;
    $rm = $rm ."<tbody>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 15px;' width='230'><img style='max-width: 230px; height: 180px; vertical-align: top; display: block;' src='".$_REQUEST["v4photov"]."' alt='會員照片' /></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 8px;' width='230'><span style='color: #000; font-size: 20px; font-weight: bold;'>".$_REQUEST["v4area"]."</span></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 10px;' width='230'>".PHP_EOL;
    $rm = $rm ."<p style='margin: 0; font-size: 16px; color: #000; line-height: 1.4;'>".$v4data."</p>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td width='230'><a style='display: inline-block; background-color: #c2267d; padding: 6px 12px; color: #fff; font-size: 16px; text-decoration: none;' href='https://www.singleparty.com.tw/profile.asp?m=".$_REQUEST["v4num"]."' target='_blank' rel='noopener'> 打招呼去！ </a></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."</tbody>".PHP_EOL;
    $rm = $rm ."</table>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."<td width='30'>&nbsp;</td>".PHP_EOL;
    $rm = $rm ."<!-- 會員照 -->".PHP_EOL;
    $rm = $rm ."<td width='230'>".PHP_EOL;
    $rm = $rm ."<table border='0' width='230' cellspacing='0' cellpadding='0'>".PHP_EOL;
    $rm = $rm ."<tbody>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 15px;' width='230'><img style='max-width: 230px; height: 180px; vertical-align: top; display: block;' src='".$_REQUEST["v5photov"]."' alt='會員照片' /></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 8px;' width='230'><span style='color: #000; font-size: 20px; font-weight: bold;'>".$_REQUEST["v5area"]."</span></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 10px;' width='230'>".PHP_EOL;
    $rm = $rm ."<p style='margin: 0; font-size: 16px; color: #000; line-height: 1.4;'>".$v5data."</p>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td width='230'><a style='display: inline-block; background-color: #c2267d; padding: 6px 12px; color: #fff; font-size: 16px; text-decoration: none;' href='https://www.singleparty.com.tw/profile.asp?m=".$_REQUEST["v5num"]."' target='_blank' rel='noopener'> 打招呼去！ </a></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."</tbody>".PHP_EOL;
    $rm = $rm ."</table>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."<td width='30'>&nbsp;</td>".PHP_EOL;
    $rm = $rm ."<!-- 會員照 -->".PHP_EOL;
    $rm = $rm ."<td width='230'>".PHP_EOL;
    $rm = $rm ."<table border='0' width='230' cellspacing='0' cellpadding='0'>".PHP_EOL;
    $rm = $rm ."<tbody>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 15px;' width='230'><img style='max-width: 230px; height: 180px; vertical-align: top; display: block;' src='".$_REQUEST["v6photov"]."' alt='會員照片' /></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 8px;' width='230'><span style='color: #000; font-size: 20px; font-weight: bold;'>".$_REQUEST["v6area"]."</span></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td style='padding-bottom: 10px;' width='230'>".PHP_EOL;
    $rm = $rm ."<p style='margin: 0; font-size: 16px; color: #000; line-height: 1.4;'>".$v6data."</p>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td width='230'><a style='display: inline-block; background-color: #c2267d; padding: 6px 12px; color: #fff; font-size: 16px; text-decoration: none;' href='https://www.singleparty.com.tw/profile.asp?m=".$_REQUEST["v6num"]."' target='_blank' rel='noopener'> 打招呼去！ </a></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."</tbody>".PHP_EOL;
    $rm = $rm ."</table>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."</tbody>".PHP_EOL;
    $rm = $rm ."</table>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td><img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_footer_title.png' alt='跨平台約會聯合服務會館' border='0' /></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td>".PHP_EOL;
    $rm = $rm ."<table style='font-size: 0;' border='0' width='800' cellspacing='0' cellpadding='0'>".PHP_EOL;
    $rm = $rm ."<tbody>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td><a style='border: 0; outline: 0; text-decoration: none;' href='https://www.singleparty.com.tw/index.asp?cc=singleparty_EDM_E'> <img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_footer_sp.png' alt='約會專家' border='0' /> </a></td>".PHP_EOL;
    $rm = $rm ."<td><a style='border: 0; outline: 0; text-decoration: none;' href='https://www.springclub.com.tw/?cc=singleparty_EDM_E'> <img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_footer_spring.png' alt='春天會館' border='0' /> </a></td>".PHP_EOL;
    $rm = $rm ."<td><a style='border: 0; outline: 0; text-decoration: none;' href='https://www.funtour.com.tw/?cc=singleparty_EDM_E'> <img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_footer_fun.png' alt='好好玩旅行社' border='0' /> </a></td>".PHP_EOL;
    $rm = $rm ."<td><a style='border: 0; outline: 0; text-decoration: none;' href='https://www.datemenow.com.tw/?cc=singleparty_EDM_E'> <img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_footer_dmn.png' alt='datemenow' border='0' /> </a></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."</tbody>".PHP_EOL;
    $rm = $rm ."</table>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td><img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_footer_add.png' alt='約會專家聯合服務會館地址' border='0' /></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td>".PHP_EOL;
    $rm = $rm ."<table border='0' width='800' cellspacing='0' cellpadding='0'>".PHP_EOL;
    $rm = $rm ."<tbody>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td><img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_footer_bar.png' alt='約會專家border' border='0' /></td>".PHP_EOL;
    $rm = $rm ."<td><a href='https://www.facebook.com/%E7%B4%84%E6%9C%83%E5%B0%88%E5%AE%B6Single-Party-1702184089840480/?cc=singleparty_EDM_E'> <img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_footer_fb.png' alt='facebook-icon' border='0' /> </a></td>".PHP_EOL;
    $rm = $rm ."<td><a href='https://www.youtube.com/channel/UCvNiE9iw31K4eNaot5IwVSA?cc=singleparty_EDM_E'> <img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_footer_yt.png' alt='youtube-icon' border='0' /> </a></td>".PHP_EOL;
    $rm = $rm ."<td><a href='https://www.instagram.com/singlepartyhigh/?cc=singleparty_EDM_E'> <img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_footer_ig.png' alt='instagram-icon' border='0' /> </a></td>".PHP_EOL;
    $rm = $rm ."<td><img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_footer_bar.png' alt='約會專家border' border='0' /></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."</tbody>".PHP_EOL;
    $rm = $rm ."</table>".PHP_EOL;
    $rm = $rm ."</td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."<tr>".PHP_EOL;
    $rm = $rm ."<td><a href='_unsub_'><img style='vertical-align: top; display: block;' src='https://www.singleparty.com.tw/edm/images/sp_footer_copyright.png' alt='單身聯誼,婚友聯誼社 copyright @約會專家' border='0' /></a></td>".PHP_EOL;
    $rm = $rm ."</tr>".PHP_EOL;
    $rm = $rm ."</tbody>".PHP_EOL;
    $rm = $rm ."</table>".PHP_EOL;
    $rm = $rm ."</body>".PHP_EOL;
    $rm = $rm ."</html>";

    //檔案路徑
    $fname = "temp_excel/si_autoedm.html";
    
    //存入暫存路徑
    file_put_contents($fname, $rm);
    //下載檔案
    $filename = str_replace("/", "", date("Y/m/d")) . $_REQUEST["sextype"] . ".html";
    header('content-disposition:attachment;filename=' . $filename); //告訴瀏覽器通過何種方式處理檔案
    header('content-length:' . filesize($fname)); //下載檔案的大小
    readfile($fname); //讀取檔案
    unlink($fname); //下載後刪除
}

