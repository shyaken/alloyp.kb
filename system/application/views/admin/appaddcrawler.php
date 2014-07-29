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
				<option value="<?=$category->category_id?>" <?php if($trungcat == $category->category_id){echo "selected='selected'";}?>><?=$category->category_name?></option>
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
				<option value="0" selected="selected">Tắt</option>
			</select>
		</p>
	</div>
	<div class="grid_6">
		<p class="thumbnailclass">
			<label>Ảnh đại diện</label>
			<?php if($crawler): ?>
			<img src="<?=base_url() . $app->thumbnail?>" width="80px" height="80px" />
			<input type="hidden" name="thumbnail_crawler" value="<?=$app->thumbnail?>" />
			<input type="file" name="thumbnail" size="25" />
			<?php endif; ?>
		</p>
	</div>	
	<div class="grid_16" style="display:none;line-height:120%;color:green;" id="translated">
	<p>
		<label>
			Bản dịch mô tả 
			- <a href="javascript:;" onclick="$('#translated').hide();">Ẩn</a>
		</label>
		<?=$app->translated?>
	</p>	
	</div>
	<div class="grid_16">
		<p class="descriptionclass">
			<label>
				Mô tả ứng dụng 
				- <a href="javascript:;" onclick="$('#translated').show();">Dịch</a>
			</label>
			<textarea id="description_id" name="description" rows="6"><?php if($crawler) echo $app->description?></textarea>
			<?php echo $editor->replace('description');?>
		</p>
	</div>
	
	<div id="screenshots" class="grid_16">
	<h2>Screenshot</h2>
		<table>
		<?php if($crawler && $app->images): ?>
			<tr>
			<?php $i=0; foreach($app->images as $image):?>
				<td align="center" style="width:100px;">
				<input type="hidden" name="crawler_sc_link[]" value="<?=$image?>" />
				<input type="checkbox" name="crawler_sc<?=$i?>" checked="checked" style="width:20px;" /><b>Save</b><br />
				<img src="<?=base_url() . $image?>" width="100" height="150px" />
				</td>
			<?php $i++; endforeach;?>
			</tr>
		<?php endif;?>
		<tr>
			<td>
				<a href="javascript:;" onclick="addScreenshot();">Thêm</a>
				<a href="javascript:;" onclick="removeScreenshot();">Xóa</a>
			</td>
			</tr>
		</table>
		
