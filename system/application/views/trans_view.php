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
</head>
<body>
<div id="topholder">
  <div id="topbar">
    <div id="leftnav"> <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a><a href="<?=base_url()?>">Back</a></div>
    <div id="title"> Giao dịch</div>
    <div id="usernav"><a  href="#"  id="mypaneltabs"><img src="<?=base_url()?>images/button/user1.png"></a> <a href="<?=site_url('home/help')?>"><img src="<?=base_url()?>images/button/help1.png"></a></div>
  </div>
</div>
<div id="content">
  <div class="transactions">
    <ul>
      <li ><a href="<?=site_url('home/eventlog')?>"><span class="shadown"><img alt="list" src="<?=base_url()?>images/button/lichsugiaodich.png" /></span> <span class="name">Lịch sử mở hộp quà</span><span class="arrow"></span></a></li>
      <!--
      <li ><a href="javasript:alert('Coming soon');"><span class="shadown"><img alt="list" src="images/button/lichsugiaodich.png" /></span> <span class="name">Lịch sử giao dịch</span><span class="arrow"></span></a></li>
      <li ><a href="javasript:alert('Coming soon');"><span class="shadown"><img alt="list" src="images/button/nap.png" /></span> <span class="name">Nạp TYM vào tài khoản</span><span class="arrow"></span></a></li>
      <li ><a href="javasript:alert('Coming soon');"><span class="shadown"><img alt="list" src="images/button/film.png" /></span> <span class="name">Đăng ký gia hạn gói xem film</span><span class="arrow"></span></a></li>
      <li ><a href="javasript:alert('Coming soon');"><span class="shadown"><img alt="list" src="images/button/FShare.png" /></span> <span class="name">Đăng ký gói tải VIP FShafe</span><span class="arrow"></span></a></li>
      <li ><a href="javasript:alert('Coming soon');"><span class="shadown"><img alt="list" src="images/button/megashare.png" /></span> <span class="name">Đăng ký gói tải VIP Megashare</span><span class="arrow"></span></a></li>
      -->
    </ul>
  </div>
</div>
</body>
</html>
