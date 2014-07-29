<div id="content" class="container_16 clearfix">
	<?php if(isset($success)):?>
		<p class="success"><?=$success?></p>
	<?php endif;?>
	<div class="grid_16">
		<h2>Chi tiết sự kiện</h2>
	</div>
	<div class="grid_16">
	<style>tr.odd{width: 100%; border: 1px solid silver;}</style>
	<table style="width: 100%; border: 1px solid silver;">
		<tr class="odd">
			<td width="50%"><label>Tên sự kiện</label></td>
			<td style="text-align:right;"><label><?=$event->name?></label></td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>Loại sự kiện</label></td>
			<td style="text-align:right;">
                <label>
                    <?php 
                        $eventType = $this->event_model->getEventType($event->type_id);
                        echo $eventType->name;
                    ?>
                </label>
            </td>
		</tr>
		<tr>
			<td width="50%"><label>Mô tả</label></td>
			<td style="text-align:right;"><label><?=$event->desc?></label></td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>Số người đang chơi</label></td>
			<td style="text-align:right;"><label><?=$event->playing?></label></td>
		</tr>
		<tr>
			<td width="50%"><label>Ảnh đại diện</label></td>
			<td style="text-align:right;">
                <img src="<?=base_url().$event->image?>" />
            </td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>Nhà tài trợ</label></td>
			<td style="text-align:right;"><label><?=$event->sponsor?></label></td>
		</tr>
		<tr>
			<td width="50%"><label>Ngày hết hạn</label></td>
			<td style="text-align:right;"><label><?=date('d/m/Y', $event->expired_time)?></label></td>
		</tr>
        <tr class="odd">
			<td width="50%"><label>Đang bật ở trang chủ</label></td>
			<td style="text-align:right;"><label><?=$event->active?></label></td>
		</tr>
	</table>
	</div>	
    
    <div class="grid_16">
        <?php if($giftboxs){ ?>
        <form action="<?=site_url('admin/event/detail/'.$event->event_id)?>" method="post">
        <table>
            <tr>
                <td colspan="3">
                    Số hộp quà hiện tại trong sự kiện này
                    -
                    <a href="<?=site_url('admin/event/addgiftbox/'.$event->event_id)?>">Thêm hộp quà</a>
                </td>
            </tr>
            <tr>
                <td>ID</td>
                <td>Order</td>
                <td>Name</td>
                <td>Input TYM</td>
                <td>Publish</td>
                <td>Action</td>
            </tr>
            <?php foreach($giftboxs as $giftbox):?>
            <tr id="giftbox<?=$giftbox->box_id?>">
                <td><?=$giftbox->box_id?></td>
                <td><input type="text" style="width:40px;text-align:center;" name="order<?=$giftbox->box_id?>" value="<?=$giftbox->order?>" /></td>
                <td><?=$giftbox->name?></td>
                <td><?=$giftbox->input_tym?></td>
                <td>
                    <select name="publish<?=$giftbox->box_id?>" style="width:80px;">
                        <option value="1" <?php if($giftbox->publish) echo 'selected="selected"'; ?>>Yes</option>
                        <option value="0" <?php if(!$giftbox->publish) echo 'selected="selected"'; ?>>No</option>
                    </select>
                </td>
                <td>
                    <a href="<?=site_url('admin/event/editgiftbox/'.$giftbox->box_id)?>" target="_blank">Sửa</a>
                    - 
                    <a href="javascript:deleteGiftbox(<?=$giftbox->box_id?>);">Xóa</a>
                </td>
            </tr>
            <?php endforeach;?>
            <tr>
                <td colspan="5">
                    <input type="submit" value="Save order" name="saveorder" />
                </td>
            </tr>
        </table>
        </form>
        <?php } else {?>
        <center>Hiện tại chưa có hộp quà nào trong sự kiện này, <a href="<?=site_url('admin/event/addgiftbox/'.$event->event_id)?>">nhấn đây để thêm mới</a></center>
        <?php }?>
    </div>
</div>
<script>
function deleteGiftbox(id) {
    $.ajax({
        url: "<?=site_url('admin/event/deletegiftbox')?>/" + id,
        success: function(data) {
            $('#giftbox' + id).hide('slow');
        }
    })
}
</script>