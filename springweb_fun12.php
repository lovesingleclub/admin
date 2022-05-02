<?php
    /*****************************************/
    //檔案名稱：springweb_fun12.php
    //後台對應位置：春天網站系統/愛心公益
    //改版日期：2022.4.14
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
        $SQL = "update ad_civic set adc_desc=".$nowline." where adc_desc=".$upline."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $SQL = "update ad_civic set adc_desc=".$upline." where adc_auto=".SqlFilter($_REQUEST["adc_auto"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }

    // 文章下移
    if($_REQUEST["st"] == "down_line"){
        $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
        $upline = $nowline-1;
        $SQL = "update ad_civic set adc_desc=".$nowline." where adc_desc=".$upline."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $SQL = "update ad_civic set adc_desc=".$upline."where adc_auto=".SqlFilter($_REQUEST["adc_auto"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }

    // 刪除(有刪除圖片功能待測)
    if($_REQUEST["st"] == "del"){
        $SQL = "select * from web_photo where num=".SqlFilter($_REQUEST["id"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            foreach($result as $re){
                DelFile("image/".$re["photo_name"]); //刪除圖片 
            }
        }

        $SQL = "delete from ad_civic where adc_auto=".SqlFilter($_REQUEST["id"],"int")."";
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
            <li class="active">愛心公益</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>愛心公益</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p><input type="button" class="btn btn-info" value="新增愛心公益" onclick="location.href='springweb_fun12_add.php?act=ad'"></p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th width="70"></th>
                            <th>日期</th>
                            <th>標題</th>
                            <!--<th width="30">精選</th>-->
                            <th>操作</th>
                        </tr>
                        <?php 
                            $SQL = "SELECT * FROM ad_civic order by adc_desc desc";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                $ii = 0;
                                foreach($result as $re){
                                    if($ii == 0){
                                        $uahref = "#nu\" onclick=\"alert('無法向上');\"";
                                    }else{
                                        $uahref = "?st=up_line&ad=".$re["adc_desc"]."&adc_auto=".$re["adc_auto"];
                                    }                                   
                                    if($ii == count($result)-1){
                                        $dahref = "#nu\" onclick=\"alert('無法向下');\"";
                                    }else{
                                        $dahref = "?st=down_line&ad=".$re["adc_desc"]."&adc_auto=".$re["adc_auto"];
                                    } ?>
                                    <tr>
                                        <td><a href="<?php echo $uahref; ?>"><span class="fa fa-arrow-up margin-left-10 margin-right-10"></span></a><a href="<?php echo $dahref; ?>"><span class="fa fa-arrow-down"></span></a></td>
                                        <td><?php echo Date_EN($re["adc_showtime"],1); ?></td>
                                        <td><?php echo $re["adc_title"]; ?></td>
                                        <td>
                                            <a href="springweb_fun12_add.php?act=up&id=<?php echo $re["adc_auto"]; ?>">編輯</a>					
                                            <a href="javascript:Mars_popup2('springweb_fun12.php?st=del&id=<?php echo $re["adc_auto"]; ?>','','width=300,height=200,top=100,left=100')">刪除</a>						
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
require_once("./include/_bottom.php");
?>