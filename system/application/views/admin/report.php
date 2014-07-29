<div id="content" class="container_16 clearfix">

<div class="grid_6">
	<p>
		<label>Lỗi bị báo cáo</label>
		<select name="code">
			<option value="-1">Tất cả</option>
		<?php $i = 1; foreach($reasons as $reason):?>
			<option value="<?=$i?>"><?=$reason?></option>
		<?php $i++; endforeach;?>
		</select>
	</p>
</div>
<div class="grid_2">
	<p>
		<label>user_id</label>
		<input type="text" name="user_id" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label>app_id</label>
		<input type="text" name="app_id" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label>Đã sửa</label>
		<select name="fixed">
			<option value="-1">Tất cả</option>
			<option value="1">Rồi</option>
			<option value="0">Chưa</option>
		</select>
	</p>
</div>
<div class="grid_2">
	<p>
		<label>Report/Page</label>
		<select name="limit">
			<option value="25" <?php if($limit==25) echo 'selected="selected"';?>>25</option>
			<option value="50" <?php if($limit==50) echo 'selected="selected"';?>>50</option>
			<option value="100" <?php if($limit==100) echo 'selected="selected"';?>>100</option>
		</select>
	</p>
</div>
<div class="grid_2">
	<p>
		<label>Lọc</label>
		<input type="button" value="Lọc" style="width:50px;" onclick="filter();" />
	</p>
</div>

<p></p>

<div class="grid_16">
	<h2>Danh sách report bởi người dùng</h2>
</div>

<table style="margin-top:-20px;">
<thead style="font-weight:bold;">
	<td width="10%">Mã lỗi</td>
	<td width="30%">Nội dung báo lỗi</td>
	<td width="10%">App_id</td>
	<td width="10%">User_id</td>
	<td width="10%">Đã sửa</td>
</thead>
<?php 
	$CI =& get_instance();
	$CI->load->model('user_model');
	//$CI->load->model('app_model');
	foreach($reports as $report) { ?>
<tr>
	<td><?=$report->code?></td>
	<td><?=$report->content?></td>
	<td><?php 
		$app = $CI->app->getInfo($report->app_id);
		if($app) {
		?>
		<a href="<?php echo site_url('admin/managerapp/edit/' . $report->app_id)?>" target="_blank" title="<?=$app->app_name?>"><?=$report->app_id?></a>
		<?php } else echo 'none';?>
		</td>		
	<td><?php 
		$user = $CI->user_model->getUserById($report->user_id);
		if($user) {
		?>
		<a href="javascript:void(0);" title="<?=$user->username?>"><?=$report->user_id?></a>
		<?php } else {echo 'none';}?>
		</td>
	<td id="report<?=$report->report_id?>"><a href="javascript:void(0);" onclick="updateFixed('<?=$report->report_id?>', '<?=$report->fixed?>');">
			<?php 
			if($report->fixed) echo "Rồi";
			else echo "Chưa";
			?>
		</a></td>
</tr>
<?php } ?>
</table>
<?php echo $this->pagination->create_links();?>

<style>
td a {text-decoration:none;}
.price-input {width:50px;border:1px solid green;}
.report-input {width:70px;border:1px solid green;}
</style>
<script type="text/javascript">
function updateFixed(report_id, status) {
	if(status == 1) status = 0;
	else status = 1;
	$.ajax({
		url: '<?php echo site_url('admin/report/updateFixed')?>/' + report_id + '/' + status,
		beforeSend: function() {
			$('#report' + report_id).html('<font color="green">Updating</font>');
		},
		success: function(data) {
			$('#report' + report_id).html(data);
		}
	});
}

function filter() {
    var url = '<?=site_url('admin/report/viewAll/')?>' + '/<?php echo "$sort/$order/";?>' ;
	
    var code = $('select[name=code]').val();
   		code = (code=='')?-1:code;
        url += encodeURIComponent(code);
    			
    var user_id = $('input[name=user_id]').val();
    	user_id = (user_id=='')?0:user_id;
        url += '/' + encodeURIComponent(user_id);

    var app_id = $('input[name=app_id]').val();
    	app_id = (app_id=='')?0:app_id;
        url += '/' + encodeURIComponent(app_id);

    var fixed = $('select[name=fixed]').val();
    	fixed = (fixed=='')?-1:fixed;
        url += '/' + encodeURIComponent(fixed);   

    var limit = $('select[name=limit]').val();
    	limit = (limit=='')?10:limit;
        url += '/' + encodeURIComponent(limit);             

    window.location.href = url;
}    
 </script>

</div>