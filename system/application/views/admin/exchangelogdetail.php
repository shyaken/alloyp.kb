<div id="content" class="container_16 clearfix">
	
	<div class="grid_10">
		<h2>Chi tiết log quy đổi id = <?=$log->id?></h2>
	</div>
	<div class="grid_6" style="text-align: right;">
		<h2><a href="javascript:window.close();" class="error">close</a></h2>
	</div>	
	<div class="grid_16">
	<style>tr.odd{width: 100%; border: 1px solid red;}</style>
	<table style="width: 100%; border: 1px solid silver;">
		<tr class="odd">
			<td width="50%"><label>Mã giao dịch</label></td>
			<td style="text-align:right;"><label><?=$log->id?></label></td>
		</tr>
        <tr>
			<td width="50%"><label>Thời gian</label></td>
			<td style="text-align:right;"><label><?=date('d/m/Y H:i:s', $log->time)?></label></td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>User ID</label></td>
			<td style="text-align:right;"><label><?=$log->user_id?></label></td>
		</tr>
		<tr>
			<td width="50%"><label>Username</label></td>
            <td style="text-align:right;"><label><?=$log->username?></label></td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>T1 ban đầu</label></td>
			<td style="text-align:right;"><label><?=$log->t1_old?></label></td>
		</tr>
		<tr>
			<td width="50%"><label>T1 đã quy đổi</label></td>
			<td style="text-align:right;"><label><?=$log->t1_used?></label></td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>T1 còn lại</label></td>
			<td style="text-align:right;"><label><?=$log->t1_new?></label></td>
		</tr>
        <tr>
			<td width="50%"><label>Tỷ lệ quy đổi</label></td>
			<td style="text-align:right;"><label><?=$log->rate?></label></td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>Kiểu Tx nhận được</label></td>
			<td style="text-align:right;"><label><?=$log->tx_type?></label></td>
		</tr>
        <tr>
			<td width="50%"><label>Tx nhận được</label></td>
			<td style="text-align:right;"><label><?=$log->tx_receive?></label></td>
		</tr>
        <tr class="odd">
			<td width="50%"><label>Tx ban đầu</label></td>
			<td style="text-align:right;"><label><?=$log->tx_old?></label></td>
		</tr>
        <tr>
			<td width="50%"><label>Tx mới</label></td>
			<td style="text-align:right;"><label><?=$log->tx_new?></label></td>
		</tr>
	</table>
	</div>	
</div>