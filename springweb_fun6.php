<?php
    /*****************************************/
    //檔案名稱：springweb_fun6.php
    //後台對應位置：春天網站系統/部落格
    //改版日期：2022.5.9
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

    // 文章上移
    if($_REQUEST["st"] == "up_line"){
        $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
        $upline = $nowline+1;
        $SQL = "update bloglist set t_desc=".$nowline." where t_desc=".$upline."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $SQL = "update bloglist set t_desc=".$upline." where auton=".SqlFilter($_REQUEST["t_auto"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }

    // 文章下移
    if($_REQUEST["st"] == "down_line"){
        $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
        $upline = $nowline-1;
        $SQL = "update bloglist set t_desc=".$nowline." where t_desc=".$upline."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $SQL = "update bloglist set t_desc=".$upline." where auton=".SqlFilter($_REQUEST["t_auto"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }

    // 刪除(有刪除圖片功能待測)
    if($_REQUEST["st"] == "del"){
        $SQL = "select * from bloglist where auton=".SqlFilter($_REQUEST["a"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            $fullpic = $result["fullpic"];
            $urlpath = "webfile/springclub/blog/image/";
            if(strpos($fullpic,",") !== false){
                foreach(explode(",",$fullpic) as $pic){
                    DelFile($urlpath.$pic); //刪除圖片 
                }
            }
        }

        $SQL = "delete from bloglist where auton=".SqlFilter($_REQUEST["a"],"int")."";
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
            <li class="active">部落格</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>部落格</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p><a href="springweb_fun6_add.php" class="btn btn-info">新增文章</a></p>
                <table class="table table-striped table-bordered bootstrap-datatable">                    
                    <?php 
                        $SQL = "SELECT * FROM bloglist order by t_desc desc";
                        $rs = $SPConn->prepare($SQL);
                        $rs->execute();
                        $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                        if($result){
                            $ii = 0;
                            foreach($result as $re){
                                if($ii == 0){
                                    $uahref = "#nu\" onclick=\"alert('無法向上');\"";
                                }else{
                                    $uahref = "?st=up_line&ad=".$re["t_desc"]."&t_auto=".$re["auton"];
                                }                                   
                                if($ii == count($result)-1){
                                    $dahref = "#nu\" onclick=\"alert('無法向下');\"";
                                }else{
                                    $dahref = "?st=down_line&ad=".$re["t_desc"]."&t_auto=".$re["auton"];
                                } ?>
                                <tr>
                                    <td><a href="<?php echo $uahref; ?>"><span class="fa fa-arrow-up margin-left-10 margin-right-10"></span></a><a href="<?php echo $dahref; ?>"><span class="fa fa-arrow-down"></span></a></td>
                                    <td><?php echo $re["adc_title"]; ?></td>
                                    <td><a href="http://www.springclub.com.tw/blog/post.asp?id=<?php echo $re["auton"]; ?>" target="_blank"><?php echo $re["title"]; ?></a></td>
                                    <td>
                                        <a href="springweb_fun6_add.php?t=ed&a=<?php echo $re["auton"]; ?>">修改</a>					
                                        <a href="javascript:Mars_popup2('?st=del&a=<?php echo $re["auton"]; ?>','','width=300,height=200,top=100,left=100')">刪除</a>						
                                    </td>
                                </tr>
                            <?php $ii = $ii+1; }
                        }else{
                            echo "<tr><td colspan=4>目前無資料</td></tr>";
                        }
                    ?>                    
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