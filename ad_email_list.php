<?php
    /*****************************************/
    //檔案名稱：ad_email_list.php
    //後台對應位置：管理系統/信箱對照
    //改版日期：2022.3.15
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

    if($_REQUEST["st"] == "send"){
        $emails = SqlFilter($_REQUEST["emails"],"tab");
        $emails = str_replace(" ","",$emails);
        if($emails == ""){
            call_alert("請在查詢框內輸入 email",0,0);
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
        $sqls = "SELECT * FROM member_data WHERE mem_mail in (".$emailss.")";
    }else{
        $sqls = "SELECT * FROM member_data WHERE 1=0";
    }

    $sqls = $sqls . " order by mem_auto desc";
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li class="active">信箱對照</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>信箱對照</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">

                <table class="table table-striped table-bordered bootstrap-datatable">

                    <tr>
                        <form action="?st=send" method="post" name="form5">
                            <td class="text-center"><textarea name="emails" style="width:80%;height:100px;"></textarea>
                                <p><input type="submit" class="btn btn-info" style="width:50%;" value="查詢"></p>
                            </td>
                        </form>
                    </tr>
                </table>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>會館</th>
                            <th>秘書名</th>
                            <th>會員姓名</th>
                            <th>會員學歷</th>
                            <th>會員電話</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $rs = $SPConn->prepare($sqls);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                $i = 0;
                                foreach($result as $re){ ?>
                                    <tr>
                                        <td><?php echo $i ?></td>
                                        <td><?php echo $re["mem_branch"] ?></td>
                                        <td><?php echo SingleName($re["mem_single"],"normal"); ?></td>
                                        <td><?php echo $re["mem_name"]; ?></td>
                                        <td><?php echo $re["mem_school"]; ?></td>
                                        <td><?php echo $re["mem_mobile"]; ?></td>
                                    </tr>
                                <?php 
                                    $i = $i + 1;
                                }
                            }else{
                                echo "<tr><td colspan=6 height=200>目前沒有資料</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
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