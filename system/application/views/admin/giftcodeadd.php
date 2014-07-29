<link rel="stylesheet" href="<?=base_url()?>style/datepicker/themes/base/jquery.ui.all.css" type="text/css" media="screen" charset="utf-8" />
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.datepicker.js"></script>
<div id="content" class="container_16 clearfix">
	<?php if(isset($error)):?>
		<p class="error"><?=$error?></p>
	<?php endif;?>	
	<?php if(isset($success)):?>
		<p class="success"><?=$success?></p>
	<?php endif;?>
    <?php if(isset($giftcode)):?>
        <div class="grid_16">
        <p class="success">Vui lòng copy list mã giftcode, cách nhau bởi ;</p>    
        <p>
            <input type="text" value="<?=$giftcode?>" onclick="this.select();" />
        </p>
        </div>
	<?php endif;?>
	<div class="grid_16">
		<h2>Thêm mới giftcode</h2>
	</div>
	<form method="post" action="<?php echo site_url('admin/giftcode/add');?>">
    <div class="grid_3">
        <p>
            <label>Loại</label>
            <select name="type">
                <option value="t1">t1</option>
                <option value="t2">t2</option>
            </select>
        </p>
    </div>
    <div class="grid_3">
        <p>
            <label>Giá trị</label>
            <input type="text" name="value" value="10" />
        </p>
    </div>
    <div class="grid_2">
        <p>
            <label>Số lượng</label>
            <input type="text" name="quantity" value="1" />
        </p>
    </div>
    <div class="grid_2">
        <p>
            <label>Thời hạn</label>
            <input type="text" name="expire" id="datepicker" />
        </p>
    </div>  
    <div class="grid_3">
        <p>
            <label>Lý do</label>
            <input type="text" name="reason" />
        </p>
    </div>
    <div class="grid_2">
        <p>
            <label>Hành động</label>
            <input type="submit" name="insert" value="Thêm giftcode" />
        </p>
    </div>
	</form>
</div>
<script>
$(function() {
    $('#datepicker').datepicker({ dateFormat: 'yymmdd' });
});
</script>