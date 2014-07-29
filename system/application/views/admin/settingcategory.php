<div id="content" class="container_16 clearfix">

<div class="grid_2">
	<p>
		<label>category_id</label>
		<input type="text" name="category_id_filter" />
	</p>
</div>
<div class="grid_3">
	<p>
		<label>category_name</label>
		<input type="text" name="category_name_filter" />
	</p>
</div>
<div class="grid_3">
	<p>
		<label>Loại tym</label>
		<select name="tym_type_filter">
			<option value="0">All</option>
			<option value="t1">Tym 1 (đỏ)</option>
			<option value="t2">Tym 2 (tím)</option>
			<option value="t3">Tym 3 (xanh lá)</option>
			<option value="t4">Tym 4 (xanh vàng)</option>
		</select>
	</p>
</div>
<div class="grid_3">
	<p>
		<label>Phương thức</label>
		<select name="method_filter">
			<option value="0">None</option>
			<option value="hit">Hit</option>
			<option value="all">All</option>
		</select>
	</p>
</div>
<div class="grid_3">
	<p>
		<label>Theo gói</label>
		<select name="package_filter">
			<option value="-1">Tất cả</option>
			<option value="1">Có</option>
			<option value="0">Không</option>
		</select>
	</p>
</div>
<div class="grid_2">
	<p>
		<label>Lọc</label>
		<input type="button" value="Lọc" style="width:50px;" onclick="filter();" />
	</p>
</div>

<br /> <br />

<div class="grid_16">
	<h2>Danh sách Category và thiết lập giá</h2>
</div>

<table style="margin-top:-20px;">
<thead style="font-weight:bold;">
	<td width="35%">Tên chuyên mục</td>
	<td width="12%">Giá</td>
	<td width="12%">Loại Tym</td>
	<td width="12%">Phương thức</td>
	<td width="12%">Theo gói</td>
	<td width="27%">Hành động</td>
</thead>
<?php 
	foreach($categories as $category) { ?>
<tr>
	<td><?=$category->category_name?></td>
	<td id="price<?=$category->category_id?>"><?php if($category->price){echo $category->price;}else{echo "Chưa có";}?></td>		
	<td id="tym_type<?=$category->category_id?>"><?php if($category->tym_type){echo $category->tym_type;}else{echo "Chưa có";}?></td>
	<td id="method<?=$category->category_id?>"><?=$category->method?></td>
	<td id="package<?=$category->category_id?>"><?php 
			if($category->package) echo "Có";
			else echo "Không";
			?></td>
	<td>
		<input type="button" value="Edit" name="Edit" id="Edit<?=$category->category_id?>" onclick="var cf=window.confirm('Bạn có chắc chắn muốn chỉnh sửa?');if(cf){editCategoryForm('<?=$category->category_id?>');}else{return false;}" />
		<input type="button" value="Save" name="Save" id="Save<?=$category->category_id?>" onclick="editCategory('<?=$category->category_id?>')" style="display:none;border:1px solid green;" />
		</td>		
</tr>
<?php } ?>
</table>

<style>
td a {text-decoration:none;}
.price-input {width:50px;border:1px solid green;}
.tym-input {width:70px;border:1px solid green;}
</style>
<script type="text/javascript">
var lastEditId = 0;
var lastPrice = "";
var lastTym = "";
var lastMethod = "";
var lastPackage = "";

function editCategoryForm(category_id) {
	restore();
	var currentPrice = $('#price' + category_id).html();
		lastPrice = currentPrice;
	var currentTym = $('#tym_type' + category_id).html();
		lastTym = currentTym;
	var currentMethod = $('#method' + category_id).html();
		lastMethod = currentMethod;
	var currentPackage = $('#package' + category_id).html();
		lastPackage = currentPackage;	 
		currentPackage = (currentPackage=='Không')?'0':'1';

	//price
	var price = (currentPrice=='Chưa có')?'':currentPrice;
	var priceTxt = '<input type="text" id="price' + category_id + 'input" value="' + price + '" class="price-input" />';
	$('#price' + category_id).html(priceTxt);

	//tym_type
	var tym = (currentTym=='Chưa có')?'':currentTym;
	var tymTxt = '<select name="tym_type' + category_id + '" class="tym-input" id="tym_type_' + category_id + '">';
		tymTxt+= '<option value="t1">Tym 1 (đỏ)</option>';
		tymTxt+= '<option value="t2">Tym 2 (tím)</option>';
		tymTxt+= '<option value="t3">Tym 3 (xanh lá)</option>';
		tymTxt+= '<option value="t4">Tym 4 (vàng)</option>'; 
		tymTxt+= '</select>';
	$('#tym_type' + category_id).html(tymTxt);

	var cityselect = document.getElementById('tym_type_' + category_id);
	for(var i=0; i < cityselect.options.length; i++){
		if(cityselect.options[i].value == currentTym) cityselect.options[i].selected='selected';
	}	

	//method
	var methodTxt = '<select name="method' + category_id + '" class="tym-input" id="method_' + category_id + '">';
		methodTxt+= '<option value="all">All</option>';
		methodTxt+= '<option value="hit">Hit</option>';
		methodTxt+= '</select>';
	$('#method' + category_id).html(methodTxt);
	var cityselect1 = document.getElementById('method_' + category_id);
	for(var i=0; i < cityselect1.options.length; i++){
		if(cityselect1.options[i].value == currentMethod) cityselect1.options[i].selected='selected';
	}

	//package
	var packageTxt = '<select name="method' + category_id + '" class="tym-input" id="package_' + category_id + '">';
		packageTxt+= '<option value="1">Có</option>';
		packageTxt+= '<option value="0">Không</option>';
		packageTxt+= '</select>';
	$('#package' + category_id).html(packageTxt);
	var cityselect2 = document.getElementById('package_' + category_id);
	for(var i=0; i < cityselect2.options.length; i++){
		if(cityselect2.options[i].value == currentPackage) cityselect2.options[i].selected='selected';
	}

	//hide Edit - show Save
	$('#Edit' + category_id).hide('slow');
	$('#Save' + category_id).show('slow');

	//save lastEditId
	lastEditId = category_id;
}

function editCategory(category_id) {
	var price = $('#price' + category_id + 'input').val();
		lastPrice = price;
	var tym_type = $('#tym_type_' + category_id).val();
		lastTym = tym_type;
	var method = $('#method_' + category_id).val();
		lastMethod = method;
	var packagex = $('#package_' + category_id).val();
		lastPackage = (packagex==0)?'Không':'Có';

	$.ajax({
		url: "<?php echo site_url('admin/setting/editCategory')?>",
		data: "category_id=" + category_id + "&price=" + price + "&tym_type=" + tym_type + "&method=" + method + "&package=" + packagex,
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

	$('#price' + lastEditId).html(lastPrice);
	$('#tym_type' + lastEditId).html(lastTym);
	$('#method' + lastEditId).html(lastMethod);
	$('#package' + lastEditId).html(lastPackage);
	$('#Save' + lastEditId).hide('slow');
	$('#Edit' + lastEditId).show('slow');
}

function filter() {
    var url = '<?=site_url('admin/setting/category')?>' + '/<?php echo "$sort/$order/";?>' ;
	
    var category_id = $('input[name=category_id_filter]').val();
    	category_id = (category_id=='')?0:category_id;
        url += encodeURIComponent(category_id);
    			
    var category_name = $('input[name=category_name_filter]').val();
    	category_name = (category_name=='')?0:category_name;
        url += '/' + encodeURIComponent(category_name);

    var tym_type = $('select[name=tym_type_filter]').val();
   		tym_type = (tym_type=='')?0:tym_type;
        url += '/' + encodeURIComponent(tym_type);
        
    var method = $('select[name=method_filter]').val();
    	method = (method=='')?0:method;
        url += '/' + encodeURIComponent(method);

    var packagex = $('select[name=package_filter]').val();
    	packagex = (packagex=='')?-1:packagex;
        url += '/' + encodeURIComponent(packagex);   

    window.location.href = url;
}    
 </script>

</div>