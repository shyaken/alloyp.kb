<?php
    $CI =& get_instance();
    $CI->load->model('user_model', 'user');
    $CI->load->model('app_model', 'app');
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
<script>
$(document).ready(function(){
	$("#price_help").colorbox({width:"100%", top:"200px", inline:true, href:"#price_helper"});
	setTimeout('$("#success-alert").hide("slow");', 3000);
});
</script>
<div style="display:none;">
	<div id="price_helper">
		<ul style="font-size:14px;">
			<li>Dạng a-b</li>
			<li></b>a</b> sẽ tìm kiếm giá bằng a</li>
			<li></b>a-</b> sẽ tìm kiếm giá >= a</li>
			<li></b>-a</b> sẽ tìm kiếm giá <= a</li>
			<li></b>a-b</b> sẽ tìm kiếm a =< giá <= b </li>
		</ul>
	</div>
</div>
	<form name="statistic_form" id="statistic_form" method="post">
	<div class="grid_16"><h2><?=$info?></h2></div>

	<div class="grid_2">
		user_id
		<input type="text" name="user_id" />
	</div>    
	<div class="grid_2">
		app_id
		<input type="text" name="app_id" />
	</div>
    <div class="grid_2">
		Vendor
		<input type="text" name="vendor" />
	</div>
    <div class="grid_2">
        Loại tym
        <select name="tym_type">
            <option value="">Tất cả</option>
            <option value="t1">Tym đỏ</option>
            <option value="t2">Tym tím</option>
            <option value="t3">Tym xanh</option>
            <option value="t4">Tym vàng</option>
        </select>
    </div>
    <div class="grid_2">
        Price {<a href="#" id="price_help">Help</a>}</label>
        <input type="text" name="price_filter" value="<?php if($price != "0") echo $price;?>" />
    </div>   
    <div class="grid_2">
		Ngày bắt đầu
		<input type="text" id="start_datepicker" />
	</div>
	<div class="grid_2">
		Ngày kết thúc
		<input type="text" id="end_datepicker" />
	</div>
	<div class="grid_2">
		Bắt đầu lọc
		<input type="submit" value="Lọc dữ liệu" onclick="startFilter(); return false;" style="font-size: 14px; font-weight: bold;" />
	</div>
	
	<div class="grid_16" id="statistic">
		<label><h2>Thống kê chung</h2></label>
		<table>
		<tr class="odd">
			<td width="50%"><label>Tổng số lượt tải</label></td>
			<td style="text-align:right;"><label><?=$totalDownload?></label></td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>Tổng số tym đã sử dụng</label></td>
			<td style="text-align:right;"><label><?=$totalMoney?></label></td>
		</tr>
	</table>
	</div>

	<div class="grid_16" id="list_download">
		<label>
			<h2>Danh sách chi tiết download</h2>
		</label>
		<table width="100%">
			<thead>
			<tr class="even">
				<td>app_id</td>
				<td>user_id</td>
                <td>usernam</td>
				<td>tym_price</td>
				<td>tym_type</td>
				<td style="text-align:center;">Thời gian ( dd/mm/yyyy )</td>
			</tr>
			</thead>
			<tbody>
			<?php $count = 0; foreach($downloads as $down):?>
			<tr <?php if($count%2==0){ echo "class='odd'";} else {echo "class='even'";} ?>>
				<td>
                    <?php
                       $app = $CI->app->getInfo($down->app_id);
                       if($app) {
                    ?>
                    <a href="<?php echo site_url('admin/managerapp/detail/' . $app->app_id)?>" title="<?=$app->app_name?>"><?=$down->app_id?></a>
                    <?php } ?>
                </td>
                <td>
                    <?php
						$user = $CI->user->getUserById($down->user_id);
						if($user) echo $down->user_id;
						else echo '-1';
					?>
                </td>
				<td><?php
						$user = $CI->user->getUserById($down->user_id);
						if($user) echo '<a href="' . site_url('admin/user/downloadLog/' . $user->user_id) . '">' . $user->username . '</a>';
						else echo 'none';
					?>
				</td>
				<td><?=$down->tym_price?></td>
				<td><?=$down->tym_type?></td>
				<td style="text-align:center;"><?=date('d-m-Y H:i:s', $down->time)?></td>
			</tr>
			</tbody>
			<?php $count++; endforeach;?>
			<tr>
				<td colspan="5" align="left">
					<?php echo $this->pagination->create_links();?>
				</td>
			</tr>
		</table>
	</div>
	
	<div class="grid_16">
		<h2>
			Xuất dữ liệu thống kê download
			<input type="button" value="Excel" onclick="exportData('excel');" style="width: 100px; color:green;" />
			<!--  
			<input type="button" value="CSV" onclick="exportData('csv');" style="width: 100px; color:green;" />
			-->
		</h2>
	</div>
</form>	
</div>
<script>
function exportData(type) {
	var starttime = $("#start_datepicker").val();
	var endtime = $("#end_datepicker").val();
	if(starttime == "" || endtime == "") {
		starttime = "0";
		endtime = "0";
	}

	var app_id = $('input[name=app_id]').val();
	app_id = (app_id == '')?0:app_id;
var user_id = $('input[name=user_id]').val();
	user_id = (user_id == '')?0:user_id;
var start = starttime.replace(/\//g, "_");
var end = endtime.replace(/\//g, "_");
window.location.href = "<?php echo site_url('admin/statistic/export/')?>/" + app_id + "/" + user_id + "/" + start + "/" + end;	
}

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

	var app_id = $('input[name=app_id]').val();
		app_id = (app_id == '')?0:app_id;
	var user_id = $('input[name=user_id]').val();
		user_id = (user_id == '')?0:user_id;
	var vendor = $('input[name=vendor]').val();
		vendor = (vendor == '')?0:vendor;        
    var tym_type = $('select[name=tym_type]').val();
        tym_type = (tym_type == '')?0:tym_type;
    var price = $('input[name=price_filter]').val();
    	price = (price=='')?0:price;
	var start = starttime.replace(/\//g, "_");
	var end = endtime.replace(/\//g, "_");
	window.location.href = "<?php echo site_url("admin/statistic/viewAll/$sort/$order")?>/" + user_id + "/" + app_id + "/" + vendor + "/" + tym_type + "/" + price + "/" + start + "/" + end;
}
</script>