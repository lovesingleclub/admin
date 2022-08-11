<?php

/*****************************************/
//檔案名稱：singleweb_fun18.php
//後台對應位置：約會專家系統/APP推播
//改版日期：2022.8.11
//改版設計人員：Jack
//改版程式人員：Jack
/*****************************************/

require_once("_inc.php");
require_once("./include/_function.php");
require_once("./include/_top.php");
require_once("./include/_sidebar_single.php");
//POST function
function http_post_data($url, $data) {         
    //將陣列轉成json格式
    $data = json_encode($data);
    
    //1.初始化curl控制代碼
    $ch = curl_init(); 
    
    //2.設定curl
    //設定訪問url
    curl_setopt($ch, CURLOPT_URL, $url);  
    
    //捕獲內容，但不輸出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    //模擬傳送POST請求
    curl_setopt($ch, CURLOPT_POST, 1);  
    
    //傳送POST請求時傳遞的引數
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
    
    //設定頭資訊
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
        'Content-Type: application/json; charset=utf-8',
        'Authorization: key=AAAA6BtI1o4:APA91bG8xhy-DdsRDIKd1lfZh12spkAUgq643iFs_rvJ4jEPbAL6fFBg92t4YRua5ZNmsSUdfB6nQaSvlGfw8djio0TUqJ4B5Iv8upvsDbwTQLqyBZ4lt8_CXPSdsm-a3XrF3tYQbHL3',
        'project_id: 996890171022')
    );  

    //3.執行curl_exec($ch)
    $return_content = curl_exec($ch);  
    
    //如果獲取失敗返回錯誤資訊
    if($return_content === FALSE){ 
        $return_content = curl_errno($ch);
    }
    
    $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
    
    //4.關閉curl
    curl_close($ch);
    
    return array($return_code, $return_content);  
}

//程式開始 *****
if ($_SESSION["MM_Username"] == "") {
    call_alert("請重新登入。", "login.php", 0);
}

if($_SESSION["MM_UserAuthorization"] != "admin" && $_SESSION["singleweb"] != "1"){
    call_alert("您沒有查看此頁的權限。", "login.php", 0);
}

//打API(傳送json檔)至APP
if($_REQUEST["st"] == "send"){
    $title = SqlFilter($_REQUEST["title"],"tab");
    $body = SqlFilter($_REQUEST["body"],"tab");
    $link = SqlFilter($_REQUEST["link"],"tab");
    if($title == ""){
        call_alert("請輸入標題。", 0, 0);
    }
    if($body == ""){
        call_alert("請輸入內文。", 0, 0);
    }
    if($body != ""){
        $body = str_replace(PHP_EOL,"\n",$body);
    }

    $tnum = "";

    if($link != ""){
        if(strpos($link,"m=") > 0){
            $word1 = substr($link, strpos($link,"m=")+2);
            if(strpos($word1,"&") > 0){
                $word1 = substr($word1, strpos($word1,"&")-1);
            }
            $link = substr($link, strpos($link,"m=")-2);
            $tnum = $word1;
        }
    }
    //API網址
    $apiurl = "https://fcm.googleapis.com/fcm/send";
    //傳送的資料
    $data = ["to"=>"/topics/all","notification"=>["title"=>$title,"body"=>$body],"data"=>["title"=>$title,"body"=>$body,"link"=>$link,"tnum"=>$tnum]];
    //傳送API，如果獲取失敗返回錯誤資訊
    list($return_code, $return_content) = http_post_data($apiurl, $data);  

    $SQL = "INSERT INTO si_webdata (d1,d2,d3,alt,types,times) VALUES ('".$title."','".$body."','".$link."','".$return_content."','appmessage','".date("Y/m/d H:i:s")."')";
    $rs = $SPConn->prepare($SQL);
	$rs->execute();
    call_alert("推播訊息已送出。", "singleweb_fun18.php", 0);
}
?>
<!-- MIDDLE -->
<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <ol class="breadcrumb">
            <li><a href="index.php">管理系統</a></li>
            <li>約會專家系統</li>
            <li class="active"><a href="singleweb_fun18.php">APP推播</a></li>
        </ol>
    </header>
    <!-- /page title -->

    <div id="content" class="padding-20">
        <!-- content starts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>APP推播</strong> <!-- panel title -->
                </span>
            </div>

            <div class="panel-body">
                <form action="?st=send" method="post" id="form1" class="form-inline">
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <tbody>
                            <tr>
                                <td>
                                    標題：<input name="title" type="text" id="title" class="form-control" style="width:80%" required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    內文：<textarea name="body" type="text" id="body" class="form-control" style="width:80%" rows=5 required></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    連結：<input name="link" type="text" id="link" class="form-control" style="width:60%">&nbsp;&nbsp;<small>未填預設為開啟APP</small>
                                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><a href="#" data-toggle="modal" data-target="#linkhelp">連結對照表</a></small>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <div style="background:#e6e6e6;width:500px;color:#000;border-radius:10px;padding:15px;">
                                            <div style="color:#333333;font-weight:bold">LOGO - 約會專家 ‧ 1 分鐘</div>
                                            <div style="margin-top:5px;font-weight:bold;color:#000000" id="showtitle"></div>
                                            <div style="margin-top:5px;" id="showbody"></div>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <input id="submit3" type="submit" value="發送推播" class="btn btn-info" style="width:50%;">
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </form>
                <br>
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <tbody>
                        <tr>
                            <th width="160">發送時間</th>
                            <th>標題</th>
                            <th>內文</th>
                            <th>連結</th>
                            <th></th>
                        </tr>
                        <?php 
                            //取得總筆數
                            $SQL = "Select count(auton) As total_size FROM si_webdata where types='appmessage'";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result=$rs->fetchAll(PDO::FETCH_ASSOC);
                            foreach($result as $re);
                            if ( count($result) == 0 || $re["total_size"] == 0 ) {
                                $total_size = 0;
                            }else{
                                $total_size = $re["total_size"];
                            }

                            //取得分頁資料
                            $tPageSize = 50; //每頁幾筆
                            $tPage = 1; //目前頁數
                            if ( $_REQUEST["tPage"] > 1 ){ $tPage = $_REQUEST["tPage"];}
                            $tPageTotal = ceil(($total_size/$tPageSize)); //總頁數
                            if ( $tPageSize*$tPage < $total_size ){
                                $page2 = 50;
                            }else{
                                $page2 = (50-(($tPageSize*$tPage)-$total_size));
                            }

                            $SQL = "SELECT * FROM (SELECT TOP " .$page2. " * FROM (SELECT TOP " .($tPageSize*$tPage). " * FROM si_webdata WHERE types='appmessage' order by times desc ) t1 order by times) t2 order by times desc";
                            $SQL = "SELECT * FROM si_webdata where types='appmessage' order by times desc";
                            $rs = $SPConn->prepare($SQL);
                            $rs->execute();
                            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if($result){
                                foreach($result as $re){ 
                                        $body = $re["d2"];
                                        if($body != ""){
                                            RemoveHTML($body);
                                        }
                                        $link =$re["d3"];
                                        if($link == ""){
                                            $link = "預設頁";
                                        }
                                    ?>
                                    <tr>
                                        <td><?php echo Date_EN($re["times"],9) ?></td>
                                        <td><?php echo $re["d1"]; ?></td>
                                        <td><?php echo $body; ?></td>
                                        <td><?php echo $link; ?></td>
                                        <td><?php echo $re["alt"]; ?></td>
                                        </tr> 
                                    </tr>                                    
                                <?php }
                            }else{
                                echo "<tr><td colspan=5>目前無資料</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>

            </div>
            <!-- 頁碼 -->
            <?php require_once("./include/_page.php"); ?>
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

<!-- 連結對照表 -->
<div id="linkhelp" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">對照表</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <textarea style="width:100%;height:500px;" readonly>
invitation.asp 邀約中
inviteme.asp 邀請我
wait.asp 待約會
finish.asp 已完成
love.asp 互相喜歡
ilike.asp 我喜歡
likeyou.asp 喜歡我
who_seen.asp 誰來看我
to_seen.asp 我看過誰
who_gift.asp 誰來送禮
to_gift.asp 送禮給誰
message.asp 留言紀錄
member_list.asp 新進會員
member_supay.asp 速配名單
myprofile.asp 個人狀態
myphoto.asp 上傳照片
mydata.asp 會員資料修改
mypage.asp 個人頁面(自己)
profile.asp?m=1480801 個人頁面(別人)
notice.asp 服務通知
notice.asp?tab=2 約會行程
event.asp 主題約會
preferences.asp 偏好設定
member_rights.asp 會員權益與升級
message_send.asp?m=1301116 留言
                </textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $mtu = "singleweb_fun18.";

    $(function() {

        $("#title").keyup(function() {
            var $keyed = $(this).val();
            $("#showtitle").html($keyed);
        });
        $("#body").keyup(function() {
            var $keyed = $(this).val().replace(/\n/g, "<br>");
            $("#showbody").html($keyed);
        });

    });
</script>