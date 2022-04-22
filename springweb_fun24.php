<?php
    /*****************************************/
    //檔案名稱：springweb_fun24.php
    //後台對應位置：春天網站系統/活動表下載
    //改版日期：2022.4.23
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

    // 刪除(有刪除圖檔功能待測)
    if($_REQUEST["st"] == "del"){
        $SQL = "select d2 from webdata where auton=".SqlFilter($_REQUEST["an"],"int")." and types='actiondownload'";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        $result = $rs->fetch(PDO::FETCH_ASSOC);
        if($result){
            DelFile("upload_image".$result["d2"]);
            $SQL = "UPDATE webdata SET d2=NULL where auton=".SqlFilter($_REQUEST["an"],"int")." and types='actiondownload'";
            $rs = $SPConn->prepare($SQL);
            $rs->execute();
        }
        reURL("springweb_fun24.php");
    }
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>春天網站系統</li>
            <li class="active">活動表下載</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>活動表下載</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th width="70">會館</th>
                            <th>圖片</th>
                            <th>操作</th>
                        </tr>
                        <?php 
                            $SQL = "SELECT * FROM webdata where types='actiondownload' order by d1 asc";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetch(PDO::FETCH_ASSOC);
                            if($result){
                                foreach($result as $re){ ?>
                                    <tr>
                                        <td><?php echo $re["d1"]; ?><%=rs("d1")%></td>				
                                        <td>
                                            <?php 
                                                if($re["d2"] != ""){ ?>
                                                     <a href="upload_image/<?php echo $re["d2"]; ?>" class="fancybox"><img src="upload_image/<?php echo $re["d2"]; ?>" border=0 height=40></a>
                                                <?php }else{
                                                    echo "無";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="javascript:Mars_popup('springweb_fun24_add.asp?an=<%=rs("auton")%>','','scrollbars=yes,status=yes,menubar=yes,resizable=yes,width=690,height=300,top=10,left=10');">編輯</a>
                                            <a title="刪除" href="springweb_fun24.asp?st=del&an=<%=rs("auton")%>">刪除</a>						
                                        </td>
                                    </tr>
                                <?php }
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