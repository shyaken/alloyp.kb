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
        Danh sách log tham gia sự kiện 
        -
        <a href="<?=site_url('admin/event')?>">Quản lý sự kiện</a>
    </h2>
</div>
    
<div class="grid_16">
    <h2>
        Thống kê luồng tym sử dụng
        -
        Có <?=$uniqueUser?> người chơi
    </h2>
    <table width="100%" style="top:-20px;">
        <tr>
            <td>Loại tym sử dụng</td>
            <td>Hệ thống sử dụng</td>
            <td>Người dùng sử dụng</td>
            <td>Hệ thống - Người dùng</td>
        </tr>
        <tr>
            <td>t1</td>
            <td><?=number_format($sysT1)?></td>
            <td><?=number_format($userT1)?></td>
            <td><?=number_format($sysT1-$userT1)?></td>
        </tr>
        <tr>
            <td>t2</td>
            <td><?=number_format($sysT2)?></td>
            <td><?=number_format($userT2)?></td>
            <td><?=number_format($sysT2-$userT2)?></td>
        </tr>
        <tr>
            <td>t3</td>
            <td><?=number_format($sysT3)?></td>
            <td><?=number_format($userT3)?></td>
            <td><?=number_format($sysT3-$userT3)?></td>
        </tr>
        <tr>
            <td>t1</td>
            <td><?=number_format($sysT4)?></td>
            <td><?=number_format($userT4)?></td>
            <td><?=number_format($sysT4-$userT4)?></td>
        </tr>
    </table>
</div>    

<form name="filter_actionlog" method="post">
<div class="grid_2">
    <p>
        <label>User ID</label>
        <input type="text" name="user_id" value="<?php if(isset($user_id) && $user_id != '0') echo $user_id?>" />
    </p>
</div>
<div class="grid_2">
    <p>
        <label>Username</label>
        <input type="text" name="username" value="<?php if(isset($username) && $username != '0') echo $username?>" />
    </p>
</div>
<div class="grid_2">
    <p>
        <label>Event ID</label>
        <input type="text" name="event_id" value="<?php if(isset($event_id) && $event_id != '0') echo $event_id?>" />
    </p>
</div>      
<div class="grid_3">
    <p>
        <label>Receive Type</label>
        <select name="receive_type">
            <option value="0">All</option>    
            <?php
                $types = array('t1', 't2', 't3', 't4', 'giftcode', 'card', 'text');
                foreach($types as $type) {
            ?>
            <option value="<?=$type?>" <?php if($type == $receive_type) echo 'selected="selected"'; ?>><?=$type?></option>
            <?php }?>
        </select>
    </p>
</div>
<div class="grid_3">
    <p>
        <label>Receive Status</label>
        <select name="receive_status">
            <option value="-1">All</option>    
            <option value="1" <?php if($receive_status == 1) echo 'selected="selected"'; ?>>Yes</option>
            <option value="0" <?php if(!$receive_status) echo 'selected="selected"'; ?>>No</option>
        </select>
    </p>
</div>    
<div class="grid_2">
    <p>
        <label>Time</label>
        <input type="text" name="time" id="datepicker" />
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
                     Logs/Page
                     <select name="limit" style="width:120px;">
                         <option value="10" <?php if($limit==10) echo 'selected="selected"';?>>10</option>
                         <option value="25" <?php if($limit==25) echo 'selected="selected"';?>>25</option>
                         <option value="50" <?php if($limit==50) echo 'selected="selected"';?>>50</option>
                         <option value="100" <?php if($limit==100) echo 'selected="selected"';?>>100</option>
                     </select>
                        <?php echo $this->pagination->create_links();?>
                 </td>
                     <td colspan="4" align="left"><b>Tổng số lượt tham gia = <?=$totalLog?></b></td>
                 </tr>
            <tr>
                <th width="40px">
                    <?php 
                        $order_ = '';
                        if($order == 'ASC') $order_ = 'DESC';
                        else $order_ = 'ASC';
                        $href = site_url("admin/event/giftboxLog/log_id/$order/$user_id/$username/$event_id/$receive_type/$receive_status/$time/$limit/$start");
                        echo "<a href='" . $href . "'>ID</a>";
                    ?>
                </th>
                <th width="80px">Username</th>
                <th width="40px">Event ID</th>
                <th width="40px">Giftbox ID</th>
                <th width="40px">Gift ID</th>
                <th width="40px">Tym Type</th>
                <th width="40px">Tym Price</th>
                <th width="60px">Receive Type</th>
                <th width="100px">Receive Value</th>
                <th width="60px">Receive Status</th>
                <th width="100px">Comment</th>
                <th width="80px">Time</th>
            </tr>
        </thead>
        <tbody>
            <?php if($logs):?>
            <?php foreach($logs as $log):?>
            <tr id='hover'>
                <td><?=$log->log_id?></td>
                <td><?php
                        $url = site_url('admin/user/edit/'.$log->user_id);
                    ?>
                    <a href="<?=$url?>"><?=$log->username?></a>
                </td>
                <td><?php
                    $url = site_url('admin/event/detail/'.$log->event_id);                            
                    ?>
                    <a href="<?=$url?>" target="_blank"><?=$log->event_id?></a>
                </td>
                <td><?php
                    $url = site_url('admin/event/giftboxDetail/'.$log->giftbox_id);                            
                    ?>
                    <a href="<?=$url?>" target="_blank"><?=$log->giftbox_id?></a>
                </td>
                <td><?php
                    $url = site_url('admin/event/giftDetail/'.$log->gift_id);                            
                    ?>
                    <?=$log->gift_id?></td>
                <td><?=$log->tym_type?></td>
                <td><?=$log->tym_price?></td>
                <td><?=$log->receive_type?></td>
                <td><?=$log->receive_value?></td>
                <td><?=($log->receive_status)?'Yes':'No'?></td>
                <td><?=$log->reason?></td>
                <td><?=date('d/m/Y H:i:s', $log->time)?></td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
        </tbody>
    </table>
            <?php if(!$logs):?>
                <center>Không tìm thấy dữ liệu</center>
            <?php endif;?>
    </div>
    </form>
<script type="text/javascript">
    function filter() {
        var url = '<?=site_url('admin/event/giftboxLog')?>' + '/<?php echo "$sort/$order/";?>' ;

        var user_id = $('input[name=user_id]').val();
        user_id = (user_id=='')?0:user_id;
        url += encodeURIComponent(user_id) + '/';
        
        var username = $('input[name=username]').val();
        username = (username=='')?0:username;
        url += encodeURIComponent(username) + '/';
        
        var event_id = $('input[name=event_id]').val();
        event_id = (event_id=='')?0:event_id;
        url += encodeURIComponent(event_id) + '/';

        var receive_type = $('select[name=receive_type]').val();
        url += encodeURIComponent(receive_type) + '/';

        var receive_status = $('select[name=receive_status]').val();
        url += encodeURIComponent(receive_status) + '/';
        
        var time = $('input[name=time]').val();
        time = (time=='')?0:time;
        url += encodeURIComponent(time) + '/';
        
        var limit = $('select[name=limit]').val();
        url += encodeURIComponent(limit) + '/';
        //alert(url);
        window.location.href = url;
    }  
    
    $(function() {
        $( "#datepicker" ).datepicker({ dateFormat: 'yymmdd' });
    });
</script>				
</div>