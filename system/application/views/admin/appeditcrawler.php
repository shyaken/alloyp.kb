	<div class="grid_5">
		<p class="nameclass">
			<label for="title">Tên ứng dụng</label>
			<input type="text" name="app_name" value="<?php if($crawler) echo $app->name?>" />
		</p>
	</div>
	<div class="grid_5">
		<p class="vendorclass">
			<label for="title">Nhà cung cấp</label>
			<input type="text" name="vendor" value="<?php if($crawler) echo $app->vendor?>" />
		</p>
	</div>
	<div class="grid_6">
		<p class="vendor_siteclass">
			<label for="title">Trang chủ của nhà cung câp</label>
			<input type="text" name="vendor_site" value="<?php if($crawler) echo $app->vendor_site?>" />
		</p>
			
	</div>
	
	<div class="grid_5">
		<p class="categoryclass">
			<label for="category">Category</label>
			<select name="category" id="listcategory">
				<option value="none" <?php if(!$trungcat){echo 'selected="selected"';}?>>None</option>
				<?php foreach($categories as $category):?>
				<option value="<?=$category->category_id?>" <?php if($category->category_id == $trungcat){echo 'selected="selected"';}?>><?=$category->category_name?></option>
				<?php endforeach;?>
			</select>
		</p>
	</div>
	<div class="grid_5">
		<p>
			<label>Released date</label>
			<input type="text" name="released_date" value="<?php if($crawler) echo $app->released?>" />
		</p>
	</div>
	<div class="grid_6">
		<p>
			<label>Version</label>
			<input type="text" name="version" value="<?php if($crawler) echo $app->version?>" />
		</p>
	</div>
		
	<div class="grid_5">
		<p>
			<label>Size</label>
			<input type="text" name="size" value="<?php if($crawler) echo $app->size?>" />
		</p>
	</div>
	<div class="grid_5">
		<p>
			<label>Language</label>
			<input type="text" name="released_date" value="<?php if($crawler) echo $app->language?>" />
		</p>
	</div>
	<div class="grid_6">
		<p>
			<label>Requirements</label>
			<input type="text" name="requirement" value="<?php if($crawler) echo $app->requirement?>" />
		</p>
	</div>
	
	<div class="grid_5">
		<p>
			<label>Price</label>
			<input type="text" name="price" value="<?php if($crawler) echo $app->price?>" />
		</p>
	</div>
	<div class="grid_5">
		<p>
			<label>Publish</label>
			<select name="publish">
				<option value="1">Bật</option>
				<option value="0">Tắt</option>
			</select>
		</p>
	</div>
	<div class="grid_6">
		<label>Ảnh đại diện</label>
			<?php if($crawler): ?>
			<img src="<?=base_url() . $app->thumbnail?>" width="80px" height="80px" />
			<input type="hidden" name="thumbnail_crawler" value="<?=$app->thumbnail?>" />
			<input type="file" name="thumbnail" size="25" />
			<?php endif; ?>
	</div>	

	<div class="grid_16">
		<p class="descriptionclass">
			<label>Mô tả ứng dụng</label>
			<textarea name="description" rows="6"><?php if($crawler) echo $app->description?></textarea>
			<?php echo $editor->replace('description');?>
		</p>
	</div>