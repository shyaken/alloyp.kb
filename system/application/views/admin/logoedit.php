<div id="content" class="container_16 clearfix">
	<?php if(isset($error)):?>
		<p class="error"><?=$error?></p>
	<?php endif;?>	
	<?php if(isset($success)):?>
		<p class="success"><?=$success?></p>
	<?php endif;?>
	<div class="grid_16">
		<h2>chỉnh sửa logo</h2>
	</div>
	<form name="category_form" method="post" action="<?php echo site_url('admin/logo/edit/'.$logo->id);?>" enctype="multipart/form-data">
	<div style="float:left; width: 50%;"><label class="category_nameclass">Tên logo</label></div>	
	<div style="float:left; width: 50%;">
		<input type="text" name="name" value="<?=$logo->name?>" size="50" />
	</div>
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="float:left; width: 50%;"><label>Ảnh đại diện</label></div>	
	<div style="float:left; width: 50%;">
                <img src="<?=base_url().$logo->image?>" width="100px" height="80px" />
		<input type="file" name="image" size="15" />
	</div>	
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="float:left; width: 50%;"><label>Thiết lập mặc định</label></div>	
	<div style="float:left; width: 50%;">
            <select name="default">
                <option value="1" <?php if($logo->default){echo 'selected="selected"';}?>>Có</option>
                <option value="0" <?php if(!$logo->default){echo 'selected="selected"';}?>>Không</option>
            </select>
	</div>	
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	<div>
		<input type="reset" value="Nhập lại" />
		<input type="submit" value="Chỉnh sửa" name="update" />
	</div>
	</form>
</div>