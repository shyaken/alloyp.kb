<script src="<?=base_url()?>/js/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>
<link href="<?=base_url()?>/js/colorbox/css/colorbox.css" media="screen" rel="stylesheet" type="text/css" />
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
<?php 
	if(!isset($this->uri->segments[5])) $this->uri->segments[5] = 'ASC';
	else {
		if($this->uri->segments[5] == 'DESC') $this->uri->segments[5] = 'ASC';
		else $this->uri->segments[5] = 'DESC';
	}
?>

<div id="content" class="container_16 clearfix">

<?php
	if(isset($success)) {
?>
<p class="success" id="success-alert"><?=$success?></p>
<?php }?>

<div class="grid_1">
	<p>
		<label>id</label>
		<input type="text" name="app_id_filter" style="width:50px !important;" value="<?php if($app_id != '0') echo $app_id; ?>" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label>app_name</label>
		<input type="text" name="app_name_filter" value="<?php if($app_name != "0") echo $app_name;?>" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label>Priority_price</label>
		<select name="priority_price_filter">
			<option value="-1" <?php if($priority_price == '-1') echo 'selected="selected"';?>>Tất cả</option>
			<option value="1" <?php if($priority_price == '1') echo 'selected="selected"';?>>Có</option>
			<option value="0" <?php if($priority_price == '0') echo 'selected="selected"';?>>Không</option>
		</select>
	</p>
</div>
<div class="grid_3">
	<p>
		<label>Price {<a href="#" id="price_help">Help</a>}</label>
		<input type="text" name="price_filter" value="<?php if($price != "0") echo $price;?>" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label>tym_type</label>
		<select name="tym_type_filter">
			<option value="0">All</option>
			<option value="t1" <?php if($tym == 't1') echo 'selected="selected"';?>>Tym 1 (đỏ)</option>
			<option value="t2" <?php if($tym == 't2') echo 'selected="selected"';?>>Tym 2 (tím)</option>
			<option value="t3" <?php if($tym == 't3') echo 'selected="selected"';?>>Tym 3 (xanh lá)</option>
			<option value="t4" <?php if($tym == 't4') echo 'selected="selected"';?>>Tym 4 (vàng)</option>
		</select>
	</p>
</div>
<div class="grid_2">
	<p>
		<label>Method</label>
		<select name="method_filter">
			<option value="0" <?php if($method == '0') echo 'selected="selected"';?>>None</option>
			<option value="hit" <?php if($method == 'hit') echo 'selected="selected"';?>>Hit</option>
			<option value="all" <?php if($method == 'all') echo 'selected="selected"';?>>All</option>
		</select>
	</p>
</div>
<div class="grid_2">
	<p>
		<label>Package</label>
		<select name="package_filter">
			<option value="-1" <?php if($package == '-1') echo 'selected="selected"';?>>Tất cả</option>
			<option value="1" <?php if($package == '1') echo 'selected="selected"';?>>Có</option>
			<option value="0" <?php if($package == '0') echo 'selected="selected"';?>>Không</option>
		</select>
	</p>
</div>
<div class="grid_2">
	<p>
		<label>App/Page</label>
		<select name="limit_filter">
			<option value="10" <?php if($limit==10) echo 'selected="selected"';?>>10</option>
			<option value="25" <?php if($limit==25) echo 'selected="selected"';?>>25</option>
			<option value="50" <?php if($limit==50) echo 'selected="selected"';?>>50</option>
			<option value="100" <?php if($limit==100) echo 'selected="selected"';?>>100</option>
		</select>
	</p>
</div>
<div class="grid_2">
	<p>
		<input type="button" value="Lọc" style="width:150px;" onclick="filter();" />
	</p>
</div>

<br /><br />

<div class="grid_16">
	<h2>Danh sách ứng dụng và thiết lập giá</h2>
</div>

<table style="margin-top:-20px;">
<form action="<?php echo site_url($this->uri->uri_string)?>" method="post" id="list_app_form">
<thead style="font-weight:bold;">
	<th width="6px">
		<input style="width: 20px;" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
	</th>
	<td width="21%">Ứng dụng</td>
	<td width="13%">
		<?php 
			$this->uri->segments[4] = 'category';
			$link = site_url(implode('/', $this->uri->segments));
		?>
		<a href="<?=$link?>">Chuyên mục</a>
	</td>
	<td width="10%">
		<?php 
			$this->uri->segments[4] = 'tym_price';
			$link = site_url(implode('/', $this->uri->segments));
		?>
		<a href="<?=$link?>">Giá</a>
	</td>
	<td width="10%">Giá ưu tiên</td>
	<td width="10%">Loại Tym</td>
	<td width="12%">Phương thức</td>
	<td width="10%">
		<?php 
			$this->uri->segments[4] = 'package';
			$link = site_url(implode('/', $this->uri->segments));
		?>
		<a href="<?=$link?>">Theo gói</a>
	</td>
	<td width="25%">Hành động</td>
</thead>
<?php 
	$CI =& get_instance();
	$CI->load->model('category_model');
	foreach($apps as $app) { ?>
<tr>
	<td><input style="width:20px;" type="checkbox" name="selected[]" value="<?=$app->app_id;?>" />
	<td><a href="<?php echo site_url('admin/managerapp/detail/' . $app->app_id)?>" target="_blank"><?=substr($app->app_name, 0, 40) . ' ... '?></a></td>
	<td><?php
			$category = $CI->category_model->getInfo($app->category);
			if($category) echo substr($category->category_name, 0, 20) . ' ... ';
			else echo "none";
			?></td>
	<td id="tym_price<?=$app->app_id?>"><?php if($app->tym_price){echo $app->tym_price;}else{echo "Chưa có";}?></td>
	<td id="priority_price<?=$app->app_id?>"><?php 
			if($app->priority_price) echo "Có";
			else echo "Không";
			?></td>
	<td id="tym_type<?=$app->app_id?>"><?php if($app->tym_type){echo $app->tym_type;}else{echo "Chưa có";}?></td>
	<td id="method<?=$app->app_id?>"><?=$app->method?></td>
	<td id="package<?=$app->app_id?>"><?php 
			if($app->package) echo "Có";
			else echo "Không";
			?></td>
	<td>
		<input type="button" value="Edit" name="Edit" id="Edit<?=$app->app_id?>" onclick="var cf=window.confirm('Bạn có chắc chắn muốn chỉnh sửa?');if(cf){editAppForm('<?=$app->app_id?>');}else{return false;}" />
		<input type="button" value="Save" name="Save" id="Save<?=$app->app_id?>" onclick="editApp('<?=$app->app_id?>')" style="display:none;border:1px solid green;" />
		</td>		
</tr>
<?php } ?>
<tr style="border:1px solid silver;">
<td colspan="2">
	<a href="javascript:showSaveButton();" style="color:green;">Set giá cho app(s) đã lựa chọn</a> 
</td>
<td colspan="6">
<span id="save-list-button" style="display:none">
		<input type="text" name="list_tym_price" style="text-align:center;width:80px;" value="price" onfocus="if(this.value=='price') this.value='';" />
		<select name="list_tym_type">
			<option value="t1">Loại tym ( mặc định đỏ )</option>
			<option value="t1">t1 - Tym đỏ</option>
			<option value="t2">t2 - Tym vàng</option>
			<option value="t3">t3 - Tym Tím</option>
			<option value="t4">t4 - Tym Xanh</option>
		</select>
		<select name="list_method">
			<option value="hit">Phương thức</option>
			<option value="hit">Hit</option>
			<option value="all">All</option>
		</select>
		<select name="list_package">
			<option value="1">Theo gói</option>
			<option value="1">Có</option>
			<option value="0">Không</option>
		</select>
		<input type="submit" value="Lưu lại" name="savePrice" onclick="return saveListPrice();" />
</span>
</td>
</tr>
</form>
</table>
<?php echo $this->pagination->create_links();?>

<style>
td a {text-decoration:none;}
.price-input {width:50px;border:1px solid green;}
.tym-input {width:70px;border:1px solid green;}
</style>
<script type="text/javascript">
var lastEditId = 0;
var lastPrice = "";
var lastPriority = "";
var lastTym = "";
var lastMethod = "";
var lastPackage = "";

function editAppForm(app_id) {
	restore();
	var currentPrice = $('#tym_price' + app_id).html();
		lastPrice = currentPrice;
	var currentPriority = $('#priority_price' + app_id).html();
		lastPriority = currentPriority;	
		currentPriority = (currentPriority=='Không')?'0':'1';
	var currentTym = $('#tym_type' + app_id).html();
		lastTym = currentTym;
	var currentMethod = $('#method' + app_id).html();
		lastMethod = currentMethod;
	var currentPackage = $('#package' + app_id).html();
		lastPackage = currentPackage;	 
		currentPackage = (currentPackage=='Không')?'0':'1';

	//price
	var price = (currentPrice=='Chưa có')?'':currentPrice;
	var priceTxt = '<input type="text" id="tym_price' + app_id + 'input" value="' + price + '" class="price-input" />';
	$('#tym_price' + app_id).html(priceTxt);

	//priority_price
	var priorityTxt = '<select name="priority_price' + app_id + '" class="tym-input" id="priority_price_' + app_id + '">';
		priorityTxt+= '<option value="1">Có</option>';
		priorityTxt+= '<option value="0">Không</option>';
		priorityTxt+= '</select>';
	$('#priority_price' + app_id).html(priorityTxt);

	var cityselectxxx = document.getElementById('priority_price_' + app_id);
	for(var i=0; i < cityselectxxx.options.length; i++){
		if(cityselectxxx.options[i].value == currentPriority) cityselectxxx.options[i].selected='selected';
	}	

	//tym_type
	var tym = (currentTym=='Chưa có')?'':currentTym;
	var tymTxt = '<select name="tym_type' + app_id + '" class="tym-input" id="tym_type_' + app_id + '">';
		tymTxt+= '<option value="t1">Tym 1 (đỏ)</option>';
		tymTxt+= '<option value="t2">Tym 2 (tím)</option>';
		tymTxt+= '<option value="t3">Tym 3 (xanh lá)</option>';
		tymTxt+= '<option value="t4">Tym 4 (vàng)</option>'; 
		tymTxt+= '</select>';
	$('#tym_type' + app_id).html(tymTxt);

	var cityselect = document.getElementById('tym_type_' + app_id);
	for(var i=0; i < cityselect.options.length; i++){
		if(cityselect.options[i].value == currentTym) cityselect.options[i].selected='selected';
	}	

	//method
	var methodTxt = '<select name="method' + app_id + '" class="tym-input" id="method_' + app_id + '">';
		methodTxt+= '<option value="all">All</option>';
		methodTxt+= '<option value="hit">Hit</option>';
		methodTxt+= '</select>';
	$('#method' + app_id).html(methodTxt);
	var cityselect1 = document.getElementById('method_' + app_id);
	for(var i=0; i < cityselect1.options.length; i++){
		if(cityselect1.options[i].value == currentMethod) cityselect1.options[i].selected='selected';
	}

	//package
	var packageTxt = '<select name="method' + app_id + '" class="tym-input" id="package_' + app_id + '">';
		packageTxt+= '<option value="1">Có</option>';
		packageTxt+= '<option value="0">Không</option>';
		packageTxt+= '</select>';
	$('#package' + app_id).html(packageTxt);
	var cityselect2 = document.getElementById('package_' + app_id);
	for(var i=0; i < cityselect2.options.length; i++){
		if(cityselect2.options[i].value == currentPackage) cityselect2.options[i].selected='selected';
	}

	//hide Edit - show Save
	$('#Edit' + app_id).hide('slow');
	$('#Save' + app_id).show('slow');

	//save lastEditId
	lastEditId = app_id;
}

function editApp(app_id) {
	var price = $('#tym_price' + app_id + 'input').val();
		lastPrice = price;
	var priority_price = $('#priority_price_' + app_id).val();
		lastPriority = priority_price;		
	var tym_type = $('#tym_type_' + app_id).val();
		lastTym = tym_type;
	var method = $('#method_' + app_id).val();
		lastMethod = method;
	var packagex = $('#package_' + app_id).val();
		lastPackage = (packagex==0)?'Không':'Có';

	$.ajax({
		url: "<?php echo site_url('admin/setting/editApp')?>",
		data: "app_id=" + app_id + "&tym_price=" + price + "&priority_price=" + priority_price + "&tym_type=" + tym_type + "&method=" + method + "&package=" + packagex,
		type: "POST",
		beforeSend: function() {

		},
		success: function(data) {
			if(data == 1) {
				alert('saved');
			} else {
				alert(data);
			}
		}
	});
}

//phục hồi lastEditId
function restore() {
	if(lastEditId == 0) return;

	$('#tym_price' + lastEditId).html(lastPrice);
	$('#tym_type' + lastEditId).html(lastTym);
	$('#priority_price' + lastEditId).html(lastPriority);
	$('#method' + lastEditId).html(lastMethod);
	$('#package' + lastEditId).html(lastPackage);
	$('#Save' + lastEditId).hide('slow');
	$('#Edit' + lastEditId).show('slow');
}

function filter() {
    var url = '<?=site_url('admin/setting/app')?>' + '/<?php echo "$sort/$order/";?>' ;
	
    var app_id = $('input[name=app_id_filter]').val();
    	app_id = (app_id=='')?0:app_id;
        url += encodeURIComponent(app_id);
    			
    var app_name = $('input[name=app_name_filter]').val();
    	app_name = (app_name=='')?0:app_name;
        url += '/' + encodeURIComponent(app_name);

    var priority_price = $('select[name=priority_price_filter]').val();
    	priority_price = (priority_price=='')?0:priority_price;
        url += '/' + encodeURIComponent(priority_price);        

    var price = $('input[name=price_filter]').val();
    	price = (price=='')?0:price;
        url += '/' + encodeURIComponent(price);        

    var tym_type = $('select[name=tym_type_filter]').val();
   		tym_type = (tym_type=='')?0:tym_type;
        url += '/' + encodeURIComponent(tym_type);
        
    var method = $('select[name=method_filter]').val();
    	method = (method=='')?0:method;
        url += '/' + encodeURIComponent(method);

    var packagex = $('select[name=package_filter]').val();
    	packagex = (packagex=='-1')?-1:packagex;
        url += '/' + encodeURIComponent(packagex);   

    var limit = $('select[name=limit_filter]').val();
    	limit = (limit=='')?10:limit;
        url += '/' + encodeURIComponent(limit);             

    window.location.href = url;
}    

function showSaveButton() {
	$('#save-list-button').show('slow');
}

function saveListPrice() {
	var confirm = window.confirm('Bạn có chắc chắn muốn lưu lại thiết lập giá cho app (s) không???');
		if(!confirm) return false;
	return true;	
}
 </script>

</div>