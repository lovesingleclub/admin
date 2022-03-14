<?php
    /*****************************************/ 
    //檔案名稱：ad_market2.php
    //後台對應位置：管理系統/行銷活動統計-特1
    //改版日期：2022.1.5
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

    $marking_list = SqlFilter($_REQUEST["marking_list"],"tab");  

?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li class="active">行銷活動統計-特1</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>行銷活動統計-特1</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form action="?st=send" method="post" name="counts_form" id="counts_form" onsubmit="return check_form()">
                    <p>請選擇時段：<input type="text" name="start_time" id="start_time" class="datepicker" autocomplete="off" value="<?php echo SqlFilter($_REQUEST["start_time"],"tab"); ?>">　～　<input type="text" name="end_time" id="end_time" class="datepicker" autocomplete="off" value="<?php echo SqlFilter($_REQUEST["end_time"],"tab"); ?>">　<select id="fasttime" onchange="fast_sel_time($(this).val())">
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
                        </select></p>
                    <p>
                        <!--<a data-toggle="collapse" href="#marking_action_list" data-parent="#menu_group" class="btn btn-info">活動選擇</a>--><input id="send_submit" type="submit" class="btn btn-default" value="送出">
                    <div class="" id="marking_action_list">
                        <?php 
                            if($_REQUEST["st"] == "send"){
                                $stime = Date_EN(SqlFilter($_REQUEST["start_time"],"tab"),1) . " 00:00";
                                $etime = Date_EN(SqlFilter($_REQUEST["end_time"],"tab"),1) . " 23:59";
                                echo "<p>".$stime." ~ ".$etime."</p>";
                                echo "<table class='table table-striped table-bordered bootstrap-datatable'>";
                                echo "<tr><td colspan=30>你的戀愛能力有多強</td></tr>";
                                echo "<tr>";
                                echo "<td>18-25-男</td>";
                                echo "<td>18-25-女</td>";
                                echo "<td>26-30-男</td>";
                                echo "<td>26-30-女</td>";
                                echo "<td>31-35-男</td>";
                                echo "<td>31-35-女</td>";
                                echo "<td>36-40-男</td>";
                                echo "<td>36-40-女</td>";
                                echo "<td>41-45-男</td>";
                                echo "<td>41-45-女</td>";
                                echo "<td>46-50-男</td>";
                                echo "<td>46-50-女</td>";
                                echo "<td>51up男</td>";
                                echo "<td>51up女</td>";
                                echo "<td>博士-男</td>";
                                echo "<td>博士-女</td>";
                                echo "<td>碩士-男</td>";
                                echo "<td>碩士-女</td>";
                                echo "<td>大學-男</td>";
                                echo "<td>大學-女</td>";
                                echo "<td>專科-男</td>";
                                echo "<td>專科-女</td>";
                                echo "<td>高職-男</td>";
                                echo "<td>高職-女</td>";
                                echo "<td>高中-男</td>";
                                echo "<td>高中-女</td>";
                                echo "<td>國中-男</td>";
                                echo "<td>國中-女</td>";
                                echo "<td>其他-男</td>";
                                echo "<td>其他-女</td>";            
                                echo "</tr>";

                                $SQL = "select ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '2000' and '2002' and mem_sex='男' THEN mem_auto END), 0) AS 'y18-25b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '2000' and '2002' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y18-25g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1990' and '1994' and mem_sex='男' THEN mem_auto END), 0) AS 'y26-30b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1990' and '1994' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y26-30g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1985' and '1989' and mem_sex='男' THEN mem_auto END), 0) AS 'y31-35b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1985' and '1989' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y31-35g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1980' and '1984' and mem_sex='男' THEN mem_auto END), 0) AS 'y36-40b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1980' and '1984' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y36-40g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1975' and '1979' and mem_sex='男' THEN mem_auto END), 0) AS 'y41-45b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1975' and '1979' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y41-45g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1970' and '1974' and mem_sex='男' THEN mem_auto END), 0) AS 'y46-50b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1970' and '1974' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y46-50g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by <= 1969 and mem_sex='男' THEN mem_auto END), 0) AS 'y51upb', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by <= 1969 and mem_sex<>'男' THEN mem_auto END), 0) AS 'y51upg', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='博士' and mem_sex='男' THEN mem_auto END), 0) AS 's1b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='博士' and mem_sex<>'男' THEN mem_auto END), 0) AS 's1g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='碩士' and mem_sex='男' THEN mem_auto END), 0) AS 's2b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='碩士' and mem_sex<>'男' THEN mem_auto END), 0) AS 's2g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='大學' and mem_sex='男' THEN mem_auto END), 0) AS 's3b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='大學' and mem_sex<>'男' THEN mem_auto END), 0) AS 's3g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='專科' and mem_sex='男' THEN mem_auto END), 0) AS 's4b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='專科' and mem_sex<>'男' THEN mem_auto END), 0) AS 's4g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高職' and mem_sex='男' THEN mem_auto END), 0) AS 's5b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高職' and mem_sex<>'男' THEN mem_auto END), 0) AS 's5g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高中' and mem_sex='男' THEN mem_auto END), 0) AS 's6b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高中' and mem_sex<>'男' THEN mem_auto END), 0) AS 's6g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='國中' and mem_sex='男' THEN mem_auto END), 0) AS 's7b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='國中' and mem_sex<>'男' THEN mem_auto END), 0) AS 's7g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='其他' and mem_sex='男' THEN mem_auto END), 0) AS 's8b', ";
                                $SQL .=  "ISNULL(COUNT(CASE WHEN mem_school ='其他' and mem_sex<>'男' THEN mem_auto END), 0) AS 's8g' ";
                                $SQL .= " from member_data where mem_come='行銷活動' and mem_come2 like '你的戀愛能力有多強%' and mem_time between '".$stime."' and '".$etime."'";
                                $rs = $SPConn->prepare($SQL);
                                $rs->execute();
                                $result = $rs->fetch(PDO::FETCH_ASSOC);
                                if($result){
                                    echo "<tr>";
                                    echo "<td>".$result["y18-25b"]."</td>";
                                    echo "<td>".$result["y18-25g"]."</td>";
                                    echo "<td>".$result["y26-30b"]."</td>";
                                    echo "<td>".$result["y26-30g"]."</td>";
                                    echo "<td>".$result["y31-35b"]."</td>";
                                    echo "<td>".$result["y31-35g"]."</td>";
                                    echo "<td>".$result["y36-40b"]."</td>";
                                    echo "<td>".$result["y36-40g"]."</td>";
                                    echo "<td>".$result["y41-45b"]."</td>";
                                    echo "<td>".$result["y41-45g"]."</td>";
                                    echo "<td>".$result["y46-50b"]."</td>";
                                    echo "<td>".$result["y46-50g"]."</td>";
                                    echo "<td>".$result["y51upb"]."</td>";
                                    echo "<td>".$result["y51upg"]."</td>";
                                    echo "<td>".$result["s1b"]."</td>";
                                    echo "<td>".$result["s1g"]."</td>";
                                    echo "<td>".$result["s2b"]."</td>";
                                    echo "<td>".$result["s2g"]."</td>";
                                    echo "<td>".$result["s3b"]."</td>";
                                    echo "<td>".$result["s3g"]."</td>";
                                    echo "<td>".$result["s4b"]."</td>";
                                    echo "<td>".$result["s4g"]."</td>";
                                    echo "<td>".$result["s5b"]."</td>";
                                    echo "<td>".$result["s5g"]."</td>";
                                    echo "<td>".$result["s6b"]."</td>";
                                    echo "<td>".$result["s6g"]."</td>";
                                    echo "<td>".$result["s7b"]."</td>";
                                    echo "<td>".$result["s7g"]."</td>";
                                    echo "<td>".$result["s8b"]."</td>";
                                    echo "<td>".$result["s8g"]."</td>";
                                    echo "</tr>";
                                }


                                echo "<tr><td colspan=30>愛情實驗室</td></tr>";
                                echo "<tr>";
                                echo "<td>18-25-男</td>";
                                echo "<td>18-25-女</td>";
                                echo "<td>26-30-男</td>";
                                echo "<td>26-30-女</td>";
                                echo "<td>31-35-男</td>";
                                echo "<td>31-35-女</td>";
                                echo "<td>36-40-男</td>";
                                echo "<td>36-40-女</td>";
                                echo "<td>41-45-男</td>";
                                echo "<td>41-45-女</td>";
                                echo "<td>46-50-男</td>";
                                echo "<td>46-50-女</td>";
                                echo "<td>51up男</td>";
                                echo "<td>51up女</td>";
                                echo "<td>博士-男</td>";
                                echo "<td>博士-女</td>";
                                echo "<td>碩士-男</td>";
                                echo "<td>碩士-女</td>";
                                echo "<td>大學-男</td>";
                                echo "<td>大學-女</td>";
                                echo "<td>專科-男</td>";
                                echo "<td>專科-女</td>";
                                echo "<td>高職-男</td>";
                                echo "<td>高職-女</td>";
                                echo "<td>高中-男</td>";
                                echo "<td>高中-女</td>";
                                echo "<td>國中-男</td>";
                                echo "<td>國中-女</td>";
                                echo "<td>其他-男</td>";
                                echo "<td>其他-女</td>";            
                                echo "</tr>";

                                $SQL = "select ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '2000' and '2002' and mem_sex='男' THEN mem_auto END), 0) AS 'y18-25b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '2000' and '2002' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y18-25g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1990' and '1994' and mem_sex='男' THEN mem_auto END), 0) AS 'y26-30b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1990' and '1994' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y26-30g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1985' and '1989' and mem_sex='男' THEN mem_auto END), 0) AS 'y31-35b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1985' and '1989' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y31-35g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1980' and '1984' and mem_sex='男' THEN mem_auto END), 0) AS 'y36-40b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1980' and '1984' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y36-40g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1975' and '1979' and mem_sex='男' THEN mem_auto END), 0) AS 'y41-45b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1975' and '1979' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y41-45g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1970' and '1974' and mem_sex='男' THEN mem_auto END), 0) AS 'y46-50b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1970' and '1974' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y46-50g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by <= 1969 and mem_sex='男' THEN mem_auto END), 0) AS 'y51upb', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by <= 1969 and mem_sex<>'男' THEN mem_auto END), 0) AS 'y51upg', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='博士' and mem_sex='男' THEN mem_auto END), 0) AS 's1b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='博士' and mem_sex<>'男' THEN mem_auto END), 0) AS 's1g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='碩士' and mem_sex='男' THEN mem_auto END), 0) AS 's2b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='碩士' and mem_sex<>'男' THEN mem_auto END), 0) AS 's2g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='大學' and mem_sex='男' THEN mem_auto END), 0) AS 's3b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='大學' and mem_sex<>'男' THEN mem_auto END), 0) AS 's3g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='專科' and mem_sex='男' THEN mem_auto END), 0) AS 's4b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='專科' and mem_sex<>'男' THEN mem_auto END), 0) AS 's4g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高職' and mem_sex='男' THEN mem_auto END), 0) AS 's5b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高職' and mem_sex<>'男' THEN mem_auto END), 0) AS 's5g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高中' and mem_sex='男' THEN mem_auto END), 0) AS 's6b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高中' and mem_sex<>'男' THEN mem_auto END), 0) AS 's6g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='國中' and mem_sex='男' THEN mem_auto END), 0) AS 's7b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='國中' and mem_sex<>'男' THEN mem_auto END), 0) AS 's7g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='其他' and mem_sex='男' THEN mem_auto END), 0) AS 's8b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='其他' and mem_sex<>'男' THEN mem_auto END), 0) AS 's8g' ";
                                $SQL .= " from member_data where mem_come='行銷活動' and mem_come2 like '愛情實驗室%' and mem_time between '".$stime."' and '".$etime."'";
                                $rs = $SPConn->prepare($SQL);
                                $rs->execute();
                                $result = $rs->fetch(PDO::FETCH_ASSOC);
                                if($result){
                                    echo "<tr>";
                                    echo "<td>".$result["y18-25b"]."</td>";
                                    echo "<td>".$result["y18-25g"]."</td>";
                                    echo "<td>".$result["y26-30b"]."</td>";
                                    echo "<td>".$result["y26-30g"]."</td>";
                                    echo "<td>".$result["y31-35b"]."</td>";
                                    echo "<td>".$result["y31-35g"]."</td>";
                                    echo "<td>".$result["y36-40b"]."</td>";
                                    echo "<td>".$result["y36-40g"]."</td>";
                                    echo "<td>".$result["y41-45b"]."</td>";
                                    echo "<td>".$result["y41-45g"]."</td>";
                                    echo "<td>".$result["y46-50b"]."</td>";
                                    echo "<td>".$result["y46-50g"]."</td>";
                                    echo "<td>".$result["y51upb"]."</td>";
                                    echo "<td>".$result["y51upg"]."</td>";
                                    echo "<td>".$result["s1b"]."</td>";
                                    echo "<td>".$result["s1g"]."</td>";
                                    echo "<td>".$result["s2b"]."</td>";
                                    echo "<td>".$result["s2g"]."</td>";
                                    echo "<td>".$result["s3b"]."</td>";
                                    echo "<td>".$result["s3g"]."</td>";
                                    echo "<td>".$result["s4b"]."</td>";
                                    echo "<td>".$result["s4g"]."</td>";
                                    echo "<td>".$result["s5b"]."</td>";
                                    echo "<td>".$result["s5g"]."</td>";
                                    echo "<td>".$result["s6b"]."</td>";
                                    echo "<td>".$result["s6g"]."</td>";
                                    echo "<td>".$result["s7b"]."</td>";
                                    echo "<td>".$result["s7g"]."</td>";
                                    echo "<td>".$result["s8b"]."</td>";
                                    echo "<td>".$result["s8g"]."</td>";
                                    echo "</tr>";
                                }

                                echo "<tr><td colspan=30>2020年12星座愛情指數測驗</td></tr>";
                                echo "<tr>";
                                echo "<td>18-25-男</td>";
                                echo "<td>18-25-女</td>";
                                echo "<td>26-30-男</td>";
                                echo "<td>26-30-女</td>";
                                echo "<td>31-35-男</td>";
                                echo "<td>31-35-女</td>";
                                echo "<td>36-40-男</td>";
                                echo "<td>36-40-女</td>";
                                echo "<td>41-45-男</td>";
                                echo "<td>41-45-女</td>";
                                echo "<td>46-50-男</td>";
                                echo "<td>46-50-女</td>";
                                echo "<td>51up男</td>";
                                echo "<td>51up女</td>";
                                echo "<td>博士-男</td>";
                                echo "<td>博士-女</td>";
                                echo "<td>碩士-男</td>";
                                echo "<td>碩士-女</td>";
                                echo "<td>大學-男</td>";
                                echo "<td>大學-女</td>";
                                echo "<td>專科-男</td>";
                                echo "<td>專科-女</td>";
                                echo "<td>高職-男</td>";
                                echo "<td>高職-女</td>";
                                echo "<td>高中-男</td>";
                                echo "<td>高中-女</td>";
                                echo "<td>國中-男</td>";
                                echo "<td>國中-女</td>";
                                echo "<td>其他-男</td>";
                                echo "<td>其他-女</td>";            
                                echo "</tr>";

                                $SQL = "select ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '2000' and '2002' and mem_sex='男' THEN mem_auto END), 0) AS 'y18-25b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '2000' and '2002' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y18-25g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1990' and '1994' and mem_sex='男' THEN mem_auto END), 0) AS 'y26-30b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1990' and '1994' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y26-30g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1985' and '1989' and mem_sex='男' THEN mem_auto END), 0) AS 'y31-35b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1985' and '1989' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y31-35g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1980' and '1984' and mem_sex='男' THEN mem_auto END), 0) AS 'y36-40b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1980' and '1984' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y36-40g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1975' and '1979' and mem_sex='男' THEN mem_auto END), 0) AS 'y41-45b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1975' and '1979' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y41-45g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1970' and '1974' and mem_sex='男' THEN mem_auto END), 0) AS 'y46-50b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1970' and '1974' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y46-50g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by <= 1969 and mem_sex='男' THEN mem_auto END), 0) AS 'y51upb', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by <= 1969 and mem_sex<>'男' THEN mem_auto END), 0) AS 'y51upg', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='博士' and mem_sex='男' THEN mem_auto END), 0) AS 's1b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='博士' and mem_sex<>'男' THEN mem_auto END), 0) AS 's1g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='碩士' and mem_sex='男' THEN mem_auto END), 0) AS 's2b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='碩士' and mem_sex<>'男' THEN mem_auto END), 0) AS 's2g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='大學' and mem_sex='男' THEN mem_auto END), 0) AS 's3b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='大學' and mem_sex<>'男' THEN mem_auto END), 0) AS 's3g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='專科' and mem_sex='男' THEN mem_auto END), 0) AS 's4b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='專科' and mem_sex<>'男' THEN mem_auto END), 0) AS 's4g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高職' and mem_sex='男' THEN mem_auto END), 0) AS 's5b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高職' and mem_sex<>'男' THEN mem_auto END), 0) AS 's5g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高中' and mem_sex='男' THEN mem_auto END), 0) AS 's6b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高中' and mem_sex<>'男' THEN mem_auto END), 0) AS 's6g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='國中' and mem_sex='男' THEN mem_auto END), 0) AS 's7b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='國中' and mem_sex<>'男' THEN mem_auto END), 0) AS 's7g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='其他' and mem_sex='男' THEN mem_auto END), 0) AS 's8b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='其他' and mem_sex<>'男' THEN mem_auto END), 0) AS 's8g' ";
                                $SQL .= " from member_data where mem_come='行銷活動' and mem_come2 like '2020年12星座愛情指數測驗%' and mem_time between '".$stime."' and '".$etime."'";
                                $rs = $SPConn->prepare($SQL);
                                $rs->execute();
                                $result = $rs->fetch(PDO::FETCH_ASSOC);
                                if($result){
                                    echo "<tr>";
                                    echo "<td>".$result["y18-25b"]."</td>";
                                    echo "<td>".$result["y18-25g"]."</td>";
                                    echo "<td>".$result["y26-30b"]."</td>";
                                    echo "<td>".$result["y26-30g"]."</td>";
                                    echo "<td>".$result["y31-35b"]."</td>";
                                    echo "<td>".$result["y31-35g"]."</td>";
                                    echo "<td>".$result["y36-40b"]."</td>";
                                    echo "<td>".$result["y36-40g"]."</td>";
                                    echo "<td>".$result["y41-45b"]."</td>";
                                    echo "<td>".$result["y41-45g"]."</td>";
                                    echo "<td>".$result["y46-50b"]."</td>";
                                    echo "<td>".$result["y46-50g"]."</td>";
                                    echo "<td>".$result["y51upb"]."</td>";
                                    echo "<td>".$result["y51upg"]."</td>";
                                    echo "<td>".$result["s1b"]."</td>";
                                    echo "<td>".$result["s1g"]."</td>";
                                    echo "<td>".$result["s2b"]."</td>";
                                    echo "<td>".$result["s2g"]."</td>";
                                    echo "<td>".$result["s3b"]."</td>";
                                    echo "<td>".$result["s3g"]."</td>";
                                    echo "<td>".$result["s4b"]."</td>";
                                    echo "<td>".$result["s4g"]."</td>";
                                    echo "<td>".$result["s5b"]."</td>";
                                    echo "<td>".$result["s5g"]."</td>";
                                    echo "<td>".$result["s6b"]."</td>";
                                    echo "<td>".$result["s6g"]."</td>";
                                    echo "<td>".$result["s7b"]."</td>";
                                    echo "<td>".$result["s7g"]."</td>";
                                    echo "<td>".$result["s8b"]."</td>";
                                    echo "<td>".$result["s8g"]."</td>";
                                    echo "</tr>";
                                }

                                echo "<tr><td colspan=30>穩定交往的秘訣</td></tr>";
                                echo "<tr>";
                                echo "<td>18-25-男</td>";
                                echo "<td>18-25-女</td>";
                                echo "<td>26-30-男</td>";
                                echo "<td>26-30-女</td>";
                                echo "<td>31-35-男</td>";
                                echo "<td>31-35-女</td>";
                                echo "<td>36-40-男</td>";
                                echo "<td>36-40-女</td>";
                                echo "<td>41-45-男</td>";
                                echo "<td>41-45-女</td>";
                                echo "<td>46-50-男</td>";
                                echo "<td>46-50-女</td>";
                                echo "<td>51up男</td>";
                                echo "<td>51up女</td>";
                                echo "<td>博士-男</td>";
                                echo "<td>博士-女</td>";
                                echo "<td>碩士-男</td>";
                                echo "<td>碩士-女</td>";
                                echo "<td>大學-男</td>";
                                echo "<td>大學-女</td>";
                                echo "<td>專科-男</td>";
                                echo "<td>專科-女</td>";
                                echo "<td>高職-男</td>";
                                echo "<td>高職-女</td>";
                                echo "<td>高中-男</td>";
                                echo "<td>高中-女</td>";
                                echo "<td>國中-男</td>";
                                echo "<td>國中-女</td>";
                                echo "<td>其他-男</td>";
                                echo "<td>其他-女</td>" ;           
                                echo "</tr>";

                                $SQL = "select ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '2000' and '2002' and mem_sex='男' THEN mem_auto END), 0) AS 'y18-25b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '2000' and '2002' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y18-25g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1990' and '1994' and mem_sex='男' THEN mem_auto END), 0) AS 'y26-30b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1990' and '1994' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y26-30g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1985' and '1989' and mem_sex='男' THEN mem_auto END), 0) AS 'y31-35b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1985' and '1989' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y31-35g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1980' and '1984' and mem_sex='男' THEN mem_auto END), 0) AS 'y36-40b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1980' and '1984' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y36-40g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1975' and '1979' and mem_sex='男' THEN mem_auto END), 0) AS 'y41-45b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1975' and '1979' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y41-45g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1970' and '1974' and mem_sex='男' THEN mem_auto END), 0) AS 'y46-50b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1970' and '1974' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y46-50g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by <= 1969 and mem_sex='男' THEN mem_auto END), 0) AS 'y51upb', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by <= 1969 and mem_sex<>'男' THEN mem_auto END), 0) AS 'y51upg', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='博士' and mem_sex='男' THEN mem_auto END), 0) AS 's1b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='博士' and mem_sex<>'男' THEN mem_auto END), 0) AS 's1g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='碩士' and mem_sex='男' THEN mem_auto END), 0) AS 's2b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='碩士' and mem_sex<>'男' THEN mem_auto END), 0) AS 's2g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='大學' and mem_sex='男' THEN mem_auto END), 0) AS 's3b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='大學' and mem_sex<>'男' THEN mem_auto END), 0) AS 's3g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='專科' and mem_sex='男' THEN mem_auto END), 0) AS 's4b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='專科' and mem_sex<>'男' THEN mem_auto END), 0) AS 's4g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高職' and mem_sex='男' THEN mem_auto END), 0) AS 's5b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高職' and mem_sex<>'男' THEN mem_auto END), 0) AS 's5g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高中' and mem_sex='男' THEN mem_auto END), 0) AS 's6b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高中' and mem_sex<>'男' THEN mem_auto END), 0) AS 's6g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='國中' and mem_sex='男' THEN mem_auto END), 0) AS 's7b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='國中' and mem_sex<>'男' THEN mem_auto END), 0) AS 's7g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='其他' and mem_sex='男' THEN mem_auto END), 0) AS 's8b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='其他' and mem_sex<>'男' THEN mem_auto END), 0) AS 's8g' ";
                                $SQL .= " from member_data where mem_come='行銷活動' and mem_come2 like '穩定交往的秘訣%' and mem_time between '".$stime."' and '".$etime."'";
                                $rs = $SPConn->prepare($SQL);
                                $rs->execute();
                                $result = $rs->fetch(PDO::FETCH_ASSOC);
                                if($result){
                                    echo "<tr>";
                                    echo "<td>".$result["y18-25b"]."</td>";
                                    echo "<td>".$result["y18-25g"]."</td>";
                                    echo "<td>".$result["y26-30b"]."</td>";
                                    echo "<td>".$result["y26-30g"]."</td>";
                                    echo "<td>".$result["y31-35b"]."</td>";
                                    echo "<td>".$result["y31-35g"]."</td>";
                                    echo "<td>".$result["y36-40b"]."</td>";
                                    echo "<td>".$result["y36-40g"]."</td>";
                                    echo "<td>".$result["y41-45b"]."</td>";
                                    echo "<td>".$result["y41-45g"]."</td>";
                                    echo "<td>".$result["y46-50b"]."</td>";
                                    echo "<td>".$result["y46-50g"]."</td>";
                                    echo "<td>".$result["y51upb"]."</td>";
                                    echo "<td>".$result["y51upg"]."</td>";
                                    echo "<td>".$result["s1b"]."</td>";
                                    echo "<td>".$result["s1g"]."</td>";
                                    echo "<td>".$result["s2b"]."</td>";
                                    echo "<td>".$result["s2g"]."</td>";
                                    echo "<td>".$result["s3b"]."</td>";
                                    echo "<td>".$result["s3g"]."</td>";
                                    echo "<td>".$result["s4b"]."</td>";
                                    echo "<td>".$result["s4g"]."</td>";
                                    echo "<td>".$result["s5b"]."</td>";
                                    echo "<td>".$result["s5g"]."</td>";
                                    echo "<td>".$result["s6b"]."</td>";
                                    echo "<td>".$result["s6g"]."</td>";
                                    echo "<td>".$result["s7b"]."</td>";
                                    echo "<td>".$result["s7g"]."</td>";
                                    echo "<td>".$result["s8b"]."</td>";
                                    echo "<td>".$result["s8g"]."</td>";
                                    echo "</tr>";
                                }


                                echo "<tr><td colspan=30>DMN官網名單</td></tr>";
                                echo "<tr>";
                                echo "<td>18-25-男</td>";
                                echo "<td>18-25-女</td>";
                                echo "<td>26-30-男</td>";
                                echo "<td>26-30-女</td>";
                                echo "<td>31-35-男</td>";
                                echo "<td>31-35-女</td>";
                                echo "<td>36-40-男</td>";
                                echo "<td>36-40-女</td>";
                                echo "<td>41-45-男</td>";
                                echo "<td>41-45-女</td>";
                                echo "<td>46-50-男</td>";
                                echo "<td>46-50-女</td>";
                                echo "<td>51up男</td>";
                                echo "<td>51up女</td>";
                                echo "<td>博士-男</td>";
                                echo "<td>博士-女</td>";
                                echo "<td>碩士-男</td>";
                                echo "<td>碩士-女</td>";
                                echo "<td>大學-男</td>";
                                echo "<td>大學-女</td>";
                                echo "<td>專科-男</td>";
                                echo "<td>專科-女</td>";
                                echo "<td>高職-男</td>";
                                echo "<td>高職-女</td>";
                                echo "<td>高中-男</td>";
                                echo "<td>高中-女</td>";
                                echo "<td>國中-男</td>";
                                echo "<td>國中-女</td>";
                                echo "<td>其他-男</td>";
                                echo "<td>其他-女</td>";            
                                echo "</tr>";

                                $SQL = "select ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '2000' and '2002' and mem_sex='男' THEN mem_auto END), 0) AS 'y18-25b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '2000' and '2002' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y18-25g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1990' and '1994' and mem_sex='男' THEN mem_auto END), 0) AS 'y26-30b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1990' and '1994' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y26-30g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1985' and '1989' and mem_sex='男' THEN mem_auto END), 0) AS 'y31-35b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1985' and '1989' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y31-35g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1980' and '1984' and mem_sex='男' THEN mem_auto END), 0) AS 'y36-40b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1980' and '1984' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y36-40g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1975' and '1979' and mem_sex='男' THEN mem_auto END), 0) AS 'y41-45b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1975' and '1979' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y41-45g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1970' and '1974' and mem_sex='男' THEN mem_auto END), 0) AS 'y46-50b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by between '1970' and '1974' and mem_sex<>'男' THEN mem_auto END), 0) AS 'y46-50g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by <= 1969 and mem_sex='男' THEN mem_auto END), 0) AS 'y51upb', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_by <= 1969 and mem_sex<>'男' THEN mem_auto END), 0) AS 'y51upg', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='博士' and mem_sex='男' THEN mem_auto END), 0) AS 's1b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='博士' and mem_sex<>'男' THEN mem_auto END), 0) AS 's1g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='碩士' and mem_sex='男' THEN mem_auto END), 0) AS 's2b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='碩士' and mem_sex<>'男' THEN mem_auto END), 0) AS 's2g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='大學' and mem_sex='男' THEN mem_auto END), 0) AS 's3b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='大學' and mem_sex<>'男' THEN mem_auto END), 0) AS 's3g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='專科' and mem_sex='男' THEN mem_auto END), 0) AS 's4b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='專科' and mem_sex<>'男' THEN mem_auto END), 0) AS 's4g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高職' and mem_sex='男' THEN mem_auto END), 0) AS 's5b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高職' and mem_sex<>'男' THEN mem_auto END), 0) AS 's5g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高中' and mem_sex='男' THEN mem_auto END), 0) AS 's6b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='高中' and mem_sex<>'男' THEN mem_auto END), 0) AS 's6g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='國中' and mem_sex='男' THEN mem_auto END), 0) AS 's7b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='國中' and mem_sex<>'男' THEN mem_auto END), 0) AS 's7g', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='其他' and mem_sex='男' THEN mem_auto END), 0) AS 's8b', ";
                                $SQL .= "ISNULL(COUNT(CASE WHEN mem_school ='其他' and mem_sex<>'男' THEN mem_auto END), 0) AS 's8g' ";
                                $SQL .= " from member_data where mem_come = 'DMN網站' and mem_time between '".$stime."' and '".$etime."'";
                                $rs = $SPConn->prepare($SQL);
                                $rs->execute();
                                $result = $rs->fetch(PDO::FETCH_ASSOC);
                                if($result){
                                    echo "<tr>";
                                    echo "<td>".$result["y18-25b"]."</td>";
                                    echo "<td>".$result["y18-25g"]."</td>";
                                    echo "<td>".$result["y26-30b"]."</td>";
                                    echo "<td>".$result["y26-30g"]."</td>";
                                    echo "<td>".$result["y31-35b"]."</td>";
                                    echo "<td>".$result["y31-35g"]."</td>";
                                    echo "<td>".$result["y36-40b"]."</td>";
                                    echo "<td>".$result["y36-40g"]."</td>";
                                    echo "<td>".$result["y41-45b"]."</td>";
                                    echo "<td>".$result["y41-45g"]."</td>";
                                    echo "<td>".$result["y46-50b"]."</td>";
                                    echo "<td>".$result["y46-50g"]."</td>";
                                    echo "<td>".$result["y51upb"]."</td>";
                                    echo "<td>".$result["y51upg"]."</td>";
                                    echo "<td>".$result["s1b"]."</td>";
                                    echo "<td>".$result["s1g"]."</td>";
                                    echo "<td>".$result["s2b"]."</td>";
                                    echo "<td>".$result["s2g"]."</td>";
                                    echo "<td>".$result["s3b"]."</td>";
                                    echo "<td>".$result["s3g"]."</td>";
                                    echo "<td>".$result["s4b"]."</td>";
                                    echo "<td>".$result["s4g"]."</td>";
                                    echo "<td>".$result["s5b"]."</td>";
                                    echo "<td>".$result["s5g"]."</td>";
                                    echo "<td>".$result["s6b"]."</td>";
                                    echo "<td>".$result["s6g"]."</td>";
                                    echo "<td>".$result["s7b"]."</td>";
                                    echo "<td>".$result["s7g"]."</td>";
                                    echo "<td>".$result["s8b"]."</td>";
                                    echo "<td>".$result["s8g"]."</td>";
                                    echo "</tr>";
                                }
                                echo "</table>";
                            }
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

    });
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