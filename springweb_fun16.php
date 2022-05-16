<?php
    /*****************************************/
    //檔案名稱：springweb_fun8.php
    //後台對應位置：春天網站系統/會員通知訊息
    //改版日期：2022.5.16
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");
    require_once("./include/_top.php");
    require_once("./include/_sidebar_spring.php");

    //程式開始 *****
    if ($_SESSION["MM_Username"] == "") {
        call_alert("請重新登入。", "login.php", 0);
    }

    if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["funtourpm"] != "1"){
        call_alert("您沒有查看此頁的權限。", "login.php", 0);
    }

    if($_REQUEST["st"] == "del"){
        $SQL = "delete from member_announce where auton=".SqlFilter($_REQUEST["id"],"int");
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        
        reURL("win_close.php?m=刪除中.....");
    }
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>春天網站系統</li>
            <li class="active">會員通知訊息</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>會員通知訊息</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p><input type="button" class="btn btn-info" value="新增通知訊息" onclick="location.href='springweb_fun16_add.php?act=ad'"></p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th width=150>發送日期</th>
                            <th width=80>類型</th>
                            <th width="160">標題</th>
                            <th>內文</th>
                            <th width=60>操作</th>
                        </tr>
                        <?php 
                            $SQL = "SELECT * FROM member_announce order by times desc";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                $ii = 0;
                                foreach($result as $re){ ?>
                                    <tr>
                                        <td><?php echo $re["times"]; ?></td>
                                        <td><?php echo $re["types"]; ?></td>
                                        <td><?php echo $re["title"]; ?></td>
                                        <td><?php echo substr(RemoveHTML($re["notes"]),0,50); ?></td>			
                                        <td>
                                            <a href="springweb_fun16_add.php?act=up&id=<?php echo $re["auton"]; ?>">編輯</a>					
                                            <a href="javascript:Mars_popup2('springweb_fun16.php?st=del&id=<?php echo $re["auton"]; ?>','','width=300,height=200,top=100,left=100')">刪除</a>						
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