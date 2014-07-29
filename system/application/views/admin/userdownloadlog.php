<?php 
    $CI =& get_instance();
    $CI->load->model('user_model', 'user');
    $CI->load->model('app_model', 'app');
?>
<link rel="stylesheet" href="<?=base_url()?>style/datepicker/themes/base/jquery.ui.all.css" type="text/css" media="screen" charset="utf-8" />
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.datepicker.js"></script>

<div id="content" class="container_16 clearfix">
	<style>
		.even{border:1px silver solid;color:blue;}
		.odd{color:green;}
		tr{border:1px solid silver;}
         .td-center{text-align:center;}
	</style>
	<form name="statistic_form" id="statistic_form" method="post">
	<div class="grid_16"><h2><?=$info?></h2></div>
	
	<div class="grid_6" id="search_form">
		Ngày bắt đầu
		<h2><input type="text" id="start_datepicker" /></h2>
	</div>
	<div class="grid_6">
		Ngày kết thúc
		<h2><input type="text" id="end_datepicker" /></h2>
	</div>
	<div class="grid_4">
		Bắt đầu lọc
		<h2><input type="submit" value="Lọc dữ liệu" onclick="startFilter(); return false;" style="font-size: 14px; font-weight: bold;" /></h2>
	</div>
	
	<div class="grid_16" id="list_download">
		<label>
			<h2>Danh sách chi tiết download người dùng <b><font color='green'><?=$username?></font></b></h2>
		</label>
		<table width="100%">
			<thead>
			<tr class="even">
                <td class="td-center">log_id</td>
				<td class="td-center">app_id</td>
				<td class="td-center">user_id</td>
				<td class="td-center">tym_price</td>
				<td class="td-center">tym_type</td>
				<td class="td-center">Thời gian ( dd/mm/yyyy )</td>
			</tr>
			</thead>
			<tbody>
			<?php $count = 0; if($logs):foreach($logs as $log):?>
			<tr <?php if($count%2==0){ echo "class='odd'";} else {echo "class='even'";} ?>>
				<td class="td-center"><?=$log->id?></td>
                <td class="td-center">
                    <?php
                        $app = $CI->app->getInfo($log->app_id);
                    ?>
                    <a href="<?php echo site_url('admin/managerapp/detail/' . $app->app_id)?>" title="<?=$app->app_name?>"><?=$app->app_id?></a>
                </td>
				<td class="td-center"><?php
						$user = $CI->user->getUserById($log->user_id);
						if($user) echo '(' . $log->user_id . ') ' . $user->username;
						else echo '(-1) none';
					?>
				</td>
				<td class="td-center"><?=$log->tym_price?></td>
				<td class="td-center"><?=$log->tym_type?></td>
				<td class="td-center"><?=date('d-m-Y H:i:s', $log->time)?></td>
			</tr>
			</tbody>
			<?php $count++; endforeach;endif;?>
			<tr>
				<td colspan="5" align="left">
					<?php echo $this->pagination->create_links();?>
				</td>
			</tr>
            <?php if(!$logs): ?>
            <tr>
                <td colspan="6"><center>Hiện tại người dùng này chưa download cái gì cả!!!</center></td>
            </tr>
            <?php endif; ?>
		</table>
	</div>
	
</form>	
</div>
<script>
$(function() {
	$( "#start_datepicker" ).datepicker();
	$( "#end_datepicker" ).datepicker();
});

function startFilter() {
	var starttime = $("#start_datepicker").val();
	var endtime = $("#end_datepicker").val();
	if(starttime == "" || endtime == "") {
		starttime = "0";
		endtime = "0";
	}

	var start = starttime.replace(/\//g, "_");
	var end = endtime.replace(/\//g, "_");
	window.location.href = "<?php echo site_url("admin/user/downloadLog/$userid/$sort/$order")?>/" + start + "/" + end;
}
</script>