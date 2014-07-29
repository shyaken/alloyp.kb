<link rel="stylesheet" href="<?=base_url()?>style/datepicker/themes/base/jquery.ui.all.css" type="text/css" media="screen" charset="utf-8" />
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.datepicker.js"></script>

<script type="text/javascript">
    function publishID(ad_id,value) {
        $.ajax({
           type: "POST",
           data: "ad_id=" + ad_id + "&value=" + value,
           url: "<?php echo site_url('admin/ad/publishID');?>",
           beforeSend: function(){
               $("#publish"+ad_id).html('working');
           },
           success: function(response){
              $("#publish"+ad_id).html(response); 
           }
        });
    }
</script>
<div id="content" class="container_16 clearfix">
	<div class="grid_10">
				<h2>Danh sách quảng cáo - <a href="<?=site_url('admin/ad/all')?>">Theo ngày</a></h2>
			</div>
			<div class="grid_6" style="text-align: right;">
				<h2>
					<a href="<?php echo site_url('admin/ad/add')?>"><img src="<?=base_url()?>style/admin/Add.png" height="26px" /></a>
				</h2>
			</div>
	<div class="grid_16">
		<table>
			<thead>
				<tr>
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
				<?php foreach($ads as $headerAd):?>
				<tr>
					<td><?=$headerAd->name?></td>
					<td><?=$headerAd->start?></td>
					<td><?=$headerAd->unit?></td>
					<td><?=$headerAd->type?></td>
					<td><?=$headerAd->section?></td>
                                        <td>
                                            <?php if($headerAd->code) echo "mã code"; ?>
                                            <?php if($headerAd->image) echo "ảnh"; ?>
                                        </td>
                                        <td><?=$headerAd->click?>/<?=$headerAd->view?></td>
                                        
                                        <td id="publish<?=$headerAd->id?>">
                                            <?php 
                                                if($headerAd->publish == 1){
                                                    echo "<a href='javascript:;' onclick='publishID(".$headerAd->id.",1)';> Yes </a>";
                                                } else {
                                                    echo "<a href='javascript:;' onclick='publishID(".$headerAd->id.",0)';> No </a>";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                echo date("m/d/Y H:i:s",$headerAd->start_date);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                echo date("m/d/Y H:i:s",$headerAd->end_date);
                                                ?>
                                        </td>
					
                                        <td>
						<a href="<?php echo site_url('admin/ad/edit/' . $headerAd->id)?>" class="edit">Edit</a>
					</td>
					<td>
						<a href="<?php echo site_url('admin/ad/delete/' . $headerAd->id)?>" class="delete" onclick="var x=confirm('Bạn có chắc chắn muốn xóa quảng cáo này không???');if(!x){return false;}">Delete</a>
					</td>
				</tr>
				<?php endforeach;?>
				<?php endif;?>
			</tbody>
		</table>
				<?php if(!$ads):?>
					<center>Không tìm thấy dữ liệu</center>
				<?php endif;?>
	</div>
</div>