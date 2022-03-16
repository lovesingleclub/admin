<?php
    /*****************************************/
    //檔案名稱：ad_noemail_list.php
    //後台對應位置：管理系統/不願收到廣告信
    //改版日期：2022.3.14
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");
    require_once("./include/_top.php");
    require_once("./include/_sidebar.php");

    //程式開始 *****
    if ($_SESSION["MM_Username"] == "") {
        call_alert("請重新登入。", "login.php", 0);
    }
    if($_SESSION["MM_UserAuthorization"] != "admin"){
        if($_SESSION["funtourpm"] != "1"){
            call_alert("您沒有查看此頁的權限。","login.php",0);
        }        
    }

    // 刪除
    if($_REQUEST["st"] == "del"){
        $SQL = "select email from log_noemail where auton=".SqlFilter($_REQUEST["an"],"int")."";
        $rs = $FunConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $emails = $result["email"];

            // 刪除 好好玩log_noemail上的資料
            $SQL = "DELETE from log_noemail where auton=".SqlFilter($_REQUEST["an"],"int")."";
            $rs = $FunConn->prepare($SQL);
            $rs->execute();

            // 更新春天member_data上的資料
            $SQL = "update member_data set noemail=0 WHERE mem_mail ='".$emails."'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();

            // 更新春天love_keyin上的資料
            $SQL = "update love_keyin set noemail=0 WHERE k_yn ='".$emails."'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();

            // 更新好好玩member_data上的資料
            $SQL = "update member_data set noemail=0 WHERE mem_mail = '".$emails."'";
            $rs = $FunConn->prepare($SQL);
            $rs->execute();

            // 更新好好玩goldcard_data上的資料
            $SQL = "update goldcard_data set noemail=0 WHERE mem_mail = '".$emails."'";
            $rs = $FunConn->prepare($SQL);
            $rs->execute();

            // 更新好好玩love_keyin上的資料
            $SQL = "update love_keyin set noemail=0 WHERE k_yn = '".$emails."'";
            $rs = $FunConn->prepare($SQL);
            $rs->execute();
        }
        reURL("win_close.php?m=刪除中");
    }

    // 要排除的信箱
    if($_REQUEST["st"] == "send"){
        $emails = SqlFilter($_REQUEST["emails"],"tab");
        $emails = str_replace(" ","",$emails);
        if($emails == ""){
            call_alert("請在輸入框內輸入 email",0,0);
        }
        foreach(explode(PHP_EOL,$emails) as $eitem){
            if(strlen($eitem) > 1){
                $eitem = "'".$eitem."'";
                if($emailss != ""){
                    $emailss = $emailss.",".$eitem;
                }else{
                    $emailss = $eitem;
                }
            }
        }
        $i1 = 0;
        $i2 = 0;
        $i3 = 0;
        $i4 = 0;
        $i5 = 0;
        $oo1 = 0;
        $oo2 = 0;
        $oo3 = 0;
        $oo4 = 0;
        $oo5 = 0;

        $SQL = "SELECT * FROM member_data WHERE mem_mail in (".$emailss.")";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            foreach($result as $re){
                $i1 = $i1 + 1;
                if(is_null($re["noemail"]) || $re["noemail"] != 1){
                    $oo1 = $oo1 + 1;
                    $SQL = "UPDATE member_data SET noemail = 1 WHERE mem_mail in (".$emailss.")";
                    $rs = $SPConn->prepare($SQL);
                    $rs->execute();
                }
            }
        }

        $SQL = "SELECT * FROM love_keyin WHERE k_yn in (".$emailss.")";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            foreach($result as $re){
                $i2 = $i2 + 1;
                if(is_null($re["noemail"]) || $re["noemail"] != 1){
                    $oo2 = $oo2 + 1;
                    $SQL = "UPDATE love_keyin SET noemail = 1 WHERE k_yn = ".$re["k_yn"]."";
                    $rs = $SPConn->prepare($SQL);
                    $rs->execute();
                }
            }
        }

        $SQL = "SELECT * FROM member_data WHERE mem_mail in (".$emailss.")";
        $rs = $FunConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            foreach($result as $re){
                $i3 = $i3 + 1;
                if(is_null($re["noemail"]) || $re["noemail"] != 1){
                    $oo3 = $oo3 + 1;
                    $SQL = "UPDATE member_data SET noemail = 1 WHERE mem_mail = ".$re["mem_mail"]."";
                    $rs = $FunConn->prepare($SQL);
                    $rs->execute();
                }
            }
        }

        $SQL = "SELECT * FROM goldcard_data WHERE mem_mail in (".$emailss.")";
        $rs = $FunConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            foreach($result as $re){
                $i4 = $i4 + 1;
                if(is_null($re["noemail"]) || $re["noemail"] != 1){
                    $oo4 = $oo4 + 1;
                    $SQL = "UPDATE goldcard_data SET noemail = 1 WHERE mem_mail = ".$re["mem_mail"]."";
                    $rs = $FunConn->prepare($SQL);
                    $rs->execute();
                }
            }
        }

        $SQL = "SELECT * FROM love_keyin WHERE k_yn in (".$emailss.")";
        $rs = $FunConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            foreach($result as $re){
                $i5 = $i5 + 1;
                if(is_null($re["noemail"]) || $re["noemail"] != 1){
                    $oo5 = $oo5 + 1;
                    $SQL = "UPDATE love_keyin SET noemail = 1 WHERE k_yn = ".$re["k_yn"]."";
                    $rs = $FunConn->prepare($SQL);
                    $rs->execute();
                }
            }
        }
    }

    if($_REQUEST["st"] == "cut"){
        $head = "自行輸入不願收到";
    }else{
        $head = "不願收到廣告信";
    }

?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li class="active"><?php echo $head; ?></li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong><?php echo $head; ?></strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <?php 
                    if($_REQUEST["st"] == "cut"){ ?>
                        <a href="ad_noemail_list.php">不願收到廣告信</a>　自行輸入不願收到<br><br>
						<table class="table table-striped table-bordered bootstrap-datatable">
							<tr><th width=200>時間</th><th>信箱</th><th width=200>管理</th></tr>
                            <?php
                                $SQL = "select * from log_noemail order by times desc";
                                $rs = $FunConn->prepare($SQL);
                                $rs->execute();
                                $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                                if($result){
                                    foreach($result as $re){
                                        echo "<tr><td>".changeDate($re["times"])."</td><td>".$re["email"]."</td><td><a href=\"javascript:Mars_popup2('?st=del&an=".$re["auton"]."','','width=300,height=200,top=100,left=100');\">刪除</a></td></tr>";
                                    }
                                }
                            ?>
                        </table>
                    <?php }else{ ?> 
                        不願收到廣告信　<a href="ad_noemail_list.php?st=cut">自行輸入不願收到</a><br><br>
						<table class="table table-striped table-bordered bootstrap-datatable">
                            <thead>
                                <?php 
                                    if($i1 > 0 || $i2 > 0 || $i3 > 0 || $i4 > 0 || $i5 > 0){
                                        echo "<tr><td>春天未入會、會員 ".$oo1."/".$i1." 筆、春天報名 ".$oo2."/".$i2." 筆、好好玩會員 ".$oo3."/".$i3." 筆、好好玩金卡 ".$oo4."/".$i4." 筆、好好玩報名 ".$oo5."/".$i5." 筆</td></tr>";
                                    }
                                ?>
                                <tr>
                                    <form action="?st=send" method="post" name="form5">
                                        <td>請直接輸入要排除的信箱，每一個信箱地址一行。<br><textarea name="emails" style="width:80%;height:500px;"></textarea><br><input type="submit" value="送出"></td>
                                    </form>
                                </tr>
                            </thead>
                        </table>  
                    <?php }
                ?>
            </div>
        </div>
        <!--/span-->

    </div>
    <!--/row-->

    <hr>
    </div>
    <!--/.fluid-container-->

</section>
<!-- /MIDDLE -->

<?php
require_once("./include/_bottom.php");
?>