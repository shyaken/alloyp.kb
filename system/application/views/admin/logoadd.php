<div id="content" class="container_16 clearfix">
	<?php if(isset($error)):?>
		<p class="error"><?=$error?></p>
	<?php endif;?>	
	<?php if(isset($success)):?>
		<p class="success"><?=$success?></p>
	<?php endif;?>
	<div class="grid_16">
		<h2>Thêm mới logo</h2>
	</div>
	<form name="category_form" method="post" action="<?php echo site_url('admin/logo/add');?>" enctype="multipart/form-data">
	<div style="float:left; width: 50%;"><label class="category_nameclass">Tên logo</label></div>	
	<div style="float:left; width: 50%;">
		<input type="text" name="name" size="50" />
	</div>
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="float:left; width: 50%;"><label>Ảnh đại diện</label></div>	
	<div style="float:left; width: 50%;">
		<input type="file" name="image" size="35" />
	</div>	
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="float:left; width: 50%;"><label>Thiết lập mặc định</label></div>	
	<div style="float:left; width: 50%;">
            <select name="default">
                <option value="1">Có</option>
                <option value="0">Không</option>
            </select>
	</div>	
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	<div>
		<input type="reset" value="Nhập lại" />
		<input type="submit" value="Thêm mới" name="insert" />
	</div>
	</form>
</div>