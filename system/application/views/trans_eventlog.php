<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="index,follow" name="robots" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link href="pics/homescreen.gif" rel="apple-touch-icon" />
<meta content="minimum-scale=1.0, width=device-width, maximum-scale=0.6667, user-scalable=no" name="viewport" />
<?php 
        if (base_url() == "http://appstore.vn/e/") {
    ?>
        <link href="<?=base_url()?>css/style-e.css" media="screen" rel="stylesheet" type="text/css" />
    <?php 
        } else if(base_url() == "http://appstore.vn/a/") {
    ?>
        <link href="<?=base_url()?>css/style-a.css" media="screen" rel="stylesheet" type="text/css" />
    <?php    
        } else { 
        $link = base_url().'css/'.$this->session->userdata('style').'.css';
    ?>
        <link href="<?=$link?>" media="screen" rel="stylesheet" type="text/css" />
    <?php } ?>
<title>appstore.vn</title>
<link href="<?=base_url()?>pics/startup.png" rel="apple-touch-startup-image" />
<meta content="" name="keywords" />
<meta content="" name="description" />
<script type="text/javascript" src="<?=base_url()?>js/jquery-1.4.2.min.js"></script>
<script src="<?=base_url()?>js/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>
	<link href="<?=base_url()?>js/colorbox/css/colorbox.css" media="screen" rel="stylesheet" type="text/css">
</head>
<body>
<div id="topholder">
  <div id="topbar">
    <div id="leftnav"> <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a><a href="<?=site_url('home/help')?>">Back</a></div>
    <div id="title">Hộp quà</div>
    <div id="usernav"><a  href="#"  id="mypaneltabs"><img src="<?=base_url()?>images/button/user1.png"></a> <a href="<?=site_url('home/help')?>"><img src="<?=base_url()?>images/button/help1.png"></a></div>
  </div>
</div>
<script>
    var page = 0;
    function showMore() {
        $('#showmore').hide();
        $('#loader').show();
        page++;
        $.ajax({
            url: "<?=site_url('home/moreEventLog/'.$time)?>/" + page,
            success: function(data){
                window.location.hash = '#' + page;
                $('#newitem').append(data);
                $('#loader').hide();
                $('#showmore').show();
            }
        });
    }

    $(document).ready(function(){
        curPage = window.location.hash;
        page = 0;
        currentPage = curPage.substr(1);
        if (currentPage>0) {
            for (i=1; i<currentPage; i++) {
                showMore();
            }
        }
    });
    
    function filter() {
        var url = "<?=site_url('home/eventlog/0')?>";
        var day = $('select[name=day]').val();
            url += "/" + day;
        var month = $('select[name=month]').val();
            url += "/" + month;
        var year = $('select[name=year]').val();
            url += "/" + year;
        window.location.href = url;
    }

</script>    
<div id="content">
  <div class="desc_support">
    <div class="info_event">
    	<div class="titleDate">
            <?php 
                if($time) {
                    $time = strtotime($time);
                } else {
                    $time = time();
                }
                $day = date('d', $time);
                $month = date('m', $time);
                $year = date('Y', $time);
            ?>
        	<label style="width:50px;">Ngày:</label> 
            <select name="day" style="width:40px;">
            	<?php for($i=1; $i<=31; $i++) {if($i<=9) $i = '0'.$i;?>
            	<option value="<?=$i?>" <?php if($day == $i) echo 'selected="selected"';?>><?=$i?></option>
                <?php }?>
            </select>
            <label style="width:50px;">/</label> <select name="month" style="width:40px;">
                <?php for($i=1; $i<=12; $i++) {if($i<=9) $i = '0'.$i;?>
            	<option value="<?=$i?>" <?php if($month == $i) echo 'selected="selected"';?>><?=$i?></option>
                <?php }?>
            </select>
            <label style="width:50px;">/</label> 
            <select name="year" style="width:50px;">
                <option value="2011" <?php if($year == 2011) echo 'selected="selected"';?>>2011</option>
                <option value="2012" <?php if($year == 2012) echo 'selected="selected"';?>>2012</option>
            </select>
            <label><input type="button" value="Xem" onclick="filter();" /></label>
        </div>
    	<ul><?php if($logs) {?>
            <?=$logs?>
            <!-- More Games -->
            <li id="newitem"></li>
            <li>
            	<div id="loader" style="display: none; text-align: center; margin: 5px;"><img src="<?=base_url()?>/images/loading.gif"/></div>
                <a id="showmore" style="text-decoration:none;" href="javascript:showMore();"><span class="more">Xem tiếp...</span></a>
            </li>
            <?php } else {?>
                Không có sự kiện nào!
            <?php } ?>
        </ul>
    </div>
  </div>
</div>
<script>
    
</script>    
</body>
</html>
