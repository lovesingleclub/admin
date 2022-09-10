<?php
/*****************************************/
//檔案名稱：dmnweb_fun4.php
//後台對應位置：DateMeNow網站系統/談情說愛-文章管理
//改版日期：2022.8.22
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/
require_once("_inc.php");
require_once("./include/_function.php");
// ajax 非同步更新
if($_REQUEST["st"] == "sa"){
    $SQL = "update lovestory set set_top=".SqlFilter($_REQUEST["v"],"int")." where id=".SqlFilter($_REQUEST["t"],"int")."";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    exit();
}
require_once("./include/_top.php");
require_once("./include/_sidebar_dmn.php");

function lovestory_name($n){
    switch($n){
        case "1":
        case "跟我說愛情":
            if($n==1){
                $lovestory_name = "跟我說愛情";
            }else{
                $lovestory_name = "1";
            }
            break;
        case "2":
        case "跟我做心測":
                if($n==2){
                    $lovestory_name = "跟我做心測";
                }else{
                    $lovestory_name = "2";
                }
                break;
        case "3":
        case "跟我變Beauty":
                if($n==3){
                    $lovestory_name = "跟我變Beauty";
                }else{
                    $lovestory_name = "3";
                }
                break;
        case "4":
        case "跟我看花絮":
                if($n==4){
                    $lovestory_name = "跟我看花絮";
                }else{
                    $lovestory_name = "4";
                }
                break;
        case "5":
        case "跟我挖新聞":
                if($n==5){
                    $lovestory_name = "跟我挖新聞";
                }else{
                    $lovestory_name = "5";
                }
                break;
        case "6":
        case "跟我玩樂趣":
                if($n==6){
                    $lovestory_name = "跟我玩樂趣";
                }else{
                    $lovestory_name = "6";
                }
                break;
        case "7":
        case "跟我談兩性":
                if($n==7){
                    $lovestory_name = "跟我談兩性";
                }else{
                    $lovestory_name = "7";
                }
                break;
        case "8":
        case "跟我享好康":
                if($n==8){
                    $lovestory_name = "跟我享好康";
                }else{
                    $lovestory_name = "8";
                }
                break;
        case "9":
        case "跟我聊星座":
                if($n==9){
                    $lovestory_name = "跟我聊星座";
                }else{
                    $lovestory_name = "9";
                }
                break;        
    }
    return $lovestory_name;
}

// 程式開始
if($_SESSION["MM_Username"] == ""){
    call_alert("請重新登入。","login.php",0);
}

if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["dmnweb"] != "1"){
    call_alert("您沒有查看此頁的權限。","login.php",0);
}

// 文章上移
if($_REQUEST["st"] == "up_line"){
    $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
    $upline = $nowline+1;
    $SQL = "update lovestory set des=".$nowline." where category_id='".SqlFilter($_REQUEST["cid"],"int")."' and des='".$upline."'";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $SQL = "update lovestory set des=".$upline." where category_id='".SqlFilter($_REQUEST["cid"],"int")."' and id=".SqlFilter($_REQUEST["id"],"int")."";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
}

// 文章下移
if($_REQUEST["st"] == "down_line"){
    $nowline = round(SqlFilter($_REQUEST["ad"],"int"));
    $upline = $nowline-1;
    $SQL = "update lovestory set des=".$nowline." where category_id='".SqlFilter($_REQUEST["cid"],"int")."' and des=".$upline."";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
    $SQL = "update lovestory set des=".$upline." where category_id='".SqlFilter($_REQUEST["cid"],"int")."' and id=".SqlFilter($_REQUEST["id"],"int")."";
    $rs = $DMNConn->prepare($SQL);
    $rs->execute();
}

// 刪除文章
if($_REQUEST["st"] == "del"){   
    $SQL = "delete from lovestory where category_id='".SqlFilter($_REQUEST["cid"],"int")."' and id=".SqlFilter($_REQUEST["id"],"int")."";
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
            <li class="active">談情說愛-文章管理</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>談情說愛-文章管理</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">

                <p>
                    <select id="sel_type">
                        <option value="">所有類型</option>
                        <option value="1">跟我說愛情</option>
                        <option value="2">跟我做心測</option>
                        <option value="3">跟我變Beauty</option>
                        <option value="4">跟我看花絮</option>
                        <option value="5">跟我挖新聞</option>
                        <option value="6">跟我玩樂趣</option>
                        <option value="7">跟我談兩性</option>
                        <option value="8">跟我享好康</option>
                    </select>
                    <input type="button" class="btn btn-info" value="新增文章" onclick="location.href='dmnweb_fun4_add.php?act=ad'">
                </p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th width="70"></th>
                            <th width=150>日期</th>
                            <th width=120>分類</th>
                            <th width="160">標題</th>
                            <th>內文</th>
                            <th width="60">作者</th>
                            <th width="60">精選</th>
                            <th width=60>操作</th>
                        </tr>
                        <?php 
                            $sqls = "";
                            if($_REQUEST["cid"] != ""){
                                $sqls = " and category_id='".SqlFilter($_REQUEST["cid"],"int")."'"	;                                
                            }
                            $SQL = "SELECT * FROM lovestory where 1=1".$sqls." order by des desc";
                            $rs = $DMNConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                $ii = 0;
                                foreach($result as $re){
                                    if($ii == 0){
                                        $uahref = "#nu\" onclick=\"alert('無法向上');\"";
                                    }else{
                                        $uahref = "?st=up_line&cid=".$re["category_id"]."&ad=".$re["des"]."&id=".$re["id"];
                                    }                                   
                                    if($ii == count($result)-1){
                                        $dahref = "#nu\" onclick=\"alert('無法向下');\"";
                                    }else{
                                        $dahref = "?st=down_line&cid=".$re["category_id"]."&ad=".$re["des"]."&id=".$re["id"];
                                    } ?>
                                    <tr>
                                        <td><a href="<?php echo $uahref; ?>"><span class="fa fa-arrow-up margin-left-10 margin-right-10"></span></a><a href="<?php echo $dahref; ?>"><span class="fa fa-arrow-down"></span></a></td>
                                        <td><?php echo changeDate($re["create_time"]); ?></td>
                                        <td><?php echo lovestory_name($re["category_id"]); ?></td>
                                        <td><?php echo $re["name"]; ?></td>
                                        <td><?php echo mb_substr(RemoveHTML($re["content"]),0,50,"utf-8"); ?></td>
                                        <td><?php echo $re["writer"]; ?></td>
                                        <td>
                                            <?php
                                                if($re["set_top"] == 1){
                                                    echo "<input data-no-uniform='true' type='checkbox' id='".$re["id"]."' class='show_check' checked>";
                                                }else{
                                                    echo "<input data-no-uniform='true' type='checkbox' id='".$re["id"]."' class='show_check'>";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="dmnweb_fun4_add.php?act=up&id=<?php echo $re["id"]; ?>">編輯</a>					
                                            <a href="javascript:Mars_popup2('dmnweb_fun4.php?st=del&cid=<?php echo $re["category_id"]; ?>&id=<?php echo $re["id"]; ?>','','width=300,height=200,top=100,left=100')">刪除</a>						
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
                url: "dmnweb_fun4.php",
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
        $("#sel_type").on("change", function() {
            if ($(this).val()) location.href = 'dmnweb_fun4.php?cid=' + $(this).val();
            else location.href = 'dmnweb_fun4.php';
        });
    });
</script>