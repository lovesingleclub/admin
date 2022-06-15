<?php

/*****************************************/
//檔案名稱：singleweb_fun6.php
//後台對應位置：約會專家系統/戀愛學院-豪華講師
//改版日期：2022.5.23
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");
require_once("./include/_top.php");

//程式開始 *****
if ($_SESSION["MM_Username"] == "") {
    call_alert("請重新登入。", "login.php", 0);
}

if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["singleweb"] != "1"){
    call_alert("您沒有查看此頁的權限。", "login.php", 0);
}

// 文章上移
if($_REQUEST["st"] == "up_line"){
    $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
    $upline = $nowline+1;
    $SQL = "update si_salon_teacher set descs=".$nowline." where descs='".$upline."'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $SQL = "update si_salon_teacher set descs=".$upline." where auton=".SqlFilter($_REQUEST["auton"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
}

// 文章下移
if($_REQUEST["st"] == "down_line"){
    $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
    $upline = $nowline-1;
    $SQL = "update si_salon_teacher set descs=".$nowline." where descs=".$upline."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $SQL = "update si_salon_teacher set descs=".$upline." where auton=".SqlFilter($_REQUEST["auton"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
}

// 刪除文章(有刪除圖片功能待測)
if($_REQUEST["st"] == "del"){
    $SQL = "select pics from si_salon_teacher where auton=".SqlFilter($_REQUEST["id"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        DelFile("singleparty_image/salon/".$re["ads_pic1"]); //刪除圖片 
    }

    $SQL = "delete from si_salon_teacher where auton=".SqlFilter($_REQUEST["id"],"int")."";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();

    reURL("win_close.php?m=刪除中.....");
}

switch($_SESSION["MM_UserAuthorization"]){
    case "admin":
        $power_edit = 1;
        $sqlss = "";
        include_once("./include/_sidebar_single.php");
        break;
    default:
        $power_edit = 0;
        $sqlss = " where single = '".$_SESSION["MM_Username"]."'";
        include_once("./include/_sidebar.php");
}
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>約會專家系統</li>
            <li class="active">戀愛學院-豪華講師</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>戀愛學院-豪華講師</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p><input type="button" class="btn btn-info" value="新增講師資料" onclick="location.href='singleweb_fun6_add.php?act=ad'"></p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th width="70"></th>
                            <th width="180">職稱</th>
                            <th width="120">名字</th>
                            <th width="60">顯示</th>
                            <th width="60">審核</th>

                            <th>經歷</th>
                            <th width="120">照片</th>
                            <th width="120">操作</th>
                        </tr>
                        <?php 
                            $SQL = "SELECT * FROM si_salon_teacher".$sqlss." order by descs desc";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                $ii = 0;
                                foreach($result as $re){
                                    if($ii == 0){
                                        $uahref = "#nu\" onclick=\"alert('無法向上');\"";
                                    }else{
                                        $uahref = "?st=up_line&ad=".$re["descs"]."&auton=".$re["auton"];
                                    }                                   
                                    if($ii == count($result)-1){
                                        $dahref = "#nu\" onclick=\"alert('無法向下');\"";
                                    }else{
                                        $dahref = "?st=down_line&ad=".$re["descs"]."&auton=".$re["auton"];
                                    } ?>

                                    <tr>
                                        <td>
                                            <?php if($power_edit == 1){ ?>
                                                <a href="<?php echo $uahref; ?>"><span class="fa fa-arrow-up margin-left-10 margin-right-10"></span></a><a href="<?php echo $dahref; ?>"><span class="fa fa-arrow-down"></span></a>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $re["title"]; ?></td>				
				                        <td><?php echo $re["name"]; ?><?php if($re["p_auto"] != "") echo "[".$re["p_auto"]."]"; ?></td>
                                        <?php 
                                            if($re["show"] == "1"){
                                                $shows = "<span class='label label-success'>顯示</span>";
                                            }else{
                                                $shows = "<span class='label label-danger'>隱藏</span>";
                                            }
                                            if($re["review"] == "1"){
                                                $review = "<span class='label label-success'>通過</span>";
                                            }else{
                                                $review = "<span class='label label-danger'>未審</span>";
                                            }
                                        ?>
                                        <td><?php echo $shows; ?></td>
				                        <td><?php echo $review; ?></td>
                                        <td>
                                            <?php 
                                                $notes = RemoveHTML($re["notes"]);
                                                if(mb_strlen($notes,"utf-8") > 40){
                                                    $notes = mb_substr($notes,0,40,"utf-8")."...";
                                                }
                                                echo $notes;
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if($re["pics"] != ""){ ?>
                                                    <a href="singleparty_image/salon/<?php echo $re["pics"]; ?>" class='fancybox'>點我查看</a>
                                                <?php }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if($power_edit == 1){ ?>
                                                    <a href="singleweb_fun6_add.php?act=up&id=<?php echo $re["auton"]; ?>">編輯</a>					
					                                <a href="javascript:Mars_popup2('singleweb_fun6.php?st=del&id=<?php echo $re["auton"]; ?>','','width=300,height=200,top=100,left=100')">刪除</a>	
                                                <?php }
                                            ?>
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
require("./include/_bottom.php");
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
                url: "singleweb_fun6.php",
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