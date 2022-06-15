<?php

/*****************************************/
//檔案名稱：singleweb_fun22.php
//後台對應位置：約會專家系統/GT活動管理
//改版日期：2022.6.1
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");
require_once("./include/_top.php");
require_once("./include/_sidebar_single.php");

//程式開始 *****
if ($_SESSION["MM_Username"] == "") {
    call_alert("請重新登入。", "login.php", 0);
}

if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["singleweb"] != "1"){
    call_alert("您沒有查看此頁的權限。", "login.php", 0);
}

// 上移
if($_REQUEST["st"] == "mup"){
	$nowline = round(SqlFilter($_REQUEST["i1"],"int"));
	$upline = $nowline+1;
	$SQL = "update si_webdata set i1=".$nowline." where i1='".$upline."'";
	$rs = $SPConn->prepare($SQL);
	$rs->execute();
	$SQL = "update si_webdata set i1=".$upline." where auton=".SqlFilter($_REQUEST["an"],"int")."";
	$rs = $SPConn->prepare($SQL);
	$rs->execute();

	reURL("singleweb_fun22.php");
}

// 下移
if($_REQUEST["st"] == "mdo"){
	$nowline = round(SqlFilter($_REQUEST["i1"],"int"));
	$upline = $nowline-1;
	$SQL = "update si_webdata set i1=".$nowline." where i1=".$upline."";
	$rs = $SPConn->prepare($SQL);
	$rs->execute();
	$SQL = "update si_webdata set i1=".$upline." where auton=".SqlFilter($_REQUEST["an"],"int")."";
	$rs = $SPConn->prepare($SQL);
	$rs->execute();

	reURL("singleweb_fun22.php");
}

// 刪除圖片(待測試)
if($_REQUEST["st"] == "del"){
	$SQL = "select d5 from si_webdata where auton=".SqlFilter($_REQUEST["an"],"int")."  and types='event_201220'";
	$rs = $SPConn->prepare($SQL);
	$rs->execute();
	$result = $rs->fetch(PDO::FETCH_ASSOC);
	if($result){
        if($result["d5"] != ""){
            foreach(explode(",", $result["d5"]) as $dd5){
                DelFile(("singleparty_image/event_custom/".$dd5));
            }
        }
		
		$SQL = "delete from si_webdata where auton=".SqlFilter($_REQUEST["an"],"int")." and types='event_201220'";
		$rs = $SPConn->prepare($SQL);
		$rs->execute();
		if($rs){
			reURL("singleweb_fun22.php");
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
            <li>約會專家系統</li>
            <li class="active">GT活動管理</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>GT活動管理</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">

                <p><input type="button" class="btn btn-info" value="新增GT活動" onclick="location.href='singleweb_fun22_add.php'"></p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th width="70"></th>
                            <th width=120>圖片</th>
                            <th>活動標題</th>
                            <th>簡介</th>

                            <th>操作</th>
                        </tr>
                        <?php 
                            $SQL = "SELECT * FROM si_webdata where types='event_201220' order by i1 desc";
                            $rs = $SPConn->prepare($SQL);
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
                                        <td>
                                            <?php 
                                                if($re["d5"] != ""){ 
                                                   echo "有";
                                                }else{
                                                    echo "無";
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $re["d1"] ?></td>
                                        <td><?php echo $re["n1"] ?></td>
                                        <td>
                                            <a href="singleweb_fun22_add.php?an=<?php echo $re["auton"] ?>">編輯</a>
                                            <a title="刪除" href="singleweb_fun22.php?st=del&an=<?php echo $re["auton"] ?>">刪除</a>						
                                        </td>
                                    </tr>
                                <?php $ii = $ii+1; }   
                            }else{
                                echo "<tr><td colspan=5>目前無資料</td></tr>";
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
require_once("./include/_bottom.php");
?>