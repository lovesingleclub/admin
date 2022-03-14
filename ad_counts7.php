<?php
    /*****************************************/ 
    //檔案名稱：ad_counts7.php
    //後台對應位置：管理系統/網路名單圖表
    //改版日期：2022.1.11
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");
    require_once("./include/_top.php");
    require_once("./include/_sidebar.php");

    //程式開始 *****
	if($_SESSION["MM_Username"] == "" ){ 
        call_alert("請重新登入。","login.php",0);
    }
    if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["funtourpm"] != 1){
        call_alert("您沒有查看此頁的權限。","login.php",0);
    }

    $start_time = SqlFilter($_REQUEST["start_time"],"tab");
    $end_time = SqlFilter($_REQUEST["end_time"],"tab");

    
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li class="active">網路名單圖表</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>網路名單圖表</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form action="?st=send" method="post" name="counts_form" id="counts_form" onsubmit="return check_form()">
                    <p>
                        <input type="text" name="start_time" id="start_time" class="datepicker" autocomplete="off" placeholder="開始時間" value="<?php echo $start_time; ?>">　～　<input type="text" name="end_time" id="end_time" class="datepicker" autocomplete="off" placeholder="結束時間" value="<?php echo $end_time; ?>">　<select id="fasttime" onchange="fast_sel_time($(this).val())">
                            <option value="">快速選擇</option>
                            <option value="0">今天</option>
                            <option value="1">昨天</option>
                            <option value="2">前天</option>
                            <option value="3">本周</option>
                            <option value="4">上周</option>
                            <option value="5">本月</option>
                            <option value="6">上月</option>
                            <option value="7">今年</option>
                            <option value="8">去年</option>
                        </select>&nbsp;<input id="send_submit" type="submit" class="btn btn-default" value="送出">
                    </p>
                </form>
                <?php 
                    if($start_time == "" || $end_time == ""){
                        echo "請先選擇時間";
                    }else{
                        if(!chkDate($start_time)){
                            call_alert("開始時間錯誤。", 0, 0);                            
                        }
                        if(!chkDate($end_time)){
                            call_alert("結束時間錯誤。", 0, 0);                            
                        }
                        if(ceil((strtotime($end_time) - strtotime($start_time))/ (60*60*24)) > 365){
                            call_alert("時間區間需在 12 個月內。", 0, 0); 
                        }
                        if(strtotime($end_time) - strtotime($start_time) < 0){
                            call_alert("沒有資料或日期選擇不正確。", 0, 0);
                        }
                        $canshow = 1;
                    }                    
                ?>
                <div>'網站行銷','春天網站','DMN名單','DMN網站','約會專家','網站活動','網站排約','行銷活動','迷你約'</div>
                <div style="text-align:center;margin:10px">
                    <div id="chart_div"></div>
                </div>


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

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script language="JavaScript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var data = new google.visualization.DataTable();
        data.addColumn('date', 'Time of Day');
        data.addColumn('number', 'Rating');
        <?php 
            if($canshow == 1){
                $SQL = "get_member_come '".$start_time."','".$end_time."'";
                $rs = $SPConn->prepare($SQL);
                $rs->execute();
                $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                if($result){
                    $ii = 1;
                    echo "data.addRows([";
                    foreach($result as $re){
                        $ddr = $ddr."[new Date(".date("Y",strtotime($re["theDate"])).", ".date("m",strtotime($re["theDate"])).", ".date("d",strtotime($re["theDate"]))."), ".$re["theValue"]."],".PHP_EOL;
                        $ii = $ii+1;
                    }
                    echo $ddr;
					echo "]);".PHP_EOL;
                }
            }
        ?>
        var options = {
            title: '',
            width: '100%',
            height: 600,
            hAxis: {
                format: 'yy/M/d',
                gridlines: {
                    count: 15
                }
            },
            vAxis: {
                gridlines: {
                    color: 'none'
                },
                minValue: 0
            }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

        chart.draw(data, options);

        var button = document.getElementById('change');

        button.onclick = function() {

            // If the format option matches, change it to the new option,
            // if not, reset it to the original format.
            options.hAxis.format === 'M/d/yy' ?
                options.hAxis.format = 'MMM dd, yyyy' :
                options.hAxis.format = 'M/d/yy';

            chart.draw(data, options);
        };
    }
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

        return true;
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
            case "0":
                $("#start_time").val(GetDateStr(0));
                $("#end_time").val(GetDateStr(0));
                break;
            case "1":
                $("#start_time").val(GetDateStr(-1));
                $("#end_time").val(GetDateStr(-1));
                break;
            case "2":
                $("#start_time").val(GetDateStr(-2));
                $("#end_time").val(GetDateStr(-2));
                break;
            case "3":
                $("#start_time").val(getThisWeekDate()[0]);
                $("#end_time").val(getThisWeekDate()[1]);
                break;
            case "4":
                $("#start_time").val(getPrevWeekDate()[0]);
                $("#end_time").val(getPrevWeekDate()[1]);
                break;
            case "5":
                $("#start_time").val(getThisMonthDate()[0]);
                $("#end_time").val(getThisMonthDate()[1]);
                break;
            case "6":
                $("#start_time").val(getPrevMonthDate()[0]);
                $("#end_time").val(getPrevMonthDate()[1]);
                break;
            case "7":
                $("#start_time").val(getThisYearDate()[0]);
                $("#end_time").val(getThisYearDate()[1]);
                break;
            case "8":
                $("#start_time").val(getPrevYearDate()[0]);
                $("#end_time").val(getPrevYearDate()[1]);
                break;
        }
    }
</script>