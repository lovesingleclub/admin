<?php
    /*****************************************/
    //檔案名稱：springweb_fun26.php
    //後台對應位置：春天網站系統/服務據點-環境照
    //改版日期：2022.4.24
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
        $SQL = "update web_photo set de=".$nowline." where de=".$upline." and types='place'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $SQL = "update web_photo set de=".$upline." where auton=".SqlFilter($_REQUEST["an"],"int")." and types='place'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();

        reURL("springweb_fun26.php?b=".SqlFilter($_REQUEST["b"],"tab"));
    }

    // 圖片下移
    if($_REQUEST["st"] == "mdo"){
        $nowline = round(SqlFilter($_REQUEST["i1"],"int"));
        $upline = $nowline-1;
        $SQL = "update web_photo set de=".$nowline." where de=".$upline." and types='place'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $SQL = "update web_photo set de=".$upline." where auton=".SqlFilter($_REQUEST["an"],"int")." and types='place'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();

        reURL("springweb_fun26.php?b=".SqlFilter($_REQUEST["b"],"tab"));
    }

    // 刪除圖片(待測試)
    if($_REQUEST["st"] == "del"){
        $SQL = "select photo_name from web_photo where auton=".SqlFilter($_REQUEST["an"],"int")." and types='place'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            DelFile(("upload_image/".$result["photo_name"]));
            $SQL = "delete from photo_name where auton=".SqlFilter($_REQUEST["an"],"int")." and types='place'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
            if($rs){
                reURL("springweb_fun26.php?b=".SqlFilter($_REQUEST["b"],"tab"));
            }
        }        
    }

    if($_REQUEST["b"] != ""){
        $branch = SqlFilter($_REQUEST["b"],"tab");
    }else{
        $branch = "台北";
    }

?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>春天網站系統</li>
            <li class="active">服務據點-環境照</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>服務據點-環境照-台北</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <p>
                    <a href="javascript:Mars_popup('springweb_fun26_add.php?b=<?php echo $branch; ?>','','scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=690,height=300,top=10,left=10');" class="btn btn-success">新增環境照</a>
                    <a href="?b=台北" class="btn btn-info">台北會館</a>
                    <a href="?b=桃園" class="btn btn-info">桃園會館</a>
                    <a href="?b=新竹" class="btn btn-info">新竹會館</a>
                    <a href="?b=台中" class="btn btn-info">台中會館</a>
                    <a href="?b=台南" class="btn btn-info">台南會館</a>
                    <a href="?b=高雄" class="btn btn-info">高雄會館</a>

                </p>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th width="70"></th>
                            <th>會館</th>
                            <th>圖片</th>
                            <th>ALT</th>
                            <th>操作</th>
                        </tr>
                        <?php
                            $SQL = "SELECT * FROM web_photo where types='place' and branch='".$branch."' order by de desc";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                $ii = 0;
                                foreach($result as $re){
                                    if($ii == 0){
                                        $uahref = "#nu\" onclick=\"alert('無法向上');\"";
                                    }else{
                                        $uahref = "?st=mup&b=".$branch."&an=".$re["auton"]."&i1=".$re["de"];
                                    }                                   
                                    if($ii == count($result)-1){
                                        $dahref = "#nu\" onclick=\"alert('無法向下');\"";
                                    }else{
                                        $dahref = "?st=mdo&b=".$branch."&an=".$re["auton"]."&i1=".$re["de"];
                                    } ?>
                                    <tr>
                                        <td><a href="<?php echo $uahref; ?>"><span class="fa fa-arrow-up margin-left-10 margin-right-10"></span></a><a href="<?php echo $dahref; ?>"><span class="fa fa-arrow-down"></span></a><?php echo $re["de"]; ?></td>
                                        <td><?php echo $re["branch"]; ?></td>
                                        <td>
                                            <?php if($re["photo_name"] != ""){ ?>
                                                <a href="upload_image/<?php echo $re["photo_name"] ?>" class="fancybox"><img src="upload_image/<?php echo $re["photo_name"] ?>" border=0 height=40></a>
                                            <?php }else{
                                                echo "無";
                                            }
                                            ?>                                           
                                        </td>
                                        <td><?php echo $re["vurl"] ?></td>
                                        <td>
                                            <a href="javascript:Mars_popup('springweb_fun26_add.php?an=<?php echo $re["auton"] ?>','','scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=690,height=300,top=10,left=10');">編輯</a>
                                            <a title="刪除" href="springweb_fun26.php?st=del&an=<?php echo $re["auton"] ?>">刪除</a>						
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