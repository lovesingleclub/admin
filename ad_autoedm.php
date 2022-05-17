<?php

/*****************************************/
//檔案名稱：ad_autoedm.php
//後台對應位置：春天網站系統/配對信產生器
//改版日期：2022.5.16
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");
// ajax讀取會員
if ($_REQUEST["st"] == "read") {
    $SQL = "select mem_photo, mem_nick, mem_school from member_data where mem_num='" . SqlFilter($_REQUEST["num"], "int") . "'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        echo $result["mem_nick"] . "|" . $result["mem_school"] . "|" . $result["mem_photo"];
    } else {
        echo "err";
    }
    exit();
}
require_once("./include/_top.php");
require_once("./include/_sidebar_spring.php");

//程式開始 *****
if ($_SESSION["MM_Username"] == "") {
    call_alert("請重新登入。", "login.php", 0);
}

if ($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["funtourpm"] != "1") {
    call_alert("您沒有查看此頁的權限。", "login.php", 0);
}

//刪除
if ($_REQUEST["st"] == "del") {
    $getpath = "..\singleclub";
    $fullpath = $getpath . SqlFilter($_REQUEST["fname"], "tab");
    DelFile($fullpath);
    reURL("win_close.php?m=資料刪除中");
}

?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>春天網站系統</li>
            <li class="active">配對信產生器</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>配對信產生器</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <?php
                if ($_REQUEST["sextype"] == "") { ?>
                    <div style="height:200px;">
                        <br><br><br>
                        <a href="?sextype=1" class="btn btn-info">發送給男生</a>&nbsp;&nbsp;&nbsp;<a href="?sextype=0" class="btn btn-warning">發送給女生</a>
                    </div>
                <?php } else { ?>
                    <p>
                        <?php
                        if ($_REQUEST["sextype"] == "1") { ?>
                            <a href="#x" class="btn btn-info">發送給男生</a>
                        <?php } else { ?>
                            <a href="#x" class="btn btn-warning">發送給女生</a>
                        <?php }
                        ?>
                    </p>
                    <form action="ad_autoedm_download.php?st=make" method="post">
                        <p>UTM Code：<input type="text" name="utmcode" id="utmcode" style="width:90%" value="utm_source=Nomembership&utm_medium=mail_Month_edm&utm_campaign=girls&cc=springclub_mail_Monthedm0331"></p>
                        <table style="width:760px">
                            <tr>
                                <td align="left"><a href="http://www.springclub.com.tw/?utm_source=Nomembership&utm_medium=mail_Month_edm&utm_campaign=girls&cc=springclub_mail_Monthedm0331"><img src="http://www.springclub.com.tw/images/edms/logo.png" width=200 border=0 style="display:block;"></a></td>
                            </tr>
                            <tr>
                                <td align="left" height="50"><input type="text" name="t1" id="t1" value="春天會館幫你精挑細選最佳配對對象，今天開始來談場戀愛吧！" style="width:80%"></td>
                            </tr>
                            <tr>
                                <td align="left" height="40" style="background:url(http://www.springclub.com.tw/images/edms/tag1.png) no-repeat;font-size:15px;line-height:40px;height:40px;"><input type="text" name="t2" id="t2" value="HOT! 人氣靚妹" style="width:13%"></td>
                            </tr>
                            <tr>
                                <td align="left" style="width:760px;padding-top:10px;padding-bottom:10px;background:#f5d7df">
                                    <table width=760 cellspacing=0 cellpadding=0 style="width:760px;border:0;margin:0;padding:0">
                                        <tr>
                                            <td width=30></td>
                                            <td id="v1div" style="background:url(http://www.springclub.com.tw/images/edms/mbg01.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px">
                                            </td>
                                            <td id="v2div" style="background:url(http://www.springclub.com.tw/images/edms/mbg02.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                            <td id="v3div" style="background:url(http://www.springclub.com.tw/images/edms/mbg03.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                            <td id="v4div" style="background:url(http://www.springclub.com.tw/images/edms/mbg04.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                            <td id="v5div" style="background:url(http://www.springclub.com.tw/images/edms/mbg05.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width=30>
                                                <input type="hidden" name="v1num" id="v1num"><input type="hidden" name="v1photo" id="v1photo"><input type="hidden" name="v1name" id="v1name"><input type="hidden" name="v1school" id="v1school">
                                                <input type="hidden" name="v2num" id="v2num"><input type="hidden" name="v2photo" id="v2photo"><input type="hidden" name="v2name" id="v2name"><input type="hidden" name="v2school" id="v2school">
                                                <input type="hidden" name="v3num" id="v3num"><input type="hidden" name="v3photo" id="v3photo"><input type="hidden" name="v3name" id="v3name"><input type="hidden" name="v3school" id="v3school">
                                                <input type="hidden" name="v4num" id="v4num"><input type="hidden" name="v4photo" id="v4photo"><input type="hidden" name="v4name" id="v4name"><input type="hidden" name="v4school" id="v4school">
                                                <input type="hidden" name="v5num" id="v5num"><input type="hidden" name="v5photo" id="v5photo"><input type="hidden" name="v5name" id="v5name"><input type="hidden" name="v5school" id="v5school">
                                            </td>
                                            <td style="padding-left:10px"><input type="text" name="v1" id="v1" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v1"></td>
                                            <td style="padding-left:10px"><input type="text" name="v2" id="v2" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v2"></td>
                                            <td style="padding-left:10px"><input type="text" name="v3" id="v3" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v3"></td>
                                            <td style="padding-left:10px"><input type="text" name="v4" id="v4" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v4"></td>
                                            <td style="padding-left:10px"><input type="text" name="v5" id="v5" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v5"></td>
                                        </tr>

                                        <tr>
                                            <td width=30></td>
                                            <td id="v6div" style="background:url(http://www.springclub.com.tw/images/edms/mbg05.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px">
                                            </td>
                                            <td id="v7div" style="background:url(http://www.springclub.com.tw/images/edms/mbg04.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                            <td id="v8div" style="background:url(http://www.springclub.com.tw/images/edms/mbg02.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                            <td id="v9div" style="background:url(http://www.springclub.com.tw/images/edms/mbg03.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                            <td id="v10div" style="background:url(http://www.springclub.com.tw/images/edms/mbg01.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width=30>
                                                <input type="hidden" name="v6num" id="v6num"><input type="hidden" name="v6photo" id="v6photo"><input type="hidden" name="v6name" id="v6name"><input type="hidden" name="v6school" id="v6school">
                                                <input type="hidden" name="v7num" id="v7num"><input type="hidden" name="v7photo" id="v7photo"><input type="hidden" name="v7name" id="v7name"><input type="hidden" name="v7school" id="v7school">
                                                <input type="hidden" name="v8num" id="v8num"><input type="hidden" name="v8photo" id="v8photo"><input type="hidden" name="v8name" id="v8name"><input type="hidden" name="v8school" id="v8school">
                                                <input type="hidden" name="v9num" id="v9num"><input type="hidden" name="v9photo" id="v9photo"><input type="hidden" name="v9name" id="v9name"><input type="hidden" name="v9school" id="v9school">
                                                <input type="hidden" name="v10num" id="v10num"><input type="hidden" name="v10photo" id="v10photo"><input type="hidden" name="v10name" id="v10name"><input type="hidden" name="v10school" id="v10school">
                                            </td>
                                            <td style="padding-left:10px"><input type="text" name="v6" id="v6" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v6"></td>
                                            <td style="padding-left:10px"><input type="text" name="v7" id="v7" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v7"></td>
                                            <td style="padding-left:10px"><input type="text" name="v8" id="v8" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v8"></td>
                                            <td style="padding-left:10px"><input type="text" name="v9" id="v9" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v9"></td>
                                            <td style="padding-left:10px"><input type="text" name="v10" id="v10" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v10"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td height=20></td>
                            </tr>

                            <tr>
                                <td align="left" height="40" style="background:url(http://www.springclub.com.tw/images/edms/tag2.png) no-repeat;font-size:15px;line-height:40px;height:40px;"><input type="text" name="t3" id="t3" value="$ 氣質美人" style="width:13%"></td>
                            </tr>
                            <tr>
                                <td align="left" style="width:760px;padding-top:10px;padding-bottom:10px;background:#f5d7df">
                                    <table width=760 cellspacing=0 cellpadding=0 style="width:760px;border:0;margin:0;padding:0">
                                        <tr>
                                            <td width=30></td>
                                            <td id="v11div" style="background:url(http://www.springclub.com.tw/images/edms/mbg01.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px">
                                            </td>
                                            <td id="v12div" style="background:url(http://www.springclub.com.tw/images/edms/mbg02.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                            <td id="v13div" style="background:url(http://www.springclub.com.tw/images/edms/mbg03.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                            <td id="v14div" style="background:url(http://www.springclub.com.tw/images/edms/mbg04.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                            <td id="v15div" style="background:url(http://www.springclub.com.tw/images/edms/mbg05.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width=30>
                                                <input type="hidden" name="v11num" id="v11num"><input type="hidden" name="v11photo" id="v11photo"><input type="hidden" name="v11name" id="v11name"><input type="hidden" name="v11school" id="v11school">
                                                <input type="hidden" name="v12num" id="v12num"><input type="hidden" name="v12photo" id="v12photo"><input type="hidden" name="v12name" id="v12name"><input type="hidden" name="v12school" id="v12school">
                                                <input type="hidden" name="v13num" id="v13num"><input type="hidden" name="v13photo" id="v13photo"><input type="hidden" name="v13name" id="v13name"><input type="hidden" name="v13school" id="v13school">
                                                <input type="hidden" name="v14num" id="v14num"><input type="hidden" name="v14photo" id="v14photo"><input type="hidden" name="v14name" id="v14name"><input type="hidden" name="v14school" id="v14school">
                                                <input type="hidden" name="v15num" id="v15num"><input type="hidden" name="v15photo" id="v15photo"><input type="hidden" name="v15name" id="v15name"><input type="hidden" name="v15school" id="v15school">
                                            </td>
                                            <td style="padding-left:10px"><input type="text" name="v11" id="v11" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v11"></td>
                                            <td style="padding-left:10px"><input type="text" name="v12" id="v12" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v12"></td>
                                            <td style="padding-left:10px"><input type="text" name="v13" id="v13" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v13"></td>
                                            <td style="padding-left:10px"><input type="text" name="v14" id="v14" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v14"></td>
                                            <td style="padding-left:10px"><input type="text" name="v15" id="v15" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v15"></td>
                                        </tr>
                                        <tr>
                                            <td width=30></td>
                                            <td id="v16div" style="background:url(http://www.springclub.com.tw/images/edms/mbg05.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px">
                                            </td>
                                            <td id="v17div" style="background:url(http://www.springclub.com.tw/images/edms/mbg04.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                            <td id="v18div" style="background:url(http://www.springclub.com.tw/images/edms/mbg02.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                            <td id="v19div" style="background:url(http://www.springclub.com.tw/images/edms/mbg03.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                            <td id="v20div" style="background:url(http://www.springclub.com.tw/images/edms/mbg01.png) no-repeat;width:120px;height:180px;color:#EA5B85;font-size:13px;text-align:center;padding-left:20px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width=30>
                                                <input type="hidden" name="v16num" id="v16num"><input type="hidden" name="v16photo" id="v16photo"><input type="hidden" name="v16name" id="v16name"><input type="hidden" name="v16school" id="v16school">
                                                <input type="hidden" name="v17num" id="v17num"><input type="hidden" name="v17photo" id="v17photo"><input type="hidden" name="v17name" id="v17name"><input type="hidden" name="v17school" id="v17school">
                                                <input type="hidden" name="v18num" id="v18num"><input type="hidden" name="v18photo" id="v18photo"><input type="hidden" name="v18name" id="v18name"><input type="hidden" name="v18school" id="v18school">
                                                <input type="hidden" name="v19num" id="v19num"><input type="hidden" name="v19photo" id="v19photo"><input type="hidden" name="v19name" id="v19name"><input type="hidden" name="v19school" id="v19school">
                                                <input type="hidden" name="v20num" id="v20num"><input type="hidden" name="v20photo" id="v20photo"><input type="hidden" name="v20name" id="v20name"><input type="hidden" name="v20school" id="v20school">
                                            </td>
                                            <td style="padding-left:10px"><input type="text" name="v16" id="v16" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v16"></td>
                                            <td style="padding-left:10px"><input type="text" name="v17" id="v17" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v17"></td>
                                            <td style="padding-left:10px"><input type="text" name="v18" id="v18" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v18"></td>
                                            <td style="padding-left:10px"><input type="text" name="v19" id="v19" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v19"></td>
                                            <td style="padding-left:10px"><input type="text" name="v20" id="v20" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v20"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td align="center" height=80><a href="#" style="padding:15px 30px;background:#E84A83;text-decoration: none;"><input type="text" name="t4" id="t4" value="立即查看更多" style="width:13%"></a></td>
                            </tr>
                            <tr>
                                <td align="left">
                                    春天會館開放時間&nbsp;&nbsp;週一~週六 pm 13:30~pm 21:30 <br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;週日 am 10:00~pm 18:00<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table width=800 cellspacing=0 cellpadding=0 style="width:800;border:0;margin:0;padding:0">
                                        <tr>
                                            <td>台北會館 (02)2381-1348 台北市重慶南路一段49號8樓<br>
                                                桃園會館 (03)347-5825 桃園市復興路205號18樓之三<br>
                                                新竹會館 (03)535-6676 新竹市北區北大路307號14樓之1<br></td>
                                            <td>台中會館 (04)2326-5300 台中市台灣大道2段307號11樓之一<br>
                                                台南會館 (06)250-6900 台南市成功路515號8樓<br>
                                                高雄會館 (07)216-1988 高雄市中山二路507號5樓<br></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <br>
                                    單身聯誼、婚友聯誼社 Copyright &copy 春天會館 版權所有&nbsp;&nbsp;&nbsp;<a style="text-decoration: none;color:#999" href="http://www.springclub.com.tw/?service_post=1">取消訂閱</a>
                                </td>
                            </tr>

                        </table>

                        <input type="hidden" name="sextype" id="sextype" value="<?php echo SqlFilter($_REQUEST["sextype"],"int"); ?>">
                        <hr>
                        <input id="send_submit" type="submit" value="產生配對信" class="btn btn-lg btn-danger">
                    </form>
                <?php }
                ?>
            </div>
        </div>
        <!--/span-->
    </div>
    <!--/row-->

    </div>
    <!--/.fluid-container-->
</section>
<!-- /MIDDLE -->

<?php
require_once("./include/_bottom.php");
?>

<script type="text/javascript">
    $(function() {
        $(".vnum").on("click", function() {
            var $this = $(this),
                $thisid = $this.data("num"),
                $thisphoto = $("#" + $thisid + "photo"),
                $thisnum = $("#" + $thisid + "num"),
                $thisschool = $("#" + $thisid + "school"),
                $thisname = $("#" + $thisid + "name"),
                $thisnumv = $("#" + $thisid);
            if (!$thisnumv.val()) {
                alert("請輸入編號。");
                $thisnumv.focus();
                return false;
            }
            $.ajax({
                url: "ad_autoedm.php?st=read&num=" + $thisnumv.val(),
            }).done(function(msg) {
                if (msg == "err") {
                    alert("會員資料錯誤");
                } else {
                    if (msg.indexOf("|") <= 0) alert("會員資料分析錯誤");
                    else {
                        var $v1 = msg.split("|")[0],
                            $v2 = msg.split("|")[1],
                            $v3 = msg.split("|")[2];
                        $thisnum.val($thisnumv.val());
                        $thisname.val($v1);
                        $thisschool.val($v2);
                        $thisphoto.val($v3);
                        relaod_data($("#" + $thisid + "div"), $v1, $v2, $v3, $thisnum.val());
                    }
                }
            });
        });
    });

    function relaod_data($dv, $v1, $v2, $v3, $v4) {
        if (!$dv) return false;
        var $utmcode = $("#utmcode").val();
        $dv.html("");

        $newhtml = "<a href=\"http://www.springclub.com.tw/lovepy_profile.php?m=" + $v4 + "&" + $utmcode + "\"><img src=\"http://www.springclub.com.tw/photo/" + $v3 + "\" width=104 height=126 border=0 style=\"display:block;\"></a>";
        $newhtml += "<a href=\"http://www.springclub.com.tw/lovepy_profile.php?m=" + $v4 + "&" + $utmcode + "\" style=\"text-decoration: none;\">" + $v1 + " (" + $v2 + ")</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        $dv.html($newhtml);
    }
</script>