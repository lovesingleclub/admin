<?php 
    
?>
<!-- header -->
<?php require_once("_topmenu.php");?>
<!-- aside -->
<aside id="aside" class="no-print">
    <nav id="sideNav">
        <ul class="nav nav-list">
            <h3> --- 約會專家系統 ---</h3>
            <li><a href="singleweb_index.php"><i class="main-icon fa fa-list-alt"></i><span> 網站主機資訊</span></a></li>												
            <li><a href="singleweb_fun1.php"><i class="main-icon fa fa-list-alt"></i><span> 頁面TDK</span></a></li>
            
            <li><a href="singleweb_fun9.php"><i class="main-icon fa fa-list-alt"></i><span> 首頁-BANNER 電腦版</span></a></li>	
            <li><a href="singleweb_fun10.php"><i class="main-icon fa fa-list-alt"></i><span> 首頁-BANNER 手機版</span></a></li>	
            
            <li><a href="singleweb_fun4.php"><i class="main-icon fa fa-list-alt"></i><span> 戀愛學院-文章分類</span></a></li>
            <li><a href="singleweb_fun5.php"><i class="main-icon fa fa-list-alt"></i><span> 戀愛學院-文章管理</span></a></li>
            <li><a href="singleweb_fun6.php"><i class="main-icon fa fa-list-alt"></i><span> 戀愛學院-豪華講師</span></a></li>	
            
            <li><a href="singleweb_fun7.php"><i class="main-icon fa fa-list-alt"></i><span> 主題約會-BANNER 電腦版</span></a></li>	
            <li><a href="singleweb_fun8.php"><i class="main-icon fa fa-list-alt"></i><span> 主題約會-BANNER 手機版</span></a></li>	
            
            <li><a href="singleweb_fun11.php"><i class="main-icon fa fa-list-alt"></i><span> 優惠卷-BANNER 電腦版</span></a></li>						
            <li><a href="singleweb_fun12.php"><i class="main-icon fa fa-list-alt"></i><span> 優惠卷-BANNER 手機版</span></a></li>						
            <li><a href="singleweb_fun13.php"><i class="main-icon fa fa-list-alt"></i><span> 優惠卷管理</span></a></li>
            
            <li><a href="singleweb_fun15.php"><i class="main-icon fa fa-list-alt"></i><span> 禮物管理</span></a></li>
            
            <li><a href="singleweb_fun14.php"><i class="main-icon fa fa-list-alt"></i><span> 企業專區</span></a></li>
            
            <li><a href="singleweb_fun16.php"><i class="main-icon fa fa-list-alt"></i><span> 主題活動</span></a></li>
            <li><a href="singleweb_fun22.php"><i class="main-icon fa fa-list-alt"></i><span> GT活動</span></a></li>
            <li><a href="singleweb_fun17.php"><i class="main-icon fa fa-list-alt"></i><span> 首頁推薦會員</span></a></li>
            <li><a href="singleweb_fun23.php"><i class="main-icon fa fa-list-alt"></i><span> 交友大廳會員</span></a></li>
            <li><a href="singleweb_fun20.php"><i class="main-icon fa fa-list-alt"></i><span> EDM推薦會員</span></a></li>
            <li><a href="singleweb_fun21.php"><i class="main-icon fa fa-search"></i><span> 配對信產生器</span></a></li>
            <?php 
                if($_SESSION["MM_Username"] == "KYOE" || $_SESSION["MM_Username"] == "SHEERY03130513" || $_SESSION["MM_Username"] == "LI6954029"){ ?>
                    <li><a href="singleweb_fun18.php"><i class="main-icon fa fa-list-alt"></i><span> APP推播</span></a></li>
               <?php }
            ?>           
            <li><a href="singleweb_fun2.php"><i class="main-icon fa fa-list-alt"></i><span> 操作說明</span></a></li>						
            <li><a href="singleweb_fun19.php"><i class="main-icon fa fa-home"></i><span> 部落格</span></a></li>
            <!--<li><a href="singleweb_fun3.php"><i class="main-icon fa fa-list-alt"></i><span> 聯繫客服</span></a></li> -->
            <li><a href="index.php"><i class="main-icon fa fa-share-alt"></i><span> 回客戶管理系統</span></a></li>
        </ul>
    </nav>
</aside>