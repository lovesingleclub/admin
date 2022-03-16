<?php
    /*****************************************/
    //檔案名稱：ad_allemail_list.php
    //後台對應位置：管理系統/電子信箱列表
    //改版日期：2022.3.15
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/
    
    require_once("_inc.php");
    require_once("./include/_function.php");
    require_once("./include/_top.php");
    require_once("./include/_sidebar.php");

    // 刪除
    if($_REQUEST["st"] == "del"){
        $SQL = "delete from emailpaper where auton=".SqlFilter($_REQUEST["an"],"int")."";
        $rs = $SPConn->prepare($SQL);
        $rs->execute();
        reURL("win_close.php?m=刪除中");
    }
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li class="active">電子信箱列表</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>電子信箱列表</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <?php 
                    if($_REQUEST["y1"] != ""){
                        $y1 = SqlFilter($_REQUEST["y1"],"tab")." 00:00";
                    }else{
                        $y1 = date("Y")."/".date("m")."/1"." 00:00";
                    }

                    if($_REQUEST["y2"] != ""){
                        $y2 = SqlFilter($_REQUEST["y1"],"tab")." 23:59";
                    }else{
                        $y2 = date("Y/m/d")." 23:59";
                    }

                ?>
                <form name="form1" method="post" action="ad_allemail_list_excel.php" class="form-inline">
                    <p>日期： <input type="text" name="y1" class="datepicker" autocomplete="off" value="<?php echo Date_EN($y1,1); ?>"> 至
                        <input type="text" name="y2" class="datepicker" autocomplete="off" value="<?php echo Date_EN($y2,1); ?>">
                        <input type="checkbox" name="sex_1" value="1"<?php if($_REQUEST["sex_1"] == "1") echo " checked"; ?>> 男&nbsp;&nbsp;<input type="checkbox" name="sex_2" value="1"<?php if($_REQUEST["sex_2"] == "1") echo " checked"; ?>> 女<br>
                        <input type="checkbox" name="aa3" value="1"<?php if($_REQUEST["aa3"] == "1") echo " checked"; ?>> 已入會&nbsp;&nbsp;<input type="checkbox" name="aa1" value="1"<?php if($_REQUEST["aa1"] == "1") echo " checked"; ?>> 未入會&nbsp;&nbsp;<input type="checkbox" name="aa2" value="1"<?php if($_REQUEST["aa2"] == "1") echo " checked"; ?>> 報名資料
                    </p>
                    <p>
                        <input type="radio" name="bb" value="1"<?php if($_REQUEST["bb"] == "1") echo " checked"; ?>> 春天&nbsp;&nbsp;<input type="radio" name="bb" value="2"<?php if($_REQUEST["bb"] == "2") echo " checked"; ?>> 八德
                        &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="Submit" class="btn btn-default" value="匯出 Excel">
                    </p>
                </form>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <?php 
                        $tsql = " between '".$y1."' and '".$y2."'";
                        if($_REQUEST["sex_1"] == "1" && $_REQUEST["sex_2"] == "1"){
                            $tsql1 = $tsql1."";
                            $tsql2 = $tsql2."";
                        }elseif($_REQUEST["sex_1"] == "1"){
                            $tsql1 = $tsql1." and mem_sex='男'";
                            $tsql2 = $tsql2." and k_sex='男'";
                        }elseif($_REQUEST["sex_2"] == "1"){
                            $tsql1 = $tsql1." and mem_sex='女'";
                            $tsql2 = $tsql2." and k_sex='女'";
                        }
                        $vtsql1 = "";
                        $vtsql2 = "";
                        
                        if($_REQUEST["bb"] == "2"){
                            $vtsql1 = $vtsql1." and mem_branch='八德'";
                            $vtsql2 = $vtsql2." and k_branch='八德'";
                        }else{
                            $vtsql1 = $vtsql1." and mem_branch<>'八德'";
                            $vtsql2 = $vtsql2." and k_branch<>'八德'";
                        }
            
                        if($_REQUEST["aa1"] != "" || $_REQUEST["aa2"] != "" || $_REQUEST["aa3"] != ""){
                            if($_REQUEST["aa1"] == "1" && $_REQUEST["aa2"] == "1"){
                                $fsql =  "select mem_mail as e, mem_time as t, '未入會' as c, mem_sex as d, mem_branch as b from member_data as md1 where mem_level='guest' ".$vtsql1." and mem_mail <> '' and (NOT (noemail = 1)) and ((select count(mem_auto) from member_data as md2 where mem_level='guest' and md1.mem_mail = md2.mem_mail and md2.mem_time > md1.mem_time) <= 0) and mem_time".$tsql."".$tsql1." UNION ALL";
                                $fsql = $fsql . " select k_yn as e, k_time as t, '報名資料' as c, k_sex as d, k_branch as b from love_keyin as lkdb1 where k_yn <> '' ".$vtsql2." and (NOT (noemail = 1)) and ((select count(k_id) from love_keyin as lkdb2 where lkdb1.k_yn = lkdb2.k_yn and lkdb1.k_time > lkdb2.k_time) <= 0) and ((select count(mem_auto) from member_data where member_data.mem_mail = lkdb1.k_yn) <= 0) and k_time".$tsql."".$tsql2." ";
                                $fsql = $fsql . "order by t desc";
                            }elseif($_REQUEST["aa1"] == "1" && $_REQUEST["aa3"] == "1"){
                                $fsql = "select mem_mail as e, mem_time as t, mem_level as c, mem_sex as d, mem_branch as b from member_data as md1 where 1=1 ".$vtsql1." and mem_mail <> '' and (NOT (noemail = 1)) and mem_time".$tsql."".$tsql1." order by t desc";
                            }elseif($_REQUEST["aa3"] == "1"){
                                $fsql = "select mem_mail as e, mem_time as t, '已入會' as c, mem_sex as d, mem_branch as b from member_data as md1 where mem_level='mem' ".$vtsql1." and mem_mail <> '' and (NOT (noemail = 1)) and mem_time".$tsql."".$tsql1." order by t desc";
                            }elseif($_REQUEST["aa1"] == "1"){
                                $fsql = "select mem_mail as e, mem_time as t, '未入會' as c, mem_sex as d, mem_branch as b from member_data as md1 where mem_level='guest' ".$vtsql1." and mem_mail <> '' and (NOT (noemail = 1)) and ((select count(mem_auto) from member_data as md2 where mem_level='guest' and md1.mem_mail = md2.mem_mail and md2.mem_time > md1.mem_time) <= 0) and mem_time".$tsql."".$tsql1." order by t desc";
                            }elseif($_REQUEST["aa2"] == "1"){
                                $fsql = "select k_yn as e, k_time as t, '報名資料' as c, k_sex as d, k_branch as b from love_keyin as lkdb1 where k_yn <> '' ".$vtsql2." and (NOT (noemail = 1)) and ((select count(k_id) from love_keyin as lkdb2 where lkdb1.k_yn = lkdb2.k_yn and lkdb1.k_time > lkdb2.k_time) <= 0) and ((select count(mem_auto) from member_data where member_data.mem_mail = lkdb1.k_yn) <= 0) and k_time".$tsql."".$tsql2." order by t desc";
                            }
            
                            $rs = $SPConn->prepare($fsql);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                echo "<p>統計共 ".count($result)." 筆</p>";
                                echo "<tr><th width=200>時間</th><th>信箱</th><th width=50>性別</th><th width=50>會館</th><th width=100>來源</th></tr>";
                                foreach($result as $re){
                                    echo "<tr><td>".changeDate($re["t"])."</td><td>".$re["e"]."</td><td>".$re["d"]."</td><td>".$re["b"]."</td><td>".$re["c"]."</td></tr>";
                                }
                            }
                        }
                    ?>
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