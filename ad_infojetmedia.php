<?php
    error_reporting(0); 
    /*****************************************/
    //檔案名稱：ad_infojetmedia.php
    //後台對應位置：名單/發送記錄>春天-捷億創意
    //改版日期：2021.11.12
    //改版設計人員：Jack
    //改版程式人員：Queena
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");
    require_once("./include/_top.php");
    require_once("./include/_sidebar.php");

    $auth_limit = 6;
    require_once("./include/_limit.php");

    if ( SqlFilter($_REQUEST["st"],"tab") == "double" ){
        $SQL = "Select * From infojetmedia Where auton=".SqlFilter($_REQUEST["auton"],"int");
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result=$rs->fetchAll(PDO::FETCH_ASSOC);
        if ( count($result) > 0 ){
            $SQL_u = "Update infojetmedia Set stat = 2 Where auton=".SqlFilter($_REQUEST["auton"],"int");
            $rs_u = $SPConn->prepare($SQL_u);
            $rs_u->execute();
        }
        reURL("ad_infojetmedia.php");
        exit;
    }
    
    if ( SqlFilter($_REQUEST["st"],"tab") == "trans" ){
        //Set R1 = Server.CreateObject("ADODB.Recordset")
        //Set R2 = Server.CreateObject("ADODB.Recordset")
        $SQL = "Select * From infojetmedia Where auton = ".SqlFilter($_REQUEST["auton"],"int");
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result=$rs->fetchAll(PDO::FETCH_ASSOC);
        if ( count($result) > 0 ){
            $SQL1 = "Select * From msg_num Where m_auto = 1";
            $rs1 = $SPConn->prepare($SQL1);
            $rs1->execute();
            $result1=$rs1->fetchAll(PDO::FETCH_ASSOC);
            foreach($result1 as $re1);
            $mem_num = ($re1["m_num"] + 1);
            //**Update msg_num
            $SQL_u = "Update msg_num Set m_num = ".$mem_num." Where m_auto = 1";
            $rs_u = $SPConn->prepare($SQL_u);
            $rs_u->execute();
            if ( $re["sex"] == "女" ){ $mem_photo = "girl.jpg"; }else{ $mem_photo = "boy.jpg"; }
            //**Insert member_data
            $SQL_i  = "Insert Into member_data(";
            $SQL_i .= "all_type, mem_level, mem_num, mem_photo, mem_come, mem_cc, mem_time, mem_name, mem_sex, mem_blood, mem_marry, mem_mobile, mem_by, mem_bm, mem_bd, ";
            $SQL_i .= "mem_area, mem_mail, mem_school, all_note) Values ( ";
            $SQL_i .= "'未處理',";
            $SQL_i .= "'guest',";
            $SQL_i .= "'".$mem_num."',";
            $SQL_i .= "'".$mem_photo."',";
            $SQL_i .= "'億捷創意',";
            $SQL_i .= "'".$re["regc"]."',";
            $SQL_i .= "'".strftime("%Y/%m/%d %H:%M:%S")."',";
            $SQL_i .= "'".$re["names"]."',";
            $SQL_i .= "'".$re["sex"]."',";
            $SQL_i .= "'A',";
            $SQL_i .= "'未婚',";
            $SQL_i .= "'".$re["mobile"]."',";
            if ( chkDate($re["age"]) ){
                $SQL_i .= "'".date("Y",$re["age"])."',";
                $SQL_i .= "'".date("m",$re["age"])."',";
                $SQL_i .= "'".date("d",$re["age"])."',";
            }else{
                $SQL_i .= "'',";
                $SQL_i .= "'',";
                $SQL_i .= "'',";
            }
            $SQL_i .= "'".$re["city"]."',";
            $SQL_i .= "'".$re["email"]."',";
            $SQL_i .= "'".$re["school"]."',";
            $SQL_i .= "'".$re["times"]." - 自 春天-億捷創意 轉換')";
            $rs_i = $SPConn->prepare($SQL_i);
            $rs_i->execute();
            $mem_auto = $pdo -> lastInsertId();

            //**Update infojetmedia
            $SQL_u = "Update infojetmedia Set mem_auto = ".$mem_auto." Where auton = ".SqlFilter($_REQUEST["auton"],"int");
            $rs_u = $SPConn->prepare($SQL_u);
            $rs_u->execute();
        }
        reURL("ad_infojetmedia..php");
        exit;
    }
    
    //刪除 infojetmedia
    if ( SqlFilter($_REQUEST["st"],"tab") == "del" ){
        $SQL_d = "Delete From infojetmedia_dmn Where auton=".SqlFilter($_REQUEST["auton"],"int");
        $rs_d = $SPConn->prepare($SQL_d);
        $rs_d->execute();
        reURL("win_close.asp?m=資料刪除中");
        exit;
    }

    //程式
    $stat = SqlFilter($_REQUEST["stat"],"tab");
    switch ($stat){
	    case "2":
            $subSQL1 = " stat = 2";
            $d1 = "";
            $d2 = "";
            $d3 = "disabled";
            break;
	    case "1":
	        $subSQL1 = " stat = 1";
	        $d1 = "";
	        $d2 = "disabled";
	        $d3 = "";
            break;
        default:
	        $subSQL1 = " stat = 0";
	        $d1 = "disabled";
	        $d2 = "";
	        $d3 = "";
            break;
    }

    if ( SqlFilter($_REQUEST["times1"],"tab") != "" ){
        $t1 = SqlFilter($_REQUEST["times1"],"tab");
        if ( chkDATE($t1) == false){
            call_alert("起始日期有誤。", 0, 0);
        }
        $t1 = $t1 . " 00:00";
    }

    if ( SqlFilter($_REQUEST["times2"],"tab") != "" ){
        $t2 = SqlFilter($_REQUEST["times2"],"tab");
        if ( chkDate($t2) == false ){
            call_alert("結束日期有誤。", 0, 0);
        }
        $t2 = $t2 . " 23:59";
    }

    if ( $t1 != "" && $t2 != "" ){
	    $subSQL2 = " And times Between '".$t1."' And '".$t2."'";
    }

    //取得總筆數
    $SQL = "Select count(auton) As total_size From infojetmedia Where ".$subSQL1.$subSQL2;
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result=$rs->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $re);
    if ( count($result) == 0 || $re["total_size"] == 0 ) {
        $total_size = 0;
    }else{
        $total_size = $re["total_size"];
    }

    //取得分頁資料
    $tPageSize = 50; //每頁幾筆
    $tPage = 1; //目前頁數
    if ( $_REQUEST["tPage"] > 1 ){ $tPage = $_REQUEST["tPage"];}
    $tPageTotal = ceil(($total_size/$tPageSize)); //總頁數
    if ( $tPageSize*$tPage < $total_size ){
        $page2 = 50;
    }else{
        $page2 = (50-(($tPageSize*$tPage)-$total_size));
    }

    //分頁語法
    $SQL  = "Select * From (";
    $SQL .= "Select TOP ".$page2." * From (";
    $SQL .= "Select TOP ".($tPageSize*$tPage)." * From infojetmedia Where ".$subSQL1.$subSQL2." Order By times Desc) t1 Where".$subSQL1.$subSQL2." Order By times Asc) t2 Where".$subSQL1.$subSQL2." Order By times Desc ";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result=$rs->fetchAll(PDO::FETCH_ASSOC);
?>
<script type="text/JavaScript" src="./include/script.js"></script>
<script type="text/JavaScript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
$(function() {
    $("#U1").click(function(){
	var time1 = $("#times1").val();
    var time2 = $("#times2").val();
	Mars_popup("ad_infojetmedia_excel.php?times1="+time1+"&times2="+time2);
	});
});
</script>
<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li class="active">春天-億捷創意</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>春天-億捷創意 - 數量：<?php echo $total_size;?></strong> <!-- panel title -->
                </span>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <p>
                        <span class="text-status">目前資料狀態 </span> ▶&nbsp;
                        <a href="ad_infojetmedia.php" class="btn btn-info<?php if ( $stat == "" ){?> btn-active<?php }?>"<?php if ( $stat == "" ){?> disabled<?php }?>>未處理</a>
                        <a href="ad_infojetmedia.php?stat=1" class="btn btn-info<?php if ( $stat == 1 ){?> btn-active<?php }?>"<?php if ( $stat == 1 ){?> disabled<?php }?>>已處理</a>
                        <a href="ad_infojetmedia.php?stat=2" class="btn btn-info<?php if ( $stat == 2 ){?> btn-active<?php }?>"<?php if ( $stat == 2 ){?> disabled<?php }?>>重複</a>
                        <?php if ( $stat == 1 ){ ?>&nbsp;&nbsp;<input name="U1" type="button" class="btn btn-success" id="U1" value="匯出Excel"><?php }?>
                    </p>
                    <p>
                    <form name="form1" method="post" action="?vst=full&stat=0" style="margin:0px;">
                        &nbsp;&nbsp;日期：
                        <input type="text" class="datepicker" autocomplete="off" style="width:100px;" name="times1" id="times1" value="<?php echo SqlFilter($_REQUEST["times1"],"tab");?>">
                        ～
                        <input type="text" class="datepicker" autocomplete="off" style="width:100px;" name="times2" id="times2" value="<?php echo SqlFilter($_REQUEST["times2"],"tab");?>">
                        <input type="hidden" name="stat" id="stat" value="<?php echo SqlFilter($_REQUEST["stat"],"tab");?>">
                        <input type="submit" name="Submit" style="height:28px;margin-top:-7px;" value="查詢">
                    </form>
                    </p>
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <thead>
                            <tr>
                                <th>時間</th>
                                <th>姓名</th>
                                <th>性別</th>
                                <th>生日</th>
                                <th>學歷</th>
                                <th>手機</th>
                                <th>Email</th>
                                <th>地區</th>
                                <th>註冊來源</th>
                                <th>註冊時間</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ( count($result) == 0 ){
                                echo "<tr><td colspan='10'>目前已無資料</td></tr>";
                            }else{
                                foreach($result as $re){
	                                $mobile = $re["mobile"];
	                                $havee = 0;
                                    $SQL1 = "Select Top 1 * From member_data Where mem_branch = '八德' And mem_mobile='".$mobile."'";
                                    $rs1 = $SPConn->prepare($SQL1);
                                    $rs1->execute();
                                    $result1=$rs1->fetchAll(PDO::FETCH_ASSOC);
                                    if ( count($result1) > 0 ){
		                                $havee = 1;
                                    }
	                                $SQL1 = "Select Top 1 * From love_keyin Where all_branch = '八德' And k_mobile='".$mobile."'";
                                    $rs1 = $SPConn->prepare($SQL1);
                                    $rs1->execute();
                                    $result1=$rs1->fetchAll(PDO::FETCH_ASSOC);
                                    if ( count($result1) > 0 ){
		                                $havee = 1;
                                    } ?>
                                    <tr>
                                        <td align="center"><?php echo changeDate($re["times"]);?></td>
                                        <td align="center"><?php echo $re["names"];?><?php if ( $stat !=1 ){?>(<a href="ad_no_mem_s.php?mem_mobile=<?php echo $mobile;?>" target="_blank">查</a>)<?php }?></td>
                                        <td align="center"><?php echo $re["sex"];?></td>
                                        <td align="center"><?php echo $re["age"];?></td>
                                        <td align="center"><?php echo $re["school"];?></td>
                                        <td align="center"><?php echo $mobile;?></td>
                                        <td align="center"><?php echo $re["email"];?></td>
                                        <td align="center"><?php echo $re["city"];?></td>
                                        <td align="center"><?php echo $re["regc"];?></td>
                                        <td align="center"><?php echo $re["regt"];?></td>
                                        <td align="center">
                                        <?php if ( $stat == 0 ){ ?>
                                            <div class="btn-group">
                                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">操作 <span class="caret"></span></button>
                                                <ul class="dropdown-menu pull-right">
                                                    <?php if ( $havee == 1 && SqlFilter($_REQUEST["stat"],"tab") != "1" ){?>
                                                        <li><a href="?st=double&auton=<?php echo $re["auton"];?>"><i class="icon-share"></i> 此電話重複</a></li>
                                                    <?php }elseif ( SqlFilter($_REQUEST["stat"],"tab") != "1" ){ ?>
                                                        <li><a href="?st=trans&auton=<?php echo $re["auton"];?>"><i class="icon-share"></i> 轉入未入會資料</a></li>
                                                    <?php }?>
                                                    <li><a href="javascript:Mars_popup2('ad_infojetmedia_dmn.php?st=del&auton=<?php echo $re["auton"];?>','','width=300,height=200,top=100,left=100')"><i class="icon-trash"></i> 刪除</a></li>
                                                </ul>
                                            </div>
                                        <?php }elseif ( $stat == 1 ){
                                            $reports = get_report_num($mobile);
                                            if ( substr_count($reports, "|+|") > 0 ){
                                                $reports_array = explode("|+|", $reports);
                                                $report = $reports_array[0];
                                                $report_text = $reports_array[1];
                                            }else{
                                                $report = 0;
                                                $report_text = "無";
                                            }
                                        ?>
                                            <a href="javascript:Mars_popup('ad_report.php?k_id=<?php echo $re["mem_auto"];?>&ty=member','','scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=690,height=600,top=10,left=10');">回報(<?php echo $report;?>)</a>
                                        <?php }?>
                                        </td>
                                    </tr>
                                <?php }?>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            <?php require_once("./include/_page.php"); ?>
        </div>
        <!--/span-->
    </div>
    <!--/row-->
</section>
    <!-- /MIDDLE -->
<?php require_once("./include/_bottom.php");?>