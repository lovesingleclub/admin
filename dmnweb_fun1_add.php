<?php
    /*****************************************/
    //檔案名稱：dmnweb_fun1.php
    //後台對應位置：DateMeNow網站系統/自訂ABOUT
    //改版日期：2022.8.12
    //改版設計人員：Jack
    //改版程式人員：Jack
    /*****************************************/

    require_once("_inc.php");
    require_once("./include/_function.php");
    require_once("./include/_top.php");
    require_once("./include/_sidebar.php");

    // 程式開始
    if($_SESSION["MM_Username"] == ""){
        call_alert("請重新登入。","login.php",0);
    }

    if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["dmnweb"] != "1"){
        call_alert("您沒有查看此頁的權限。","login.php",0);
    }

    //編輯、新增檔案(有新增及寫入檔案功能)
    if($_REQUEST["st"] == "save"){
        $getpath = "../datemenow";

        if($_REQUEST["t"] == "ed" && $_REQUEST["fname"] != ""){
            $fname = SqlFilter($_REQUEST["fname"],"tab");
        }else{
            if(is_dir($getpath)) {
                if($dh = opendir($getpath)) {
                    $nowsize = 0;
                    while (($file = readdir($dh)) !== false) {
                        if(substr($file,0,6) == "about_"){
                            $nowsize = $nowsize + 1;
                            $fname = $file;
                        }
                    }
                    closedir($dh);
                }
            }

            if(is_file($getpath."/".$fname)){
                $fname = "about_".($nowsize + 1).".php";
            }else{
                $fname = "about_1.php";
            }        
        }

        $notes = $notes . "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>" . PHP_EOL;
        $notes = $notes . "<html xmlns='http://www.w3.org/1999/xhtml'>" . PHP_EOL;
        $notes = $notes . "<head>" . PHP_EOL;
        $notes = $notes . "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>" . PHP_EOL;
        $notes = $notes . "<title>".SqlFilter($_REQUEST["title"],"tab")."</title>" . PHP_EOL;
        $notes = $notes . "<meta name='description' content='".SqlFilter($_REQUEST["description"],"tab")."'>" . PHP_EOL;
        $notes = $notes . "<meta name='keywords' content='".SqlFilter($_REQUEST["keywords"],"tab")."'>" . PHP_EOL;
        $notes = $notes . "</head>" . PHP_EOL;            
        $notes = $notes . "<style>" . PHP_EOL;
        $notes = $notes . "table, table tr, table td {border:0px solid #ffffff;color:#fff;background:#fff}" . PHP_EOL;
        $notes = $notes . "a {color:#fff;text-decoration:none}" . PHP_EOL;
        $notes = $notes . "</style>" . PHP_EOL;
        $notes = $notes . "<body>" . PHP_EOL;
        $notes = $notes . "<!-- bodystart -->" . PHP_EOL;
        $notes = $notes . strip_tags($_REQUEST["notes"]) . PHP_EOL;    
        $notes = $notes . "<!-- bodyend -->" . PHP_EOL;
        $notes = $notes . "</body>" . PHP_EOL;

        //寫入OR新增檔案
        file_put_contents($getpath."/".$fname, $notes);   
    
        reURL("dmnweb_fun1.php");
    }

    // 讀取檔案
    if($_REQUEST["t"] == "ed"){
        $tt = "修改";
        $fname = SqlFilter($_REQUEST["f"],"tab");
        if($fname != ""){
            $getpath = "../datemenow";
            //讀取檔案
            $notes = file_get_contents($getpath."/".$fname);
            //讀取title
            $kk = "<title>";
            $t1 = stripos($notes,$kk);
            if($t1 > 0){            
                $notes1 = substr($notes,($t1+strlen($kk)));
                $t2 = stripos($notes1,"</title>"); 
                $title = substr($notes1,0,$t2);            
            }
            //讀取description
            $kk = "<meta name='description' content='";
            $t1 = stripos($notes,$kk);
            if($t1 > 0){            
                $notes1 = substr($notes,($t1+strlen($kk)));
                $t2 = stripos($notes1,"'>"); 
                $description = substr($notes1,0,$t2);            
            }
            //讀取keywords
            $kk = "<meta name='keywords' content='";
            $t1 = stripos($notes,$kk);
            if($t1 > 0){            
                $notes1 = substr($notes,($t1+strlen($kk)));
                $t2 = stripos($notes1,"'>"); 
                $keywords = substr($notes1,0,$t2);            
            }

            //讀取本文
            $notes = substr($notes,(stripos($notes,"<!-- bodystart -->")+18));
            $notes = substr($notes,0,stripos($notes,"<!-- bodyend -->"));
        }
    }else{
        $tt = "新增";
    }
?>

<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>DateMeNow網站系統</li>
            <li class="active"><?php echo $tt; ?>ABOUT</li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong><?php echo $tt; ?>ABOUT</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">

                <form id="mform" name="mform" method="post" action="?st=save" class="form-inline">
                    <div class="content">
                        <p>Title: <input name="title" id="title" style="width:80%;" value="<?php echo $title; ?>" class="form-control" required></p>
                        <p>description: <input name="description" id="description" style="width:80%;" value="<?php echo $description; ?>" class="form-control"></p>
                        <p>keywords: <input name="keywords" id="keywords" style="width:80%;" value="<?php echo $keywords; ?>" class="form-control"></p>
                        <textarea name="notes" class="editor"><?php echo $notes; ?></textarea>
                        <input type="hidden" name="t" value="<?php SqlFilter($_REQUEST["t"],"tab"); ?>">
                        <input type="hidden" name="fname" value="<?php echo $fname; ?>">
                        <p><input type="submit" value="送出" class="btn btn-info" style="width:50%"></p>
                    </div>
                </form>
            </div>
            <!--/span-->
        </div>
        <!--/row-->

    </div>
    <!--/.fluid-container-->
</section>
<!-- /MIDDLE -->

<?php
require_once("./include/_bottom.php")
?>

<script src="js/tinymce/tinymce.min.js"></script>

<script language="JavaScript">
    $mtu = "dmnweb_fun1.";
    tinymce.init({
        selector: ".editor",
        plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons template textcolor paste fullpage textcolor"
        ],

        toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | fontsizeselect forecolor backcolor | code preview",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | undo redo | link unlink image media | inserttime | table",

        menubar: false,
        language: 'zh_TW',
        height: 600
    });
</script>