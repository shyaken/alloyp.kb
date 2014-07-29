<div id="content" class="container_16 clearfix">
<?php if(isset($success)):?>
	<p class="success"><?=$success?></p>
<?php endif;?>

<div class="grid_7">
	<h2>Bộ lọc từ ngữ xấu cho bình luận</h2>
</div>
<span style="font-size:22px;">
<form action="<?php echo site_url('admin/managerapp/commentfilter') ?>" method="post">
<div class="grid_3">
	<input type="text" name="find" />
</div>	
<div class="grid_3">
	<input type="text" name="replace" />
</div>
<div class="grid_3">
	<input type="submit" value="Thêm mới" />
</div>
</form>
</span>

<table>
<thead style="font-weight:bold;">
	<td width="10%">ID</td>
	<td width="35%">Từ xấu</td>
	<td width="35%">Từ thay thế</td>
	<td width="20%">Hành động</td>
</thead>
<?php 
	foreach($words as $word) { ?>
<tr id="tr<?=$word->id?>">
	<td><?=$word->id?></td>
	<td id="find<?=$word->id?>"><?=$word->find?></td>		
	<td id="replace<?=$word->id?>"><?=$word->replace?></td>
	<td>
		<input type="button" value="Edit" name="Edit" id="Edit<?=$word->id?>" onclick="var cf=window.confirm('Bạn có chắc chắn muốn chỉnh sửa?');if(cf){editWordForm('<?=$word->id?>');}else{return false;}" />
		<input type="button" value="Save" name="Save" id="Save<?=$word->id?>" onclick="editWord('<?=$word->id?>')" style="display:none;border:1px solid green;" />
		<input type="button" value="Delete" name="Delete" onclick="deleteWord('<?=$word->id?>');" />
		</td>		
</tr>
<?php } ?>
</table>

<style>
td a {text-decoration:none;}
.find-input {width:150px;border:1px solid green;}
.tym-input {width:70px;border:1px solid green;}
</style>
<script type="text/javascript">
var lastEditId = 0;
var lastFind = "";
var lastReplace = "";

function editWordForm(word_id) {
	restore();
	var currentFind = $('#find' + word_id).html();
		lastFind = currentFind;
	var currentReplace = $('#replace' + word_id).html();
		lastReplace = currentReplace;

	//find	
	var findTxt = '<input type="text" id="find' + word_id + 'input" value="' + currentFind + '" class="find-input" />';
	$('#find' + word_id).html(findTxt);
	//replace
	var replaceTxt = '<input type="text" id="replace' + word_id + 'input" value="' + currentReplace + '" class="find-input" />';
	$('#replace' + word_id).html(replaceTxt);
	
	//hide Edit - show Save
	$('#Edit' + word_id).hide('slow');
	$('#Save' + word_id).show('slow');

	//save lastEditId
	lastEditId = word_id;
}

function editWord(word_id) {
	var find = $('#find' + word_id + 'input').val();
		lastFind = find;
	var replace = $('#replace' + word_id + 'input').val();
	lastReplace = replace;

	$.ajax({
		url: "<?php echo site_url('admin/managerapp/editBadWord')?>",
		data: "id=" + word_id + "&find=" + find + "&replace=" + replace,
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

	$('#find' + lastEditId).html(lastFind);
	$('#replace' + lastEditId).html(lastReplace);
	$('#Save' + lastEditId).hide('slow');
	$('#Edit' + lastEditId).show('slow');
}

//xóa từ
function deleteWord(word_id) {
	$.ajax({
		url: '<?php echo site_url('admin/managerapp/deleteBadWord')?>/' + word_id,
		success: function(data) {
			$('#tr' + word_id).hide('slow');
		}
	});
}
</script>

</div>