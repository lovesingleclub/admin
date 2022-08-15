<?php
/*****************************************/
//檔案名稱：dmnweb_fun5.php
//後台對應位置：DateMeNow網站系統/首頁-Banner
//改版日期：2022.8.15
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/
    
require_once("_inc.php");
require_once("./include/_function.php");
require_once("./include/_top.php");
require_once("./include/_sidebar_dmn.php");

// 程式開始
if($_SESSION["MM_Username"] == ""){
    call_alert("請重新登入。","login.php",0);
}

if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["dmnweb"] != "1"){
    call_alert("您沒有查看此頁的權限。","login.php",0);
}


// 圖片上移
if($_REQUEST["st"] == "mup"){
    $nowline = round(SqlFilter($_REQUEST["i1"],"int"));
    $upline = $nowline+1;
    $SQL = "update webdata set i1=".$nowline." where i1='".$upline."' and types='newindex_banner'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $SQL = "update webdata set i1=".$upline." where auton=".SqlFilter($_REQUEST["an"],"int")." and types='newindex_banner'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();

    reURL("dmnweb_fun5.php");
}

// 圖片下移
if($_REQUEST["st"] == "mdo"){
    $nowline = round(SqlFilter($_REQUEST["i1"],"int"));
    $upline = $nowline-1;
    $SQL = "update webdata set i1=".$nowline." where i1=".$upline." and types='newindex_banner'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $SQL = "update webdata set i1=".$upline." where auton=".SqlFilter($_REQUEST["an"],"int")." and types='newindex_banner'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();

    reURL("dmnweb_fun5.php");
}

// 刪除圖片(待測試)
if($_REQUEST["st"] == "del"){
    $SQL = "select d2 from webdata where auton=".SqlFilter($_REQUEST["an"],"int")." and types='newindex_banner'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        DelFile(("datemenow_image/upload/".$result["d2"]));
        $SQL = "delete from webdata where auton=".SqlFilter($_REQUEST["an"],"int")." and types='newindex_banner'";
        $rs = $DMNConn->prepare($SQL);
        $rs->execute();
        if($rs){
            reURL("dmnweb_fun5.php");
        }
    }        
}
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>DateMeNow網站系統</li>
            <li class="active">首頁-Banner</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>首頁-Banner</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">

                <p><input type="button" class="btn btn-info" value="新增 Banner" onclick="Mars_popup('dmnweb_fun5_add.php','','scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=690,height=300,top=10,left=10');"></p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th width="70"></th>
                            <th>圖片</th>
                            <th>連結位置</th>
                            <th>ALT</th>
                            <th width="160">資料時間</th>
                            <th>操作</th>
                        </tr>
                        <?php 
                            $SQL = "SELECT * FROM webdata where types='newindex_banner' order by i1 desc";
                            $rs = $DMNConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                $ii = 0;
                                foreach($result as $re){
                                    if($ii == 0){
                                        $uahref = "#nu\" onclick=\"alert('無法向上');\"";
                                    }else{
                                        $uahref = "?st=mup&an=".$re["auton"]."&i1=".$re["i1"];
                                    }                                   
                                    if($ii == count($result)-1){
                                        $dahref = "#nu\" onclick=\"alert('無法向下');\"";
                                    }else{
                                        $dahref = "?st=mdo&an=".$re["auton"]."&i1=".$re["i1"];
                                    } ?>
                                    <tr>
                                        <td><a href="<?php echo $uahref; ?>"><span class="fa fa-arrow-up margin-left-10 margin-right-10"></span></a><a href="<?php echo $dahref; ?>"><span class="fa fa-arrow-down"></span></a></td>
                                        <td><a href="datemenow_image/upload/<?php echo $re["d2"] ?>" class="fancybox"><img src="datemenow_image/upload/<?php echo $re["d2"] ?>" border=0 height=40></a></td>
                                        <td><?php echo $re["d1"]; ?></td>
                                        <td><?php echo $re["alt"]; ?></td>
                                        <td><?php echo changeDate($re["t1"]); ?></td>
                                        <td>
                                            <a href="javascript:Mars_popup('dmnweb_fun5_add.php?an=<?php echo $re["auton"] ?>','','scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=690,height=300,top=10,left=10');">編輯</a>
                                            <a title="刪除" href="dmnweb_fun5.php?st=del&an=<?php echo $re["auton"] ?>">刪除</a>						
                                        </td>
                                    </tr>
                                <?php $ii = $ii+1; } 
                            }else{
                                echo "<tr><td colspan=4>目前無資料</td></tr>";
                            }
                        ?>
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
require_once("./include/_bottom.php")
?>