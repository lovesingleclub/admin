<?php
require_once("./include/_inc.php");
require_once("./include/_function.php");
require_once("./include/_top.php");
require_once("./include/_sidebar.php")
?>

<link href="css/select2.min.css" rel="stylesheet">
<!-- MIDDLE  -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.asp">管理系統</a></li>
            <li><a href="ad_system_sign_list.asp">申請簽核</a></li>
            <li class="active">處理申請</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>申請簽核-處理申請</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">

                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <td><label class="pull-left vcenter" style="height:34px;line-height:34px;">申請人：總管理處 - 曉娟</label></td>
                        </tr>
                        <tr>
                            <td><label class="pull-left vcenter" style="height:34px;line-height:34px;">類型：活動異動單</label>
                            </td>
                        </tr>
                        <tr>
                            <td>

                                <textarea style="width:100%;height:300px;" name="notes" readonly>活動資訊：2021/12/19 下午 02:30:00 國營企業40歲↓男[13183] 申請新增</textarea>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                [2021-09-17 15:12] 曉娟提出申請 - 活動新增<br>[2021-09-17 15:12] 由 系統自動 結案-已處理(活動標註為活動新增)
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- /MIDDLE  -->

<?php
require_once("./include/_bottom.php");
?>

<script src="js/select2.min.js"></script>
<script language="JavaScript">
    $mtu = "ad_system_sign_list.";
    $(function() {

    });
</script>