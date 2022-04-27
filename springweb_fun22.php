<?php
    /*****************************************/
    //檔案名稱：springweb_fun22.php
    //後台對應位置：春天網站系統/首頁-文字設定
    //改版日期：2022.4.27
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

    if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["funtourpm"] != "1"){
        call_alert("您沒有查看此頁的權限。", "login.php", 0);
    }

    if($_REQUEST["st"] == "txtsave"){
        if($_REQUEST["txt1"] != ""){
            $txt1= SqlFilter($_REQUEST["txt1"],"tab");
        }else{
            $txt1 = NULL;
        }
        $SQL = "SELECT * FROM webdata where types='mobile_index_banner_txt1'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $SQL = "UPDATE webdata SET d1='".$txt1."' where types='mobile_index_banner_txt1'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }else{
            $SQL = "INSERT INTO webdata (d1,types) VALUES ('".$txt1."','mobile_index_banner_txt1')";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }

        if($_REQUEST["txt2"] != ""){
            $txt2= SqlFilter($_REQUEST["txt2"],"tab");
        }else{
            $txt2 = NULL;
        }
        $SQL = "SELECT * FROM webdata where types='mobile_index_banner_txt2'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $SQL = "UPDATE webdata SET d1='".$txt2."' where types='mobile_index_banner_txt2'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }else{
            $SQL = "INSERT INTO webdata (d1,types) VALUES ('".$txt2."','mobile_index_banner_txt2')";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }

        if($_REQUEST["txt3"] != ""){
            $txt3= SqlFilter($_REQUEST["txt3"],"tab");
        }else{
            $txt3 = NULL;
        }
        $SQL = "SELECT * FROM webdata where types='mobile_index_banner_txt3'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $SQL = "UPDATE webdata SET d1='".$txt3."' where types='mobile_index_banner_txt3'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }else{
            $SQL = "INSERT INTO webdata (d1,types) VALUES ('".$txt3."','mobile_index_banner_txt3')";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }

        if($_REQUEST["txt4"] != ""){
            $txt4= SqlFilter($_REQUEST["txt4"],"tab");
        }else{
            $txt4 = NULL;
        }
        $SQL = "SELECT * FROM webdata where types='mobile_index_banner_txt4'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            $SQL = "UPDATE webdata SET d1='".$txt4."' where types='mobile_index_banner_txt4'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }else{
            $SQL = "INSERT INTO webdata (d1,types) VALUES ('".$txt4."','mobile_index_banner_txt4')";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }

        reURL("springweb_fun22.php");
    }
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>春天網站系統</li>
            <li class="active">首頁-文字設定</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>首頁-文字設定</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form method="post" action="?st=txtsave">
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <tbody>
                            <?php 
                                $SQL = "SELECT * FROM webdata where types in ('mobile_index_banner_txt1','mobile_index_banner_txt2','mobile_index_banner_txt3','mobile_index_banner_txt4')";
                                $rs = $SPConn->prepare($SQL);
                                $rs->execute();
                                $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                                if($result){
                                    foreach($result as $re){
                                        switch($re["types"]){
                                            case "mobile_index_banner_txt1":
                                                $txt1 = $re["d1"];
                                                break;
                                            case "mobile_index_banner_txt2":
                                                $txt2 = $re["d1"];
                                                break;
                                            case "mobile_index_banner_txt3":
                                                $txt3 = $re["d1"];
                                                break;
                                            case "mobile_index_banner_txt4":
                                                $txt4 = $re["d1"];
                                                break;
                                        }
                                    }                                    
                                }
                            ?>
                            <tr>
                                <td>首頁-上方Banner-文字</td>
                                <td>大字：<input type="text" name="txt1" id="txt1" value="<?php echo $txt1; ?>">
                                    <br>小字：<input type="text" name="txt2" id="txt2" value="<?php echo $txt2; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>首頁-中間Banner-文字</td>
                                <td>大字：<input type="text" name="txt3" id="txt3" value="<?php echo $txt3; ?>">
                                    <br>小字：<input type="text" name="txt4" id="txt4" value="<?php echo $txt4; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2 style="text-align:center;"><input type="submit" value="送出" class="btn btn-info"></td>
                            </tr>
                        </tbody>
                    </table>
                </form>

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