<?php
/*****************************************/
//檔案名稱：dmnweb_fun11.php
//後台對應位置：DateMeNow網站系統/精選會員
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
    $SQL = "delete from IndexHotUser where BoardSN='".SqlFilter($_REQUEST["an"],"int")."'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    reURL("dmnweb_fun10.php");
}

//新增
if($_REQUEST["st"] == "add" && $_REQUEST["userid"] != ""){
    $SQL = "select * from UserData where UserId='".SqlFilter($_REQUEST["userid"],"tab")."'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        $UserId = $result["UserID"];
        $NickName = $result["NickName"];
        $Gender = $result["Gender"];
        $Generation = $result["Generation"];
        $Education = $result["Education"];
        $City = $result["City"];		
        $HeadPhotoURL = $result["HeadPhotoURL"];
        $Constellation = $result["Constellation"];
        $Occupation = $result["Occupation"];
        $Tall = $result["Tall"];
        $InsertDate = str_replace("/", "",date("Y/m/d"));
    }else{
        call_alert("會員資料庫中沒有此編號。", "dmnweb_fun10.php", 0);
    }
    
    if($UserId != ""){
        $SQL = "select * from IndexHotUser where UserId='".$UserId."'";
        $rs = $DMNConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            call_alert("精選會員中已有此編號。", "dmnweb_fun10.php", 0);
        }else{
            $SQL = "INSERT INTO IndexHotUser (UserID,VisitNum,VerifyLevel,NickName,Gender,Occupation,Generation,Education,City,HeadPhotoURL,Constellation,Tall,InsertDate) VALUES ('".$UserId."','1','1','".$NickName."','".$Gender."','".$Occupation."','".$Generation."','".$Education."','".$City."','".$HeadPhotoURL."','".$Constellation."','".$Tall."','".$InsertDate."')";
            $rs = $DMNConn->prepare($SQL);
            $rs->execute();
        }      
    }
    call_alert("新增完成。", "dmnweb_fun10.php", 0);
   
}
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>DateMeNow網站系統</li>
            <li class="active">精選會員</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>精選會員</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p>
                <form action="?st=x" method="post" target="_self" onsubmit="return chk_s_form()" class="form-inline" style="display:inline-block;margin:0px;">
                    <input type="text" name="keyword" id="keyword" class="form-control" placeholder="請輸入要搜尋的會員編號">&nbsp;<input type="submit" class="btn btn-warning" value="搜尋精選會員">
                </form>&nbsp;&nbsp;&nbsp;
                <form action="?st=add" method="post" target="_self" onsubmit="return chk_add_form()" class="form-inline" style="display:inline-block;margin:0px;">
                    <input type="text" name="userid" id="userid" class="form-control" placeholder="請輸入要新增的會員編號">&nbsp;<input type="submit" class="btn btn-info" value="新增精選會員">
                </form>
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
                            // 關鍵字篩選
                            if($_REQUEST["keyword"] != ""){
                                $sqls = $sqls . " and UserId like '%".$_REQUEST["keyword"]."%'";
                            }else{
                                $sqls = "";
                            }

                            //取得總筆數
                            $SQL = "Select count(BoardSN) As total_size FROM IndexHotUser where 1=1 ".$sqls."";
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
                            $SQL_list .= "over(order by BoardSN desc) As rownumber, BoardSN, UserID, VisitNum, NickName, Gender, City, HeadPhotoURL, VerifyLevel, Constellation, Tall, InsertDate, Generation, Education, Occupation ";
                            $SQL_list .= "From IndexHotUser Where 1=1 ".$sqls." ) temp_row ";
                            $SQL_list .= "Where rownumber > ".($tPageSize*$tPage_list);

                            $rs = $DMNConn->prepare($SQL_list);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            
                            if($result){
                                foreach($result as $re){ ?> 
                                    <tr>
                                        <td>
                                            <?php 
                                                if($re["HeadPhotoURL"] != ""){
                                                    echo "<a href='dphoto/".$re["HeadPhotoURL"]."' class='fancybox'><img src='dphoto/".$re["HeadPhotoURL"]."' border=0 height=40></a>";
                                                }else{
                                                    echo "無";
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $re["UserID"]; ?></td>
                                        <td><?php echo $re["NickName"]; ?></td>
                                        <td>
                                            <?php 
                                                if($re["Gender"] == "M"){
                                                    echo "男";
                                                }else{
                                                    echo "女";
                                                }
                                            ?>
                                        </td>					
                                        <td><?php echo $re["Generation"]; ?></td>					
                                        <td><?php echo $re["City"]; ?></td>
                                        <td><?php echo $re["Occupation"]; ?></td>
                                        <td><?php echo $re["Education"]; ?></td>
                                        <td><?php echo $re["Constellation"]; ?></td>
                                        <td><?php echo $re["Tall"]; ?></td>
                                        <td>					
                                            <a title="刪除" href="dmnweb_fun10.php?st=del&an=<?php echo $re["BoardSN"]; ?>">刪除</a>						
                                        </td>
                                    </tr>
                                <?php }
                            }else{
                                echo "<tr><td colspan=11>目前無資料</td></tr>";
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
    $(function() {

    });

    function chk_s_form() {
        if (!$("#keyword").val()) {
            alert("請輸入要搜尋的會員編號。");
            $("#keyword").focus();
            return false;
        }
        return true;
    }

    function chk_add_form() {
        if (!$("#userid").val()) {
            alert("請輸入要搜尋的會員編號。");
            $("#userid").focus();
            return false;
        }
        return true;
    }
</script>