<div id="content" class="container_16 clearfix">
	
	<div class="grid_10">
		<h2>
            Chi tiết hộp quà <?=$giftbox->name?>
            - 
            <a href="<?=site_url('admin/event/editgiftbox/'.$giftbox->box_id)?>">Sửa</a>
        </h2>
	</div>
	<div class="grid_6" style="text-align: right;">
		<h2><a href="javascript:window.history.go(-1);" class="error">back</a></h2>
	</div>	
	<div class="grid_16">
	<style>tr.odd{width: 100%; border: 1px solid silver;}</style>
	<table style="width: 100%; border: 1px solid silver;">
		<tr class="odd">
			<td width="50%"><label>Tên hộp quà</label></td>
			<td style="text-align:right;"><label><?=$giftbox->name?></label></td>
		</tr>
        <tr class="odd">
			<td width="50%"><label>Thuộc sự kiện</label></td>
			<td style="text-align:right;">
                <label>
                    <?php
                        $event = $this->event_model->getInfo($giftbox->event_id);
                        echo $event->name;
                    ?>
                </label></td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>Loại tym bị trừ</label></td>
			<td style="text-align:right;"><label><?=$giftbox->tym_type?></label></td>
		</tr>
		<tr>
			<td width="50%"><label>Số tym bị trừ</label></td>
			<td style="text-align:right;"><label><?=$giftbox->input_tym?></label></td>
		</tr>
		<tr class="odd">
			<td width="50%"><label>Hình ảnh đại diện</label></td>
			<td style="text-align:right;">
                <img src="<?=$giftbox->image?>" width='100px' height='100px' />
            </td>
		</tr>
		<tr>
			<td width="50%"><label>Số ngẫu nhiên random</label></td>
			<td style="text-align:right;"><label><?=$giftbox->random?></label></td>
		</tr>
	</table>
    <h2>
        Danh sách các quà trong hộp quà này
    </h2>
    <table>
        <tr>
            <td>Gift ID</td>
            <td>Tên quà</td>
            <td>Kiểu</td>
            <td>Giá trị</td>
            <td>Số lượng</td>
            <td>Đã trúng</td>
            <td>Xác suất trúng</td>
        </tr>
        <?php if($gifts) { foreach($gifts as $gift) {?>
        <tr>
            <td><?=$gift->gift_id?></td>
            <td><?=$gift->name?></td>
            <td><?=$gift->type?></td>
            <td><?=$gift->value?></td>
            <td><?=$gift->quantity?></td>
            <td><?=$gift->datrung?></td>
            <td><?=$gift->xacsuat?></td>
        </tr>
        <?php }} ?>
    </table>
	</div>	
</div>