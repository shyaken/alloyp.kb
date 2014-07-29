<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="index,follow" name="robots" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link href="<?=base_url()?>pics/homescreen.gif" rel="apple-touch-icon" />
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
<script src="<?=base_url()?>js/functions.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/jquery.1.3.2.min.js" type="text/javascript"></script>
<title>appstore.vn</title>
<link href="<?=base_url()?>pics/startup.png" rel="apple-touch-startup-image" />
<meta content="" name="keywords" />
<meta content="" name="description" />
</script>

</head>
<body>
<!-- bước 1 -->
<div id="help-home">
<div id="topbar">
    <div id="leftnav"> <a href="<?=base_url()?>">Back</a></div>
  <div id="title"> Trợ giúp</div>
</div>
<div id="content">
<?php 
    $CI =& get_instance();
    $CI->load->model('textnote_model');
    $textnote = $CI->textnote_model->getInfoByKey('help_contact');
    echo $textnote->value;
?>
</div>
</div>
</body>
</html>
