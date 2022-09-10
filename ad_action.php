<?php   
/***************************************/
//檔案名稱：ad_action.php
//後台對應位置：名單/發送記錄》活動報名資料
//改版日期：2021.10.19
//改版設計人員：Jack
//改版程式人員：Queena
/***************************************/

require_once("_inc.php");
require_once("./include/_function.php");
require_once("./include/_top.php");
require_once("./include/_sidebar.php");

//判斷登入
if ( $_SESSION["MM_Username"] == "" ){ call_alert_new(3,"請重新登入。","login.php"); }

//判斷權限
check_page_power("ad_action");

//麵包屑
$unitprocess = $m_home.$icon."名單/發送記錄".$icon."DMN企業專區";

//接收值
$st = SqlFilter($_REQUEST["st"],"tab");
$ty = SqlFilter($_REQUEST["ty"],"tab");
$k_id = SqlFilter($_REQUEST["k_id"],"tab");
$vst = SqlFilter($_REQUEST["vst"],"tab");
$sear = SqlFilter($_REQUEST["sear"],"tab");
$s99 = SqlFilter($_REQUEST["s99"],"tab");
$stt = SqlFilter($_REQUEST["stt"],"tab");
$orderby = SqlFilter($_REQUEST["orderby"],"tab");
$keyword_type = SqlFilter($_REQUEST["keyword_type"],"tab");
$keyword = SqlFilter($_REQUEST["keyword"],"tab");
$sb = SqlFilter($_REQUEST["sb"],"tab");
$sk = SqlFilter($_REQUEST["sk"],"tab");
$sd1 = SqlFilter($_REQUEST["sd1"],"tab");
$sd2 = SqlFilter($_REQUEST["sd2"],"tab");
$sv = SqlFilter($_REQUEST["sv"],"tab");

//接收值(進階搜尋)
$s2 = SqlFilter($_REQUEST["s2"],"tab");
$s3 = SqlFilter($_REQUEST["s3"],"tab");
$s8 = SqlFilter($_REQUEST["s8"],"tab");
$s7 = SqlFilter($_REQUEST["s7"],"tab");
$s9 = SqlFilter($_REQUEST["s9"],"tab");
$s6 = SqlFilter($_REQUEST["s6"],"tab");
$branch = SqlFilter($_REQUEST["branch"],"tab");
$single = SqlFilter($_REQUEST["single"],"tab");
$s95 = SqlFilter($_REQUEST["s95"],"tab");
$s97 = SqlFilter($_REQUEST["s97"],"tab");
$nodouble = SqlFilter($_REQUEST["nodouble"],"tab");
$a1 = SqlFilter($_REQUEST["a1"],"tab");
$b1 = SqlFilter($_REQUEST["b1"],"tab");

if ( $st == "trans" ){
    $SQL = "Select * From love_keyin Where all_kind = '活動' And k_id='".$k_id."'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result=$rs->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $re);
    if ( count($result) > 0 ){
        $SQL_u = "Update msg_num Set m_num=m_num+1 Where m_auto = 1";
        $rsu = $SPConn->prepare($SQL_u);
        $rsu->execute();
        if ( $re["k_sex"] == "女"){ $mem_photo = "girl.jpg"; }else{ $mem_photo = "boy.jpg"; }
        //新增 member_data
        $SQL_i  = "Insert Into member_data(";
        $SQL_i .= "all_type, mem_level, mem_num, mem_photo, mem_come, mem_come, mem_come2, mem_come6, mem_come6_name, mem_cc, mem_time, mem_name, mem_sex, mem_blood, mem_marry";
        $SQL_i .= "mem_by, mem_bm, mem_bd, mem_area, mem_mail, mem_job2, mem_msn, mem_school, mem_branch, mem_single, all_note) Values (";
        $SQL_i .= "'已發送',";
        $SQL_i .= "'guest',";
        $SQL_i .= "'".$mem_num."',";
        $SQL_i .= "'".$mem_photo."',";
        if ( $re["k_come"] != "" ){
            $SQL_i .= "'".$re["k_come"]."',";
            if ( $re["k_come"] != "委外活動23" ){
                $SQL_i .= "'通路合作',";
                $SQL_i .= "'活動報名',";
                $SQL_i .= "'pr23',";
                $SQL_i .= "'貳叁公關',";
            }else{
                $SQL_i .= "'',";
                $SQL_i .= "'',";
                $SQL_i .= "'',";
                $SQL_i .= "'',";
            }
        }else{
            $SQL_i .= "'網站活動',";
        }
        $SQL_i .= "'".$re["k_cc"]."',";
        $SQL_i .= "'".date("Y-m-d H:i:s")."',";
        $SQL_i .= "'".$re["k_name"]."',";
        $SQL_i .= "'".$re["k_sex"]."',";
        $SQL_i .= "'A',";
        if ( $re["k_marry"] != "" ){
            $SQL_i .= "'".$re["k_marry"]."',";
        }else{
            $SQL_i .= "'未婚',";
        }
        if ( chkDate($re["k_bday"]) ){
            $SQL_i .= "'".date("Y",$re["k_bday"])."',";
            $SQL_i .= "'".date("m",$re["k_bday"])."',";
            $SQL_i .= "'".date("d",$re["k_bday"])."',";
        }else{
            $SQL_i .= "'',";
            $SQL_i .= "'',";
            $SQL_i .= "'',";
        }
        $SQL_i .= "'".$re["k_area"]."',";
        $SQL_i .= "'".$re["k_yn"]."',";
        $SQL_i .= "'".$re["k_job"]."',";
        $SQL_i .= "'".$re["k_line"]."',";
        $SQL_i .= "'".$re["k_school"]."',";
        $SQL_i .= "'".$re["all_branch"]."',";
        $SQL_i .= "'".$re["all_single"]."',";
        $SQL_i .= "'由".$_SESSION["pname"]."自 活動報名資料[".$re["k_id"]."] 轉換',";
        $rs_i = $SPConn->prepare($SQL_i);
        $rs_i->execute();
        //Update love_kyein
        $SQL_u = "Update love_keyin Set k_trans = 1";
        $rs_u = $SPConn->prepare($SQL_u);
        $rs_u->execute();
    }
    reURL("ad_action.php?a=b".$st);
    exit;
}

if ( ( $a1 != "" && $b1 == "" ) || ( $a1 == "" && $b1 != "" ) ) {
    call_alert_new(1,"日期選擇起始和結束時間。","");
}
if ( $a1 > $b1 ){ call_alert_new(1,"日期請由小到大選擇",""); }

if ( $a1 != "" ){
    if ( substr_count($a1,"00:00") > 0 ){
        $a1 = str_replace("00:00","",$a1);
    }
    $a1 = $a1." 00:00";
}else{
    $a1 = "1900/1/1";
}

if ( $b1 != "" ){
    if ( substr_count($b1,"23:59") > 0 ){
        $b1 = str_replace("23:59","",$b1);
    }
    $b1 = $b1." 23:59";
}else{
    $b1 = "2020/12/31";
}

//Select ".$subSQL1." From love_keyin As dba Where
if ( $_SESSION["MM_UserAuthorization"] == "admin" ){
    $subSQL1 = "And all_kind = '活動' ";
    if ( $sear != "1" ){
        if ( $s99 != "" ){
            $subSQL1 .= "And all_type <> '未處理' ";
            $all_type = "已處理";
        }else{
            $subSQL1 .= "And all_type = '未處理' ";
            $all_type = "未處理";
        }
    }else{
        $all_type = "資料搜尋";
    }
}elseif ( $_SESSION["MM_UserAuthorization"] == "branch" || $_SESSION["MM_UserAuthorization"] == "pay" ) {	  
    $subSQL1 .= "all_kind = '活動' And all_branch = '".$_SESSION["branch"]."' ";
}elseif ( $_SESSION["MM_UserAuthorization"] == "single" || $_SESSION["MM_UserAuthorization"] == "love" || $_SESSION["MM_UserAuthorization"] == "action" || $_SESSION["MM_UserAuthorization"] == "manager" || $_SESSION["MM_UserAuthorization"] == "love_manager" ){
    $subSQL1 .= "all_kind = '活動' And all_single = '".$_SESSION["MM_username"]."' ";
}

//篩選條件(手機) Or 進階搜尋(手機)
if ( ($keyword_type == "s2" && is_numeric($keyword) != "") || $s2 != "" ){ 
    if ( $keyword_type == "s2" && is_numeric($keyword) != "" ){
        $cs2 = reset_number($keyword);
    }else{
        $cs2 = reset_number($s2);
    }
    $subSQL1 .= "And k_mobile Like '%".$cs2."%' ";
}
//篩選條件(姓名) Or 進階搜尋(姓名)
if ( $keyword_type == "s3" || $s3 != "" ){
    if ( $s3 != "" ){ $keyword = $s3; }
    $subSQL1 .= "And k_name Like '%" . str_Replace("'", "''", $keyword) . "%' ";
}
//進階搜尋(性別)
if ( $s8 != "" ){
    $subSQL1 .= "And k_sex Like '%" . str_Replace("'", "''", $s8) . "%'";
}
//進階搜尋(祕書)
if ( $single != "" ){
    $subSQL1 .= "And all_single Like '%" . str_Replace("'", "''", $single) . "%'";
}
//進階搜尋(會館)
if ( $branch != "" ){
    $subSQL1 .= "And all_branch Like '%" . str_Replace("'", "''", $branch) . "%'";
}
//進階搜尋(參加活動)
if ( $s95 != "" ){
    $subSQL1 .= "And action_title = '" . str_Replace("'", "''", $s95) . "'";
}
//進階搜尋(來源)
if ( $s9 != "" ){
    $subSQL1 .= "And k_come = '".$s9."'";
}
//進階搜尋(自訂來源)
if ( $s97 != "" ){
    $subSQL1 .= "And k_cc = '" . str_Replace("'", "''", $s97) . "'";
}
//進階搜尋(處理情形)
if ( $stt != "" ){
    $subSQL1 .= "And all_type = '".$stt."'";
}
//進階搜尋(日期)
if ( empty($a1) == 1 ){
    $subSQL1 .= "And k_time between '".$a1."' And '".$b1."' ";
}

if ( $sv != "" ){
    if ( substr_count($sv, "-") > 0 ){
        $sv_array = explode("-",$sv);
        $sv1 = $sv_array[0];
        $sv2 = $sv_array[1];
        $sv3 = $sv_array[2];
        $subSQL1 .= "And action_branch='".$sv1."' And action_title='".$sv2."'";
    }
}
//進階搜尋(不重複名單)
if ( $nodouble == "1" ){
    switch ( $_SESSION["MM_UserAuthorization"] ){
        case "admin":
            $subSQL1 .= "And (SELECT count(k_id) FROM love_keyin as dbb Where (all_kind = '活動') AND (k_mobile = love_keyin.k_mobile)) <= 1 ";
            break;
        case "branch":
            $subSQL1 .= "And (SELECT count(k_id) FROM love_keyin as dbb Where (all_kind = '活動') AND (k_mobile = love_keyin.k_mobile) and (all_branch = '".$_SESSION["branch"]."')) <= 1 ";
            break;
        default:
            $subSQL1 .= "And (SELECT count(k_id) FROM love_keyin as dbb Where (all_kind = '活動') AND (k_mobile = love_keyin.k_mobile) and (all_branch = '".$_SESSION["branch"]."') and (all_single = '".$_SESSION["MM_username"]."')) <= 1 ";
            break;
    }
}

if ( $sear != "1" ){
    if ( $ty == "1" ){
        $subSQL1 .= "And action_kind = '網站活動' ";
        $tit = "網站活動";
    }else{
        $subSQL1 .= "And (action_kind <> '網站活動' or action_kind is null) ";
        $tit = "會館活動";
    }
}else{
    $tit = "所有活動";
}

switch ( $orderby ){
    case "1":   //依資料時間排序
        $subSQL2 = " Order By k_time Desc";
        break;
    case "2":   //依督導發送排序
        $subSQL2 = " Order By send_time Desc";
        break;
    default:
        $subSQL2 = " Order By k_id Desc";
        break;
}
//$sqls2 = $sqls2 . $sqlss;

//取得總筆數
$SQL = "Select count(k_id) As total_size From love_keyin Where 1=1 ".$subSQL1;
$rs = $SPConn->prepare($SQL);
$rs->execute();
$result=$rs->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $re);
if ( count($result) == 0 || $re["total_size"] == 0 ) {
    $total_size = 0;
}else{
    $total_size = $re["total_size"];
}

//查看清單連結文字
if ( $vst == "full" ){
    $count_href = "　<a href=\"javascript:full_btn('n');\" class='btn btn-success'>查看前五百筆</a>";
}else{
    if ( $total_size > 500 ){ $total_size = 500;}
    $count_href = "　<a href=\"javascript:full_btn('full');\" class='btn btn-success'>查看完整清單</a>";
}

//取得分頁資料
$tPageSize = 30; //每頁幾筆
$tPage = 1; //目前頁數
$tPage_list = 0;
if ( $_REQUEST["tPage"] > 1 ){ 
    $tPage = $_REQUEST["tPage"];
    $tPage_list = ($tPage-1);
}
$tPageTotal = ceil(($total_size/$tPageSize)); //總頁數

//分頁語法
$SQL_list  = "Select Top ".$tPageSize." * ";
$SQL_list .= "From (Select row_number() ";
$SQL_list .= "over(".$subSQL2.") As rownumber, k_id, k_name, k_mobile, k_sex, action_branch, action_time, action_title, all_branch, all_single, isbe, k_come, k_area, k_cc, k_time ";
$SQL_list .= "From love_keyin Where 1=1 ".$subSQL1." ) temp_row ";
$SQL_list .= "Where rownumber > ".($tPageSize*$tPage_list);
$rs_list = $SPConn->prepare($SQL_list);
$rs_list->execute();
$result_list = $rs_list->fetchAll(PDO::FETCH_ASSOC);
?>
<section id="middle">

    <!-- 麵包屑 -->
    <header id="page-header">
        <div class="m-crumb"><i class="fa fa-folder-open-o"></i><?php echo $unitprocess;?></div>
    </header>
    <!-- /麵包屑 -->

    <div id="content" class="padding-20">
        <div class="panel panel-default">
            <h2 class="pageTitle">名單/發送功能 》活動報名資料 》資料列表(<?php echo $all_type;?>) [ <i style="color: #76192e;">共計 <?php echo $total_size."筆資料</i> ]"; if ( $total_size >= 500 ){ echo $count_href;}?></h2>
            <p>
                <a class="btn btn-info<?php if ( $ty == "" ){ echo " btn-active";} ?>" <?php if ( $ty == "" ){?> style="cursor:auto;"<?php }?> href="ad_action.php">會館活動</a>
            	<a class="btn btn-info<?php if ( $ty == 1 ){ echo " btn-active";} ?>" <?php if ( $ty == 1 ){?> style="cursor:auto;"<?php }?> href="ad_action.php?ty=1">網站活動</a>
                <div style="text-align:right">
                    
                </div>
			</p>
            <div class="panel-body">
                <form id="searchform" method="post" action="ad_action.php?vst=full&sear=1" target="_self">
                    <div class="m-search-bar">
                        <span class="span-group">
                            <select name="action_type" id="action_type" onchange="javascript:action_type_link(this.value);">
                                <option value="">功能</option>
                                <?php if ( $_SESSION["MM_UserAuthorization"] == "admin" || $_SESSION["MM_UserAuthorization"] == "branch" ){?>
                                    <option value="a1">多選修改</option>
								    <?php if ( $_SESSION["MM_UserAuthorization"] == "admin" ){ ?>
                                        <option value="a2">多選刪除</option>
                                    <?php }?>
                                <?php }?>
							    <?php if ( $_SESSION["MM_UserAuthorization"] == "admin" ){?>
							        <?php if ( $all_type == "未處理" ){?>
                                        <option value="a3">切換已處理</option>
								    <?php }?>
                                    <?php if ( $all_type == "已處理" ){?>
                                        <option value="a4">切換未處理</option>
                                    <?php }?>
                                <?php }?>
                                <option value="a5">進階搜尋</option>
                                <option value="a6"<?php if ( $action_type == "a6" || $sv != "" ){ echo " selected";}?>>活動搜尋</option>
                                <option value="a7">新增參加人員</option>
                            </select>
                        </span>
                        <span class="span-group">
                            <select name="keyword_type" id="keyword_type">
                                <option value="s2"<?php if ( $keyword_type == "s2" ){ echo " selected";}?>>手機</option>
                                <option value="s3"<?php if ( $keyword_type == "s3" ){ echo " selected";}?>>姓名</option>
                            </select>
                        </span>
                        <span class="span-group">
                            <input id="keyword" name="keyword" id="keyword" class="form-control" type="text" value="<?php echo $keyword;?>">
                        </span>
                        <span class="span-group">
                            <input type="submit" value="送出" class="btn btn-default">
                        </span>
                        <span class="span-group">
                            <?php
						    //20220218 新增活動篩選程式 By Queena
                            if ( $sb != "" || $sk != "" || $sd1 != "" || $sd2 != "" || $sv != "" ){
                                if ( $sb != "" ){ $subSQLa .= "And (ac_branch = '".$sb."') ";}
                                if ( $sk != "" ){ $subSQLa .= "And (ac_title like '%".$sk."%') ";}
                                if ( $sd1 != "" && $sd2 != "" ){ $subSQLa .= "And (ac_time BETWEEN '".$sd1."' And '".$sd2."') ";}
                                $SQL = "Select * From action_data Where 1=1 ".$subSQLa."Order By ac_time Desc";
                                $rs = $SPConn->prepare($SQL);
                                $rs->execute();
                                $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                                if ( count($result) > 0 ){
                                    echo "<select name='sv' id='sv' class='form-control' onchange='javascript:searchform.submit();''>";
                                    echo "<option value=''>活動篩選結果 ◀ 選擇活動後會自動送出，不需點選按鈕</option>";
                                    foreach($result as $re){
                                        echo "<option value='".strip_tags($re["ac_branch"])."-".strip_tags(str_replace(" ", "", $re["ac_title"]))."-".$re["ac_time"]."'";
                                        if ( substr_count($sv,$re["ac_title"] )> 0 ){ echo " selected";}
                                        echo ">".$re["ac_branch"]." - ".$re["ac_time"]." - ".$re["ac_title"]."</option>";
                                    }
                                    echo "</select>";
                                    //echo "<span style='background-color:yellow;color:red'>◀ 選擇活動後會自動送出，不需點選按鈕</span>";
                                }else{
                                    echo "<span style='color:fuchsia;'>[".$sb.$sk.$sd1."-".$sd2.$sv."]無此條件的的活動資料</span>";

                                }
                            }
                            ?>
                            <input type="hidden" name="sb" id="sb" value="<?php echo $sb;?>">
                            <input type="hidden" name="sk" id="sk" value="<?php echo $sk;?>">
                            <!-- <input type="hidden" name="sv" id="sv" value="<?php echo $sv;?>"> -->
                            <input type="hidden" name="sd1" id="sd1" value="<?php echo $sd1;?>">
                            <input type="hidden" name="sd2" id="sd2" value="<?php echo $sd2;?>">
                            <input type="hidden" name="a1" id="a1" value="<?php echo $a1;?>">
                            <input type="hidden" name="b1" id="b1" value="<?php echo $b1;?>">
                            <input type="hidden" name="s8" id="s8" value="<?php echo $s8;?>">
                            <input type="hidden" name="s9" id="s9" value="<?php echo $s9;?>">
                            <input type="hidden" name="s3" id="s3" value="<?php echo $s3;?>">
                            <input type="hidden" name="s2" id="s2" value="<?php echo $s2;?>">
                            <input type="hidden" name="s95" id="s95" value="<?php echo $s95;?>">
                            <input type="hidden" name="branch" id="branch" value="<?php echo $branch;?>">
                            <input type="hidden" name="single" id="single" value="<?php echo $single;?>">
                            <input type="hidden" name="stt" id="stt" value="<?php echo $stt;?>">
                        </span>
                    </div>
                    <input type="hidden" name="vst" id="vst">
                </form>
                <span>
                    <strong style="background-color: yellow; color:brown">
                        ※每頁 30 筆資料。
                    </strong>
                </span>                
                <table class="table table-bordered bootstrap-datatable table-hover">
                    <thead>
                        <tr style="background-color: #FFDA96;">
                            <th width="2%"><input data-no-uniform="true" type="checkbox" id="selnums"></th>
                            <th width="20%">姓名</th>
                            <th width="3%">性別</th>
                            <th width="5%" style="text-align: center;">手機</th>
                            <th width="5%" style="text-align: center;">地區</th>
                            <th width="5%">活動會館</th>
                            <th>參加活動</th>
                            <th width="10%">處理</th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ( count($result_list) == 0 ){
                            echo "<tr><td colspan='8' height='200' style='color:fuchsia;'>目前沒有資料</td></tr>";
                        }else{
                            $ci = 0;
                            foreach($result_list as $re_list){
                                if ( $ci == 0 ){
						            $bgc = "#f2f2f2";
						            $ci = 1;
                                }else{
						            $bgc = "#ffffff";
						            $ci = 0;
                                } ?>
                                <tr id="showtr_<?php echo $re_list["k_id"];?>" style="background-color:<?php echo $bgc;?>">
							        <td><input data-no-uniform="true" type="checkbox" name="nums" value="<?php echo $re_list["k_id"];?>"></td>
								    <td class="center"><?php echo $ismem;?><a href="#c" onclick="Mars_popup('ad_love_detail.php?k_id=<?php echo $re_list["k_id"];?>','','status=yes,menubar=yes,resizable=yes,scrollbars=yes,width=700,height=350,top=150,left=150');"><?php echo $re_list["k_name"];?></a>
                                        <?php
                                        $doub = "";
                                        if ( $re_list["k_mobile"] != "" And $re_list["k_mobile"] != "0912345678" ){
			                                $check_double = 0;
                                            $SQL = "Select count(mem_auto) As cc From member_data Where mem_mobile = '".$re_list["k_mobile"]."'";
                                            $rs = $SPConn->prepare($SQL);
                                            $rs->execute();
                                            $result=$rs->fetchAll(PDO::FETCH_ASSOC);
                                            foreach($result as $re);
			                                if ( count($result) > 0 ){
				                                if ( $re["cc"] > 0 ){
                                                    $check_double = 1;
                                                }
			                                }
                                            
                                            $SQL  = "Select count(k_id) As cc From love_keyin Where all_kind='活動' And k_mobile='".$re_list["k_mobile"]."' ";
                                            $SQL .= "And Not k_id = '".$re_list["k_id"]."'";
                                            $rs = $SPConn->prepare($SQL);
                                            $rs->execute();
                                            $result=$rs->fetchAll(PDO::FETCH_ASSOC);
                                            foreach($result as $re);                                            
			                                if ( count($result) > 0 ){
				                                if ( $re["cc"] > 0 ){
				                                    $check_double = 1;
                                                }
			                                }
                                        }			
			                            if ( $check_double == 1 ){
			                                $doub = " <span class='label label-warning'><a href='ad_no_mem_s.php?mem_mobile=".$re_list["k_mobile"]."' target='_blank' style='color:white;'>重</a></span>";
                                        } ?>
								        <div style="float:right">
                                            <?php echo $doub;?>
                                            <a href="ad_no_mem_s.php?mem_mobile=<?php echo $re_list["k_mobile"];?>" target="_blank"><span class="label label-info">查</span></a>
								            <?php
								            $isfav = 0;
                                            switch ($_SESSION["MM_UserAuthorization"]){								
								                case "admin":
                                                    $SQL = "Select count(mem_auto) As favb From member_data Where mem_mobile = '".$re_list["k_mobile"]."' And mem_fav = 1";
                                                    break;
								                case "branch":
								                    $SQL = "Select count(mem_auto) As favb From member_data Where mem_mobile = '".$re_list["k_mobile"]."' And mem_branch='".$_SESSION["branch"]."' And mem_fav = 1";
                                                    break;
								                default:
									                $SQL = "Aelect count(mem_auto) As favb From member_data Where mem_mobile = '".$re_list["k_mobile"]."' And mem_single='".$_SESSION["MM_Username"]."' And mem_fav = 1";
                                            }
                                            $rs = $SPConn->prepare($SQL);
                                            $rs->execute();
                                            $result=$rs->fetchAll(PDO::FETCH_ASSOC);
			                                if ( count($result) > 0 ){
			          	                        if ( $re["favb"] >= 1 ){
			          	                            echo "<span class='label fav_tag_".$re_list["k_mobile"]."' style='background:red'><a href='#' style='color:white;' data-toggle='tooltip' title='關注名單'>關</a></span> ";
			                                      	$isfav = 1;
                                                  }
                                            }			          
								            $SQL = "Select beca From call_list Where fn = '".$re_list["k_mobile"]."' And types='val'";
                                            $rs = $SPConn->prepare($SQL);
                                            $rs->execute();
                                            $result=$rs->fetchAll(PDO::FETCH_ASSOC);
                                            foreach($result as $re);
                                            if ( count($result) > 0 ){                                            
								                echo "<span class='label' style='background:black'><a href='#m' data-toggle='tooltip' data-original-title='".$re["beca"]."' style='color:white;'>黑</a></span>";
							                }

								            if ( $_SESSION["MM_UserAuthorization"] == "admin" || $_SESSION["MM_UserAuthorization"] == "branch" || $_SESSION["MM_UserAuthorization"] == "pay" ){                
                                                if ( $ddoub != "" ){
									                echo "<a href='#c' onclick=alert('暫停使用') style='color:white;' data-toggle='tooltip' title='".$ddoub."'>";
                                                }else{
									                echo "<a href='#c' onclick=alert('暫停使用')>";
								                }
								
								                if ( $re_list["k_area"] != "" ){
				                                    $k_area = $re_list["k_area"];
                                                }else{
				                                    $k_area = "不明";
			                                    }
								                echo "<span class='label label-success'>".$k_area."</span>";
								                echo "</a>";
                                            } ?>
								        </div>
								    </td>
                                    <td style="text-align: center;"><?php echo $re_list["k_sex"];?></td>
                                    <td style="text-align: center;"><?php echo $re_list["k_mobile"];?></td>
                                    <td style="text-align: center;"><?php echo $k_area;?></td>
                                    <td style="text-align: center;"><?php echo $re_list["action_branch"];?></td>
                                        <?php
                                        /*if ( $re_list["action_time"] != "" ){
                                            if ( chkDate($re_list["action_time"]) ){
                                                $at = date_create_from_format( "Y-m-d" , $re_list["action_time"] );
                                            }else{
                                                
                                            }
                                        }*/
                                        $at = date("Y/m/d",strtotime($re_list["action_time"]));
                                        if ( $re_list["isbe"] == 1 ){
                                            $isbe = "<span class='label label-warning' style='background-color:blue'>正取</span>";
                                        }else{
                                            $isbe = "";
                                        } ?>
								    <td class="center"><?php echo $at;?>-<?php echo $re_list["action_title"];?><?php echo $isbe;?></td>
								    <td class="center"><?php echo $re_list["all_branch"];?>-<?php echo SingleName($re_list["all_single"],"normal");?></td>
								    <td class="center">
		                                <?php
		                                $reports = get_report_num($re_list["k_mobile"]);
		                                if ( substr_count($reports, "|+|") > 0 ){
                                            $reports_array = explode("|+|",$reports);
		                                    $report = $reports_array[0];
		                                    $report_text = $reports_array[1];
                                        }else{
		   	                                $report = 0;
		   	                                $report_text = "無";
		                                } ?>
			    		                <div class="btn-group">							
							                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">操作 <span class="caret"></span></button>
							                <ul class="dropdown-menu pull-right">
								                <li><a href="javascript:Mars_popup('ad_love_detail.php?k_id=<?php echo $re_list["k_id"];?>','','status=yes,menubar=yes,resizable=yes,scrollbars=yes,width=700,height=350,top=150,left=150');"><i class="icon-file"></i> 詳細</a></li>
								                
                                                <?php if ( $_SESSION["MM_UserAuthorization"] == "branch" || $_SESSION["MM_UserAuthorization"] == "admin" ){ ?>
								                    <li><a href="javascript:Mars_popup('ad_send_love_branch.php?k_id=<?php echo $re_list["k_id"];?>','','status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=400,height=250,top=200,left=200');"><i class="icon-file"></i> 修改處理祕書</a></li>
                								<?php }?>
								                
                                                <?php if ( $re_list["all_type"] != "未處理" ){ ?>
								                    <li><a href="javascript:Mars_popup('ad_report.php?k_id=<?php echo $re_list["k_id"];?>&ty=love','','scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=690,height=600,top=10,left=10');"><i class="icon-list-alt"></i> 回報(<?php echo $report;?>)</a></li>
                                                <?php }?>
								                
                                                <?php if ( $_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["MM_UserAuthorization"] != "branch" ){ ?>
								                    <?php if ( $re_list["k_trans"] != "1" ){?>
							                            <li><a href="?st=trans&k_id=<?php echo $re_list["k_id"];?>"><i class="icon-share"></i> 轉入未入會</a></li>
                                                        <!--原始程式<li><a href="?st=trans&k_id=<%=rs("k_id")&strRequest("k_id")%>"><i class="icon-share"></i> 轉入未入會</a></li>-->
							                        <?php }else{?>
							                            <li><a style="color:#ccc"><i class="icon-share" style="color:#ccc"></i> 已轉未入會</a></li>
							                        <?php }?>
                                                <?php }?>

                                                <?php if ( $_SESSION["MM_UserAuthorization"] == "admin" ){?>
								                    <li><a href="#" onClick="Mars_popup2('ad_love_del.php?k_id=<?php echo $re_list["k_id"];?>','','width=300,height=200,top=100,left=100')"><i class="icon-trash"></i> 刪除</a></li>
								                <?php }?>
							                </ul>
						                </div>								
								    </td>
							    </tr>
                                <tr style="background-color:<?php echo $bgc; ?>">
                                    <td colspan="10" style="BORDER-bottom: #666666 1px dotted">
                                        <?php echo date("Y-m-d H:i",strtotime($re_list["k_time"]));?>&nbsp;&nbsp;
                                        <?php
                                        if ( $_SESSION["MM_UserAuthorization"] != "single" ){					
                                            if ( $re_list["k_cc"] != "" ){
					                            $k_cc = $re_list["k_cc"];
					                            if ( substr_count($k_cc, "sale-") > 0 ){
                                                    $k_cc_array = explode($k_cc,"-");
					  	                            $k_cc = "推廣：".SingleName($k_cc_array[1],"auto");
                                                }
						                        $k_cc = " [".$k_cc."]";
                                            }else{
						                        $k_cc = "";
					                        }
					                        echo $k_cc;
					                        if ( $re_list["k_come"] != "" ){
						                        $k_come = " [".$re_list["k_come"]."]　";
                                            }else{
						                        $k_come = "";
					                        }
					                        echo $k_come;
                                        }?>
			                            (<a href="javascript:Mars_popup('ad_report.php?k_id=<?php echo $re_list["k_id"];?>&ty=love','','scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=690,height=600,top=10,left=10');">回報(<?php echo $report;?>)</a>，
                                        處理情形：<font color="#FF0000" size="2"><?php echo $re_list["all_type"].$re_list["all_branch"];?>
                                        <?php if ( $re_list["all_single"] != "" ){ echo SingleName($re_list["all_single"],"normal");} ?></font>)
                                        內容：<?php echo $report_text;?>
                                        <?php if ( $re_list["all_note"] != "" ){?>
                                            <font color="blue">備註：</font><?php echo $re_list["all_note"];?>
                                        <?php }?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php require_once("./include/_page.php"); ?>
        </div>
        <hr>
        <!--活動搜尋彈跳視窗-->
        <div class="modal fade" id="search_div">
            <div class="modal-dialog"><div class="modal-content">
                <div class="modal-header">
                    <button type="button" id="search_div_close" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">活動搜尋</h4>
                </div>
                <div class="modal-body">
                    活動會館
                    <select name="search_branch" id="search_branch">
                        <option value="">請選擇</option>
                        <?php for ($i=0;$i<count($branch_option);$i++){?>
                            <option value="<?php echo $branch_option[$i];?>"><?php echo $branch_option[$i];?></option>
                        <?php }?>
                    </select>
                    　關鍵字：<input type="text" name="search_keyword" id="search_keyword" class="form" placeholder="請輸入要搜尋的活動關鍵字" style="width: 50%;">
                    <br><br>活動日期：
                    <input type="text" name="d1" id="d1" class="datepicker" autocomplete="off"> 至 <input type="text" name="d2" id="d2" class="datepicker" autocomplete="off">
                </div>
                <div class="modal-footer">
                    <a href="#close" id="search_div_close" class="btn btn-default" data-dismiss="modal">關閉</a>
                    <a href="#send" id="search_div_send" class="btn btn-primary">送出</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require("./include/_bottom.php");?>
<script type="text/javascript">
    $(function() {
        $("#selnums").on("click", function() {
            if ($(this).prop("checked"))
                $("input[name='nums']").each(function() {
                    $(this).prop("checked", true);
                });
            else
                $("input[name='nums']").each(function() {
                    $(this).prop("checked", false);
                });
        });

        $("#search_div_send").on("click", function() {
            //var $sv = $("#search_div_event");
            var $sb = $("#search_branch").val();
            var $sk = $("#search_keyword").val();
            var $sd1 = $("#d1").val();
            var $sd2 = $("#d2").val();
            if( $sb == "" && $sk == "" && $sd1 == "" && $sd2 == "" ) {
                alert("請輸入活動篩選條件。");
                $sb.focus();	
            } else {
                location.href="ad_action.php?sear=1&vst=full&sk="+$sk+"&sb="+$sb+"&sd1="+$sd1+"&sd2="+$sd2;
            }
        });
    });

    function mutil_send() {
        var $allnum = [];
        $("input[name='nums']").each(function() {
            if ($(this).val() && $(this).prop("checked")) $allnum.push($(this).val());
        });
        if ($allnum.length <= 0) alert("請勾選要發送的會員。");
        else Mars_popup('ad_send_love_branch_mutil.php?k_id=' + $allnum, '', 'scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=400,height=250,top=100,left=100');
    }

    function mutil_del() {
        var $allnum = [];
        $("input[name='nums']").each(function() {
            if ($(this).val() && $(this).prop("checked")) $allnum.push($(this).val());
        });
        if ($allnum.length <= 0) alert("請勾選要刪除的資料。");
        else mem_del($allnum);
    }

    function mem_del(m) {
        if (window.confirm("是否確定刪除？")) {
            myApp.showPleaseWait();
            if ($.isArray(m)) {
                $s1 = m.join(",");
                $s2 = $.each(m, function(i, val) {
                    $("#showtr_" + val + ",#showtr_" + val + "_2").remove();
                });
            } else {
                $s1 = m;
                $s2 = $("#showtr_" + m + ",#showtr_" + m + "_2").remove();
            }

            $.ajax({
                url: "ad_love_del.php",
                data: {
                    t: "n",
                    k_id: $s1
                },
                type: "POST",
                dataType: "text",
                success: function(msg) {
                    $s2;
                    myApp.hidePleaseWait();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });

        } else alert("請重新選擇。");
    }

    function chk_search_form() {
        if (!$("#keyword_type").val()) {
            alert("請選擇要搜尋的類型。");
            $("#keyword_type").focus();
            return false;
        }
        if (!$("#keyword").val()) {
            alert("請輸入要搜尋的關鍵字。");
            $("#keyword").focus();
            return false;
        }
        location.href = "ad_action.php?sear=1&vst=full&" + $("#keyword_type").val() + "=" + $("#keyword").val();
        return false;
    }

    function event_search() {
        $("#search_div").modal("show");
    }
</script>
<script language="JavaScript">
    function full_btn(vst_val){
        document.getElementById("vst").value = vst_val;
        searchform.submit();
    }
</script>

<script languagee="javascript">
    



    function action_type_link(action){
        if ( action == "a1" ){ 
            mutil_send();
        }else if ( action == "a2" ){
            mutil_del();
        }else if ( action == "a3" ){ //切換未處理
            location.href="ad_action.php?s99=1";
        }else if ( action == "a4" ){ //切換已處理
            location.href="ad_action.php";
        }else if ( action == "a5" ){ //進階搜尋
            location.href="ad_love_f.php";
        }else if ( action == "a6" ){ //活動搜尋
            $("#search_div").modal("show");
        }else if ( action == "a7" ){
            
        }
    }

    function mutil_send() {
		var $allnum = [];
		$("input[name='nums']").each(function() {
			if($(this).val() && $(this).prop("checked")) $allnum.push($(this).val());
		});
		if($allnum.length <= 0) alert("請勾選要發送的會員。");
		else Mars_popup('ad_send_love_branch_mutil.php?k_id='+$allnum,'','scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=400,height=250,top=100,left=100');
	}

    function mutil_del() {
		var $allnum = [];
		$("input[name='nums']").each(function() {
			if($(this).val() && $(this).prop("checked")) $allnum.push($(this).val());
		});
		if($allnum.length <= 0) alert("請勾選要刪除的資料。");
		else mem_del($allnum);
	}
	function mem_del(m) {
		if (window.confirm("是否確定刪除？")) {   
			//myApp.showPleaseWait();
			if($.isArray(m)) {
				$s1 = m.join(",");
				//$s2 = $.each(m, function(i, val) { $("#showtr_"+val+",#showtr_"+val+"_2").remove(); }); 
			} else {
				$s1 = m;
				//$s2 = $("#showtr_"+m+",#showtr_"+m+"_2").remove();
			}
			$.ajax({
				url: "ad_love_del.php",
				data: {t:"n", k_id : $s1 },
				type:"POST",
				dataType:"text",
				success: function(msg){
                alert("刪除成功。");
                location.reload();
			    }
            });
		} else alert("請重新選擇。");
	}
</script>