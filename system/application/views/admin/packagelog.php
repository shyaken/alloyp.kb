<?php
    $CI =& get_instance();
    $CI->load->model('user_model');
?>
<link rel="stylesheet" href="<?=base_url()?>style/datepicker/themes/base/jquery.ui.all.css" type="text/css" media="screen" charset="utf-8" />
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.datepicker.js"></script>
<script src="<?=base_url()?>/js/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>
<link href="<?=base_url()?>/js/colorbox/css/colorbox.css" media="screen" rel="stylesheet" type="text/css" />

<div id="content" class="container_16 clearfix">
<style>
    .even{border:1px silver solid;color:blue;}
    .odd{color:green;}
    tr{border:1px solid silver;}
</style>
<style>
	#price_helper{width:400px;height:150px;float:none;margin:0 auto;padding:5px;color:red;}
</style>
	<form name="statistic_form" id="statistic_form" method="post">
	<div class="grid_16">
	<?php 
		$storess = array(
			'0' => 'các kho',
			'a' => 'kho Android',
			'b' => 'kho BlackBerry',
			'e' => 'kho Ebook',
			'f' => 'kho Film',
			'i' => 'kho iOS'
		);
	?>
	<h2><?=$info?> trên <?=$storess[$store]?></h2>
	
	<h4>Tổng số người dùng đăng kí gói là: <font color="red"><?=$totalPackageUser?></font></h4>
	<h4>Tổng số lần đăng kí sử dụng gói là: <font color="red"><?=$totalLogs?></font></h4>
	<h4>Tổng số tym sử dụng để đăng kí gói là: <font color="red"><?=number_format($totalPackageTym, 0, ',', '.');?></font></h4>
	</div>
	<div class="grid_2">
		Store
        <select name="store">
            <?php foreach($stores as $key => $value):?>
            <option value="<?=$value?>" <?php if($store == $value) echo 'selected="selected"'; ?>>
            	<?php if($value){echo $value;}else{echo 'All';}?>
            </option>
            <?php endforeach;?>
        </select>
	</div>    
    <div class="grid_3">
		user_id
		<input type="text" name="user_id" />
	</div>    
	<div class="grid_3">
		Loại gói
        <select name="package_type">
            <option value="">Tất cả</option>
            <option value="p7">7 ngày</option>
            <option value="p15">15 ngày</option>
            <option value="p30">30 ngày</option>
        </select>
	</div>
    <div class="grid_3">
		Ngày bắt đầu đăng kí
		<input type="text" id="start_datepicker" />
	</div>
	<div class="grid_3">
		Ngày kết thúc đăng kí
		<input type="text" id="end_datepicker" />
	</div>
	<div class="grid_2">
		Bắt đầu lọc
		<input type="submit" value="Lọc dữ liệu" onclick="startFilter(); return false;" style="font-size: 14px; font-weight: bold;" />
	</div>
	
    <div class="grid_16" id="list_download">
		<label>
			<h2>Danh sách chi tiết đăng kí gói</h2>
		</label>
		<table width="100%">
			<thead>
			<tr class="even">
				<td>user_id</td>
				<td>Loại gói</td>
				<td>Ngày đăng kí</td>
                <td>Hạn cũ</td>
                <td>Hạn mới</td>
				<td>Giá của gói</td>
                <td>Thuộc kho</td>
			</tr>
			</thead>
			<tbody>
			<?php if($packages):$count = 0; foreach($packages as $package):?>
			<tr <?php if($count%2==0){ echo "class='odd'";} else {echo "class='even'";} ?>>
				<td><?php 
						$user = $CI->user_model->getUserById($package->user_id);
						if($user) echo $user->username;
						else echo '(-1) none';
					?>
				</td>
				<td><?=$package->package_type?></td>
				<td><?=date('d/m/Y H:i:s', $package->registered_date)?></td>
                <td><?=date('d/m/Y H:i:s', $package->expired_date)?></td>
				<td><?=date('d/m/Y H:i:s', $package->last_expired_date)?></td>
                <td><?=$package->tym_price?></td>
                <td><?=$package->store?></td>
			</tr>
			</tbody>
			<?php $count++; endforeach;endif;?>
			<tr>
				<td colspan="4" align="left">
					<?php echo $this->pagination->create_links();?>
				</td>
			</tr>
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

    var store = $('select[name=store]').val();
		store = (store == '')?0:store;
	var user_id = $('input[name=user_id]').val();
		user_id = (user_id == '')?0:user_id;
	var package_type = $('select[name=package_type]').val();
		package_type = (package_type == '')?0:package_type;        
	var start = starttime.replace(/\//g, "_");
	var end = endtime.replace(/\//g, "_");
	window.location.href = "<?php echo site_url("admin/packagelog/viewAll/$sort/$order")?>/" + store + "/" + user_id + "/" + package_type + "/" + start + "/" + end;
}
</script>
