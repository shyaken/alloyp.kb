<?php 
	$x86 = str_replace('x', '6', $smsResult);
	$x87 = str_replace('x', '7', $smsResult);
?>
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
        } if(base_url() == "http://appstore.vn/a/") {
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

	<script type="text/javascript" src="<?=base_url()?>js/jquery-1.4.2.min.js"></script>

    <script type="text/javascript" src="<?=base_url()?>js/popup/ddaccordion.js"></script>
	<!--Popup-->

	<script src="<?=base_url()?>js/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>
	<link href="<?=base_url()?>js/colorbox/css/colorbox.css" media="screen" rel="stylesheet" type="text/css">
	<script>
		$(document).ready(function(){
			//Examples of how to assign the ColorBox event to elements
			
			$("#helper_sms").colorbox({inline:true, width:"100%",href:"#smsbox",transition:"fade"});
			$("#helper_sms1").colorbox({inline:true, width:"100%",href:"#smsbox",transition:"fade"});
			$("#card_sms").colorbox({inline:true, width:"100%",href:"#cardbox",transition:"fade"});
			$("#card_sms1").colorbox({inline:true, width:"100%",href:"#cardbox",transition:"fade"});
			$("#smartlink_sms").colorbox({inline:true, width:"100%",href:"#smartlinkbox",transition:"fade"});
            $("#paypal_sms").colorbox({inline:true, width:"100%",href:"#paypalbox",transition:"fade"});
			$("#paypal_sms1").colorbox({inline:true, width:"100%",href:"#paypalbox",transition:"fade"});
            $("#helper_sendSMS").colorbox({inline:true, width:"100%",href:"#smsbox-help",transition:"fade"});
            $("#helper_sendCard").colorbox({inline:true, width:"100%",href:"#cardbox-help",transition:"fade"});
            $("#helper_sendSmartlink").colorbox({inline:true, width:"100%",href:"#smartlinkbox-help",transition:"fade"});
            $("#helper_sendPaypal").colorbox({inline:true, width:"100%",href:"#paypalbox-help",transition:"fade"});
		});
	</script>
</head>

<body>
<?php if(isset($cardSubmitted)):?>
<script>
$(document).ready(function(){
	$.fn.colorbox({inline:true,width:"100%", height:"100%", inline:true, href:"#card_result",transition:"fade"});
});
</script>
<?php endif;?>
<?php if(isset($paypalSubmitted)):?>
<script>
$(document).ready(function(){
	$.fn.colorbox({inline:true,width:"100%", height:"100%", inline:true, href:"#paypal_result",transition:"fade"});
});
</script>
<?php endif;?>
<?php if(isset($bankSubmitted)):?>
<script>
$(document).ready(function(){
	$.fn.colorbox({inline:true,width:"100%", height:"100%", inline:true, href:"#bank_result",transition:"fade"});
});
</script>
<?php endif;?>    
<script>
// validate exchange
var validate=false;

//đầu số 8x61
var x86 = '<?=$x86?>';
var x87 = '<?=$x87?>';
var logged = '<?=$logged?>';

function sms8x(x) {
    if (logged!='1') {
        alert("Xin vui lòng đăng nhập để nạp tiền");
        return;
    }
    if(x==6) {
        $('#dauso8x').html(x86);
        $('#dauso8x-click').attr('href','sms:' + x86);
    }
    if(x==7) {
        $('#dauso8x').html(x87);
        $('#dauso8x-click').attr('href','sms:' + x87);
    }
    $.fn.colorbox({width:"100%", height: "100%", inline:true, href:"#helper_sms"});
}

function sendCard() {
    if (logged!='1') {
        alert("Xin vui lòng đăng nhập để nạp tiền");
        return;
    }
    $.fn.colorbox({width:"100%", height: "100%", inline:true, href:"#helper_card"});
}

function paypal() {
    if (logged!='1') {
        alert("Xin vui lòng đăng nhập để nạp tiền");
        return;
    }
    $.fn.colorbox({width:"100%", height: "100%", inline:true, href:"#helper_paypal"});
}

function checkCard() {
	if($('#card_code').val() == ''){ alert('Vui lòng nhập mã thẻ'); return false; }
    var card_code = $('input[name=card_code]').val();
    var card_type = $('select[name=card_type]').val();
    return true;
}
</script>
    
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
<div class="sendSMS">
    <ul>
        <?php if($enableSms->value):?>
      <li >
          <a href="#" class="menuheader expandable">
              <span class="shadown">
                  <img alt="list" src="<?=base_url()?>images/button/sms.png" />
              </span> 
              <span class="name">Nạp qua tin nhắn SMS</span>
              <span class="arrow"></span></a>
          <div class="smsHelp"><a href="#" id="helper_sendSMS">?</a></div>
          <div class="categoryitems">
              <!--sms-->
              <div id='smsbox'>
                <div class="drop sms">
                  <div class="title-bx">
                    <h1>Nạp bằng SMS</h1>
                  </div>
                      <div class="content-bx">
                    <div class="listsms">
                      <ul>
                        <li>
                          <p class="comm"><strong>Nạp <?=$smss['sms6']?> <span class="tymRed">♥</span></strong> <span class="info_name">(phí 10.000 VNĐ)</span> </p>
                          <p class="des_comm"><span class="send_comm" style="width:50px;">Soạn tin</span><span class="name_comm">APP <?=$user->username?></span><br /><span class="send_comm" style="width:50px;">gửi</span> <span class="name_comm"><?=$x86?></span><a href="sms:<?=$x86?>">Nạp</a></p>
                        </li>
                        <li style="font-size:13px;padding:5px 9px !important;"><span>Bạn hãy giữ tay vào tên tài khoản để Copy</span></li>
                        <li>
                          <p class="comm"><strong>Nạp <?=$smss['sms7']?> <span class="tymRed">♥</span></strong> <span class="info_name">(phí 15.000 VNĐ)</span> </p>
                          <p class="des_comm"><span class="send_comm" style="width:50px;">Soạn tin</span><span class="name_comm">APP <?=$user->username?></span><br /><span class="send_comm" style="width:50px;">gửi</span> <span class="name_comm"><?=$x87?></span><a href="sms:<?=$x87?>">Nạp</a></p>
                        </li>
                      </ul>
                    </div>
                    <div class="buttonPanel"><a href="#" class="white button w100" onclick="ddaccordion.collapseall('expandable'); return false">Hủy bỏ</a> </div>
                  </div>
                </div>
              </div>
          </div>
      </li>
      <?php endif; //end enable_sms?>
      <li >
          <a href="#" class="menuheader expandable"><span class="shadown"><img alt="list" src="<?=base_url()?>images/button/card.png" /></span> <span class="name">Nạp bằng thẻ cào Mobi - Vina</span><span class="arrow"></span></a>
          <div class="smsHelp"><a href="#" id="helper_sendCard">?</a></div>
          <div class="categoryitems">
              <!--card-->
              <div id='cardbox'>
              <form action="<?php echo site_url('home/tym')?>" method="post" onsubmit="return checkCard();">
                <div class="drop card-line">
                  <div class="title-bx">
                    <h1>Nạp bằng thẻ cào MOBI-VINA</h1>
                  </div>
                  <div class="content-bx">
                    <ul class="formDrop">

                      <li class="smallfield"> <span class="name">Loại thẻ:</span>
                        <select name="card_type" id="card_type" >
                              <option value="VINA">Vinaphone</option>
                              <option value="MOBI">Mobiphone</option>
                            </select>
                      </li>
                      <li class="smallfield"> <span class="name">Mênh giá:</span>
                        <select name="card_valuex" id="card_valuex" >
                              <option value="1">10.000 VNĐ → <?=$cards['card10']?> ♥ đỏ</option>
                              <option value="1">20.000 VNĐ → <?=$cards['card20']?> ♥ đỏ</option>
                              <option value="1">50.000 VNĐ → <?=$cards['card50']?> ♥ đỏ</option>
                              <option value="1">100.000 VNĐ → <?=$cards['card100']?> ♥ đỏ</option>
                              <option value="1">200.000 VNĐ → <?=$cards['card200']?> ♥ đỏ</option>
                              <option value="1">300.000 VNĐ → <?=$cards['card300']?> ♥ đỏ</option>
                              <option value="1">500.000 VNĐ → <?=$cards['card500']?> ♥ đỏ</option>
                            </select>
                      </li>
                      <li class="smallfield"> <span class="name">Mã thẻ:</span>
                        <input name="card_code" id="card_code" type="text"  />
                      </li>
                      <li class="smallfield"> <strong>Chú ý:</strong> Mã thẻ là dãy số liền nhau</li>
                    </ul>
                    <div class="buttonPanel"> 
                        <input type="submit" value="Nạp thẻ" name="submitCard" class="white button w50" />
                        <a href="#" class="white button w50" onclick="ddaccordion.collapseall('expandable'); return false">Hủy bỏ</a>
                    </div>
                  </div>
                </div>
              </form>  
              </div>
          </div>
      </li>
      <li >
          <a href="#" class="menuheader expandable"><span class="shadown"><img alt="list" src="<?=base_url()?>images/button/smartlink.png" /></span> <span class="name">Nạp bằng chuyển khoản ATM</span><span class="arrow"></span></a>
          <div class="smsHelp"><a href="#" id="helper_sendSmartlink">?</a></div>
          <div class="categoryitems">
              <!-- nạp bằng ATM box -->
              <div id='smartlinkbox'>
                <form action="<?php echo site_url('home/tym')?>" method="post">
                <div class="drop smartlink-card">
                  <div class="title-bx">
                    <h1>Nạp tiền bằng chuyển khoản</h1>
                  </div>
                      <div class="content-bx">
                    <ul class="formDrop">
                          <li class="smallfield"> <span class="name">Số tiền:</span>
                        <select name="bank_amount" id="bank_amount" style="color:white;">
                              <option value="50000">50.000 VNĐ → <?=$banks['bank50']?> ♥ đỏ</option>
                              <option value="100000">100.000 VNĐ → <?=$banks['bank100']?> ♥ đỏ</option>
                              <option value="200000">200.000 VNĐ → <?=$banks['bank200']?> ♥ đỏ</option>
                              <option value="500000">500.000 VNĐ → <?=$banks['bank500']?> ♥ đỏ</option>
                              <option value="1000000">1000.000 VNĐ → <?=$banks['bank1000']?> ♥ đỏ</option>
                              <option value="20000000">2000.000 VNĐ → <?=$banks['bank2000']?> ♥ đỏ</option>
                        </select>
                      </li>
                        </ul>
                    <div class="buttonPanel"> 
                        <input type="submit" value="Tiếp tục" name="submitBank" class="white button w50" />
                        <a href="#" class="white button w50" onclick="ddaccordion.collapseall('expandable'); return false">Hủy bỏ</a>
                    </div>      
                  </div>
                </div>
                </form>
              </div>
              <!-- /nạp bằng ATM box -->
          </div>
      </li>
      <li >
          <a href="#" class="menuheader expandable"><span class="shadown"><img alt="list" src="<?=base_url()?>images/button/paypal.png" /></span> <span class="name">Nạp bằng tài khoản Paypal</span><span class="arrow"></span></a>
          <div class="smsHelp"><a href="#" id="helper_sendPaypal">?</a></div>
          <div class="categoryitems">
              <!---->
              <div id='paypalbox'>
              <form action="<?php echo site_url('home/tym')?>" method="post" onsubmit="return checkPaypal();">
                <div class="drop paypal-card">
                  <div class="title-bx">
                    <h1>Nạp tym bằng Paypal</h1>
                  </div>
                  <div class="content-bx">
                    <ul class="formDrop">
                          <li class="smallfield"> <span class="name">Số tiền:</span>
                        <select name="paypal_amount" id="paypal_amount" style="color:white;">
                          <option value="5">5$ → <?=$paypals['paypal5']?> ♥ đỏ</option>
                          <option value="10">10$ → <?=$paypals['paypal10']?> ♥ đỏ</option>
                          <option value="50">50$ → <?=$paypals['paypal50']?> ♥ đỏ</option>
                        </select>
                      </li>
                    </ul>
                    <div class="buttonPanel">
                        <input type="submit" value="Tiếp tục" name="submitPaypal" class="white button w50"/>
                        <a href="#" class="white button w50" onclick="ddaccordion.collapseall('expandable'); return false">Hủy bỏ</a>
                    </div>
                  </div>
                </div>
              </form>    
              </div>
              <!----> 
          </div>
      </li>
    </ul>
  </div>

    </div>

<!--Popup-->
<div style='display:none'> 
  
  <!-- kết quả nạp thẻ -->
  <div id="card_result">
    <div class="dialog card-line">
      <div class="title-bx">
        <h1>Nạp bằng thẻ cào MOBI-VINA</h1>
      </div>
      <div class="content-bx">
        <ul class="formDialog">
              
          <li class="smallfield">
            <p align="center">
                <?php if(isset($card_error)):?>
                <?=$card_error?>
                <?php endif;?>
                <?php if(isset($card_success)):?>
                    <?=$card_success?>
                <?php endif;?>  
            </p>
          </li>
          
        </ul>
      </div>
    </div>
  </div>
  <!-- /kết quả nạp thẻ -->

  <!-- kết quả paypal -->
  <div id="paypal_result">
      <div class="dialog card-line">
      <div class="title-bx">
        <h1>Nạp bằng Paypal</h1>
      </div>
      <div class="content-bx">
        <ul class="formDialog">
              
          <li class="formOther">
            <p align="center" class="formOther">
                <?php if(isset($paypalResult)):?>
                    <center><?=$paypalResult?></center>
                <?php endif;?>
            </p>
          </li>
          
        </ul>
      </div>
    </div>
  </div>
  <!-- /kết quả paypal -->
  
  <!-- kết quả bank -->
  <div id="bank_result">
      <div class="dialog card-line">
      <div class="title-bx">
        <h1>Nạp bằng tài khoản ngân hàng</h1>
      </div>
      <div class="content-bx">
        <ul class="formDialog">
              
          <li class="smallfield">
                <?php if(isset($bankResult)):?>
                    <center>
                        <a href="<?=$bankResult?>" style="border: 0px #CCC solid!important;">
                            <img src="<?=base_url()?>images/thanh_toan.png" style="border: 0px #CCC solid!important;" />
                        </a>         
                        <br />
                        <img src="<?=base_url()?>images/bank.png" />
                    </center>
                <?php endif;?>
          </li>
          
        </ul>
      </div>
    </div>
  </div>
  <!-- /kết quả bank -->
  
  
      
  <!--help-->
  <div id='smsbox-help'>
        <div class="dialog sms">
              <div class="title-bx">
            <h1>Hỗ trợ SMS</h1>
          </div>
              <div class="content-bx">
            <div class="listsms">
              <ul>
                <li>
                  <p ><strong>Cú pháp nạp tiền:</strong>: APP username gửi tới <?=$x87?></span> </p>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div id='cardbox-help'>
        <div class="dialog sms">
              <div class="title-bx">
            <h1>Hỗ trợ thẻ cào nhà mạng</h1>
          </div>
              <div class="content-bx">
            <div class="listsms">
              <ul>
                <li>
                  <p ><strong>Dùng thẻ cào của nhà mạng VINAPHONE và MOBIFONE</strong> </p>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div id='smartlinkbox-help'>
        <div class="dialog sms">
              <div class="title-bx">
            <h1>Hỗ trợ Smartlink</h1>
          </div>
              <div class="content-bx">
            <div class="listsms">
              <ul>
                <li>
                  <p ><strong>Nạp bằng thẻ ATM của các ngân hàng được hỗ trợ.Tài khoản ngân hàng phải hỗ trợ Internet Banking</strong></p>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div id='paypalbox-help'>
        <div class="dialog sms">
              <div class="title-bx">
            <h1>Hỗ trợ Paypal</h1>
          </div>
              <div class="content-bx">
            <div class="listsms">
              <ul>
                <li>
                  <p ><strong>Nạp bằng tài khoản Paypal</strong> </p>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
</div>
<!--End Popup-->
</body>
</html>
