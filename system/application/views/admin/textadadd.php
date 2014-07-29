<div id="content" class="container_16 clearfix">
	
	<?php
		if(isset($success)) {
	?>
	<p class="success"><?=$success?></p>
	<?php }?>
	
	<div class="grid_16">
		<h2>Thêm mới Textad</h2>
	</div>
	
	<form name="textad_form" method="post" action="<?=site_url('admin/textad/add')?>">
	
	<div class="grid_4">
		<label>Google Adsense</label>
	</div>
	<div class="grid_12">
	<p>
		<textarea name="googlead" rows="10" cols="60"></textarea>
	</p>
	</div>
	
	<div class="grid_4">
		<label>Header Text</label>
	</div>
	<div class="grid_12">
	<p>
		<textarea name="headertext" cols="60" rows="4"></textarea>
	</p>
	</div>
	
	<div class="grid_4">
		<label>Footer Text</label>
	</div>
	<div class="grid_12">
	<p>
		<textarea name="footertext" cols="60" rows="4"></textarea>
	</p>	
	</div>
	
	<div class="grid_4">
		<label>Action</label>
	</div>
	<div class="grid_12">
	<p>
		<input type="reset" value="Reset" /><input type="submit" name="insert" value="submit" />
	</p>	
	</div>
	
	</form>

</div>