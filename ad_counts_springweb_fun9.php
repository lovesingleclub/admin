<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="js/jquery-1.8.3.js" type="text/javascript"></script>
<!-- JSCal2 -->
<script src="jscal/js/jscal2.js" type="text/javascript"></script>
<script src="jscal/js/lang/tw.js" type="text/javascript"></script>
<link href="jscal/css/jscal2.css" rel="stylesheet" type="text/css" />
<link href="jscal/css/border-radius.css" rel="stylesheet" type="text/css" />
<link href="jscal/css/steel/steel.css" rel="stylesheet" type="text/css" />

<body text="#333333" leftmargin="0" topmargin="0">
    <table width="1530" border="0" cellspacing="0">
        <tr>
            <td width="30" valign="top"> </td>
            <td width="1500" valign="top">

                <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#A9C7C7">
                    <tr>
                        <td bgcolor="#C9DCDC">
                            <table width="900" border="0">
                                <tr>
                                    <td width="358"><strong>
                                            <font size="3">微電影活動統計資料</font>
                                        </strong></td>
                                    <td width="516"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table width="100%" border="0" cellpadding="3" style="line-height:20px;BORDER: #A9C7C7 1px solid">
                    <form action="#send" method="post" name="counts_form" id="counts_form" onsubmit="return check_form()">
                        <tr>
                            <td width="80" height="200" align="left">
                                <font size="2">請選擇時段：</font>
                            </td>
                            <td align="left" width="80%"><input type="text" name="start_time" id="start_time" size=10>　～　<input type="text" name="end_time" id="end_time" size=10>　<input id="send_submit" type="submit" value="送出"></td>
                        </tr>

                        <tr>
                            <td style="BORDER-bottom: #747474 1px dashed">　</td>
                            <td height="100" valign="top" align="left" style="BORDER-bottom: #747474 1px dashed">
                                <input type="button" value="今天" onclick="fast_sel_time(0)">　
                                <input type="button" value="昨天" onclick="fast_sel_time(1)">　
                                <input type="button" value="前天" onclick="fast_sel_time(2)"><br>
                                <input type="button" value="本周" onclick="fast_sel_time(3)">　
                                <input type="button" value="上周" onclick="fast_sel_time(4)"><br>
                                <input type="button" value="本月" onclick="fast_sel_time(5)">　
                                <input type="button" value="上月" onclick="fast_sel_time(6)">　
                                <input type="button" value="今年" onclick="fast_sel_time(7)">　
                                <input type="button" value="去年" onclick="fast_sel_time(8)">
                            </td>
                        </tr>

                        <tr>
                            <td colspan=2 id="outdiv"></td>
                        </tr>
                        <tr>
                            <td id="outmsg" height=20 colspan=2 bgcolor="blue" style="color:white;font-size:12px;">讀取資料中...<img src='img/wait_loading.gif' align='middle'></td>
                        </tr>
                    </form>
            </td>
        </tr>
    </table>
</body>

</html>
<script type="text/javascript">
    $("#outmsg").hide();
    Date.prototype.DateAdd = function(strInterval, Number) {
        var dtTmp = this;
        switch (strInterval) {
            case 's':
                return new Date(Date.parse(dtTmp) + (1000 * Number));
            case 'n':
                return new Date(Date.parse(dtTmp) + (60000 * Number));
            case 'h':
                return new Date(Date.parse(dtTmp) + (3600000 * Number));
            case 'd':
                return new Date(Date.parse(dtTmp) + (86400000 * Number));
            case 'w':
                return new Date(Date.parse(dtTmp) + ((86400000 * 7) * Number));
            case 'q':
                return new Date(dtTmp.getFullYear(), (dtTmp.getMonth()) + Number * 3, dtTmp.getDate(), dtTmp.getHours(), dtTmp.getMinutes(), dtTmp.getSeconds());
            case 'm':
                return new Date(dtTmp.getFullYear(), (dtTmp.getMonth()) + Number, dtTmp.getDate(), dtTmp.getHours(), dtTmp.getMinutes(), dtTmp.getSeconds());
            case 'y':
                return new Date((dtTmp.getFullYear() + Number), dtTmp.getMonth(), dtTmp.getDate(), dtTmp.getHours(), dtTmp.getMinutes(), dtTmp.getSeconds());
        }
    }

    function conutice_ajax(n1, n2, n3, n4) {
        setTimeout(function() {
            $.ajax({
                url: 'ad_counts_springweb_fun9.php?st=send',
                data: {
                    start_time: n1,
                    ostart_time: n2,
                    end_time: n3,
                    nowdays: n4
                },
                error: function(xhr) {
                    alert('Ajax request 發生錯誤');
                    button_set(1);
                },
                success: function(response) {
                    if ($("#outtable")) $("#outtable").append(response);
                    else $("#outdiv").html(response);
                }
            })
        }, 3000);
    }

    function check_form() {
        if (!$("#start_time").val()) {
            alert("請輸入開始時段。");
            $("#start_time").focus();
            return false;
        }
        if (!$("#end_time").val()) {
            alert("請輸入結束時段。");
            $("#end_time").focus();
            return false;
        }
        if (isNaN(Date.parse($("#start_time").val()))) {
            alert("你輸入的開始時段不是日期格式。");
            $("#start_time").val("");
            $("#start_time").focus();
            return false;
        }
        if (isNaN(Date.parse($("#end_time").val()))) {
            alert("你輸入的結束時段不是日期格式。");
            $("#end_time").val("");
            $("#end_time").focus();
            return false;
        }
        button_set(0);
        if ($("#outtable")) $("#outtable").html("");
        $("#outmsg").html("讀取資料中...<img src='img/wait_loading.gif' align='middle'>");
        $("#outmsg").show();
        $.ajax({
            url: 'ad_counts_springweb_fun9.php?st=send',
            data: {
                start_time: $("#start_time").val(),
                ostart_time: $("#start_time").val(),
                end_time: $("#end_time").val()
            },
            error: function(xhr) {
                alert('Ajax request 發生錯誤');
                button_set(1);
            },
            success: function(response) {
                $("#outdiv").html(response);
            }
        });

        return false;
    }

    function outmsg_show(msg) {
        $("#outmsg").html(msg);
        $('html, body').animate({
            scrollTop: $('body').height()
        }, 800);
    }

    function button_set(n) {
        if (n) {
            $(":button").attr("disabled", false);
            $(":submit").attr("disabled", false);
        } else {
            $(":button").attr("disabled", true);
            $(":submit").attr("disabled", true);
        }
    }

    function GetDateStr(AddDayCount) {
        var dd = new Date();
        dd.setDate(dd.getDate() + AddDayCount);
        return dd.pattern("yyyy-MM-dd");
    }
    Date.prototype.pattern = function(fmt) {
        var o = {
            "M+": this.getMonth() + 1, //月份     
            "d+": this.getDate(), //日     
            "h+": this.getHours() % 12 == 0 ? 12 : this.getHours() % 12, //小?     
            "H+": this.getHours(), //小?     
            "m+": this.getMinutes(), //分     
            "s+": this.getSeconds(), //秒     
            "q+": Math.floor((this.getMonth() + 3) / 3), //季度     
            "S": this.getMilliseconds() //毫秒     
        };
        var week = {
            "0": "\u65e5",
            "1": "\u4e00",
            "2": "\u4e8c",
            "3": "\u4e09",
            "4": "\u56db",
            "5": "\u4e94",
            "6": "\u516d"
        };
        if (/(y+)/.test(fmt)) {
            fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
        }
        if (/(E+)/.test(fmt)) {
            fmt = fmt.replace(RegExp.$1, ((RegExp.$1.length > 1) ? (RegExp.$1.length > 2 ? "\u661f\u671f" : "\u5468") : "") + week[this.getDay() + ""]);
        }
        for (var k in o) {
            if (new RegExp("(" + k + ")").test(fmt)) {
                fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
            }
        }
        return fmt;
    }
    var today = new Date();
    var _day = 1000 * 60 * 60 * 24;

    this.getThisWeekDate = getThisWeekDate;
    this.getPrevWeekDate = getPrevWeekDate;
    this.getThisMonthDate = getThisMonthDate;
    this.getPrevMonthDate = getPrevMonthDate;
    this.getThisYearDate = getThisYearDate;
    this.getPrevYearDate = getPrevYearDate;

    function getThisWeekDate() {
        // 第一天日期
        var firstDay = new Date(today - (today.getDay() - 1) * _day);
        // 最后一天日期
        var lastDay = new Date((firstDay * 1) + 6 * _day);
        return [firstDay.pattern("yyyy-MM-dd"), lastDay.pattern("yyyy-MM-dd")];
    }

    function getPrevWeekDate() {
        // 取上周?束日期
        var lastDay = new Date(today - (today.getDay()) * _day);
        // 取上周?始日期
        var firstDay = new Date((lastDay * 1) - 6 * _day);
        return [firstDay.pattern("yyyy-MM-dd"), lastDay.pattern("yyyy-MM-dd")];
    }

    function getThisMonthDate() {
        // 第一天日期
        var firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        // 最后一天日期
        var nextDat = new Date(today.getFullYear(), today.getMonth() + 1, 1);
        var lastDay = new Date(nextDat - _day);
        return [firstDay.pattern("yyyy-MM-dd"), lastDay.pattern("yyyy-MM-dd")];
    }

    function getPrevMonthDate() {
        // 第一天日期
        var firstDay = new Date(today.getFullYear(), today.getMonth() - 1, 1);
        // 最后一天日期
        var nextDat = new Date(today.getFullYear(), today.getMonth(), 1);
        var lastDay = new Date(nextDat - _day);
        return [firstDay.pattern("yyyy-MM-dd"), lastDay.pattern("yyyy-MM-dd")];
    }

    function getThisYearDate() {
        // 第一天日期
        var firstDay = new Date(today.getFullYear(), 0, 1);
        // 最后一天日期
        var nextDat = new Date(today.getFullYear() + 1, 0, 1);
        var lastDay = new Date(nextDat - _day);
        return [firstDay.pattern("yyyy-MM-dd"), lastDay.pattern("yyyy-MM-dd")];
    }

    function getPrevYearDate() {
        // 第一天日期
        var firstDay = new Date(today.getFullYear() - 1, 0, 1);
        // 最后一天日期
        var nextDat = new Date(today.getFullYear(), 0, 1);
        var lastDay = new Date(nextDat - _day);
        return [firstDay.pattern("yyyy-MM-dd"), lastDay.pattern("yyyy-MM-dd")];
    }

    function fast_sel_time(flag) {
        switch (flag) {
            case 0:
                $("#start_time").val(GetDateStr(0));
                $("#end_time").val(GetDateStr(0));
                break;
            case 1:
                $("#start_time").val(GetDateStr(-1));
                $("#end_time").val(GetDateStr(-1));
                break;
            case 2:
                $("#start_time").val(GetDateStr(-2));
                $("#end_time").val(GetDateStr(-2));
                break;
            case 3:
                $("#start_time").val(getThisWeekDate()[0]);
                $("#end_time").val(getThisWeekDate()[1]);
                break;
            case 4:
                $("#start_time").val(getPrevWeekDate()[0]);
                $("#end_time").val(getPrevWeekDate()[1]);
                break;
            case 5:
                $("#start_time").val(getThisMonthDate()[0]);
                $("#end_time").val(getThisMonthDate()[1]);
                break;
            case 6:
                $("#start_time").val(getPrevMonthDate()[0]);
                $("#end_time").val(getPrevMonthDate()[1]);
                break;
            case 7:
                $("#start_time").val(getThisYearDate()[0]);
                $("#end_time").val(getThisYearDate()[1]);
                break;
            case 8:
                $("#start_time").val(getPrevYearDate()[0]);
                $("#end_time").val(getPrevYearDate()[1]);
                break;
        }
        $("#counts_form").submit();
    }

    function show_start_time() {
        var TIME_CAL = new Calendar({
            inputField: "start_time",
            //cont: "live_time_msg",
            weekNumbers: false,
            trigger: "start_time",
            bottomBar: true,
            dateFormat: "%Y-%m-%d",
            //min: Calendar.dateToInt(today),
            selectionType: Calendar.SEL_SINGLE,
            showTime: false,
            onSelect: function() {
                var $sv = $("#start_time").val();
                $("#end_time").val($sv);
                this.hide();
            },
            onBlur: function() {
                this.hide();
            }
        });
        //TIME_CAL.hide();
        return TIME_CAL;
    }
    show_start_time();

    function show_end_time() {
        var TIME_CAL = new Calendar({
            inputField: "end_time",
            //cont: "live_time_msg",
            weekNumbers: false,
            trigger: "end_time",
            bottomBar: true,
            dateFormat: "%Y-%m-%d",
            //min: Calendar.dateToInt(today),
            selectionType: Calendar.SEL_SINGLE,
            showTime: false,
            onSelect: function() {
                this.hide();
            },
            onBlur: function() {
                this.hide();
            }
        });
        //TIME_CAL.hide();
        return TIME_CAL;
    }
    show_end_time();
</script>