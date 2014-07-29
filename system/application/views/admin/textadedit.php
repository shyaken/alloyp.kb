<div id="content" class="container_16 clearfix">
	
	<?php
		if(isset($success)) {
	?>
	<p class="success"><?=$success?></p>
	<?php }?>
	
	<div class="grid_16">
		<h2>Thêm mới Textad</h2>
	</div>
	
	<form name="textad_form" method="post" action="<?=site_url('admin/textad/edit')?>">
	
	<?php foreach($texts as $text): ?>
		<input type="hidden" name="id[]" value="<?=$text->id?>" />
		<?php 
			if($text->type == "googlead") {
		?>
		<div class="grid_4">
			<label><?=$text->name?></label>
		</div>
		<div class="grid_12">
			<p>
				<textarea name="googlead" rows="10" cols="60"><?=$text->code?></textarea>
			</p>
		</div>	
		<?php		
			} else {
		?>
		<div class="grid_4">
			<label><?=$text->name?></label>
		</div>
		<div class="grid_12">
			<p>
			<textarea name="<?=$text->type?>" rows="4" col="60" /><?=$text->code?></textarea>
			</p>
		</div>
		<?php		
			}
		?>
	<?php endforeach;?>
	<div class="grid_12">
	<p>
		<input type="reset" value="Reset" /><input type="submit" name="update" value="update" />
	</p>	
	</div>
	
	</form>

</div>