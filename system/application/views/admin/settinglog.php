<table style="margin-top:-10px;">
<thead style="font-weight:bold;">
	<td>log_id</td>
	<td>admin_id</td>
	<td>admin_name</td>
	<td style="width:40%;">content</td>
	<td>time</td>
</thead>
<?php foreach($logs as $log):?>
<tr>
	<td><?=$log->id?></td>
	<td><?=$log->admin_id?></td>
	<td><?=$log->admin?></td>
	<td><?=$log->content?></td>
	<td><?=date('d/m/Y H:i:s', $log->time)?></td>
</tr>
<?php endforeach;?>
<?php if(!$logs):?>
<tr>
    <td colspan="5" style="text-align:center;color:red;font-size:18px;">
        Hết dữ liệu rồi nhá!!!
    </td>
</tr>
<?php endif;?>
</table>
