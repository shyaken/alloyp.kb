<script src="<?=base_url()?>js/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>
<link href="<?=base_url()?>js/colorbox/css/colorbox.css" media="screen" rel="stylesheet" type="text/css" />
<div class="bannergroup">
	<div class="minibannerfooter">
<?php 

	$CI =& get_instance();
	$CI->load->model('advertise_model', 'ad');
	$used = array();
	$all = false;
	foreach($footerads as $ad):
	// cong luot view
	//$CI->ad->countView($ad->id);
	
	$used[] = $ad->start;
	$id = 'f' . $ad->type . $ad->start . $ad->unit;
	$width = "148";
	$height = "120";
		if($ad->unit == 2) $width = "300";
	if($ad->type == "all") { $width = "300"; $height = "250"; $all = true; }
?>
	<div id="<?=$id?>">
		<?php 
			if($ad->code == '') {
		?>
		<a href="<?php echo site_url('home/advertise/' . $ad->id)?>" target="_blank">
		<img alt="" height="<?=$height?>" src="<?=base_url() . $ad->image?>" width="<?=$width?>" />
		</a>
		<?php 
			} else {
				echo $ad->code;
			}
		?>
	</div>		
<?php endforeach;?>
<?php 
	for($i=1; $i<5; $i++) {
		if(!in_array($i, $used)) {
			if(($i==3 || $i==1) && $all == false) {
		           	  echo "<script>$('.bannergroup .minibannerfooter').css('height','68px');
		                  $('#fngang31').css('top','0px');
		                  $('#fngang41').css('top','0px');
		                  </script>";break;
                	} else {
				echo '<div id="fngang' . $i . '1">';
				echo '<img src="' . base_url() . 'uploads/ad/default.png" />';
				echo '</div>';
			}
		}
	}
?>	
	</div>	
</div>
<div class="scrollbottom">
	<marquee behavior="scroll" scrollamount="5">
	<?php if($footertext) echo $footertext->code?>
	</marquee> 
</div>
<div id="mypanel2" class="ddpanel" style="display:none;">
  <div id="mypanelcontent2" class="ddpanelcontent">
    <div class="elements login">
      <div class="title-bx">
        <h1><a href="javascript:footerLogin();">Đăng nhập</a></h1>
      
      </div>
      <div class="content-bx">
        <ul class="formElements">
          <li class="smallfield"> <span class="name">Tên đăng nhập:</span>
            <input placeholder="" type="text" name="username2" />
          </li>
          <li class="smallfield"> <span class="name">Mật khẩu:</span>
            <input placeholder="" type="password" name="password2"  />
          </li>
        </ul>
        <div class="buttonPanel"> <a href="javascript:login(2,2);" class="white button w50" id="login-bttss" >Đăng nhập</a> <a href="javascript:footerLogin();" class="white button w50">Hủy bỏ</a> </div>
      </div>
    </div>
  </div>
</div>
<div id="info-system">
  <?=$user_box?>
</div>
<script>
<?php 
	$footerCI =& get_instance();
    $footerCI->load->model('textnote_model');
    $footerCI->load->model('setting_model');
    $popup = $this->setting_model->getValueByKey('popup');
    $linkx = site_url('home/help/notice_new');
	if($popup && !$this->session->userdata('read_popup')) { 
    	$textnote = $footerCI->textnote_model->getInfoByKey('popup_text');
        $textlink = $footerCI->textnote_model->getInfoByKey('popup_link');
        if($textlink->value) $linkx = $textlink->value;
?>
$(document).ready(function(){
   //$.fn.colorbox({width:"100%", height:"100%", top: "100px", inline:true, href:"#popup_helper"});
   var confirm = window.confirm('<?=$textnote->value?>');
   if(confirm) {
       readPopup();
   }
});    
<?php } ?>
function readPopup() {
    $.ajax({
        url: "<?php echo site_url('home/readPopup') ?>/" + 1,
        success: function(data) {
            $.fn.colorbox.close();
            window.location.href = '<?=$linkx?>';
        }
    });
}
</script>
<div style="display:none;">
    <div id="popup_helper" style="font-size:12px;margin:auto;color:white;text-align:center;">
        <div id="content">
        <ul class="pageacc">
            <li class="smallfield">
            <div class="type-field">
                <ul>
                    <li class="type-1" style="width: 100%;font-size:12px;">
                        <a href="javascript:readPopup();">
                            Chúc bạn ngày mới tốt lành!
                        </a>
                    </li>
                    <li class="type-1" style="width:100%;">
                        <a href="javascript:readPopup();">
                            Chính sách mới của AppStore.VN
                        </a>
                    </li>
                </ul>
            </div>
            </li>
        </ul>
        </div>
    </div>
</div>
</body>
</html>
