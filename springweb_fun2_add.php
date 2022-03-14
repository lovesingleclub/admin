<?php
require_once("_inc.php");
require_once("./include/_function.php");
require_once("./include/_top.php");
require_once("./include/_sidebar.php");
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>春天網站系統</li>
            <li><a href="springweb_fun2.php">媒體報導</a></li>
            <li class="active">新增/修改媒體報導</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>新增/修改媒體報導</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form id="mform" method="post" action="springweb_fun2_add.php" class="form-inline" onSubmit="return chk_form()">
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <tbody>
                            <tr>
                                <td width="150" align="left" valign="middle">媒體報導大標</td>
                                <td><input name="t_title" id="t_title" value="" class="form-control" required></td>
                            </tr>
                            <tr>
                            <tr>
                                <td width="150" align="left" valign="middle">媒體報導小標</td>
                                <td><input name="st_title" id="st_title" value="" class="form-control"></td>
                            </tr>
                            <tr>
                                <td width="150" align="left" valign="middle">媒體報導類型</td>
                                <td><input name="t_type" id="t_type" value="" class="form-control"></td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">媒體報導連結位置</td>
                                <td><input name="t_url" id="t_url" value="" class="form-control"></td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">建立時間</td>
                                <td>2021/10/22 下午 01:59:50</td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">報導日期</td>
                                <td><input name="t_showtime" id="t_showtime" value="" class="datepicker" autocomplete="off"></td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">上傳圖檔</td>
                                <td>

                                    <div id="img_div">

                                    </div>
                                    <div>
                                        <span class="btn btn-danger fileinput-button"><span>上傳檔案</span><input data-no-uniform="true" id="file_uploads" type="file" class="fileupload" name="fileupload"></span>
                                        <div id="progress" class="progress progress-striped" style="display:none">
                                            <div class="bar progress-bar progress-bar-lovepy"></div>
                                        </div>
                                    </div>
                                    <div id="fileupload_show"></div>
                                    <br>
                                    取得 youtube 縮圖：http://img.youtube.com/vi/連結位置/1.jpg<br>
                                    　　　　　　　　　 http://img.youtube.com/vi/連結位置/2.jpg<br>
                                    　　　　　　　　　 http://img.youtube.com/vi/連結位置/3.jpg <br>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle">內文</td>
                                <td><textarea name="t_note" style="width:80%;height:150px;"></textarea></td>
                            </tr>
                            <tr>
                                <td colspan=2 align="center"><input name="t_pic" id="t_pic" type="hidden" value=""><input name="acts" id="acts" type="hidden" value="ad"><input name="pid" type="hidden" id="pid" value="">
                                    <input type="submit" value="確認送出" class="btn btn-info" style="width:50%;">
                                </td>
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

<script type="text/javascript" src="assets/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.fileupload.js"></script>
<script language="JavaScript">
    $mtu = "springweb_fun2.";
    var $fsend = 0;
    var $ff;

    function chk_form() {
        if (!$ff || $fsend) return true;
        if ($ff && !$fsend) {
            $ff.submit();
            return false;
        }
        return false;
    }

    $(function() {

        $(".fileupload").each(function() {

            var $this = $(this),
                $thisid = $this.attr("id"),
                $progress = $this.closest("div").find(".progress");
            var $imgs = $(this).closest("span").find("#cimg").val();

            $this.fileupload({
                    url: "springweb_fun2_add.asp?st=upload&t_pic=",
                    type: "POST",
                    dropZone: $this,
                    dataType: 'html',
                    autoUpload: false,
                    done: function(e, data) {

                        if (data.result) {
                            $("#t_pic").val(data.result);
                            $("#img_div").find("img").remove();
                            $("#img_div").append($("<img width=60 src='upload_image/" + data.result + "' border=0></img>"));
                            $fsend = 1;
                            $("#mform").submit();
                        }
                    },
                    fail: function(e, data) {

                    },
                    progressall: function(e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $progress.show().find(".progress-bar").css(
                            'width',
                            progress + '%'
                        );
                    },
                    add: function(e, data) {
                        var uploadErrors = [];
                        var acceptFileTypes = /^image\/(gif|jpe?g|png)$/i;
                        if (data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
                            uploadErrors.push('目前僅開放上傳 gif, jpg, jpeg, png 檔案。');
                        }
                        if (data.originalFiles[0]['size'] > 5000000) {
                            uploadErrors.push('檔案大小超過限制。');
                        }
                        if (uploadErrors.length > 0) {
                            alert(uploadErrors.join("\n"));
                        }
                        if (data.files) {
                            $("#fileupload_show").html(data.files[data.files.length - 1].name);
                            $ff = data;
                        }
                    }
                }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');
        });
    });
</script>