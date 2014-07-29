<script src="<?=base_url()?>/js/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>
<link href="<?=base_url()?>/js/colorbox/css/colorbox.css" media="screen" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?=base_url()?>style/datepicker/themes/base/jquery.ui.all.css" type="text/css" media="screen" charset="utf-8" />
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.datepicker.js"></script>
<style>
	#price_helper{width:400px;height:150px;float:none;margin:0 auto;padding:5px;color:red;}
</style>
<script>
$(document).ready(function(){
	$("#price_help").colorbox({width:"100%", top:"200px", inline:true, href:"#price_helper"});
    $("#price_help1").colorbox({width:"100%", top:"200px", inline:true, href:"#price_helper"});
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
<div class="grid_3">
	<p>
		<label>app_name</label>
		<input type="text" name="app_name_filter" value="<?php if($app_name != "0") echo $app_name;?>" />
	</p>
</div>
<div class="grid_3">
	<p>
		<label>Category</label>
		<select name="category_filter">
            <option value="0">All category</option>
            <?php foreach($categories as $category):?>
            <option value="<?=$category->category_id?>"<?php if($category_filter == $category->category_id){echo 'selected="selected"';}?>><?=$category->category_name?></option>
            <?php endforeach;?>
		</select>
	</p>
</div>
<div class="grid_3">
	<p>
		<label>Price {<a href="#" id="price_help">Help</a>}</label>
		<input type="text" name="price_filter" value="<?php if($price != "0") echo $price;?>" />
	</p>
</div>
<div class="grid_3">
	<p>
		<label>Giá KM{<a href="#" id="price_help1">Help</a>}</label>
		<input type="text" name="promo_price_filter" value="<?php if($p_price != "0") echo $p_price;?>" />
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
		<label>Promo start</label>
		<input type="text" name="startdate" id="startdate" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label>Promo end</label>
		<input type="text" name="enddate" id="enddate" />
	</p>
</div>
<div class="grid_3">
    <p>
        <label>Đang khuyến mãi</label>
        <select name="enable_filter">
            <option value="-1">Tất cả</option>
            <option value="1" <?php if($enable == 1) {echo 'selected="selected"';} ?>>Có</option>
            <option value="0" <?php if($enable == 0) {echo 'selected="selected"';} ?>>Không</option>
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
        <label>Hành động</label>
		<input type="button" value="Lọc" style="width:150px;" onclick="filter();" />
	</p>
</div>

<br /><br />

<div class="grid_16">
	<h2>Danh sách ứng dụng và thiết lập giá khuyến mãi</h2>
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
		<a href="<?=$link?>">Price</a>
	</td>
    <td width="5%">Type</td>
	<td width="10%">Giá KM</td>
    <td width="10%">Bật</td>
	<td width="13%">Promo start</td>
	<td width="13%">Promo end</td>
	<td width="25%">Hành động</td>
</thead>
<?php 
	foreach($apps as $app) { ?>
<tr>
	<td><input style="width:20px;" type="checkbox" name="selected[]" value="<?=$app->app_id;?>" />
	<td><a href="<?php echo site_url('admin/managerapp/detail/' . $app->app_id)?>" target="_blank"><?=substr($app->app_name, 0, 40) . ' ... '?></a></td>
	<td><?php
			$category = $this->category_model->getInfo($app->category);
			if($category) echo substr($category->category_name, 0, 20) . ' ... ';
			else echo "none";
			?></td>
    <td id="tym_price<?=$app->app_id?>"><?=$app->tym_price?></td>
    <td id="tym_type<?=$app->app_id?>"><?php if($app->tym_type){echo $app->tym_type;}else{echo "Chưa có";}?></td>
	<td id="promo_price<?=$app->app_id?>"><?php if($app->promo_price){echo $app->promo_price;}else{echo "Chưa có";}?></td>
    <td id="promo_enable<?=$app->app_id?>">
        <?php
            if($app->promo_enable) {
                $txt = "<a href='javascript:promoEnable(".$app->app_id.", 0);'>Tắt đi</a>";
            } else {
                $txt = "<a href='javascript:promoEnable(".$app->app_id.", 1);'>Bật lên</a>";
            }
            echo $txt;
        ?>
    </td>
    <td id="promo_start<?=$app->app_id?>"><?=($app->promo_start)?date('Ymd',$app->promo_start):0?></td>
    <td id="promo_end<?=$app->app_id?>"><?=($app->promo_end)?date('Ymd',$app->promo_end):0?></td>
	<td>
        <input type="hidden" id="promo_enable_hidden<?=$app->app_id?>" value="<?=$app->promo_enable?>" />
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
		<input type="text" name="list_startdate" style="text-align:center;width:100px;" id="list_startdate" value="start date" onfocus="if(this.value=='start date') this.value='';" />
        <input type="text" name="list_enddate" style="text-align:center;width:100px;" id="list_enddate" value="end date" onfocus="if(this.value=='end date') this.value='';" />
        <select name="list_promo_enable">
            <option value="1">Bật lên</option>
            <option value="0">Tắt đi</option>
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
.price-input {width:80px;border:1px solid green;}
.tym-input {width:70px;border:1px solid green;}
</style>
<script type="text/javascript">
var lastEditId = 0;
var lastPromoPrice = "";
var lastPromoStart = "";
var lastPromoEnd = "";

function editAppForm(app_id) {
	restore();
	var currentPromoPrice = $('#promo_price' + app_id).html();
		lastPromoPrice = currentPromoPrice;
	var currentPromoStart = $('#promo_start' + app_id).html();
		lastPromoStart = currentPromoStart;	
	var currentPromoEnd = $('#promo_end' + app_id).html();
		lastPromoEnd = currentPromoEnd;	
    // promo price
    var price = (currentPromoPrice=='Chưa có')?'':currentPromoPrice;
	var priceTxt = '<input type="text" id="promo_price' + app_id + 'input" value="' + price + '" class="price-input" />';
	$('#promo_price' + app_id).html(priceTxt);
    // promo start
    var promo_start = (currentPromoStart=='0')?'':currentPromoStart;
    var startTxt = '<input type="text" id="promo_start' + app_id + 'input" value="' + promo_start + '" class="price-input" />';
	$('#promo_start' + app_id).html(startTxt);
    // promo end
    var promo_end = (currentPromoEnd=='0')?'':currentPromoEnd;
    var endTxt = '<input type="text" id="promo_end' + app_id + 'input" value="' + promo_end + '" class="price-input" />';
	$('#promo_end' + app_id).html(endTxt);
    $('#promo_start'+app_id+'input').datepicker({ dateFormat: 'yymmdd' });
    $('#promo_end'+app_id+'input').datepicker({ dateFormat: 'yymmdd' });
	//hide Edit - show Save
	$('#Edit' + app_id).hide('slow');
	$('#Save' + app_id).show('slow');

	//save lastEditId
	lastEditId = app_id;
}

function editApp(app_id) {
	var promo_price = $('#promo_price' + app_id + 'input').val();
		lastPromoPrice = promo_price;
	var promo_start = $('#promo_start' + app_id + 'input').val();
		lastPromoStart = promo_start;
	var promo_end = $('#promo_end' + app_id + 'input').val();
		lastPromoEnd = promo_end;
    var promo_enable = $('#promo_enable_hidden' + app_id).val();    
        
	$.ajax({
		url: "<?php echo site_url('admin/promotion/editApp')?>",
		data: "app_id=" + app_id + "&promo_price=" + promo_price + "&promo_start=" + promo_start + "&promo_end=" + promo_end + "&promo_enable=" + promo_enable,
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

function promoEnable(app_id, status) {
    $('#promo_enable_hidden' + app_id).val(status);
    $.ajax({
        url: "<?=site_url('admin/promotion/enablePromo')?>",
        data: "app_id=" + app_id + "&status=" + status,
        type: "POST",
        success: function(data) {
            $('#promo_enable' + app_id).html(data);
        }
    })
}

//phục hồi lastEditId
function restore() {
	if(lastEditId == 0) return;

	$('#promo_price' + lastEditId).html(lastPromoPrice);
	$('#promo_start' + lastEditId).html(lastPromoStart);
    $('#promo_end' + lastEditId).html(lastPromoEnd);
	$('#Save' + lastEditId).hide('slow');
	$('#Edit' + lastEditId).show('slow');
}

function filter() {
    var url = '<?=site_url('admin/promotion/app')?>' + '/<?php echo "$sort/$order/";?>' ;
	
    var app_id = $('input[name=app_id_filter]').val();
    	app_id = (app_id=='')?0:app_id;
        url += encodeURIComponent(app_id);
    			
    var app_name = $('input[name=app_name_filter]').val();
    	app_name = (app_name=='')?0:app_name;
        url += '/' + encodeURIComponent(app_name);

    var category_price = $('select[name=category_filter]').val();
        url += '/' + encodeURIComponent(category_price);        

    var price = $('input[name=price_filter]').val();
    	price = (price=='')?0:price;
        url += '/' + encodeURIComponent(price);        
    
    var promo_price = $('input[name=promo_price_filter]').val();
    	promo_price = (promo_price=='')?0:promo_price;
        url += '/' + encodeURIComponent(promo_price);        

    var tym_type = $('select[name=tym_type_filter]').val();
   		tym_type = (tym_type=='')?0:tym_type;
        url += '/' + encodeURIComponent(tym_type);
        
    var promo_start = $('input[name=startdate]').val();
    	promo_start = (promo_start=='')?0:promo_start;
        url += '/' + encodeURIComponent(promo_start);   
        
    var promo_end = $('input[name=enddate]').val();
    	promo_end = (promo_end=='')?0:promo_end;
        url += '/' + encodeURIComponent(promo_end);    
    
    var promo_enable = $('select[name=enable_filter]').val();
        url += '/' + encodeURIComponent(promo_enable);

    var limit = $('select[name=limit_filter]').val();
    	limit = (limit=='')?10:limit;
        url += '/' + encodeURIComponent(limit);             
    window.location.href = url;
}    

function showSaveButton() {
	$('#save-list-button').show('slow');
}

function saveListPrice() {
	var confirm = window.confirm('Bạn có chắc chắn muốn lưu lại thiết lập giá khuyến mãi cho app (s) không???');
		if(!confirm) return false;
	return true;	
}
</script>
<script>
$(function() {
    $( "#startdate" ).datepicker({ dateFormat: 'yymmdd' });
    $( "#enddate" ).datepicker({ dateFormat: 'yymmdd' });
    
    $( "#list_startdate" ).datepicker({ dateFormat: 'yymmdd' });
    $( "#list_enddate" ).datepicker({ dateFormat: 'yymmdd' });
});
</script>

</div>