<?php

/*****************************************/
//檔案名稱：singleweb_fun21.php
//約會專家系統/配對信產生器
//改版日期：2022.6.15
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/
function chk_mem_name($nick, $names, $sex, $area)
{
    if ($nick != "") {
        return $area . " " . $nick;
    } else {
        if (mb_strlen($names) > 1) {
            $names = mb_substr($names, 0, 1);
        }
        if ($sex = "男") {
            $sex = "先生";
        } else {
            $sex = "小姐";
        }
        return $area . " " . $names . $sex;
    }
}

require_once("_inc.php");
require_once("./include/_function.php");
// ajax亂數讀取會員
if ($_REQUEST["st"] == "rndget") {
    if ($_REQUEST["sex"] == "1") {
        $qsql = " and mem_sex='女'";
    } else {
        $qsql = " and mem_sex='男'";
    }
    $SQL = "SELECT top 6 * FROM member_data where mem_level='mem' and singleparty_edm=1" . $qsql . " order by NEWID()";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetchAll(PDO::FETCH_ASSOC);
    if ($result) {
        foreach ($result as $re) {
            $rr = $rr . $re["mem_num"] . ",";
        }
        if (substr($rr, -1) == ",") {
            $rr = substr($rr, 0, -1);
        }
        echo $rr;
    } else {
        echo "err";
    }
    exit();
}

// ajax讀取會員
if ($_REQUEST["st"] == "read") {
    $SQL = "select mem_photo, mem_nick, mem_name, mem_sex, mem_star, mem_blood, mem_area  from member_data where mem_num='" . SqlFilter($_REQUEST["num"], "int") . "'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $na = chk_mem_name($result["mem_nick"], $result["mem_name"], $result["mem_sex"], "");
        $na = trim($na);
        $na = str_replace(" ", "", $na);
        $mdata = "暱稱：" . $na . "<br>星座：" . $result["mem_star"] . "<br>血型：" . $result["mem_blood"] . " 型";
        echo $result["mem_photo"] . "|" . $result["mem_area"] . "|" . $mdata;
    } else {
        echo "err";
    }
    exit();
}
require_once("./include/_top.php");
require_once("./include/_sidebar_single.php");

//程式開始 *****
if ($_SESSION["MM_Username"] == "") {
    call_alert("請重新登入。", "login.php", 0);
}

if ($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["singleweb"] != "1") {
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
            <li>約會專家系統</li>
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
                    <form action="singleweb_fun21_download.php?st=make" method="post">
                        <!--<p>UTM Code：<input type="text" name="utmcode" id="utmcode" style="width:90%" value="utm_source="></p>-->
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td>
                                        <span style="color: #b6b6b6;font-size: 90%;display:block; margin:10px auto 20px auto; text-align: center;">
                                            若您無法正常閱讀此封電子報，請點此處連結至
                                            <a href="https://www.singleparty.com.tw/?cc=singleparty_EDM_E" target="_blank" style="color: #b6b6b6;">
                                                約會專家官方網站
                                            </a>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="https://www.singleparty.com.tw/?cc=singleparty_EDM_E"><img src="https://www.singleparty.com.tw/edm/images/sp_logo.png" border="0" alt="約會專家logo" style="vertical-align: top; display: block;"></a></td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width="800" border="0" cellpadding="0" cellspacing="0" style="font-size: 0;">
                                            <tbody>
                                                <tr>
                                                    <td><a href="https://www.singleparty.com.tw/login.asp?cc=singleparty_EDM_E"><img src="https://www.singleparty.com.tw/edm/images/sp_header_1.png" border="0" alt="交友大廳" style="vertical-align: top; display: block;"></a></td>
                                                    <td><a href="https://www.singleparty.com.tw/event.asp?cc=singleparty_EDM_E"><img src="https://www.singleparty.com.tw/edm/images/sp_header_2.png" border="0" alt="主題約會" style="vertical-align: top; display: block;"></a></td>
                                                    <td><a href="https://www.singleparty.com.tw/loveclass_teacher.asp?cc=singleparty_EDM_E"><img src="https://www.singleparty.com.tw/edm/images/sp_header_3.png" border="0" alt="戀愛達人" style="vertical-align: top; display: block;"></a></td>
                                                    <td><a href="https://www.singleparty.com.tw/lovesalon.asp?cc=singleparty_EDM_E"><img src="https://www.singleparty.com.tw/edm/images/sp_header_4.png" border="0" alt="戀愛專欄" style="vertical-align: top; display: block;"></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="https://www.singleparty.com.tw/edm/20200727_e/images/sp_body01.png" border="0" alt="會專家配對系統為您找出速配指數極高的對象!" style="vertical-align: top; display: block;"></td>
                                </tr>
                                <tr>
                                    <td style="padding: 40px 25px;">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td colspan=5>
                                                        <a href="javascript:rndget('<?php echo $_REQUEST["sextype"]; ?>');" class="btn btn-info">亂數讀取</a><small id="rndgetmsg"></small><br>
                                                        <p style="color: #000; font-size: 24px; font-weight: bold; margin-bottom: 20px;"><input type="text" name="t1" id="t1" value="超人氣王"></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <!-- 會員照 -->
                                                    <td width="230">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="230">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding-bottom: 15px;" width="230">
                                                                        <img id="v1photo" style="max-width: 230px; height: 180px;" src="" style="vertical-align: top; display: block;">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-bottom: 8px;" width="230">
                                                                        <span style="color: #000; font-size: 20px; font-weight: bold;"><input type="text" id="v1area" name="v1area"></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-bottom: 10px;" width="230">
                                                                        <p style="margin: 0; font-size: 16px; color: #000; line-height: 1.4;">
                                                                            <textarea type="text" id="v1data" name="v1data" style="height:70px;">暱稱：OO&#13;&#10;星座：OO&#13;&#10;血型：OO</textarea>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="230">
                                                                        <input type="hidden" id="v1photov" name="v1photov"><input type="hidden" name="v1num" id="v1num"><input type="text" name="v1" id="v1" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v1">
                                                                    </td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </td>

                                                    <td width="30"></td>

                                                    <!-- 會員照 -->
                                                    <td width="230">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="230">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding-bottom: 15px;" width="230">
                                                                        <img id="v2photo" style="max-width: 230px; height: 180px;" src="" style="vertical-align: top; display: block;">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-bottom: 8px;" width="230">
                                                                        <span style="color: #000; font-size: 20px; font-weight: bold;"><input type="text" id="v2area" name="v2area"></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-bottom: 10px;" width="230">
                                                                        <p style="margin: 0; font-size: 16px; color: #000; line-height: 1.4;">
                                                                            <textarea type="text" id="v2data" name="v2data" style="height:70px;">暱稱：OO&#13;&#10;星座：OO&#13;&#10;血型：OO</textarea>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="230">
                                                                        <input type="hidden" id="v2photov" name="v2photov"><input type="hidden" name="v2num" id="v2num"><input type="text" name="v2" id="v2" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v2">
                                                                    </td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </td>

                                                    <td width="30"></td>

                                                    <!-- 會員照 -->
                                                    <td width="230">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="230">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding-bottom: 15px;" width="230">
                                                                        <img id="v3photo" style="max-width: 230px; height: 180px;" src="" style="vertical-align: top; display: block;">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-bottom: 8px;" width="230">
                                                                        <span style="color: #000; font-size: 20px; font-weight: bold;"><input type="text" id="v3area" name="v3area"></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-bottom: 10px;" width="230">
                                                                        <p style="margin: 0; font-size: 16px; color: #000; line-height: 1.4;">
                                                                            <textarea type="text" id="v3data" name="v3data" style="height:70px;">暱稱：OO&#13;&#10;星座：OO&#13;&#10;血型：OO</textarea>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="230">
                                                                        <input type="hidden" id="v3photov" name="v3photov"><input type="hidden" name="v3num" id="v3num"><input type="text" name="v3" id="v3" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v3">
                                                                    </td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" height="30"></td>
                                                </tr>
                                                <tr>
                                                    <!-- 會員照 -->
                                                    <td width="230">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="230">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding-bottom: 15px;" width="230">
                                                                        <img id="v4photo" style="max-width: 230px; height: 180px;" src="" style="vertical-align: top; display: block;">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-bottom: 8px;" width="230">
                                                                        <span style="color: #000; font-size: 20px; font-weight: bold;"><input type="text" id="v4area" name="v4area"></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-bottom: 10px;" width="230">
                                                                        <p style="margin: 0; font-size: 16px; color: #000; line-height: 1.4;">
                                                                            <textarea type="text" id="v4data" name="v4data" style="height:70px;">暱稱：OO&#13;&#10;星座：OO&#13;&#10;血型：OO</textarea>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="230">
                                                                        <input type="hidden" id="v4photov" name="v4photov"><input type="hidden" name="v4num" id="v4num"><input type="text" name="v4" id="v4" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v4">
                                                                    </td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </td>

                                                    <td width="30"></td>

                                                    <!-- 會員照 -->
                                                    <td width="230">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="230">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding-bottom: 15px;" width="230">
                                                                        <img id="v5photo" style="max-width: 230px; height: 180px;" src="" style="vertical-align: top; display: block;">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-bottom: 8px;" width="230">
                                                                        <span style="color: #000; font-size: 20px; font-weight: bold;"><input type="text" id="v5area" name="v5area"></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-bottom: 10px;" width="230">
                                                                        <p style="margin: 0; font-size: 16px; color: #000; line-height: 1.4;">
                                                                            <textarea type="text" id="v5data" name="v5data" style="height:70px;">暱稱：OO&#13;&#10;星座：OO&#13;&#10;血型：OO</textarea>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="230">
                                                                        <input type="hidden" id="v5photov" name="v5photov"><input type="hidden" name="v5num" id="v5num"><input type="text" name="v5" id="v5" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v5">
                                                                    </td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </td>

                                                    <td width="30"></td>

                                                    <!-- 會員照 -->
                                                    <td width="230">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="230">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding-bottom: 15px;" width="230">
                                                                        <img id="v6photo" style="max-width: 230px; height: 180px;" src="" style="vertical-align: top; display: block;">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-bottom: 8px;" width="230">
                                                                        <span style="color: #000; font-size: 20px; font-weight: bold;"><input type="text" id="v6area" name="v6area"></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-bottom: 10px;" width="230">
                                                                        <p style="margin: 0; font-size: 16px; color: #000; line-height: 1.4;">
                                                                            <textarea type="text" id="v6data" name="v6data" style="height:70px;">暱稱：OO&#13;&#10;星座：OO&#13;&#10;血型：OO</textarea>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="230">
                                                                        <input type="hidden" id="v6photov" name="v6photov"><input type="hidden" name="v6num" id="v6num"><input type="text" name="v6" id="v6" style="width:80px;"><input type="button" class="btn vnum" value="讀" style="margin-top:-8px;" data-num="v6">
                                                                    </td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="https://www.singleparty.com.tw/edm/images/sp_footer_title.png" border="0" alt="跨平台約會聯合服務會館" style="vertical-align: top; display: block;"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width="800" border="0" cellpadding="0" cellspacing="0" style="font-size: 0;">
                                            <tbody>
                                                <tr>
                                                    <td><a href="https://www.singleparty.com.tw/index.asp?cc=singleparty_EDM_E" style="border: 0; outline: 0; text-decoration: none;"><img src="https://www.singleparty.com.tw/edm/images/sp_footer_sp.png" border="0" alt="約會專家" style="vertical-align: top; display: block"></a></td>
                                                    <td><a href="https://www.springclub.com.tw/?cc=singleparty_EDM_E" style="border: 0; outline: 0; text-decoration: none;"><img src="https://www.singleparty.com.tw/edm/images/sp_footer_spring.png" border="0" alt="春天會館" style="vertical-align: top; display: block"></a></td>
                                                    <td><a href="https://www.funtour.com.tw/?cc=singleparty_EDM_E" style="border: 0; outline: 0; text-decoration: none;"><img src="https://www.singleparty.com.tw/edm/images/sp_footer_fun.png" border="0" alt="好好玩旅行社" style="vertical-align: top; display: block"></a></td>
                                                    <td><a href="https://www.datemenow.com.tw/?cc=singleparty_EDM_E" style="border: 0; outline: 0; text-decoration: none;"><img src="https://www.singleparty.com.tw/edm/images/sp_footer_dmn.png" border="0" alt="datemenow" style="vertical-align: top; display: block"></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="https://www.singleparty.com.tw/edm/images/sp_footer_add.png" border="0" alt="約會專家聯合服務會館地址" style="vertical-align: top; display: block;"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width="800" border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td><img src="https://www.singleparty.com.tw/edm/images/sp_footer_bar.png" border="0" alt="約會專家border" style="vertical-align: top; display: block;"></td>
                                                    <td><a href="https://www.facebook.com/%E7%B4%84%E6%9C%83%E5%B0%88%E5%AE%B6Single-Party-1702184089840480/?cc=singleparty_EDM_E"><img src="https://www.singleparty.com.tw/edm/images/sp_footer_fb.png" alt="facebook-icon" border="0" style="vertical-align: top; display: block;"></a></td>
                                                    <td><a href="https://www.youtube.com/channel/UCvNiE9iw31K4eNaot5IwVSA?cc=singleparty_EDM_E"><img src="https://www.singleparty.com.tw/edm/images/sp_footer_yt.png" alt="youtube-icon" border="0" style="vertical-align: top; display: block;"></a></td>
                                                    <td><a href="https://www.instagram.com/singlepartyhigh/?cc=singleparty_EDM_E"><img src="https://www.singleparty.com.tw/edm/images/sp_footer_ig.png" alt="instagram-icon" border="0" style="vertical-align: top; display: block;"></a></td>
                                                    <td><img src="https://www.singleparty.com.tw/edm/images/sp_footer_bar.png" border="0" alt="約會專家border" style="vertical-align: top; display: block;"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="https://www.singleparty.com.tw/contact.asp?cc=singleparty_EDM_E"><img src="https://www.singleparty.com.tw/edm/images/sp_footer_copyright.png" alt="單身聯誼,婚友聯誼社 copyright @約會專家" border="0" style="vertical-align: top; display: block;"></a></td>
                                </tr>
                            </tbody>
                        </table>

                        <input type="hidden" name="sextype" id="sextype" value="<?php echo SqlFilter($_REQUEST["sextype"], "int"); ?>">
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
                $thisnum = $("#" + $thisid + "num"),
                $thisphoto = $("#" + $thisid + "photo"),
                $thisarea = $("#" + $thisid + "area"),
                $thisdata = $("#" + $thisid + "data"),
                $thisnumv = $("#" + $thisid);
            if (!$thisnumv.val()) {
                alert("請輸入編號。");
                $thisnumv.focus();
                return false;
            }

            $.ajax({
                url: "singleweb_fun21.php?st=read&num=" + $thisnumv.val(),
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
                        if ($v1.indexOf("photo/") >= 0) {
                            $("#" + $thisid + "photov").val("https://www.singleparty.com.tw/dphoto/" + $v1);
                            $thisphoto.attr("src", "https://www.singleparty.com.tw/dphoto/" + $v1);
                        } else {
                            $("#" + $thisid + "photov").val("https://www.singleparty.com.tw/photo/" + $v1);
                            $thisphoto.attr("src", "https://www.singleparty.com.tw/photo/" + $v1);
                        }
                        $thisarea.val($v2);
                        $thisdata.val($v3.replace(/<br\s*[\/]?>/gi, "\n"));
                        //	relaod_data($("#"+$thisid+"div"), $v1, $v2, $v3, $thisnum.val());
                    }
                }
            });
        });
    });

    function rndget(s) {
        var $outmsg = $("#rndgetmsg");
        $outmsg.html("開始讀取");

        $.ajax({
            url: "singleweb_fun21.php?st=rndget&sex=" + s,
        }).done(function(msg) {
            if (msg == "err") {
                $outmsg.html("讀取失敗或已無數據");
            } else if (msg.indexOf(",") <= 0) {
                $outmsg.html("讀取失敗或已無數據");
            } else {
                $outmsg.html("正在接收數據");

                setTimeout(function() {
                    $outmsg.html("數據渲染");
                    $.each(msg.split(","), function(index, value) {
                        if ($("#v" + (index + 1)).length > 0) {
                            $("#v" + (index + 1)).val(value);
                        }

                        setTimeout(function() {
                            $outmsg.html("數據載入");
                            $(".vnum").each(function(ii, vv) {
                                $thisid = $(this).data("num");

                                if ($("#" + $thisid).val()) $(this).trigger("click");

                            });

                            setTimeout(function() {
                                var d = new Date();
                                $outmsg.html("載入完成 - " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds());
                            }, 2000);
                        }, 1000);

                    });
                }, 1000);
            }
        });

    }
</script>