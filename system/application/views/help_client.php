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
<title>AppStore.vn</title>
<link href="<?=base_url()?>pics/startup.png" rel="apple-touch-startup-image" />
<meta content="" name="keywords" />
<meta content="" name="description" />
</head>
<script type="text/javascript" src="<?=base_url()?>js/jquery-1.4.2.min.js"></script>
<body>
<div id="topholder">
  <div id="topbar">
    <div id="leftnav"> <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a><a href="<?=base_url()?>">Back</a></div>
    <?php 
        if($this->session->userdata('logged_in')) {
            $icontop='user-ac.png';
            $topUrl = site_url('home/account');
        } else {
            $icontop='user.png';
            $topUrl = 'javascript:footerLogin();';
        }
    ?>
    <div id="usernav">
        <a  href="<?=$topUrl?>"  id="login-btt-dis">
            <img src="<?=base_url()?>images/button/<?=$icontop?>">
        </a> 
        <a href="<?=site_url('home/help')?>">
            <img src="<?=base_url()?>images/button/help1.png">
        </a>
    </div>
  </div>
</div>
<div id="content">
  <div class="desc_support">
    <div class="info_support">
      <?php 
            $CI =& get_instance();
            $CI->load->model('textnote_model');
            $textnote = $CI->textnote_model->getInfoByKey($key);
            echo $textnote->value;
      ?>
    </div>
  </div>
</div>
</body>
</html>
