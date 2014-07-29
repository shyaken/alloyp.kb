<?php 
$CI =& get_instance();
$CI->load->model('advertise_model');
?>
<link rel="stylesheet" href="<?=base_url()?>style/datepicker/themes/base/jquery.ui.all.css" type="text/css" media="screen" charset="utf-8" />
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.datepicker.js"></script>
<div id="content" class="container_16 clearfix">
	<div class="grid_10">
            <h2>Danh sách quảng cáo</h2>
        </div>
        <div class="grid_6" style="text-align: right;">
            <h2>
                <a href="<?php echo site_url('admin/ad/add')?>"><img src="<?=base_url()?>style/admin/Add.png" height="26px" /></a>
            </h2>
        </div>
        <div class="grid_4">
            <lable>ID quảng cáo</lable>
            <input type="text" name="advertise_id" value="<?php if($advertise_id) echo $advertise_id?>" />
        </div>
        <div class="grid_4">
            <lable>Ngày bắt đầu</lable>
            <input name='starttime' class='date' value="<?php if($starttime) echo $starttime?>">
        </div>
        <div class="grid_4">
            <lable>Ngày kết thúc</lable>
            <input name='endtime' class='date' value="<?php if($endtime) echo $endtime?>">
        </div>
        <div class="grid_4">
            <lable>Lọc ngay</lable>
            <input type="button" value="Lọc ngay" onclick="filter();" />
        </div>
	<div class="grid_16">
		<table style="martin-top:10px;">
			<thead>
				<tr>
					<th>ID quảng cáo</th>
					<th>Tên</th>
					<th>Ô bắt đầu</th>
					<th>Số đơn vị</th>
					<th>Kiểu</th>
					<th>Khu vực</th>
                                        <th>Ảnh/Mã code</th>
                                        <th>Click/View</th>
                                        <th>Publish</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
					<th colspan="2" width="10%">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php if($ads):?>
				<?php foreach($ads as $ad):
                                    $advertise = $CI->advertise_model->getInfo($ad->advertise_id);
                                    if($advertise):
                                ?>
				<tr>
					<td>
                                            <?=$advertise->id?>
                                            <a href="<?=site_url('admin/ad/all/advertise_id/DESC/'.$advertise->id.'/'.$starttime.'/'.$endtime)?>">
                                                chi tiết
                                            </a>
                                        </td>
					<td><?=$advertise->name?></td>
                                        <td><?=$advertise->start?></td>
					<td><?=$advertise->unit?></td>
					<td><?=$advertise->type?></td>
					<td><?=$advertise->section?></td>
                                        <td>
                                            <?php if($advertise->code) echo "mã code"; ?>
                                            <?php if($advertise->image) echo "ảnh"; ?>
                                        </td>
                                        <td><?=$ad->click?>/<?=$ad->view?></td>
                                        <td><?php if($advertise->publish == 1){echo "Yes";} else {echo "No";}?></td>
                                        <td><?=date("m/d/Y H:i:s",$advertise->start_date)?></td>
                                        <td><?=date("m/d/Y H:i:s",$advertise->end_date)?></td>
					<td>
						<a href="<?php echo site_url('admin/ad/edit/' . $advertise->id)?>" class="edit">Edit</a>
					</td>
					<td>
						<a href="<?php echo site_url('admin/ad/delete/' . $advertise->id)?>" class="delete" onclick="var x=confirm('Bạn có chắc chắn muốn xóa quảng cáo này không???');if(!x){return false;}">Delete</a>
					</td>
				</tr>
                                <?php
                                    if($advertise->id == $advertise_id) {
                                        if(isset($dates)) {
                                ?>
                                <tr>
                                    <td colspan="10">
                                        <table width="100%">
                                            <tr>
                                                <td>Ngày</td>
                                                <td>Lượt click</td>
                                                <td>Lượt view</td>
                                            </tr>
                                <?php
                                            foreach($dates as $date) {
                                ?>
                                            <tr>
                                                <td><?=$date->time?></td>
                                                <td><?=$date->click?></td>
                                                <td><?=$date->view?></td>
                                            </tr>    
                                <?php
                                            } //endforeach
                                ?>                                                                                    </table>
                                    </td>
                                </tr>
                                <?php            
                                        }//endif
                                    }
                                ?>
				<?php endif;endforeach;?>
				<?php endif;?>
			</tbody>
		</table>
				<?php if(!$ads):?>
					<center>Không tìm thấy dữ liệu</center>
				<?php endif;?>
	</div>
</div>

<script>
$('.date').datepicker({ dateFormat: 'yymmdd' });

function filter() {
    var url = '<?=$firstUrl?>';
    var id = $('input[name=advertise_id]').val();
        id = (id == '')?0:id;
        url+= '/' + encodeURIComponent(id);
    var startdate = $('input[name=starttime]').val();
        startdate = (startdate == '')?0:startdate;
        url+= '/' + encodeURIComponent(startdate);
    var enddate = $('input[name=endtime]').val();
        enddate = (enddate == '')?0:enddate;
        url+= '/' + encodeURIComponent(enddate);
    window.location.href = url;
    return false;
}
</script>
