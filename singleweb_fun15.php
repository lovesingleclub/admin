<?php
/*****************************************/
//檔案名稱：singleweb_fun15.php
//後台對應位置：約會專家系統/禮物管理
//改版日期：2022.5.26
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

// 禮物上移
if($_REQUEST["st"] == "up_line"){
    $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
    $upline = $nowline+1;
    $SQL = "update si_gift_list set des=".$nowline." where des='".$upline."'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $SQL = "update si_gift_list set des=".$upline." where auton=".SqlFilter($_REQUEST["an"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
}

// 禮物下移
if($_REQUEST["st"] == "down_line"){
    $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
    $upline = $nowline-1;
    $SQL = "update si_gift_list set des=".$nowline." where des=".$upline."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $SQL = "update si_gift_list set des=".$upline." where auton=".SqlFilter($_REQUEST["an"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
}

// 刪除禮物
if($_REQUEST["st"] == "del"){
    $SQL = "delete from si_gift_list where auton=".SqlFilter($_REQUEST["an"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();

    reURL("singleweb_fun15.php");
}

?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>約會專家系統</li>
            <li class="active">禮物管理</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>禮物管理</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p><input type="button" class="btn btn-info" value="新增禮物" onclick="Mars_popup('singleweb_fun15_add.php','','scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=690,height=300,top=10,left=10');"></p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th width="70"></th>
                            <th>名稱</th>
                            <th>檔名</th>
                            <th>操作</th>
                        </tr>
                        <?php 
                            $SQL = "SELECT * FROM si_gift_list where types='gift' order by des desc";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                $ii = 0;
                                foreach($result as $re){
                                    if($ii == 0){
                                        $uahref = "#nu\" onclick=\"alert('無法向上');\"";
                                    }else{
                                        $uahref = "?st=up_line&ad=".$re["des"]."&an=".$re["auton"];
                                    }                                   
                                    if($ii == count($result)-1){
                                        $dahref = "#nu\" onclick=\"alert('無法向下');\"";
                                    }else{
                                        $dahref = "?st=down_line&ad=".$re["des"]."&an=".$re["auton"];
                                    } ?>

                                    <tr>
                                        <td>
                                            <a href="<?php echo $uahref; ?>"><span class="fa fa-arrow-up margin-left-10 margin-right-10"></span></a><a href="<?php echo $dahref; ?>"><span class="fa fa-arrow-down"></span></a>
                                        </td>
                                        <td><?php echo $re["name"]; ?></td>				
				                        <td><?php echo $re["url"]; ?></td>
                                        <td>
                                            <a href="javascript:Mars_popup('singleweb_fun15_add.php?an=<?php echo $re["auton"]; ?>','','scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=690,height=300,top=10,left=10');">編輯</a>
					                        <a title="刪除" href="singleweb_fun15.php?st=del&an=<?php echo $re["auton"]; ?>">刪除</a>
                                        </td>
                                    </tr>
                                <?php $ii = $ii+1; }
                            }else{
                                echo "<tr><td colspan=4>目前無資料</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>

                <p><input type="button" class="btn btn-info" value="新增罐頭訊息" onclick="Mars_popup('singleweb_fun15_add2.php','','scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=690,height=300,top=10,left=10');"></p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th>名稱</th>
                            <th>檔名</th>
                            <th>操作</th>
                        </tr>
                        <?php 
                            $SQL = "SELECT * FROM si_gift_list where types='text' order by auton desc";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                foreach($result as $re){ ?>
                                    <tr>
                                        <td><?php echo $re["name"]; ?></td>				
				                        <td><?php echo $re["url"]; ?></td>
                                        <td>
                                            <a href="javascript:Mars_popup('singleweb_fun15_add2.php?an=<?php echo $re["auton"]; ?>','','scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=690,height=300,top=10,left=10');">編輯</a>
					                        <a title="刪除" href="singleweb_fun15.php?st=del&an=<?php echo $re["auton"]; ?>">刪除</a>
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