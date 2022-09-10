<?php  
/*****************************************/
//檔案名稱：dmnweb_fun12.php
//後台對應位置：DateMeNow網站系統/網站會員
//改版日期：2022.8.25
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/
require_once("_inc.php");
require_once("./include/_function.php");
require_once("./include/_top.php");
require_once("./include/_sidebar_dmn.php");

// 程式開始
if ($_SESSION["MM_Username"] == "") {
    call_alert("請重新登入。", "login.php", 0);
}

if ($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["dmnweb"] != "1") {
    call_alert("您沒有查看此頁的權限。", "login.php", 0);
}
//刪除
if($_REQUEST["st"] == "del"){
    $SQL = "select HeadPhotoURL from UserData where UserSN='".SqlFilter($_REQUEST["an"],"int")."'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        if($result["HeadPhotoURL"] != ""){
            DelFile("dphoto/".$result["HeadPhotoURL"]);
        }
    }
    reURL("win_close.php?m=刪除中...");
}

?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>DateMeNow網站系統</li>
            <li class="active">網站會員</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>網站會員</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p>
                <form action="?st=x" method="post" target="_self" onsubmit="return chk_s_form()" class="form-inline" style="display:inline-block;margin:0px;">
                    <input type="text" name="keyword" id="keyword" class="form-control" placeholder="請輸入要搜尋的會員編號">&nbsp;<input type="submit" class="btn btn-warning" value="搜尋網站會員">
                </form>&nbsp;&nbsp;&nbsp;
                </p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th>照片</th>
                            <th>ID</th>
                            <th>暱稱</th>
                            <th>性別</th>
                            <th>年次</th>
                            <th>城市</th>
                            <th>職業</th>
                            <th>學歷</th>
                            <th>星座</th>
                            <th>身高</th>
                            <th>操作</th>
                        </tr>
                        <?php 
                            // 會員編號搜尋
                            if($_REQUEST["keyword"] != ""){
                                if(strpos($_REQUEST["keyword"],",")){
                                    $allKeyword = [];
                                    foreach(explode(",",$_REQUEST["keyword"]) as $keyword){
                                        array_push($allKeyword,"(UserId = '".$keyword."')");
                                    }
                                    $sqls = $sqls . " and (".join(" or ",$allKeyword).")";
                                }else{
                                    $sqls = $sqls . " and UserId like '%".$_REQUEST["keyword"]."%'";
                                }                                
                            }else{
                                $sqls = "";
                            }

                            //取得總筆數
                            $SQL = "Select count(UserID) As total_size FROM UserData where 1=1 ".$sqls."";
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
                            $SQL_list .= "over(order by UserSN desc) As rownumber, * ";
                            $SQL_list .= "From UserData Where 1=1 ".$sqls." ) temp_row ";
                            $SQL_list .= "Where rownumber > ".($tPageSize*$tPage_list);

                            $rs = $DMNConn->prepare($SQL_list);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                foreach($result as $re){ ?> 
                                    <tr>
                                        <td>
                                            <?php 
                                                $hpu = $re["HeadPhotoURL"];
                                                if($hpu != "" && strpos($hpu,"boy.") <=0 && strpos($hpu,"girl.") <=0){
                                                    echo "<a href='dphoto/".$hpu."' class='fancybox'><img src='dphoto/".$hpu."' border=0 height=40></a>";
                                                }else{
                                                    echo "無";
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $re["UserID"];?></td>
				                        <td><?php echo $re["Nickname"];?></td>
                                        <td>
                                            <?php 
                                                if($re["Gender"] == "M"){
                                                    echo "男";
                                                }else{
                                                    echo "女";
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $re["Generation"];?></td>					
                                        <td><?php echo $re["City"];?></td>
                                        <td><?php echo $re["Occupation"];?></td>
                                        <td><?php echo $re["Education"];?></td>
                                        <td><?php echo $re["Constellation"];?></td>
                                        <td><?php echo $re["Tall"];?></td>
                                        <td>					
                                            <a title="刪除" href="#d" onclick="Mars_popup2('dmnweb_fun12.php?st=del&an=<?php echo $re['UserSN']; ?>','','status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=380,height=200,top=200,left=200');">刪除</a>						
                                        </td>
                                    </tr>
                            <?php } 
                            }else{
                                echo "<tr><td colspan=4>目前無資料</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- 頁碼 -->
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
require_once("./include/_bottom.php")
?>

<script language="JavaScript">

    function chk_s_form() {
        if (!$("#keyword").val()) {
            alert("請輸入要搜尋的會員編號。");
            $("#keyword").focus();
            return false;
        }
        return true;
    }
</script>