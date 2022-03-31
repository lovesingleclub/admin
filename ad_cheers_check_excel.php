<?php

    /*****************************************/
    //檔案名稱：ad_cheers_check_excel.php
    //後台對應位置：管理系統/Cheers 檢校 > 校對 Excel
    //改版日期：2022.3.16
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");
    require_once("./include/_top.php");
    require_once("./include/_sidebar.php");
    setlocale(LC_ALL, "zh_CN");
    //程式開始 *****
    if ($_SESSION["MM_Username"] == "") {
        call_alert("請重新登入。", "login.php", 0);
    }

    if ($_REQUEST["n"] == "") {
        call_alert("讀取錯誤。", 0, 0);
    }

    // 檔案路徑名稱
    $filepath = "temp_excel/".$_REQUEST["n"];

    // 居住區域
    $allcity = "基隆市,台北市,新北市,宜蘭縣,桃園市,新竹縣,新竹市,苗栗縣,苗栗市,台中市,彰化縣,彰化市,南投縣,嘉義縣,嘉義市,雲林縣,台南市,高雄市,屏東縣,花蓮縣,台東縣,澎湖縣,金門縣,馬祖,綠島,蘭嶼,其他";

    if ($_REQUEST["st"] == "add" && $_REQUEST["totals"] != "" && $_REQUEST["n"] != "") {
        $totals = round($_REQUEST["totals"]);
        $totals = $totals - 1;
        if($totals >= 0){
            for($i=0;$i<=$totals;$i++){
                for($g=1;$g<=6;$g++){
                    ${"n".$g."v"} = $_REQUEST["n".$g][$i];
                    ${"n".$g."v"} = trim(${"n".$g."v"});                    
                }
                $mcn = 0;
                $lcn = 0;
                $err = 0;
                // 如果手機號碼開頭不是0則加上0
                if(substr($n3v,0,1) != "0"){
                    $n3v = "0".$n3v;
                }
                //如果手機號碼不是10位數則報錯
                if(strlen($n3v) != 10){
                    $err = 1;
                }
 
                if($n3v != "" && strlen($n3v) == 10){
                    // 手機號碼在member_data有幾個
                    $SQL = "select count(mem_auto) as tt from member_data where mem_mobile='".$n3v."'";
                    $rs = $SPConn->prepare($SQL);
                    $rs->execute();
                    $result = $rs->fetch(PDO::FETCH_ASSOC);
                    if($result){
                        $mcn = $result["tt"];
                    }
                    
                    // 手機號碼在love_keyin有幾個
                    $SQL = "select count(k_id) as tt from love_keyin where k_mobile='".$n3v."'";
                    $rs = $SPConn->prepare($SQL);
                    $rs->execute();
                    $result = $rs->fetch(PDO::FETCH_ASSOC);
                    if($result){
                        $lcn = $result["tt"];
                    }
                }

                $ccc = 0;
                // 是否處在臺灣區域
                foreach(explode(",",$allcity) as $cc){
                    if($n4v == $cc){
                        $ccc = 1;
                    }
                }

                if($ccc = 0){
                    $err = 1;
                }

                if($mcn == 0 && $lcn == 0 && $err != 1){
                    // msg_num的m_num + 1
                    $SQL = "SELECT * FROM msg_num Where m_auto = 1";
                    $rs = $SPConn->prepare($SQL);
                    $rs->execute();
                    $result = $rs->fetch(PDO::FETCH_ASSOC);
                    if($result){
                        $m_num = $result["m_num"] + 1;
                        $SQL = "UPDATE msg_num SET m_num ='".$m_num."' Where m_auto = 1";
                        $rs = $SPConn->prepare($SQL);
                        $rs->execute();
                    }

                    // 插入member_data
                    $SQL =  "INSERT INTO member_data (all_type,mem_level,mem_come,mem_num,mem_name,mem_mobile,mem_mail,mem_area,mem_address,keyin_single,mem_marry,mem_time) VALUES ('未處理','guest','2016Cheers名單','".$m_num."','".$n1v."','".$n3v."','".$n2v."','".$n4v."','".$n5v."','".$_SESSION["MM_Username"]."','未婚','".date("Y/m/d H:i:s")."')";
                    $rs = $SPConn->prepare($SQL);
                    $rs->execute();
                }
            }
        }

        DelFile($filepath);
        reURL("ad_no_mem.php");
    }
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li class="active">Cheers 檢校 - 校對 Excel</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>Cheers 檢校 - 校對 Excel</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <?php                    
                    if(file_exists($filepath)){ //如果檔案存在
                        echo "<form id='applyform' action='?st=add' method='post' target='_self'>";
                        echo "<table class='table table-striped table-bordered bootstrap-datatable'>";
                        echo "<tr><th>姓名</th><th>E-Mail</th><th>手機</th><th>居住城市</th><th>居住區</th><th>婚姻狀態</th></tr>";
                        $file = fopen($filepath,"r");     
                        $ii = 0; // 總行數
                        $h = 0; // 計算有效行數(不含空白行數)
                        while(!feof($file)){ // 逐行讀取csv檔                           
                            $column = fgetcsv($file,1000,",");                                                    
                            $err = 0; //錯誤代碼                     
                            if($ii != 0 && $column[0] != ""){ // 第一行跟空白行不讀取 
                                $h = $h + 1;                               
                                echo "<tr>";
                                for($i=0;$i<count($column);$i++){                               
                                    // 檢測編碼 $file_encoding = mb_detect_encoding($column[$i],array("ASCII","UTF-8","GB2312","GBK","BIG5"));                                    
                                    $vals = mb_convert_encoding($column[$i], "UTF-8", "big5");                             
                                    $vals = trim($vals);                                    
                                    $vals = str_replace(" ", "",$vals);                                    
                                    $ss = "95%;"; //input寬度
                                    $mcn = 0; // member_data同樣的手機號碼有多少
                                    $lcn = 0; // love_keyin同樣的手機號碼有多少

                                    // 手機
                                    if($i == 2){
                                        $mmobile = ""; // 手機號碼
                                        if($vals != ""){
                                            $vals = str_replace("_", "",$vals);
                                            $vals = str_replace("-", "",$vals);
                                        }
                                        if(substr($vals,0,1) != "0"){
                                            $vals = "0".$vals;
                                        }
                                        if(substr($vals,0,2) != "09"){
                                            $err = 1;
	  		                                $errmsg = "行動電話非09開頭";
                                        }
                                        if(strlen($vals) != 10){
                                            $err = 1;
	  		                                $errmsg = "行動電話非10碼";
                                        }
                                        $mmobile = $vals;

                                        if($mmobile != "" && strlen($mmobile) == 10){
                                            $SQL = "select count(mem_auto) as tt from member_data where mem_mobile='".$mmobile."'";
                                            $rs = $SPConn->prepare($SQL);
                                            $rs->execute();
                                            $result = $rs->fetch(PDO::FETCH_ASSOC);
                                            if($result){
                                                $mcn = $result["tt"];
                                            }

                                            $SQL = "select count(k_id) as tt from love_keyin where k_mobile='".$mmobile."'";
                                            $rs = $SPConn->prepare($SQL);
                                            $rs->execute();
                                            $result = $rs->fetch(PDO::FETCH_ASSOC);
                                            if($result){
                                                $lcn = $result["tt"];
                                            }
                                        }

                                        if($mcn > 0 || $lcn > 0){
                                            $err = 1;
                                            $errmsg = "重覆：會員資料庫 ".$mcn." 筆, 活動排約 ".$lcn." 筆";
                                        }
                                    }

                                    // 區域
                                    if($i == 3){
                                        $ccc = 0;
                                        foreach(explode(",",$allcity) as $cc){
                                            if($vals == $cc){
                                                $ccc = 1;
                                            }
                                        }
                                        if($ccc == 0){
                                            $err = 1;
	  		                                $errmsg = "居住地不在目前系統地區中";
                                        }
                                    }
                                    //建立表格
                                    echo "<td>";
                                    echo "<input type='text' name='n".($i+1)."[]' id='n".($i+1)."' value='".$vals."' style='width:".$ss.";'>";
                                    echo "</td>";
                                    if($i >= 7){
                                        $err = 1;
   	                                    $errmsg = "超過格數";
                                    }
                                }
                                echo "</tr>";
                                if($err == 1){
                                    echo "<tr><td colspan=7 style='color:red'>上行有問題 - ".$errmsg."</td></tr>";
                                }else{
                                    echo "<tr><td colspan=7 style='color:green'>上行通過檢驗".$mmobile."</td></tr>";
                                }                                
                            }
                            $ii = $ii + 1;
                        }
                        echo "<tr><input type='hidden' name='totals' value='".$h."'><input type='hidden' name='n' value='".$_REQUEST["n"]."'><td colspan=18 align='left'><input type='submit' value='匯入'></td></tr></table>";
                        echo "</form>";
                        fclose($file);                        
                    }
                ?>
            </div>
        </div>
        <!--/span-->

    </div>
    <!--/row-->


    <hr>
    </div>
    <!--/.fluid-container-->
</section>
<!-- /MIDDLE -->

<?php
require_once("./include/_bottom.php")
?>