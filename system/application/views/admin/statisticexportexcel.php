<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=exceldata.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<table border='1' width="70%">
<tr>
	<td colspan="6"><?=$info?></td>
</tr>
<tr>
	<td colspan="5">Tổng số lượt tải ứng dụng</td>
	<td><?=$totalDownload?></td>
</tr>
<tr>
	<td colspan="5">Tổng số Ap mà người dùng đã sử dụng</td>
	<td><?=$totalAp?></td>
</tr>
<tr>
	<td colspan="5">Tổng số tiền thu về</td>
	<td><?=$totalMoney?> VNĐ</td>
</tr>
<tr>
	<td>ID</td>
	<td>App_id</td>
	<td>Username</td>
	<td>Ap used</td>
	<td>Rate</td>
	<td width="20%">Time</td>
</tr>
<?
foreach($downloads as $download) {
?>
<tr>
	<td><?=$download->id?></td>
	<td><?=$download->app_id?></td>
	<td>
	<?php
		$CI =& get_instance();
		$CI->load->model('user_model', 'user');
		$user = $CI->user->getUserById($download->user_id);
		echo $user->username;00
	?>
	</td>
	<td><?=$download->ap?></td>
	<td><?=$download->rate?></td>
	<td><?=date('d/m/Y H:i:s', $download->time)?></td>
</tr>
<? } ?>
</table>