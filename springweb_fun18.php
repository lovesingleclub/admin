<?php
    /*****************************************/
    //檔案名稱：springweb_fun18.php
    //後台對應位置：春天網站系統/戀愛講堂-Banner
    //改版日期：2022.4.8
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

    // 圖片上移
    if($_REQUEST["st"] == "mup"){
        $nowline = round(SqlFilter($_REQUEST["i1"],"int"));
        $upline = $nowline+1;
        $SQL = "update webdata set i1=".$nowline." where i1='".$upline."'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $SQL = "update webdata set i1=".$upline." where auton=".SqlFilter($_REQUEST["an"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();

        reURL("springweb_fun18.php?ty=".SqlFilter($_REQUEST["v"],"int"));
    }

    // 圖片下移
    if($_REQUEST["st"] == "mdo"){
        $nowline = round(SqlFilter($_REQUEST["i1"],"int"));
        $upline = $nowline-1;
        $SQL = "update webdata set i1=".$nowline." where i1=".$upline."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $SQL = "update webdata set i1=".$upline." where auton=".SqlFilter($_REQUEST["an"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();

        reURL("springweb_fun18.php?ty=".SqlFilter($_REQUEST["v"],"int"));
    }

    // 刪除圖片(待測試)
    if($_REQUEST["st"] == "del"){
        $SQL = "select d2 from webdata where auton=".SqlFilter($_REQUEST["an"],"int")." and types='lovesalon_banner_".SqlFilter($_REQUEST["v"],"int")."'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            DelFile(("upload_image/".$result["d2"]));
            $SQL = "delete from webdata where auton=".SqlFilter($_REQUEST["an"],"int")." and types='lovesalon_banner_".SqlFilter($_REQUEST["v"],"int")."'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
            if($rs){
                reURL("springweb_fun18.php?ty=".SqlFilter($_REQUEST["v"],"int"));
            }
        }        
    }

    // 新增標籤
    if($_REQUEST["st"] == "addtag"){
        $SQL = "INSERT INTO webdata (d1,types,t1) VALUES ('".SqlFilter($_REQUEST["tagv"],"tab")."','lovesalon_tag','".date("Y/m/d H:i:s")."')";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();

        reURL("springweb_fun18.php");
    }

    // 移除標籤
    if($_REQUEST["st"] == "deltag"){
        $SQL = "delete from webdata where auton=".SqlFilter($_REQUEST["an"],"int")." and types='lovesalon_tag'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();

        reURL("springweb_fun18.php");
    }

    $nowty = SqlFilter($_REQUEST["ty"],"int");
    if($nowty == ""){
        $nowty = 1;
    }

    switch($nowty){
        case "2":
            $showb = "廣告一";
	        $btn2 = "disabled";
            break;
        case "3":
            $showb = "廣告二";
            $btn3 = "disabled";
            break;
        default:
            $showb = "戀愛講堂 Banner";
            $btn1 = "disabled";
    }
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>春天網站系統</li>
            <li class="active">戀愛講堂-Banner & Tag-<?php echo $showb; ?></li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>戀愛講堂-Banner & Tag-<?php echo $showb; ?></strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">

                <p><input type="button" class="btn btn-info" value="新增 Banner" onclick="Mars_popup('springweb_fun18_add.php','','scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=690,height=300,top=10,left=10');">
                    <input type="button" class="btn btn-success" value="戀愛講堂 Banner" onclick="location.href='?ty=1'" <?php echo $btn1; ?>>　
                    <input type="button" class="btn btn-warning" value="廣告一" onclick="location.href='?ty=2'" <?php echo $btn2; ?>>
                    <input type="button" class="btn btn-danger" value="廣告二" onclick="location.href='?ty=3'" <?php echo $btn3; ?>>
                </p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th width="70"></th>
                            <th>圖片</th>
                            <th>連結位置</th>
                            <th width="160">資料時間</th>
                            <th>操作</th>
                        </tr>
                        <?php 
                            $SQL = "SELECT * FROM webdata where types='lovesalon_banner_".$nowty."' order by i1 desc";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                $ii = 0;
                                foreach($result as $re){
                                    if($ii == 0){
                                        $uahref = "#nu\" onclick=\"alert('無法向上');\"";
                                    }else{
                                        $uahref = "?st=mup&an=".$re["auton"]."&i1=".$re["i1"]."&v=".$nowty."";
                                    }                                   
                                    if($ii == count($result)-1){
                                        $dahref = "#nu\" onclick=\"alert('無法向下');\"";
                                    }else{
                                        $dahref = "?st=mdo&an=".$re["auton"]."&i1=".$re["i1"]."&v=".$nowty."";
                                    } ?>
                                    <tr>
                                        <td><a href="<?php echo $uahref; ?>"><span class="fa fa-arrow-up margin-left-10 margin-right-10"></span></a><a href="<?php echo $dahref; ?>"><span class="fa fa-arrow-down"></span></a></td>
                                        <td><a href="upload_image/<?php echo $re["d2"] ?>" class="fancybox"><img src="upload_image/<?php echo $re["d2"] ?>" border=0 height=40></a></td>
                                        <td><?php echo $re["d1"] ?></td>
                                        <td><?php echo changeDate($re["t1"]) ?></td>
                                        <td>
                                            <a href="javascript:Mars_popup('springweb_fun18_add.php?an=<?php echo $re["auton"]."&v=".$nowty.""; ?>','','scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=690,height=300,top=10,left=10');">編輯</a>
                                            <a title="刪除" href="springweb_fun18.php?st=del&an=<?php echo $re["auton"]."&v=".$nowty.""; ?>">刪除</a>						
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


        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>戀愛講堂-Tag</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <?php 
                            $SQL = "SELECT * FROM webdata where types='lovesalon_tag' order by t1 desc";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                $ii = 0;
                                foreach($result as $re){ ?>
                                    <tr>
                                        <td><?php echo $re["d1"] ?></td>
                                        <td><?php echo changeDate($re["t1"]) ?></td>
                                        <td>
                                            <a title="刪除" href="springweb_fun18.php?st=deltag&an=<?php echo $re["auton"]; ?>">刪除</a>	
                                        </td>
                                    </tr>
                                <?php $ii = $ii+1; }
                            }else{
                                echo "<tr><td colspan=4>目前無資料</td></tr>";
                            }
                        ?>
                        <tr>
                            <td colspan=3>
                                <form name="forms" id="f1" method="post" action="?st=addtag" onsubmit="return check_form();">熱門標籤TAG：<input type="text" name="tagv" id="tagv" class="form-control2"> <input type="submit" class="btn btn-default" value="新增"></form>
                            </td>
                        </tr>
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
    function check_form() {
        if(!$("#tagv").val()) {
            alert("請輸入熱門標籤TAG。");
            $("#tagv").focus();
            return false;
        }
        return true;
    }
</script>