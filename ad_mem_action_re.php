<?php
    /*****************************************/
    //檔案名稱：ad_mem_action_re.php
    //後台對應位置：管理系統/排約紀錄功能/新增活動明細
    //改版日期：2022.3.17
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");
    // 查詢會員功能跳出視窗
    if($_REQUEST["st"] == "query_member"){
        if($_REQUEST["qkword"] != ""){
            $SQL = "select top 5 mem_branch,mem_username, mem_name, mem_mobile, mem_school, mem_by, mem_num FROM member_data WHERE mem_username+mem_mobile+mem_name+mem_phone+mem_mail like N'%".SqlFilter($_REQUEST["qkword"],"tab")."%' and mem_level='mem'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
            if($result){
                echo "<table>";
                foreach($result as $re){
                    echo "<tr><td height=30 style='font-size:13px'>".$re["mem_branch"]."會員 - <a href=\"ad_mem_service.php?mem_num=".$re["mem_num"]."\" target='_blank'>".$re["mem_name"]. "</a> - ".$re["mem_mobile"]." - ".($re["mem_by"]-1911)." 年次 - 身分證字號為：".strtoupper($re["mem_username"])." - ".$re["mem_school"]."</td></tr>";
                }
                echo "</table>";
                echo "<font size=2 color=red>最多顯示五筆資料，如筆數過多請縮小搜尋條件。</font><br><br><center><button onclick='window.close()' style='width:30%;height:30px;'>關閉視窗</button>";
                exit();
            }else{
                reURL("reload_window.php?m=查無此會員...");
            }
        }
    }
    require_once("./include/_top.php");
    require_once("./include/_sidebar.php");

    //程式開始 *****
    if ($_SESSION["MM_Username"] == "") {
        call_alert("請重新登入。", "login.php", 0);
    }
    if($_SESSION["MM_UserAuthorization"] == "single"){
        call_alert("您沒有查看此頁的權限。","login.php",0);
    }

    // 新增
    if($_REQUEST["st"] == "add"){
        if($_REQUEST["acre_user"] == ""){
            call_alert("請輸入身分證字號。",0,0);
        }
        if($_REQUEST["branch"] == "" || $_REQUEST["single"] == ""){
            call_alert("請輸入會館及秘書。",0,0);
        }

        $acre_num = $_REQUEST["acre_num"];
        // $acre_sign = $_REQUEST["y1"]."/".$_REQUEST["m1"]."/".$_REQUEST["d1"];
        $acre_sign = date("Y/m/d H:i:s");
        if(!chkDate($acre_sign)){
            call_alert("活動日期錯誤。",0,0);
        }
        $diff = (strtotime("now") - strtotime($acre_sign))/ (60*60*24);
        if($diff > 1){
            call_alert("無法輸入超過前一天的活動明細。20191028by主任",0,0);
        }

        if($acre_num == ""){
            call_alert("編號讀取錯誤。",0,0);
        }

        $SQL = "SELECT * FROM action_data Where ac_auto = '".$acre_num."'";
        $rs = $SPConn->prepare($SQL);
		$rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $ac_branch = $result["ac_branch"];
            $ac_time = $result["ac_time"];
            $ac_title = $result["ac_title"];
            $ac_note = $result["ac_note"];
            $ac_kind = $result["ac_kind"];
            $ac_kind2 = $result["ac_kind2"];
        }

        if($_REQUEST["acre_pay"] == "抵用卷" || $_REQUEST["acre_pay"] == "新抵用卷"){
            if($_REQUEST["acre_pay"] == "抵用卷" && $_REQUEST["acre_pay2"] != ""){
                if(intval($_REQUEST["acre_pay2"]) > intval($_REQUEST["ap_4"])){
                    call_alert("抵用卷不能超過服務成本。",0,0);
                }
            }

            if($_REQUEST["acre_pay"] == "新抵用卷" && $_REQUEST["acre_pay2"] != ""){
                if(intval($_REQUEST["acre_pay2"]) > intval($_REQUEST["ap_4new"])){
                    call_alert("新抵用卷不能超過新服務成本。",0,0);
                }
            }

            $mem_num = SqlFilter($_REQUEST["mem_num"],"int");
            if($mem_num != ""){
                $SQL = "select * from member_data where mem_num='".$mem_num."'";
                $rs = $SPConn->prepare($SQL);
                $rs->execute();
                $result = $rs->fetch(PDO::FETCH_ASSOC);
                if($result){
                    $mem_branch = $result["mem_branch"];
                    $mem_branch2 = $result["mem_branch2"];
                    $mem_single = $result["mem_single"];
                    $mem_single2 = $result["mem_single2"];
                    $mem_name = $result["mem_name"];
                    $mem_sex = $result["mem_sex"];
                    $mem_phone = $result["mem_phone"];
                    $mem_mobile = $result["mem_mobile"];
                }

                if($mem_branch != $ac_branch && $mem_branch2 != $ac_branch){
                    call_alert("會員受理會館[".$mem_branch."]與活動會館[".$ac_branch."]不同，請至 申請簽核 申請跨區。",0,0);
                }

                // 新增資料
                if($ac_branch == $mem_branch2){
                    $branch = $mem_branch2;
                    $single = $mem_single2;
                }else{
                    $branch = $mem_branch;
                    $single = $mem_single;
                }
                if($_REQUEST["acre_pay"] == "抵用卷"){
                    $pay_money3 = SqlFilter($_REQUEST["acre_pay2"],"int");
                }else{
                    $pay_money3 = "";
                }
                if($_REQUEST["acre_pay"] == "新抵用卷"){
                    $pay_money4 = SqlFilter($_REQUEST["acre_pay2"],"int");
                }else{
                    $pay_money4 = "";
                }
                if($_REQUEST["acre_pay"] == "抵用卷"){
                    if(is_numeric($_REQUEST["acre_pay2"]) && is_numeric($_REQUEST["ap_4"])){
                        $last_money = intval($_REQUEST["ap_4"]) - intval($_REQUEST["acre_pay2"]);
                    }else{
                        $last_money = "";
                    }                 
                }
                if($_REQUEST["acre_pay"] == "新抵用卷"){
                    if(is_numeric($_REQUEST["acre_pay2"]) && is_numeric($_REQUEST["ap_4new"])){
                        $last_money2 = intval($_REQUEST["ap_4new"]) - intval($_REQUEST["acre_pay2"]);
                    }else{
                        $last_money2 = "";
                    }       
                }
                $SQL = "INSERT INTO ad_advisory (mem_num,mem_branch,mem_single,mem_name,mem_sex,ac_branch,pay_money3,pay_money4,types,times,itimes,keyin,notes,mem_phone,mem_mobile,last_money,last_money2) VALUES ('".$mem_num."','".$branch."','".$single."','".$mem_name."','".$mem_sex."','".$ac_branch."','".$pay_money3."','".$pay_money4."','活動取款','".date("Y/m/d H:i:s")."','".$ac_title."','".$mem_phone."','".$mem_mobile."','".$last_money."','".$last_money2."')";
                $rs = $SPConn->prepare($SQL);
                $rs->execute();
                $advisory_auton = $SPConn->lastInsertId();

                $SQL = "select mem_auto, mem_username from member_data where mem_num='".$mem_num."'";
                $rs = $SPConn->prepare($SQL);
                $rs->execute();
                $result = $rs->fetch(PDO::FETCH_ASSOC);
                if($result){
                    $mem_auto = $result["mem_auto"];
	                $mem_username = $result["mem_username"];
                }

                if($mem_auto != ""){
                    if($_REQUEST["acre_pay"] == "抵用卷"){
                        $log_4 = "服務成本扣除於 ".date("Y-m-d H:i",strtotime("now"))." 退 ".SqlFilter($_REQUEST["acre_pay2"],"int")."[活動取款]".$acre_num."-".$ac_title."";
                    }else{
                        $log_4 = "新服務成本扣除於 ".date("Y-m-d H:i",strtotime("now"))." 退 ".SqlFilter($_REQUEST["acre_pay2"],"int")."[活動取款]".$acre_num."-".$ac_title."";
                    }
                    $SQL = "INSERT INTO log_data (log_time,log_num,log_fid,log_username,log_name,log_branch,log_single,log_1,log_2,log_4,log_5,log_service) VALUES ('".date("Y/m/d H:i:s")."','".$mem_auto."','".$mem_username."','".$mem_name."','".SingleName($_SESSION["MM_Username"],"normal")."','".$_SESSION["branch"]."','".$_SESSION["MM_Username"]."','".$mem_mobile."','系統紀錄','".$log_4."','member','1')";
                    $rs = $SPConn->prepare($SQL);
                    $rs->execute();
                }
            }
        }

        if($advisory_auton == ""){
            $advisory_auton = "";
        }
        $SQL = "INSERT INTO ac_data_re (acre_sign,acre_time2,acre_branch,acre_num,acre_title,acre_content,acre_kind,acre_kind2,acre_user,acre_pay,acre_pay2,all_branch,all_single,acre_note,advisory_auton) VALUES ('".$acre_sign."','".$ac_time."','".$ac_branch."','".$acre_num."','".$ac_title."','".$ac_note."','".$ac_kind."','".$ac_kind2."','".str_replace("'","",$_REQUEST["acre_user"])."','".SqlFilter($_REQUEST["acre_pay"],"tab")."','".SqlFilter($_REQUEST["acre_pay2"],"int")."','".SqlFilter($_REQUEST["branch"],"tab")."','".SqlFilter($_REQUEST["single"],"tab")."','".SqlFilter($_REQUEST["acre_note"],"tab")."','".$advisory_auton."')";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $acre_auto = $SPConn->lastInsertId();

        if($acre_auto != "" && $advisory_auton != ""){
            $SQL = "UPDATE ad_advisory set acre_auto = '".$acre_auto."' where auton='".$advisory_auton."'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }

        if($_REQUEST["r"] == "keyin"){
            reURL("ad_keyin_index.php");
        }else{
            reURL("ad_mem_action_re.php");
        }
    }
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li><a href="ad_mem_action_re_list.php">活動明細表</a></li>
            <li class="active">新增活動明細</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>新增活動明細</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">

                　 <p><input type="text" name="qkword" id="qkword" class="form-control2"> <input type="button" id="member_query_button" class="btn btn-danger" value="查詢會員"></p>

                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <form action="ad_mem_action_re.php" method="post">
                            <tr>
                                <td colspan="2" bgcolor="#F0F0F0">報名日期：
                                    <!--
                                        <select name="y1" id="y1" style="width:80px;">
                                            <option value="<?php echo date("Y")-1 ?>"><?php echo date("Y")-1 ?></option>
                                            <option value="<?php echo date("Y") ?>" selected><?php echo date("Y") ?></option>
                                            <option value="<?php echo date("Y")+1 ?>"><?php echo date("Y")+1 ?></option>
                                        </select>
                                        年 
                                        <select name="m1" id="m1" style="width:80px;">
                                            <option value="<?php echo date("n") ?>" selected><?php echo date("n") ?></option>
                                            <?php 
                                                for($i=1;$i<=12;$i++){
                                                    echo "<option value='".$i."'>".$i."</option>";
                                                }
                                            ?>
                                        </select>
                                        月 
                                        <select name="d1" id="d1" style="width:80px;">
                                            <option value="<?php echo date("j") ?>" selected><?php echo date("j") ?></option>
                                            <?php 
                                                for($i=1;$i<=31;$i++){
                                                    echo "<option value='".$i."'>".$i."</option>";
                                                }
                                            ?>
                                        </select>
                                        日
                                    -->
                                    <?php echo date("Y/m/d"); ?>
                                </td>
                            </tr>
                                <!-- 
                                    <tr bgcolor="#F0F0F0"> 
                                        <td colspan="2" bgcolor="#F0F0F0"> 
                                        活動日期：
                                        <select name="y2" id="y2" style="width:80px;">
                                            <option value="<?php echo date("Y")-2 ?>"><?php echo date("Y")-2 ?></option>
                                            <option value="<?php echo date("Y")-1 ?>"><?php echo date("Y")-1 ?></option>
                                            <option value="<?php echo date("Y") ?>" selected><?php echo date("Y") ?></option>
                                            <option value="<?php echo date("Y")+1 ?>"><?php echo date("Y")+1 ?></option>
                                        </select>
                                        年 
                                        <select name="m2" id="select2" style="width:80px;">
                                            <option value="<?php echo date("n") ?>" selected><?php echo date("n") ?></option>
                                            <?php 
                                                for($i=1;$i<=12;$i++){
                                                    echo "<option value='".$i."'>".$i."</option>";
                                                }
                                            ?>
                                        </select>
                                        月 
                                        <select name="d2" id="select3" style="width:80px;">
                                            <option value="<?php echo date("j") ?>" selected><?php echo date("j") ?></option>
                                            <?php 
                                                for($i=1;$i<=31;$i++){
                                                    echo "<option value='".$i."'>".$i."</option>";
                                                }
                                            ?>
                                        </select>
                                        日</td>
                                    </tr>
                                -->
                            <tr bgcolor="#F0F0F0">
                                <td>活動標題：
                                    <select name="acre_num" style="width:80%;" onchange="this.options[this.selectedIndex].value && (window.location = '?acre_num='+this.options[this.selectedIndex].value);" required>
                                        <option value="">請選擇</option>
                                        <?php 
                                            if($_SESSION["MM_UserAuthorization"] == "admin"){
                                                $ssql = "";
                                            }else{
                                                if($_SESSION["MM_Username"] == "A224258841" || $_SESSION["MM_Username"] == "A229457620"){
                                                    $ssql = " and (ac_branch='".$_SESSION["branch"]."' or ac_branch='好好玩旅行社' or crossarea=1)";
                                                }else{
                                                    $ssql = " and (ac_branch='".$_SESSION["branch"]."' or crossarea=1)";
                                                }
                                            }

                                            $SQL = "SELECT * FROM action_data Where ac_time between '".date("Y/m/d",strtotime("-300 day"))."' and '".date("Y/m/d",strtotime("+2 years"))."'".$ssql." ORDER BY ac_branch asc, ac_time DESC";
                                            $rs = $SPConn->prepare($SQL);
                                            $rs->execute();
                                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                                            if($result){
                                                foreach($result as $re){
                                                    if(strval($_REQUEST["acre_num"]) == strval($re["ac_auto"])){
                                                        $acre_num_select = " selected";
                                                    }else{
                                                        $acre_num_select = "";
                                                    }

                                                    if($re["ac_stat"] == 1){
                                                        echo "<option value='' disabled>".$re["ac_branch"]. " - " .$re["ac_time"]. " - (取消) " .$re["ac_title"]."</option>";
                                                    }else{
                                                        echo "<option value='".$re["ac_auto"]."'".$acre_num_select.">".$re["ac_branch"]. " - " .$re["ac_time"]. " - " .$re["ac_title"]."</option>";
                                                    }
                                                }
                                            }else{
                                                echo "<option value='error'>讀取錯誤</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <?php 
                                if($_REQUEST["acre_num"] != ""){ ?>
                                    <tr>
                                        <input type="hidden" name="r" value="<?php echo SqlFilter($_REQUEST["r"],"tab"); ?>">
                                        <td colspan="2" bgcolor="#F0F0F0">參加人員身分証： 
                                            <?php 
                                                if($_REQUEST["st"] == "next"){ ?>
                                                    <input type="text" class="form-control2" size="20" value="<?php echo SqlFilter($_REQUEST["acre_user"],"tab") ?>" disabled>              
            	                                    <input name="acre_user" type="hidden" id="acre_user" class="form-control2" size="20" value="<?php echo SqlFilter($_REQUEST["acre_user"],"tab") ?>">  
                                                <?php }else{ ?>
                                                    <input name="acre_user" type="text" id="acre_user" class="form-control2" size="20" value="<?php echo SqlFilter($_REQUEST["acre_user"],"tab") ?>" required>  
                                                <?php }
                                            ?>
                                    </tr>
                                <?php }                                            
                            ?>
                            <?php 
                                if($_REQUEST["st"] != "next"){ ?>
                                    <tr> 
                                        <td colspan="2" align="left">
                                            <input type="hidden" name="st" value="next">
                                            <input name="Submit" type="submit" id="Submit2" value="讀取" class="btn btn-info">
                                        </td>
                                    </tr>
                                <?php }else{ 
                                    $mem_username = SqlFilter($_REQUEST["acre_user"],"tab");
                                    $SQL = "select mem_num from member_data where mem_username='".$mem_username."'";
                                    $rs = $SPConn->prepare($SQL);
                                    $rs->execute();
                                    $result = $rs->fetch(PDO::FETCH_ASSOC);
                                    if($result){
                                        $mem_num = $result["mem_num"];
                                    }

                                    $acre_num = SqlFilter($_REQUEST["acre_num"],"int");
                                    $SQL = "select ac_branch, crossarea from action_data where ac_auto='".$acre_num."'";
                                    $rs = $SPConn->prepare($SQL);
                                    $rs->execute();
                                    $result = $rs->fetch(PDO::FETCH_ASSOC);
                                    if($result){
                                        $ac_branch = $result["ac_branch"];
                                        $crossarea = $result["crossarea"];
                                    }

                                    $ap_4 = 0;

                                    if($mem_username != "" && $mem_num != ""){ //身分證跟會員都要有
                                        $SQL = "select sum(ap_4) as pst from pay_main where pay_user='".$mem_username."' and pay_branch='".$ac_branch."'";
                                        $rs = $SPConn->prepare($SQL);
                                        $rs->execute();
                                        $result = $rs->fetch(PDO::FETCH_ASSOC);
                                        if($result){
                                            $ap_4 = $result["ap_4"];
                                        }
                                        if($ap_4 == "" || is_null($ap_4)){
                                            $ap_4 = 0;
                                        }

                                        $bap_4 = 0;
                                        $SQL = "select sum(pay_money3) as pp from ad_advisory where mem_num = '".$mem_num."' and mem_branch='".$ac_branch."'";
                                        $rs = $SPConn->prepare($SQL);
                                        $rs->execute();
                                        $result = $rs->fetch(PDO::FETCH_ASSOC);
                                        if($result){
                                            $bap_4 = $result["pp"];
                                        }
                                        if($bap_4 > 0){
                                            $ap_4 = $ap_4 - $bap_4;
                                        }
                                    }

                                    $ap_4new = 0;
                                    if($mem_username != "" && $mem_num != ""){ //身分證跟會員都要有
                                        $SQL = "select sum(ap_4new) as pst from pay_main where pay_user='".$mem_username."' and pay_branch='".$ac_branch."'";
                                        $rs = $SPConn->prepare($SQL);
                                        $rs->execute();
                                        $result = $rs->fetch(PDO::FETCH_ASSOC);
                                        if($result){
                                            $ap_4new = $result["pst"];
                                        }
                                        if($ap_4new == "" || is_null($ap_4new)){
                                            $ap_4new = 0;
                                        }

                                        $bap_4new = 0;
                                        $SQL = "select sum(pay_money4) as pp from ad_advisory where mem_num = '".$mem_num."' and mem_branch='".$ac_branch."'";
                                        $rs = $SPConn->prepare($SQL);
                                        $rs->execute();
                                        $result = $rs->fetch(PDO::FETCH_ASSOC);
                                        if($result){
                                            $bap_4new = $result["pp"];
                                        }
                                        if($bap_4new > 0){
                                            $ap_4new = $ap_4new - $bap_4new;
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td bgcolor="#F0F0F0" colspan=2>服務成本：
                                            <?php 
                                                if($ap_4 <= 0){
                                                    echo "<font color=red>0</font>";
                                                    $show_money3_sel = 0;
          			                                $pay_money3_text = "<font color=red>無成本可扣</font>";
                                                }else{
                                                    echo "<font color=blue>".$ap_4."</font>";
                                                    $show_money3_sel = 1;
                                                    $pay_money3_text = "<font color=blue>".$ap_4." 元可扣</font>";
                                                }
                                            ?>
                                            &nbsp;&nbsp;
                                            新服務成本：
                                            <?php 
                                                if($ap_4new <= 0){
                                                    echo "<font color=red>0</font>";
                                                    $show_money4_sel = 0;
                                                    $pay_money4_text = "<font color=red>無成本可扣</font>";
                                                }else{
                                                    echo "<font color=blue>".$ap_4new."</font>";
                                                    $show_money4_sel = 1;
                                                    $pay_money4_text = "<font color=blue>".$ap_4new." 元可扣</font>";
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr bgcolor="#F0F0F0"> 
                                        <td colspan="2" bgcolor="#F0F0F0">收費金額： 
                                        <select name="acre_pay" id="select5" style="width:80px;" required>
                                            <option value="現金">現金</option>
                                            <option value="刷卡">刷卡</option>
                                                    <option value="匯款">匯款</option>
                                            <option value="活動卷">活動卷</option>
                                            <option value="保證金">保證金</option>
                                            <?php if ($show_money3_sel == 1) echo "<option value='抵用卷'>抵用卷</option>"; ?>
                                            <?php if($show_money4_sel == 1) echo "<option value='新抵用卷'>新抵用卷</option>"; ?>
                                        </select>
                                        <input type="number" name="acre_pay2" id="acre_pay2" size="10" class="form-control2" required>
                                        元</td>
                                    </tr>
                                    <tr bgcolor="#F0F0F0"> 
                                        <td colspan="2" bgcolor="#F0F0F0">活動備註： 
                                            <input name="acre_note" type="text" id="acre_note" class="form-control2" style="width:80%;">
                                        </td>
                                    </tr>
                                    <tr bgcolor="#F0F0F0"> 
                                        <td colspan="2" bgcolor="#F0F0F0">
                                            <div align="left"> 
                                                受理會館： 
                                                <select name="branch" id="branch" required>
                                                    <option value="">請選擇</option>
                                                    <?php
                                                        $SQL = "Select * From branch_data Where admin_name<>'線上諮詢' and admin_name<>'好好玩旅行社' Order By admin_SOrt";
                                                        $rs = $SPConn->prepare($SQL);
                                                        $rs->execute();
                                                        $result=$rs->fetchAll(PDO::FETCH_ASSOC);                                
                                                        foreach($result as $re){
                                                            echo "<option value='".$re["admin_name"]."'>".$re["admin_name"]."</option>";                                                                                        
                                                        }                                           
                                                    ?>
                                                </select>
                                                受理秘書： 
                                                <select name="single" id="single" required>
                                                    <option value="">請選擇</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td colspan="2">
                                            <div align="center"> 
                                                <input type="hidden" name="st" value="add">
                                                <input name="ap_4" type="hidden" id="ap_4" value="<?php echo $ap_4; ?>">
                                                <input name="ap_4new" type="hidden" id="ap_4new" value="<?php echo $ap_4new; ?>">
                                                <input name="mem_num" type="hidden" id="mem_num" value="<?php echo $mem_num; ?>">
                                                <input name="Submit" type="submit" id="Submit2" value="確定送出" class="btn btn-info" style="width:50%;">
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            ?>
                        </form>     
                    </tbody>
                </table>
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

<script language="JavaScript">
    <!--
    $(function() {
        $("#pay1").on("change", function() {
            personnel_get("pay1", "pay2");
        });
        $("#member_query_button").on("click", function() {
            if (!$("#qkword").val()) {
                alert("請輸入要查詢的會員相關資料，如姓名、電話等。");
                $("#qkword").focus();
                return false;
            }
            Mars_popup('ad_mem_action_re.php?st=query_member&qkword=' + $("#qkword").val(), '', 'width=500,height=250,top=250,left=250');
        });
    });

    function chk_form() {
        if (!$("#acre_user").val()) {
            alert("請輸入身分證字號。");
            $("#acre_user").focus();
            return false;
        }
        if (!$("#acre_pay2").val()) {
            alert("請輸入收費金額。");
            $("#acre_pay2").focus();
            return false;
        }
        var reg = /^[-+]?\d+$/;
        if (!reg.test($("#acre_pay2").val())) {
            alert("收費金額請輸入數字。");
            $("#acre_pay2").val("");
            $("#acre_pay2").focus();

            return false;
        }

        if (!$("#pay1").val()) {
            alert("請選擇受理會館。");
            $("#pay1").focus();
            return false;
        }
        if (!$("#pay2").val()) {
            alert("請選擇受理秘書。");
            $("#pay2").focus();
            return false;
        }
        return true;
    }
    -->
</script>