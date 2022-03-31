<?php
    /*****************************************/
    //檔案名稱：ad_fun_action_list1.php
    //後台對應位置：好好玩管理系統/好好玩國內團控
    //改版日期：2021.12.1
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");
    require_once("./include/_top.php");
    require_once("./include/_sidebar.php");

    //程式開始 *****
    if ($_SESSION["MM_Username"] == "") {
        call_alert("請重新登入。", "login.php", 0);
    }

    // 接收值
    $vst = SqlFilter($_REQUEST["vst"],"tab");

    // 刪除國內活動並刪除圖檔(待測試刪除圖檔)
    if($_REQUEST["st"] == "del"){
        $SQL = "SELECT * FROM action_data WHERE ac_auto = ".SqlFilter($_REQUEST["ac"],"int");
        $rs = $FunConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $SQL2 = "DELETE FROM action_data WHERE ac_auto = ".SqlFilter($_REQUEST["ac"],"int");
            $rs2 = $FunConn->prepare($SQL2);
            $rs2->execute();
            if($rs2){
                if($result["ac_pic"] != ""){
                    DelFile("webfile/funtour/upload_image/".$result["ac_pic"]);
                }
                if($result["ac_pic2"] != ""){
                    DelFile("webfile/funtour/upload_image/".$result["ac_pic2"]);
                }
                if($result["ac_pic3"] != ""){
                    DelFile("webfile/funtour/upload_image/".$result["ac_pic3"]);
                }
                if($result["ac_pic4"] != ""){
                    DelFile("webfile/funtour/upload_image/".$result["ac_pic4"]);
                }
                reURL("win_close.php?m=刪除中...");
                exit();       
            }
        }
    }

    $default_sql_num = 500; // 初始查詢數字    
    if( $_REQUEST["vst"] == "full" ){
        $sqlv = "*";
        $sqlv2 = "count(ac_auto)";        
    }else{
        $sqlv = "top " .$default_sql_num. " *";
        $sqlv2 = "count(ac_auto)";
    }

    // 只取全部的筆數
    $sqls2 = "SELECT ".$sqlv2." as total_size FROM action_data WHERE  1=1";


    // 活動類型搜尋
    if($_REQUEST["types"] != "" ){
        $types = SqlFilter($_REQUEST["types"],"tab");
        $sqlss = $sqlss . " and ac_kind = '" .$types."'";
    }    

    // 活動標題搜尋
    if($_REQUEST["keyword"] != "" ){
        $keyword = SqlFilter($_REQUEST["keyword"],"tab");
        $sqlss = $sqlss . " and ac_title like '%" .$keyword."%'";
    }

    // 開發者搜尋
    if($_REQUEST["oby"] != "" ){
        $oby = SqlFilter($_REQUEST["oby"],"tab");
        $sqlss = $sqlss . " and ac_open2 = '" .$oby."'";
    }


    if( $_REQUEST["times1"] != "" && $_REQUEST["times2"] != "" ){
        if( chkDate($_REQUEST["times1"]) && chkDate($_REQUEST["times2"]) ){
            $times1_1 = SqlFilter($_REQUEST["times1"],"tab"). " 00:00";
            $times1 = SqlFilter($_REQUEST["times1"],"tab");
            $times2_1 = SqlFilter($_REQUEST["times2"],"tab"). " 23:59";
            $times2 = SqlFilter($_REQUEST["times2"],"tab");
        }        
    }

    // 以活動時間段搜尋
    if(chkDate($times1_1) && chkDate($times2_1)){        
        if(strtotime($times1_1) > strtotime($times2_1)){
            call_alert("結束日期不能小於起始日期。", 0, 0);
        }
        $sqlss = $sqlss . " and ac_time between '".$times1_1."' and '".$times2_1."'";
    }

    // 特殊使用者搜尋
    if(strtoupper($_SESSION["MM_Username"]) == "V221540975"){
        $sqlss = $sqlss . " and ac_run2 = '".$_SESSION["MM_Username"]."'";
    }

    // 總筆數SQL
    $sqls2 = $sqls2 . $sqlss;
    // 查詢總筆數
    $rs = $FunConn->prepare($sqls2);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if (!$result){
        $total_size = 0;
    }else{
        if( $_REQUEST["vst"] == "full" ){
            $total_size = $result["total_size"]; //總筆數
        }else{
            if($result["total_size"] > 500 ) {
                $total_size =  500;
            }else{
                $total_size =  $result["total_size"];
            }  
        }
    }

    $tPage = 1; //目前頁數
    $tPageSize = 50; //每頁幾筆
	if ( $_REQUEST["tPage"] > 1 ){ $tPage = $_REQUEST["tPage"];}
	$tPageTotal = ceil(($total_size/$tPageSize)); //總頁數
	if ( $tPageSize*$tPage < $total_size ){
		$page2 = 50;
	}else{
		$page2 = (50-(($tPageSize*$tPage)-$total_size));
	}

    // SQL
    $sqls = "SELECT ".$sqlv." FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM action_data WHERE 1=1";
    $sqls = $sqls . $sqlss ." order by ac_time desc ) t1 order by ac_time) t2 order by ac_time desc";

    if($_REQUEST["vst"] == "full"){
        $total_sizen = $total_size . "　<a href='?vst=n&s99=".$_REQUEST["s99"]."'>[查看前五百筆]</a>";
    }else{
        if( $total_size > 500 ) $total_size = 500;
        $total_sizen = $total_size . "　<a href='?vst=full&s99=".$_REQUEST["s99"]."'>[查看完整清單]</a>";
    }
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li>好好玩管理系統</li>
            <li class="active">好好玩國內團控</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>好好玩國內團控 - 數量：<?php echo $total_sizen; ?></strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <div class="col-md-12">
                    <a href="ad_fun_action_list1_add.php" class="btn btn-info margin-bottom-10"><i class="icon-plus-sign"></i> 新增國內活動</a>
                    <a href="ad_fun_action_list1_print.php?acre_sign1=<?php echo $times1_1; ?>&acre_sign2=<?php echo $times2_1; ?>" class="btn btn-info margin-bottom-10"><i class="icon-plus-sign"></i> 列印本頁</a>


                    <form id="searchform" action="ad_fun_action_list1.php?vst=full" method="post" target="_self" class="form-inline" onsubmit="return check_send_submit()">
                        <table class="table table-striped table-bordered bootstrap-datatable">
                            <tr>
                                <td><select name="types">
                                        <option value="">活動類型</option>
                                        <option value="戶外活動"<?php if($types == "戶外活動") echo " selected"; ?>>戶外活動</option>
                                        <option value="主題派對"<?php if($types == "主題派對") echo " selected"; ?>>主題派對</option>
                                        <option value="午茶聯誼"<?php if($types == "午茶聯誼") echo " selected"; ?>>午茶聯誼</option>
                                        <option value="特別企劃"<?php if($types == "特別企劃") echo " selected"; ?>>特別企劃</option>
                                    </select>
                                    &nbsp;&nbsp;
                                    <select name="oby">
                                        <option value="">開發者</option>
                                        <?php 
                                            $SQL = "select distinct ac_open2 from action_data where ac_open2 <> ''";
                                            $rs = $FunConn->prepare($SQL);
                                            $rs->execute();
                                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                                            if($result){
                                                foreach($result as $re){
                                                    if($oby == $re["ac_open2"]){
                                                        echo "<option value='".$re["ac_open2"]."' selected>".SingleName($re["ac_open2"],"normal")."</option>";
                                                    }else{
                                                        echo "<option value='".$re["ac_open2"]."'>".SingleName($re["ac_open2"],"normal")."</option>";
                                                    }                                                    
                                                }                                                
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td>活動時間：<input name="times1" id="times1" type="text" class="datepicker" autocomplete="off" value="<?php echo $times1 ?>">～<input name="times2" id="times2" type="text" class="datepicker" autocomplete="off" value="<?php echo $times2 ?>"></td>
                                <td>活動標題：<input name="keyword" type="text" class="form-control" value="<?php echo $keyword; ?>"></td>
                                <td><input type="submit" value="搜尋" class="btn btn-default"></td>
                            </tr>
                        </table>
                    </form>
                </div>
                <table class="table table-striped table-bordered bootstrap-datatable">

                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th width=180>活動時間</th>
                            <th width=70>活動類型</th>
                            <th width=70>活動地點</th>
                            <th width=300>活動標題</th>
                            <th></th>
                            <th width=40>花絮</th>
                            <th width=80></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $rs = $FunConn->prepare($sqls);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if(!$result){
                                echo "<tr><td colspan=8 height=200>目前沒有資料</td></tr>";
                            }else{
                                foreach($result as $re){
                                    $sql = "select count(ac_photo_auto) as tt from action_photo where ac_auto = ".$re["ac_auto"];
                                    $rs2 = $FunConn->query($sql);
                                    $result2 = $rs2->fetch(PDO::FETCH_ASSOC);
                                    if($result2){
                                        $ac_photo_size = $result2["tt"];
                                    }
                                    if($re["ac_pic"] != ""){
                                        $ac_pic =  "<a href='webfile/funtour/upload_image/".$re["ac_pic"]."' class='fancybox'><img src='webfile/funtour/upload_image/".$re["ac_pic"]."' border=0 width=90 height=60></a>";
                                    }
                                ?>
                                    <tr>
                                        <td class="center"><?php echo $re["ac_auto"]; ?></td>
                                        <td class="center"><?php echo $ac_pic ?></td>
                                        <td class="center"><?php echo changeDate($re["ac_time"]); ?></td>
                                        <td class="center"><?php echo $re["ac_kind"]; ?></td>
                                        <td class="center"><?php echo $re["ac_area"]; ?></td>
                                        <td class="center"><?php echo $re["ac_title"]; ?></td>
                                        <td class="center">
                                            <font color="blue">
                                                來源：<?php echo $re["ac_come"]; ?>　開發者：<?php echo $re["ac_open1"]; ?><?php echo SingleName($re["ac_open2"],"normal"); ?>　執行者：<?php echo $re["ac_run1"]; ?><?php echo SingleName($re["ac_run2"],"normal"); ?>
                                            </font>                                            
                                            <?php 
                                                $bsize  = 0;
                                                $bsize2 = 0;
                                                $gsize  = 0;
                                                $gsize2 = 0;
                                                $tsize  = 0;
                                                $sql = "SELECT k_sex, k_be FROM love_keyin WHERE all_kind <> '國外旅遊' and ac_auto = ".$re["ac_auto"];
                                                $rs2 = $FunConn->query($sql);
                                                $result2 = $rs2->fetchAll(PDO::FETCH_ASSOC);
                                                if($result2){
                                                    foreach($result2 as $re2){                                                    
                                                        if($re2["k_sex"] == "男"){
                                                            if($re2["k_sex"] == 1){
                                                                $bsize2 = $bsize2 + 1;
                                                            }else{
                                                                $bsize = $bsize + 1;
                                                            }
                                                        }else{
                                                            if($re2["k_be"] == 1){
                                                                $gsize2 = $gsize2 + 1;
                                                            }else{
                                                                $gsize = $gsize + 1;
                                                            }
                                                        }
                                                    }
                                                    $tsize = $gsize + $gsize2 + $bsize + $bsize2;
                                                }
                                            ?>
                                            <br><br>
                                            <a href="ad_fun_action_list_singup1.php?ac=<?php echo $re["ac_auto"]; ?>">男：正取 <?php echo $bsize; ?>/備取 <?php echo $bsize2; ?> 人、女：正取 <?php echo $gsize; ?>/備取 <?php echo $gsize2; ?> 人、共：<?php echo $tsize; ?> 人</a>
                                        </td>
                                        <td class="center">
                                            <?php 
                                                if($ac_photo_size > 0){
                                                    echo "<a href='ad_fun_action_pic.asp?ac_auto=".$re["ac_auto"]."' target='_blank'>有</a>";
                                                }else{
                                                    echo "無";
                                                }
                                            ?>
                                        </td>
                                        <td class="center">
                                            <div class="btn-group">
                                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">操作 <span class="caret"></span></button>
                                                <ul class="dropdown-menu pull-right">
                                                    <li><a href="http://funtour.com.tw/eventdetail.asp?id=<?php echo $re["ac_auto"]; ?>" target="_blank"><i class="icon-file"></i> 詳細</a></li>
                                                    <li><a href="ad_fun_action_list_singup1.php?ac=<?php echo $re["ac_auto"]; ?>"><i class="icon-file"></i> 報名資料</a></li>
                                                    <?php 
                                                        if($_SESSION["MM_UserAuthorization"] == "admin" || $_SESSION["funtourall1"] == "1" || strtoupper($_SESSION["MM_Username"]) == "V221540975"){ ?>
                                                            <li><a href="ad_fun_action_pic.php?ac_auto=<?php echo $re["ac_auto"]; ?>" target="_blank"><i class="icon-file"></i> 上傳花絮</a></li>
                                                            <li><a href="ad_fun_action_list1_add.php?ac=<?php echo $re["ac_auto"]; ?>"><i class="icon-edit"></i> 修改</a></li>
                                                        <?php }
                                                        if($_SESSION["MM_UserAuthorization"] == "admin"){ ?>
                                                            <li><a href="#" onClick="Mars_popup2('ad_fun_action_list1.php?st=del&ac=<?php echo $re["ac_auto"]; ?>','','width=300,height=200,top=100,left=100')"><i class="icon-trash"></i> 刪除</a></li>
                                                        <?php }
                                                    ?>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php require_once("./include/_page.php"); ?>

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

    function check_send_submit() {
        if (($("#times1").val() && !$("#times2").val()) || ($("#times2").val() && !$("#times1").val())) {
            alert("請正確選擇活動時間的兩個區間時間。");
            return false;
        }
        if ($("#times1").val() && $("#times2").val()) {
            if ($("#times1").val() > $("#times2").val()) {
                alert("起始活動時間不可以比結束活動時間大。");
                return false;
            }
        }

        return true;
    }
</script>