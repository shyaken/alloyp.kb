	<div class="grid_5">
		<p class="nameclass">
			<label for="title">Tên ứng dụng</label>
			<input type="text" name="app_name" />
		</p>
	</div>
	<div class="grid_5">
		<p class="vendorclass">
			<label for="title">Nhà cung cấp</label>
			<input type="text" name="vendor" />
		</p>
	</div>
	<div class="grid_6">
		<p class="vendor_siteclass">
			<label for="title">Trang chủ của nhà cung câp</label>
			<input type="text" name="vendor_site" />
		</p>
			
	</div>
	
	<div class="grid_5">
		<p>
			<label for="category">Category</label>
			<select name="category">
				<?php foreach($categories as $category):?>
				<option value="<?=$category->category_id?>"><?=$category->category_name?></option>
				<?php endforeach;?>
			</select>
		</p>
	</div>
	<div class="grid_5">
		<p>
			<label>Released date</label>
			<input type="text" name="released_date" />
		</p>
	</div>
	<div class="grid_6">
		<p>
			<label>Version</label>
			<input type="text" name="version" />
		</p>
	</div>
		
	<div class="grid_5">
		<p>
			<label>Size</label>
			<input type="text" name="size" />
		</p>
	</div>
	<div class="grid_5">
		<p>
			<label>Language</label>
			<input type="text" name="language" />
		</p>
	</div>
	<div class="grid_6">
		<p>
			<label>Requirements</label>
			<input type="text" name="requirement" />
		</p>
	</div>
	
	<div class="grid_5">
		<p>
			<label>Price</label>
			<input type="text" name="price" />
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
		<p class="thumbnailclass">
			<label>Ảnh minh họa cho ứng dụng</label>
			<input type="file" name="thumbnail" size="25" />
		</p>
	</div>	

	<div class="grid_16">
		<p class="descriptionclass">
			<label>Mô tả ứng dụng</label>
			<textarea name="description" rows="6"></textarea>
			<?php echo $editor->replace('description');?>
		</p>
	</div>
	<div id="screenshots" class="grid_16">
	<table>
			<tr><td><h2>Screenshot</h2></td></tr>
			<tr>
			<td>
				<a href="javascript:;" onclick="addScreenshot();">Thêm</a>
				<a href="javascript:;" onclick="removeScreenshot();">Xóa</a>
			</td>
			</tr>
	</table>
