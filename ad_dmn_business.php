<?php
/*****************************************/
//檔案名稱：ad_dmn_business.php
//後台對應位置：名單/發送記錄>DMN企業專區
//改版日期：2021.10.22
//改版設計人員：Jack
//改版程式人員：Queena
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");
require_once("./include/_top.php");
require_once("./include/_sidebar.php");

//判斷登入
if ( $_SESSION["MM_Username"] == "" ){ call_alert_new(3,"請重新登入。","login.php"); }

//判斷權限
$auth_limit = 4;
require_once("./include/_limit.php");

//麵包屑
$unitprocess = $m_home.$icon."名單/發送記錄".$icon."DMN企業專區";

//接收值
$st = SqlFilter($_REQUEST["st"],"tab");
$id = SqlFilter($_REQUEST["id"],"tab");
$s99 = SqlFilter($_REQUEST["s99"],"tab");

//刪除
if ( $st == "del" ){
    $SQL = "Delete From business_contact Where id='".$id."'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    reURL("win_close.php?m=刪除中...");
    exit;
}

if ( $s99 != "1" ){
    $subSQL1 .= "And (all_note IS NULL) ";
    $all_type = "未處理";
}else{
    $subSQL1 .= "And not (all_note IS NULL) ";
    $all_type = "已處理";
}
$subSQL2 = "Order By id Desc";

//取得總筆數
$SQL = "Select count(id) As total_size From business_contact Where 1=1 ".$subSQL1;
$rs = $DMNConn->prepare($SQL);
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
$tPage_list = 0;
if ( $_REQUEST["tPage"] > 1 ){ 
    $tPage = $_REQUEST["tPage"];
    $tPage_list = ($tPage-1);
}
$tPageTotal = ceil(($total_size/$tPageSize)); //總頁數

//分頁語法
$SQL_list  = "Select Top ".$tPageSize." * ";
$SQL_list .= "From (Select row_number() ";
$SQL_list .= "over(".$subSQL2.") As rownumber, name, email, companyphone, company, create_time, all_type, all_note, id ";
$SQL_list .= "From business_contact Where 1=1 ".$subSQL1." ) temp_row ";
$SQL_list .= "Where rownumber > ".($tPageSize*$tPage_list);
$rs_list = $DMNConn->prepare($SQL_list);
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
            <h2 class="pageTitle">名單/發送功能 》DMN企業專區 》資料列表 [ <i style="color: #76192e;">共計 <?php echo $total_size."筆資料</i> ]"; if ( $total_size >= 500 ){ echo $count_href;}?></h2>
            <p>
				<a class="btn btn-info<?php if ( $s99 != 1 ){ echo " btn-active";} ?>"<?php if ( $s99 == 1 ){ ?> href="ad_dmn_business.php"<?php }else{?> style="cursor:default"<?php }?>>未處理</a>
				<a class="btn btn-info<?php if ( $s99 == 1 ){ echo " btn-active";} ?>"<?php if ( $s99 != 1 ){ ?> href="?s99=1"<?php }else{?> style="cursor:default"<?php }?>>已處理</a>
			</p>
            <span>
                <strong style="background-color: yellow; color:brown">
                    ※ 排序欄位：系統編號[由大到小]。<br>
                    ※ 每頁 20 筆資料。
                </strong>
            </span>
            <div class="panel-body">
                <table class="table table-bordered bootstrap-datatable table-hover">
                    <thead>
                        <tr style="background-color: #FFDA96;">
                            <th width="6%">姓名</th>
                            <th width="10%">E-mail</th>
                            <th width="10%">電話</th>
                            <th width="10%">公司名稱</th>
                            <th width="12%">留言時間</th>
                            <th>&nbsp;</th>
                            <th width="5%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ( count($result_list) == 0 ){
                            echo "<tr><td colspan='8' height='200'>目前沒有資料</td></tr>";
                        }else{ 
                            foreach($result_list as $re_list){ ?>
                                <tr>
                                    <td class="center"><?php echo $re_list["name"]; ?></td>
                                    <td class="center"><?php echo $re_list["email"]; ?></td>
                                    <td class="center"><?php echo $re_list["companyphone"]; ?></td>
                                    <td class="center"><?php echo $re_list["company"]; ?></td>
                                    <td class="center"><?php echo changeDate($re_list["create_time"]); ?></td>
                                    <td>
                                        處理情形：<font color="#8A8A00">
                                            <?php
                                            if ( $re_list["all_type"] == "已處理" ){
                                                echo "(".$re_list["all_type"].")";
                                            }
                                            echo $re_list["all_note"]; ?>
                                        </font>
                                    </td>
                                    <td width="80" class="center">
                                        <div class="btn-group">
                                            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">操作 <span class="caret"></span></button>
                                            <ul class="dropdown-menu pull-right">
                                                <?php if ( $_SESSION["MM_UserAuthorization"] == "admin" ){ ?>
                                                    <li><a href="javascript:Mars_popup2('ad_dmn_business.php?st=del&id=<?php echo $re_list["id"];?>','','status=yes,menubar=yes,resizable=yes,scrollbars=yes,width=300,height=200,top=150,left=150');"><i class="icon-trash"></i> 刪除</a></li>
                                                <?php }?>
                                                <li><a href="javascript:Mars_popup('ad_dmn_business_fix.php?id=<?php echo $re_list["id"];?>','','status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=500,height=320,top=100,left=100');"><i class="icon-pencil"></i> 處理</a></li>
                                            </ul>
                                        </div>
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
    <!--/.fluid-container-->
</section>
<!-- /MIDDLE -->
<?php require_once("./include/_bottom.php"); ?>