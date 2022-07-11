<?php
require_once("./include/_inc.php");
require_once("./include/_function.php");
require_once("./include/_top.php");
require_once("./include/_sidebar.php")
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li><a href="ad_announce.php">公告訊息</a></li>
            <li class="active">已讀紀錄 - 員工福利特別公告</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>已讀紀錄 - 員工福利特別公告</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <div class="col-md-12 margin-bottom-10">

                    <a href="#div_0" class="btn btn-success scrollTo">台北</a>&nbsp;<a href="#div_1" class="btn btn-success scrollTo">桃園</a>&nbsp;<a href="#div_2" class="btn btn-success scrollTo">新竹</a>&nbsp;<a href="#div_3" class="btn btn-success scrollTo">台中</a>&nbsp;<a href="#div_4" class="btn btn-success scrollTo">台南</a>&nbsp;<a href="#div_5" class="btn btn-success scrollTo">高雄</a>&nbsp;<a href="#div_6" class="btn btn-success scrollTo">八德</a>&nbsp;<a href="#div_7" class="btn btn-success scrollTo">約專</a>&nbsp;<a href="#div_8" class="btn btn-success scrollTo">迷你約</a>&nbsp;<a href="#div_9" class="btn btn-success scrollTo">總管理處</a>&nbsp;
                </div>

                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <td id="div_0" class="text-info">台北</td>
                        </tr>
                        <tr>
                            <td>
                                <table class="table table-striped table-bordered bootstrap-datatable">
                                    <tr>
                                        <td>
                                            <font color=#666>台北主任</font> <small>(2022-07-02 12:59)</small>
                                        </td>
                                        <td>
                                            <font color=#666>崔慶三</font> <small>(2022-07-02 14:41)</small>
                                        </td>
                                        <td>
                                            <font color=#666>許凱甯</font> <small>(2022-07-03 10:26)</small>
                                        </td>
                                        <td>
                                            <font color=#666>黃明儀</font> <small>(2022-07-05 13:43)</small>
                                        </td>
                                        <td>
                                            <font color=#666>林馨彤</font> <small>(2022-07-06 13:42)</small>
                                        </td>
                                        <td width="14%"></td>
                                        <td width="14%"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <center><a class="btn btn-info" style="width:50%;" href="#b" onclick="window.history.back();">回上一頁</a></center>
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

<script type="text/javascript">
    $(function() {
        $("#search_send").on("click", function(event) {
            if (!$("#keyword").val() && !$("#branch").val()) {
                $("#keyword").focus();
                alert("請輸入要搜尋的關鍵字。");
                return false;
            }
            if (!$("#keyword_type").val()) {
                alert("have error。");
                return false;
            }
            location.href = "ad_single_list.php?sear=1&vst=full&branch=" + $("#branch").val() + "&" + $("#keyword_type").val() + "=" + $("#keyword").val();
        });


    });
</script>