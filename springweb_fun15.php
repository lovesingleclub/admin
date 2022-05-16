<?php
    /*****************************************/
    //檔案名稱：springweb_fun15.php
    //後台對應位置：春天網站系統/戀愛講堂
    //改版日期：2022.4.10
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
        $SQL = "update ad_salon set ads_desc=".$nowline." where ads_desc='".$upline."'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $SQL = "update ad_salon set ads_desc=".$upline." where ads_auto=".SqlFilter($_REQUEST["ads_auto"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }

    // 文章下移
    if($_REQUEST["st"] == "down_line"){
        $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
        $upline = $nowline-1;
        $SQL = "update ad_salon set ads_desc=".$nowline." where ads_auto=".$upline."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $SQL = "update ad_salon set ads_desc=".$upline." where ads_auto=".SqlFilter($_REQUEST["ads_auto"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }

    // 刪除文章(有刪除圖片功能待測)
    if($_REQUEST["st"] == "del"){
        $SQL = "select ads_pic1 from ad_salon where ads_auto=".SqlFilter($_REQUEST["id"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            DelFile("upload_image/".$re["ads_pic1"]); //刪除圖片 
        }

        $SQL = "delete from ad_salon where ads_auto=".SqlFilter($_REQUEST["id"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();

        reURL("win_close.php?m=刪除中.....");
    }

    if($_REQUEST["st"] == "sa"){
        $SQL = "update ad_salon set index_show=".SqlFilter($_REQUEST["v"],"int")." where ads_auto=".SqlFilter($_REQUEST["t"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>春天網站系統</li>
            <li class="active">戀愛講堂</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>戀愛講堂</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p><input type="button" class="btn btn-info" value="新增戀愛講堂" onclick="location.href='springweb_fun15_add.php?act=ad'"></p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th width="70"></th>			    
                            <th width=150>日期</th>
                            <th width=80>分類</th>
                            <th width="160">標題</th>
                            <th>內文</th>
                            <th width="60">狀態</th>
                            <th width="60">圖檔</th>
                            <th width="30">精選</th>
                            <th width=60>操作</th>
                        </tr>
                        <?php 
                            $SQL = "SELECT * FROM ad_salon order by ads_desc desc";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                $ii = 0;
                                foreach($result as $re){
                                    if($ii == 0){
                                        $uahref = "#nu\" onclick=\"alert('無法向上');\"";
                                    }else{
                                        $uahref = "?st=up_line&ad=".$re["ads_desc"]."&ads_auto=".$re["ads_auto"];
                                    }                                   
                                    if($ii == count($result)-1){
                                        $dahref = "#nu\" onclick=\"alert('無法向下');\"";
                                    }else{
                                        $dahref = "?st=down_line&ad=".$re["ads_desc"]."&ads_auto=".$re["ads_auto"];
                                    } ?>
                                    <tr>
                                        <td><a href="<?php echo $uahref; ?>"><span class="fa fa-arrow-up margin-left-10 margin-right-10"></span></a><a href="<?php echo $dahref; ?>"><span class="fa fa-arrow-down"></span></a></td>			    
                                        <td><?php echo Date_EN($re["ads_showtime"],1); ?></td>
                                        <td><?php echo $re["ads_kind"]; ?></td>
                                        <td><?php echo $re["ads_title"]; ?></td>
                                        <td><?php echo mb_substr(RemoveHTML($re["ads_note"]),0,50,"utf-8"); ?></td>
                                        <td>
                                            <?php
                                                if($re["upload"] == "上架"){
                                                    echo "<span style='color: green'>上架</span>";
                                                }else{
                                                    echo "<span style='color: RED'>下架</span>";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if($re["ads_pic1"] != ""){ ?>
                                                    <a href="/upload_image/<?php echo $re["ads_pic1"]; ?>" class='fancybox'>點我查看</a>
                                                <?php }
                                            ?>                                
                                        </td>
                                        <td>
                                            <?php 
                                                if($re["index_show"] == 1){
                                                    echo "<input data-no-uniform='true' type='checkbox' id='".$re["ads_auto"]."' class='show_check' checked>";
                                                }else{
                                                    echo "<input data-no-uniform='true' type='checkbox' id='".$re["ads_auto"]."' class='show_check'>";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="springweb_fun15_add.php?act=up&id=<?php echo $re["ads_auto"]; ?>">編輯</a>					
					                        <a href="javascript:Mars_popup2('springweb_fun15.php?st=del&id=<?php echo $re["ads_auto"]; ?>','','width=300,height=200,top=100,left=100')">刪除</a>
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

<script language="JavaScript">
    $(function() {

        $(".show_check").on("click", function() {
            var $this = $(this),
                $num = $this.attr("id"),
                $savediv = $("<div>儲存中</div>");
            $this.parent().append($savediv);
            if ($this.prop("checked")) $v = 1;
            else $v = 0;
            $.ajax({
                url: "springweb_fun15.php",
                data: {
                    st: "sa",
                    t: $num,
                    v: $v
                },
                type: "POST",
                dataType: 'text',
                success: function(msg) {
                    $savediv.remove();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        });
    });
</script>