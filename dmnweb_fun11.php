<?php

/*****************************************/
//檔案名稱：dmnweb_fun11.php
//後台對應位置：DateMeNow網站系統/幸福故事
//改版日期：2022.8.24
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/
require_once("_inc.php");
require_once("./include/_function.php");
//ajax
if ($_REQUEST["st"] == "sa") {
    $SQL = "update happiness set set_top=" . SqlFilter($_REQUEST["v"], "int") . " where auton=" . SqlFilter($_REQUEST["t"], "int") . "";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
}
require_once("./include/_top.php");
require_once("./include/_sidebar_dmn.php");

// 程式開始
if ($_SESSION["MM_Username"] == "") {
    call_alert("請重新登入。", "login.php", 0);
}

if ($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["dmnweb"] != "1") {
    call_alert("您沒有查看此頁的權限。", "login.php", 0);
}

// 文章上移
if ($_REQUEST["st"] == "up_line") {
    $nowline = round(SqlFilter($_REQUEST["ad"], "int"));
    $upline = $nowline + 1;
    $SQL = "update happiness set des=" . $nowline . " where des='" . $upline . "'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $SQL = "update happiness set des=" . $upline . " where auton=" . SqlFilter($_REQUEST["an"], "int") . "";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
}

// 文章下移
if ($_REQUEST["st"] == "down_line") {
    $nowline = round(SqlFilter($_REQUEST["ad"], "int"));
    $upline = $nowline - 1;
    $SQL = "update happiness set des=" . $nowline . " where des=" . $upline . "";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $SQL = "update happiness set des=" . $upline . " where auton=" . SqlFilter($_REQUEST["an"], "int") . "";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
}

// 刪除文章+圖片(待測)
if ($_REQUEST["st"] == "del") {
    $SQL = "select * from happiness where auton=" . SqlFilter($_REQUEST["an"], "int") . "";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        if ($result["pic1"] != "") {
            DelFile("datemenow_image/upload/" . $result["pic1"]);
        }
        if ($result["pic2"] != "") {
            DelFile("datemenow_image/upload/" . $result["pic2"]);
        }
        if ($result["pic3"] != "") {
            DelFile("datemenow_image/upload/" . $result["pic3"]);
        }
    }

    $SQL = "delete from happiness where auton=" . SqlFilter($_REQUEST["an"], "int") . "";
    $rs = $DMNConn->prepare($SQL);
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
            <li>DateMeNow網站系統</li>
            <li class="active">幸福故事</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>幸福故事</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">

                <p>
                    <input type="button" class="btn btn-info" value="新增幸福故事" onclick="location.href='dmnweb_fun11_add.php?act=ad'">
                </p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th width="70"></th>
                            <th width=150>日期</th>
                            <th width="160">標題</th>
                            <th>內文</th>
                            <th width="60">小字</th>
                            <th width="60">精選</th>
                            <th width=120>TAG</th>
                            <th width=60>操作</th>
                        </tr>
                        <?php
                            $SQL = "SELECT * FROM happiness order by des desc";
                            $rs = $DMNConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if ($result) {
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
                                    <td><a href="<?php echo $uahref; ?>"><span class="fa fa-arrow-up margin-left-10 margin-right-10"></span></a><a href="<?php echo $dahref; ?>"><span class="fa fa-arrow-down"></span></a></td>			    
                                        <td><?php echo changeDate($re["times"]); ?></td>				
                                        <td><?php echo $re["title"]; ?></td>
                                        <td><?php echo mb_substr(RemoveHTML($re["content"]),0,50,"utf-8"); ?></td>
                                        <td><?php echo $re["n1"]; ?></td>
                                        <td>
                                            <?php 
                                                if($re["set_top"] == 1){
                                                    echo "<input data-no-uniform='true' type='checkbox' id='".$re["auton"]."' class='show_check' checked>";
                                                }else{
                                                    echo "<input data-no-uniform='true' type='checkbox' id='".$re["auton"]."' class='show_check'>";
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $re["tag"]; ?></td>
                                        <td>
                                            <a href="dmnweb_fun11_add.php?act=up&an=<?php echo $re["auton"]; ?>">編輯</a>					
                                            <a href="javascript:Mars_popup2('dmnweb_fun11.php?st=del&an=<?php echo $re["auton"]; ?>','','width=300,height=200,top=100,left=100')">刪除</a>						
                                        </td>
                                    </tr>
                                <?php $ii = $ii+1; }
                            }else{
                                echo "<tr><td colspan=8>目前無資料</td></tr>";
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
                url: "dmnweb_fun11.php",
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