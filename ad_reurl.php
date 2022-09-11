<?php
/*****************************************/
//檔案名稱：ad_reurl.php
//後台對應位置：春天網站系統/短網址管理
//改版日期：2022.9.10
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/
require_once("_inc.php");
require_once("./include/_function.php");
require_once("./include/_top.php");
require_once("./include/_sidebar.php");

function RandomString($iSize){
    $VALID_TEXT = "abcdefghijklmnopqrstuvwxyz1234567890-_";
    $Length = strlen($VALID_TEXT);
    $sNewSearchTag = "";
    for($i=1;$i<=$iSize;$i++){
        $sNewSearchTag = $sNewSearchTag . substr($VALID_TEXT,rand(0,$Length),1);
    }
    return $sNewSearchTag;
}

if($_REQUEST["st"] == "send"){
    $link = $_REQUEST["link"];
    $url = $_REQUEST["url"];
    if($link == ""){
        call_alert("請輸入短網址。", "ad_reurl.php", 0);
    }
    if($url == ""){
        call_alert("請輸入原網址。", "ad_reurl.php", 0);
    }
    $url = RemoveHTML($url);
    $url = str_replace("'","",$url);
    $url = str_replace("\"\"","",$url);

    if($link == "auto"){
        $ix = 0;
        while($ix < 50){
            $newlink = RandomString(6);
            $SQL = "SELECT top 1 * FROM sdf where link='".$newlink."'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
            $result = $rs->fetch(PDO::FETCH_ASSOC);
            if(!$result){
                break;
            }
            $ix = $ix + 1;
        }
        $link = $newlink;
    }
    $SQL = "SELECT top 1 * FROM sdf where link='".$link."'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    $result = $rs->fetch(PDO::FETCH_ASSOC);
    if($result){
        call_alert("短網址名稱重複。", "ad_reurl.php", 0);
    }else{
        $SQL = "INSERT INTO sdf (link,url,times,owner,ownerid) VALUES ('".$link."','".$url."','".date("Y/m/d H:i:s")."','".$_SESSION["pname"]."','".$_SESSION["MM_Username"]."')";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
    }
    reURL("ad_reurl.php");
}

//刪除
if($_REQUEST["st"]=="del"){
    $SQL = "delete FROM sdf where auton='".SqlFilter($_REQUEST["an"],"int")."'";
    $rs = $SPConn->prepare($SQL);
    $rs->execute();
    reURL("ad_reurl.php");
}
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li class="active"><a href="ad_reurl.php">短網址管理</a></li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>短網址管理</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form action="?st=send" method="post" id="form1" class="form-inline">
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <tbody>
                            <tr>
                                <td>
                                    https://sdf.tw/<input type="text" id="link" name="link" class="form-control" value="auto" required>(auto = 自動產生)

                                    網址：<input type="text" id="url" name="url" style="width:40%;" class="form-control" placeholder="要縮短的網址" required>
                                    對應說明：<input type="text" id="memo" name="memo" style="width:25%;" class="form-control" placeholder="網址對應說明,字數限制100字">
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <input id="submit3" type="submit" value="產生短網址" class="btn btn-info" style="width:50%;">
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </form>

                <form id="searchform" action="ad_reurl.asp" method="post" target="_self" class="form-inline">
                    <select name="own" class="form-control">
                        <option value="">請選擇建立者</option>
                        <?php 
                            $SQL = "select ownerid,MAX(owner) as owner from sdf group by ownerid";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                foreach($result as $re){
                                    if($_REQUEST["own"] == $re["ownerid"]){
                                        echo "<option value='".$re["ownerid"]."' selected>".$re["owner"]."</option>";
                                    }else{
                                        echo "<option value='".$re["ownerid"]."'>".$re["owner"]."</option>";
                                    }
                                }
                            }
                        ?>
                    </select>
                    <input type="submit" id="search_send" class="btn btn-default" value="查詢">
                </form>

                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th width="150">建立時間</th>
                            <th width="200">對應說明</th>
                            <th>短網址(點框複製)</th>
                            <th>原網址</th>
                            <th width="80">開啟次</th>
                            <th width="80">建立者</th>
                            <th></th>
                        </tr>
                        <?php 
                            $qsql = "";
                            if($_REQUEST["own"] != ""){
                                $qsql = $qsql . " and ownerid='".SqlFilter($_REQUEST["own"],"tab")."'";                                
                            }

                            //取得總筆數
                            $SQL = "Select count(auton) As total_size FROM sdf where 1=1 ".$qsql."";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result=$rs->fetchAll(PDO::FETCH_ASSOC);
                            foreach($result as $re);
                            if ( count($result) == 0 || $re["total_size"] == 0 ) {
                                $total_size = 0;
                            }else{
                                $total_size = $re["total_size"];
                            }

                            //取得分頁資料
                            $tPageSize = 50; //每頁幾筆
                            $tPage = 1; //目前頁數
                            $tPage_list = 0;
                            if ( $_REQUEST["tPage"] > 1 ){ 
                                $tPage = $_REQUEST["tPage"];
                                $tPage_list = ($tPage-1);
                            }
                            $tPageTotal = ceil(($total_size/$tPageSize)); //總頁數

                            //分頁語法
                            $SQL_list  = "Select Top ".$tPageSize." * ";
                            $SQL_list .= "From (Select row_number() ";
                            $SQL_list .= "over(order by times desc) As rownumber, times, auton, url, link, counts, owner ";
                            $SQL_list .= "From sdf Where 1=1 ".$qsql." ) temp_row ";
                            $SQL_list .= "Where rownumber > ".($tPageSize*$tPage_list);

                            $rs = $SPConn->prepare($SQL_list);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            
                            if($result){
                                foreach($result as $re){ ?>
                                    <tr>
                                        <td><?php echo date_EN($re["times"],9); ?></td>
                                        <td><?php echo $re["memo"]; ?></td>
                                        <td><input type="text" id="input_<?php echo $re["auton"]; ?>" value="https://sdf.tw/<?php echo $re["link"]; ?>" onclick="oCopy(this)" readonly></td>
                                        <td><?php echo $re["url"]; ?></td>								  
                                        <td><?php echo $re["counts"]; ?></td>		
                                        <td><?php echo $re["owner"]; ?></td>
                                        <td><a href="?st=del&an=<?php echo $re["auton"]; ?>" class="btn btn-xs btn-danger">刪除</a></td>
                                    </tr>
                                <?php }
                            }
                        ?>
                    </tbody>
                </table>

            </div>
            <!-- 頁碼 -->
            <?php require_once("./include/_page.php"); ?>

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

<script type="text/javascript">
    $mtu = "ad_reurl.";

    $(function() {

    });

    function oCopy(obj) {
        obj.select(); // 选中输入框中的内容

        try {
            if (document.execCommand('copy', false, null)) {

            } else {
                alert("無法自動複製，請手動按 ctrl + c 複製，謝謝");
            }
        } catch (err) {
            alert("無法自動複製，請手動按 ctrl + c 複製，謝謝");
        }

    }
</script>