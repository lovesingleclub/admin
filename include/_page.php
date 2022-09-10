<?php if( (${"total_size".($c+1)} > 0) || $total_size > 0 ) { ?>
	<div class="text-center">共 <?php echo $total_size;?> 筆、第 <?php echo $tPage;?> 頁／共 <?php echo $tPageTotal;?> 頁&nbsp;&nbsp;
		<ul class="pagination pagination-md">
			<li><a href="javascript:GotoPage(1,document.frmpage);">第一頁</a></li>
			<?php
			$AreaNo = (int)(($tPage-1)/8);
			$sPage = 1 + ( 8 * $AreaNo );
			$ePage = 8 + ( 8 * $AreaNo);
			for( $Li=$sPage;$Li<=$ePage;$Li++){
				if( $Li > $tPageTotal ) break;
					if ($tPage == $Li){
						echo "<li class='active'><a href=\"javascript:void(0);\">".$Li."</a>";
					}else{
						echo "<li><a href=\"javascript:GotoPage(".$Li.",document.frmpage);\">".$Li."</a>";
				}
			}?>
			<?php if ( $tPage < $tPageTotal ){?>
				<li><a href="javascript:GotoPage(<?php echo $tPage+1;?>,document.frmpage);" class="text" title="Next">下一頁</a></li>
				
			<?php }?>
			
			<?php if ( $tPage != $tPageTotal ){?>
				<li><a href="javascript:GotoPage(<?php echo $tPageTotal;?>,document.frmpage);" class='text'>最後一頁</a></li>
			<?php }?>
			<li>
				<select style="width:60px;height:34px;margin-left:5px;" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
					<?php for ( $p=1;$p<=$tPageTotal;$p++ ){?>
						<option value="javascript:GotoPage(<?php echo $p;?>,document.frmpage);"<?php if ( $tPage == $p ){?> selected<?php }?>><?php echo $p;?></option>
					<?php }?>
				</select>
			</li>
		</ul>
	</div>
<?php }?>
<form name="frmpage" id="frmpage" method="post">
	<input type="hidden" name="tPage" id="tPage" value="<?php echo $tPage;?>">
	<input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword;?>">
	<input type="hidden" name="keyword_type" id="keyword_type" value="<?php echo $keyword_type;?>">
	<input type="hidden" name="branch" id="branch" value="<?php echo $branch;?>">
	<input type="hidden" name="single" id="single" value="<?php echo $single;?>">
	<input type="hidden" name="timeend" id="timeend" value="<?php echo $timeend;?>">
	<input type="hidden" name="daysel" id="daysel" value="<?php echo $daysel;?>">
	<input type="hidden" name="oby" id="oby" value="<?php echo $oby;?>">
	<input type="hidden" name="tr" id="tr" value="<?php echo $tr;?>">
	<input type="hidden" name="a1" id="a1" value="<?php echo $a1;?>">
	<input type="hidden" name="b1" id="b1" value="<?php echo $b1;?>">
	<input type="hidden" name="a1" id="a1" value="<?php echo $a1;?>">
	<input type="hidden" name="s8" id="s8" value="<?php echo $s8;?>">
	<input type="hidden" name="s9" id="s9" value="<?php echo $s9;?>">
	<input type="hidden" name="s3" id="s3" value="<?php echo $s3;?>">
	<input type="hidden" name="s2" id="s2" value="<?php echo $s2;?>">
	<input type="hidden" name="s95" id="s95" value="<?php echo $s95;?>">
	<input type="hidden" name="vst" id="vst" value="<?php echo $vst;?>">
	<input type="hidden" name="stt" id="stt" value="<?php echo $stt;?>">
</form>