<?php
require_once("./include/_inc.php");
require_once("./include/_function.php");
require_once("./include/_top.php");
require_once("./include/_sidebar.php")
?>


<link href="css/select2.min.css" rel="stylesheet">
<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.asp">管理系統</a></li>
            <li><a href="ad_system_sign_list.asp">申請簽核</a></li>
            <li class="active">提出申請</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>申請簽核-提出申請</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form id="searchform" action="ad_system_sign_list_add.asp" method="post" class="form-inline" target="_self">
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <tbody>
                            <tr>
                                <td><label class="pull-left vcenter" style="height:34px;line-height:34px;">申請人：總管理處 - JACK</label></td>
                            </tr>
                            <tr>
                                <td><label class="pull-left vcenter" style="height:34px;line-height:34px;">類型：</label>
                                    <select name="types" id="types" onchange="location.href='?t='+this.value+''" required>
                                        <option value="">請選擇</option>
                                        <option value="修改身分證字號">修改身分證字號</option>
                                        <option value="修改手機號碼">修改手機號碼</option>
                                        <option value="開秘書後台">開秘書後台</option>
                                        <option value="關秘書後台">關秘書後台</option>
                                        <option value="跨區/身分證重覆">跨區/身分證重覆</option>
                                        <option value="再開發資料">再開發資料</option>
                                        <option value="其他">其他</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>

                                    <textarea style="width:100%;height:300px;" name="notes" required>請先選擇類型後再依系統文字說明填入詳細資訊</textarea>

                                </td>
                            </tr>

                            <tr>
                                <td><input type="hidden" name="st" value="add"><input type="submit" value="送出申請" class="btn btn-success"></td>
                            </tr>
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