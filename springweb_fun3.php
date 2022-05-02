<?php
    /*****************************************/
    //檔案名稱：springweb_fun3.php
    //後台對應位置：春天網站系統/愛情見證
    //改版日期：2022.4.6
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");
    require_once("./include/_top.php");
    switch($_SESSION["MM_UserAuthorization"]){
        case "admin":
            include_once("./include/_sidebar_spring.php");
            $power_edit = 1;
            $sqlss = "";
            break;
        default:
            include_once("./include/_sidebar.php");
            $power_edit = 0;
            $sqlss = " and single = '".$_SESSION["MM_Username"]."'";            
    }
    

    //程式開始 *****
    if ($_SESSION["MM_Username"] == "") {
        call_alert("請重新登入。", "login.php", 0);
    }

    // 圖片上移
    if($_REQUEST["st"] == "up_line"){
        $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
        $upline = $nowline+1;
        $SQL = "update witness set t_desc=".$nowline." where t_desc=".$upline."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $SQL = "update witness set t_desc=".$upline." where ac_auto=".SqlFilter($_REQUEST["ac_auto"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }

    // 圖片下移
    if($_REQUEST["st"] == "down_line"){
        $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
        $upline = $nowline-1;
        $SQL = "update witness set t_desc=".$nowline." where t_desc=".$upline."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $SQL = "update witness set t_desc=".$upline." where ac_auto=".SqlFilter($_REQUEST["ac_auto"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }

    if($_REQUEST["st"] == "sa"){
        $SQL = "update witness set index_show=".SqlFilter($_REQUEST["v"],"int")." where ac_auto=".SqlFilter($_REQUEST["ac"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }

    // 刪除(有刪除圖片功能待測)
    if($_REQUEST["st"] == "del"){
        $SQL = "select ac_pic from witness where ac_auto=".SqlFilter($_REQUEST["id"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            foreach($result as $re){
                DelFile("upload_image/".$re["pname"]); //刪除圖片 
            }
        }

        $SQL = "delete from witness_photo where ac_auto=".SqlFilter($_REQUEST["id"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $SQL = "delete from witness where ac_auto=".SqlFilter($_REQUEST["id"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();

        reURL("win_close.php?m=刪除中.....");
    }

    if($_REQUEST["show"] == "1"){
        $sqlss = $sqlss . " and show=1";
    }


?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li class="active">愛情見證</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>愛情見證</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p><input type="button" class="btn btn-info" value="新增愛情見證" onclick="location.href='springweb_fun3_add.php?act=ad'">
                    <a class="btn btn-warning" href="?show=1">春網前台顯示</a>
                </p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th width="70"></th>
                            <th>建立日期</th>
                            <th>標題</th>
                            <th width="160">新郎</th>
                            <th width="160">新娘</th>
                            <th width="30">精選</th>
                            <th width="60">春網顯示</th>
                            <th width="60">業務顯示</th>
                            <th width="60">簽約</th>
                            <th width="60">審核</th>
                            <th width="80">建立人</th>
                            <th width="100">操作</th>
                        </tr>
                        <?php 
                            $SQL = "SELECT * FROM witness where 1=1".$sqlss." order by t_desc desc";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                $ii = 0;
                                foreach($result as $re){
                                    if($ii == 0){
                                        $uahref = "#nu\" onclick=\"alert('無法向上');\"";
                                    }else{
                                        $uahref = "?st=up_line&ad=".$re["t_desc"]."&ac_auto=".$re["ac_auto"];
                                    }                                   
                                    if($ii == count($result)-1){
                                        $dahref = "#nu\" onclick=\"alert('無法向下');\"";
                                    }else{
                                        $dahref = "?st=down_line&ad=".$re["t_desc"]."&ac_auto=".$re["ac_auto"];
                                    } ?>
                                    <tr>
                                        <td>
                                            <?php 
                                                if($power_edit == 1){ ?>
                                                    <a href="<?php echo $uahref; ?>"><span class="fa fa-arrow-up margin-left-10 margin-right-10"></span></a><a href="<?php echo $dahref; ?>"><span class="fa fa-arrow-down"></span></a>
                                                <?php }
                                            ?>
                                        </td>
                                        <td><?php echo Date_EN($re["ac_time"],9) ?></td>
                                        <td><?php echo $re["ac_title"] ?></td>
                                        <td><?php echo $re["n3"] ?></td>
                                        <td><?php echo $re["n4"] ?></td>
                                        <td>
                                            <?php 
                                                if($power_edit == 1){
                                                    if($re["index_show"] == 1){
                                                        echo "<input data-no-uniform='true' type='checkbox' id='".$re["ac_auto"]."' class='show_check' checked>";
                                                    }else{
                                                        echo "<input data-no-uniform='true' type='checkbox' id='".$re["ac_auto"]."' class='show_check'>";
                                                    }
                                                }
                                            ?>
                                        </td>
                                        <?php 
                                            if($re["show"] == "1"){
                                                $shows = "<span class='label label-success'>顯示</span>";
                                            }else{
                                                $shows = "<span class='label label-danger'>隱藏</span>";
                                            }
                                            if($re["show2"] == "1"){
                                                $shows2 = "<span class='label label-success'>顯示</span>";
                                            }else{
                                                $shows2 = "<span class='label label-danger'>隱藏</span>";
                                            }
                                            if($re["review"] == "1"){
                                                $review = "<span class='label label-success'>通過</span>";
                                            }else{
                                                $review = "<span class='label label-danger'>未審</span>";
                                            }
                                            if($re["sign"] == "1"){
                                                $sign = "<span class='label label-success'>簽約</span>";
                                            }else{
                                                $sign = "<span class='label label-danger'>未簽</span>";
                                            }
                                        ?>
                                        <td><?php echo $shows; ?></td>				
                                        <td><?php echo $shows2; ?></td>				
                                        <td><?php echo $sign; ?></td>
                                        <td><?php echo $review; ?></td>
                                        <td><?php echo $re["singlename"]; ?></td>
                                        <td>
                                            <a href="springweb_fun3_add.php?act=up&id=<?php echo $re["ac_auto"]; ?>" class="btn btn-default btn-sm">編輯</a>
                                            <?php 
                                                if($power_edit == 1){ ?>
                                                    <a href="javascript:Mars_popup2('springweb_fun3.php?st=del&id=<?php echo $re["ac_auto"]; ?>','','width=300,height=200,top=100,left=100')" class="btn btn-danger btn-sm">刪除</a>
                                                <?php }
                                            ?>
                                        </td>
                                    </tr>
                                <?php $ii = $ii+1;}
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
                url: "springweb_fun3.php",
                data: {
                    st: "sa",
                    ac: $num,
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