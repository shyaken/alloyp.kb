<?php $CI =& get_instance(); ?>
<link rel="stylesheet" href="<?=base_url()?>style/datepicker/themes/base/jquery.ui.all.css" type="text/css" media="screen" charset="utf-8" />
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.datepicker.js"></script>
<style>
#hover:hover{color:green;font-weight:bold;}
</style>
<div id="content" class="container_16 clearfix">
<?php if(isset($error)):?>
    <p class="error"><?=$error?></p>
<?php endif;?>	
<?php if(isset($success)):?>
    <p class="success"><?=$success?></p>
<?php endif;?>
<div class="grid_16">
    <h2>
        Danh sách các sự kiện
        -
        <a href="<?=site_url('admin/event/add')?>">Thêm mới sự kiện</a>
        -
        <a href="<?=site_url('admin/event/giftboxLog')?>">Log người chơi</a>
    </h2>
</div>

<form name="filter_actionlog" method="post">
<div class="grid_2">
    <p>
        <label>Event ID</label>
        <input type="text" name="event_id" value="<?php if(isset($event_id) && $event_id != '0') echo $event_id?>" />
    </p>
</div>
<div class="grid_2">
    <p>
        <label>Event Type</label>
        <select name="type_id">
            <option value="-1">All</option>
            <?php foreach($types as $eventType):?>
            <option value="<?=$eventType->type_id?>"<?php if($type == $eventType->type_id){echo ' selected="selected"';}?>><?=$eventType->name?></option>
            <?php endforeach;?>
        </select>
    </p>
</div>
<div class="grid_3">
    <p>
        <label>Name</label>
        <input type="text" name="name" />
    </p>
</div>
<div class="grid_3">
    <p>
        <label>Sponsor</label>
        <input type="text" name="sponsor" />
    </p>
</div>	
<div class="grid_2">
    <p>
        <label>Active</label>
        <select name="active">
            <option value="-1">All</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
    </p>
</div>   
<div class="grid_2">
    <p>
        <label>Limit</label>
        <select name="limit">
            <option value="10" <?php if($limit == 10) echo 'selected="selected"';?>>10</option>
            <option value="25" <?php if($limit == 25) echo 'selected="selected"';?>>25</option>
            <option value="50" <?php if($limit == 50) echo 'selected="selected"';?>>50</option>
        </select>
    </p>
</div>    
<div class="grid_1">
    <p>
        <label>&nbsp;</label>
        <input type="submit" value="Lọc" onclick="filter(); return false;" />
    </p>
</div>
</form>

<form id="list_app" method="post">
<div class="grid_16">
    <table>
        <thead>
            <tr>
                 <td colspan="4">
                      <?php echo $this->pagination->create_links();?>
                 </td>
                     <td colspan="4" align="left"><b>Tổng số Event = <?=$totalEvent?></b></td>
                 </tr>
            <tr>
                <th width="50px">
                    <?php 
                        $order_ = '';
                        if($order == 'ASC') $order_ = 'DESC';
                        else $order_ = 'ASC';
                        $href = site_url("admin/event/viewall/event_id/$order/$event_id/$type/$name/$sponsor/$active/$limit/$start");
                        echo "<a href='" . $href . "'>ID</a>";
                    ?>
                </th>
                <th width="70px">Type</th>
                <th width="100px">Name</th>
                <th width="100px">Sponsor</th>
                <th>Lượt chơi/Người chơi</th>
                <th width="100px">Expired time</th>
                <th width="80px">Active</th>
                <th width="220px">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if($events):?>
            <?php foreach($events as $event):?>
            <tr id='hover'>
                <td><?=$event->event_id?></td>
                <td>
                    <?php
                        $getType = $CI->event_model->getEventType($event->type_id);
                        if($getType) echo $getType->name;
                        else echo 'unknow';
                    ?>
                </td>
                <td><?=$event->name?></td>
                <td><?=$event->sponsor?></td>
                <td>
                    <?=$event->playing?>/
                    <?php
                        $total = $CI->event_model->uniquePlayerInEvent($event->event_id);
                        echo $total;
                    ?>
                </td>
                <td><?=date('d/m/Y', $event->expired_time)?></td>
                <td>
                    <?php
                        if($event->active) echo 'Yes';
                        else echo 'No';
                    ?>
                </td>
                <td>
                    <a href="<?=site_url('admin/event/edit/'.$event->event_id)?>" target="_blank">Sửa</a>
                    <a href="<?=site_url('admin/event/detail/'.$event->event_id)?>" target="_blank">Chi tiết</a>
                    <a href="<?=site_url('admin/event/addgiftbox/'.$event->event_id)?>" target="_blank">Thêm hộp quà</a>
                </td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
        </tbody>
    </table>
            <?php if(!$events):?>
                <center>Không tìm thấy dữ liệu</center>
            <?php endif;?>
    </div>
    </form>
<script type="text/javascript">
    function filter() {
        var url = '<?=site_url('admin/event/viewall')?>' + '/<?php echo "$sort/$order/";?>' ;

        var event_id = $('input[name=event_id]').val();
        event_id = (event_id=='')?0:event_id;
        url += encodeURIComponent(event_id) + '/';

        var type = $('select[name=type_id]').val();
        url += encodeURIComponent(type) + '/';

        var name = $('input[name=name]').val();
        name = (name=='')?0:name;
        url += encodeURIComponent(name) + '/';
        
        var sponsor = $('input[name=sponsor]').val();
        sponsor = (sponsor=='')?0:sponsor;
        url += encodeURIComponent(sponsor) + '/';
        
        var active = $('select[name=active]').val();
        url += encodeURIComponent(active) + '/';
        
        var limit = $('select[name=limit]').val();
        url += encodeURIComponent(limit) + '/';
        //alert(url);
        window.location.href = url;
    }  
</script>				
</div>