<div id="content" class="container_16 clearfix">
	
	<div class="grid_10">
		<h2>Chi tiết ứng dụng</h2>
	</div>
	<div class="grid_6" style="text-align: right;">
		<h2><a href="javascript:window.history.go(-1);" class="error">back</a></h2>
	</div>	
	<div class="grid_16">
	<style>tr.odd{width: 100%; border: 1px solid silver;}</style>
	<table style="width: 100%; border: 1px solid silver;">
		<tr class="odd">
			<td width="50%"><label>Tên ứng dụng</label></td>
			<td style="text-align:right;"><label><?=$app->app_name?></label></td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>Nhà cung cấp</label></td>
			<td style="text-align:right;"><label><?=$app->vendor?></label></td>
		</tr>
		<tr>
			<td width="50%"><label>Trang chủ nhà cung cấp</label></td>
			<td style="text-align:right;"><label><a href="<?=$app->vendor_site?>" target="_new">Xem ngay</a></label></td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>Publish</label></td>
			<td style="text-align:right;"><label><?=$app->publish?></label></td>
		</tr>
		<tr>
			<td width="50%"><label>Lượt download</label></td>
			<td style="text-align:right;"><label><?=$app->download?></label></td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>Lượt bình chọn</label></td>
			<td style="text-align:right;"><label><?=$app->vote?></label></td>
		</tr>
		<tr>
			<td width="50%"><label>Lượt bình luận</label></td>
			<td style="text-align:right;"><label><?=$app->comment?></label></td>
		</tr>
	</table>
	</div>	
</div>