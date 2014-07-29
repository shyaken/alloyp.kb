	<style>
		h2{color:green;}
	</style>
	<h2>Chi tiết giao dịch id=<?=$transaction->id?></h2>
	<table style="border: 1px solid silver;">
		<tr class="odd">
			<td width="50%"><label>Username</label></td>
			<td style="text-align:right;"><label><?=$transaction->username?></label></td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>Tym đỏ nhận được</label></td>
			<td style="text-align:right;"><label><?=$transaction->t1?></label></td>
		</tr>
		<tr>
			<td width="50%"><label>Phương thức nạp</label></td>
			<td style="text-align:right;"><label><?=$transaction->method?></label></td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>
				<?php 
					if($transaction->method == 'sms') echo 'Số điện thoại nạp';
					else if($transaction->method == 'card') echo 'Mã thẻ ĐT';
                    else echo 'Paypal';
				?>
							</label></td>
			<td style="text-align:right;">
                <label>
				<?php
					echo $transaction->user_input . ' - ';
					if($transaction->method == 'sms') echo $transaction->sms_provider;
					else if($transaction->method == 'card') echo $transaction->card_type;
                    else echo 'Nạp tiền bằng paypal';
				?>
				</label></td>
		</tr>
		<tr>
			<td width="50%"><label>Trạng thái giao dịch</label></td>
			<td style="text-align:right;"><label><?=$transaction->status?></label></td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>Ghi chú</label></td>
			<td style="text-align:right;"><label><?=$transaction->comment?></label></td>
		</tr>
		<tr>
			<td width="50%"><label>Thời gian</label></td>
			<td style="text-align:right;"><label><?=date('d/m/Y H:i:s', $transaction->time)?></label></td>
		</tr>
	</table>