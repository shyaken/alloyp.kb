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
<script type="text/javascript" src="<?=base_url()?>js/jquery-1.4.2.min.js"></script>

<!--Popup-->
<script type="text/javascript" src="<?=base_url()?>js/popup/ddaccordion.js"></script>
<script src="<?=base_url()?>js/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>
<link href="<?=base_url()?>js/colorbox/css/colorbox.css" media="screen" rel="stylesheet" type="text/css">
<script>
    $(document).ready(function(){
        //Examples of how to assign the ColorBox event to elements

        $("#helper_Red").colorbox({inline:true, width:"100%",height:"100%",href:"#helpbox",transition:"fade"});
        $("#helper_Purple").colorbox({inline:true, width:"100%",height:"100%",href:"#helpbox",transition:"fade"});
        $("#helper_Green").colorbox({inline:true, width:"100%",height:"100%",href:"#helpbox",transition:"fade"});
        $("#helper_film1").colorbox({inline:true, width:"100%",height:"100%",href:"#helpbox",transition:"fade"});
        $("#helper_film2").colorbox({inline:true, width:"100%",height:"100%",href:"#helpbox",transition:"fade"});
        $("#helper_film3").colorbox({inline:true, width:"100%",height:"100%",height:"100%",href:"#helpbox",transition:"fade"});
        $("#registerid-film").colorbox({inline:true, width:"100%",height:"100%",href:"#helpbox-film",transition:"fade"});
        $("#helper_giftcode").colorbox({inline:true, width:"100%",height:"100%",href:"#helpbox",transition:"fade"});
        $("#gift_sms").colorbox({inline:true, width:"100%", height:"100%",href:"#giftcodebox",transition:"fade"});
        $("#tym_sms").colorbox({inline:true, width:"100%",height:"100%",href:"#tymbox",transition:"fade"});
        $("#tym_sms1").colorbox({inline:true, width:"100%",height:"100%",href:"#tymbox",transition:"fade"});
        
    });
</script>
    
<script>
$(document).ready(function(){
    $('.tymx').css('color', 'purple');
});
// validate exchange
var validate=false;
var tym_type = 't2';
var currentT1 = <?=$user->t1?>;
var rate1_2 = <?=$rate['rate1_2']?>;
var rate1_3 = <?=$rate['rate1_3']?>;
var rate1_4 = <?=$rate['rate1_4']?>;
var rate1_x = rate1_2;

function calCurrentTx() {
    var numberTx = $('input[name=number_tx]').val();
    if(numberTx <= 0) {
        alert('Vui lòng nhập số nguyên tym cần nhận được');
        $('input[name=number_tx]').val(0);
        return;
    }
    if(tym_type == 't2') {
        rate1_x = rate1_2;
    }
    if(tym_type == 't3') {
        rate1_x = rate1_3;
    }
    if(tym_type == 't4') {
        rate1_x = rate1_4;
    }
    var exchangeT1 = numberTx / rate1_x;
    newT1 = currentT1 - exchangeT1;
    if (newT1 < 0) {
        validate = false;
        alert("Số tym đỏ của bạn hiện tại không đủ để quy đổi");
    } else {
        validate = true;
        $('input[name=new_t1]').val(newT1);
    }
}

function exchange() {
    if (!validate) {
        alert("Số tym đỏ của bạn hiện tại không đủ để quy đổi");
        return;
    }
    var tymType = tym_type;
    var answer = confirm('Bạn chắc chắn muốn quy đổi?');
    if (answer) {
    var numberTx = $('input[name=number_tx]').val();
    var rate1_2 = <?=$rate['rate1_2']?>;
    var rate1_3 = <?=$rate['rate1_3']?>;
    var rate1_4 = <?=$rate['rate1_4']?>;
    var rate1_x = rate1_2;
    if(tym_type == 't2') {
        rate1_x = rate1_2;
    }
    if(tym_type == 't3') {
        rate1_x = rate1_3;
    }
    if(tym_type == 't4') {
        rate1_x = rate1_4;
    }
    var exchangeT1 = numberTx / rate1_x;
        
        $.ajax({
			url: "<?php echo site_url('home/exchange');?>/"+tymType+"/"+exchangeT1,
			type: "POST",
			success: function(response) {
				if (response=='1') {
                    alert('Bạn đã quy đổi thành công :D');
                    window.location.reload(true);
                } else if(response=='-1') {
                    alert('Vui lòng không nhập hack nhá!!!');
                } else {
                    alert('Bạn không đủ tym đỏ để quy đổi');
                }
			}
		});
    }
}

function changeTymType(obj) {
    tym_type = obj.options[obj.selectedIndex].value;
    var color = 'purple';
    if(tym_type == 't3') {
        color = 'green';
    }
    if(tym_type == 't4') {
        color = 'yellow';
    }
    $('.tymx').css('color', color);
}

function registerPackage(package_type, xday) {
    var register = window.confirm('Bạn có muốn gia hạn thêm ' + xday + ' ngày ???');
        if(!register) return;
    var package_price = <?=$packages['2']?>;
    if(package_type == 'p7') {
        package_price = <?=$packages['7']?>;
    } 
    if(package_type == 'p15') {
        package_price = <?=$packages['15']?>;
    } 
    if(package_type == '30') {
        package_price = <?=$packages['30']?>;
    }
    var currentT2 = <?=$user->t2?>;
    var currentT1 = <?=$user->t1?>;
    if(currentT2 < package_price && currentT1 < 19000) {
        var confirm = window.confirm('Bạn không đủ tym tím, đến trang nạp tài khoản');
        if(confirm) {
            window.location.href = '<?php echo site_url('home/tym')?>';
        } else {
            return;
        }
    } else {
        $.ajax({
           url: "<?php echo site_url('home/registerPackage') ?>/" + package_type,
           success: function(data) {
               if(data == 'login') {
                   alert('Bạn chưa đăng nhập');
               } else {
                   alert(data);
                   window.location.href = '<?php echo site_url('home/account')?>';
               }
           }
        });
    }          
}

function chargeGiftcode() {
    var code = $('input[name=giftcode]').val();
    if(code == '') {
        alert('Vui lòng nhập mã giftcode');
        return;
    }
    $.ajax({
        url: "<?=site_url('home/chargeGiftcode')?>",
        data: "code=" + code,
        type: "POST",
        beforeSend: function(){},
        success: function(data) {
            if(data == 0) {
                alert('Yêu cầu không hợp lệ');
                return;
            } else if(data == 1) {
                alert('Vui lòng đăng nhập');
                return;
            } else if(data == 2) {
                alert('Giftcode bạn vừa nhập không hợp lệ. Lý do: 1) Giftcode không tồn tại. 2) TK của bạn chưa được kích hoạt bằng SĐT. 3) SDT của bạn không đúng với giftcode này.');
                return;
            } else if(data == 3) {
                alert('Giftcode đã sử dụng');
                return;
            } else if(data == 4) {
                alert('Giftcode đã hết hạn');
                return;
            } else if(data == 5) {
                alert('Số phone ko tương ứng');
                return;
            } else {
                var result = data.split('@@');
                var type = result[0];
                if(type == 'app') {
                    //data = type@@link@username_sender
                    alert('Bạn được TK ' + result[2] + ' tặng link ' + result[1] + ' để tải nội dung miễn phí trong đó. Hãy mở link để tải');
                    window.location.href = result[1];
                }
                alert(result[1]);
                $('input[name=giftcode]').val('');
                $('#giftcodeboxid').hide();
                $.fn.colorbox.close();
                return;
            }
        }
    });
}
</script>    
</head>

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
   <div class="cardTYM"> 
    <ul>
          <li>
              <div class="left"> <span class="tymRed">♥: <?=$user->t1?> </span></div>
              <div class="right"><a href="<?=site_url('home/tym')?>" >Nạp TYM</a><a href="#" id="helper_Red">?</a></div>
          </li>
          <li>
              <div class="left"> <span class="tymPurple">♥: <?=$user->t2?> </span></div>
              <div class="right"><a href="#" class="menuheader expandable">Đổi <span class="tymRed">♥</span> → <span class="tymPurple">♥</span></a><a href="#" id="helper_Purple">?</a></div>
              <div class="categoryitems">
                  <div id='tymbox'>
                    <div class="drop card-line">
                      <div class="title-bx">
                        <h1>Quy đổi tym</h1>
                      </div>
                          <div class="content-bx">
                        <ul class="formDrop">

                              <li class="smallfield"> <span class="name"><span class="tymRed">♥</span> hiện thời:</span>
                            <input placeholder="" type="text" value="<?=$user->t1?>" disabled="disabled" />
                          </li>
                              <li class="smallfield"> <span class="name"><span class="tymx">♥</span> quy đổi:</span>
                              <select name="tym_type" id="card_type" onchange="changeTymType(this);">
                                  <option value="t2">Tym Tím</option>
                                  <option value="t3">Tym Xanh</option>
                                  <option value="t4">Tym Vàng</option>
                                </select>


                          </li>
                          <li class="smallfield"> <span class="name"><span class="tymx">♥</span> nhận được:</span>
                            <input type="text" name="number_tx" value="0" onchange="calCurrentTx();"/>
                          </li>
                           <li class="smallfield"> <span class="name"><span class="tymRed">♥</span> còn lại:</span>
                            <input type="text" name="new_t1" disabled value="0"/>
                            </ul>
                        <div class="buttonPanel"> 
                            <a href="javascript:exchange();" class="white button w50">Xác nhận</a> 
                            <a href="#" class="white button w50" onclick="ddaccordion.collapseall('expandable'); return false">Hủy bỏ</a>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
          </li>
          <li>
              <div class="left"> <span class="tymGreen">♥: <?=$user->t3?> </span></div>
              <div class="right"><a href="#" class="menuheader expandable">Đổi <span class="tymRed">♥</span> → <span class="tymGreen">♥</span></a><a href="#" id="helper_Green">?</a></div>
              <div class="categoryitems">
                  <div id='tymbox1'>
                    <div class="drop card-line">
                      <div class="title-bx">
                        <h1>Quy đổi tym</h1>
                      </div>
                          <div class="content-bx">
                        <ul class="formDrop">

                              <li class="smallfield"> <span class="name"><span class="tymRed">♥</span> hiện thời:</span>
                            <input placeholder="" type="text" value="<?=$user->t1?>" disabled="disabled" />
                          </li>
                              <li class="smallfield"> <span class="name"><span class="tymx">♥</span> quy đổi:</span>
                              <select name="tym_type" id="card_type" onchange="changeTymType(this);">
                                  <option value="t2">Tym Tím</option>
                                  <option value="t3">Tym Xanh</option>
                                  <option value="t4">Tym Vàng</option>
                                </select>


                          </li>
                          <li class="smallfield"> <span class="name"><span class="tymx">♥</span> nhận được:</span>
                            <input type="text" name="number_tx" value="0" onchange="calCurrentTx();"/>
                          </li>
                           <li class="smallfield"> <span class="name"><span class="tymRed">♥</span> còn lại:</span>
                            <input type="text" name="new_t1" disabled value="0"/>
                            </ul>
                        <div class="buttonPanel"> 
                            <a href="javascript:exchange();" class="white button w50">Xác nhận</a>
                            <a href="#" class="white button w50" onclick="ddaccordion.collapseall('expandable'); return false">Hủy bỏ</a>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
          </li>
          <li>
              <div class="left"> <span class="codeGift"><img src="<?=base_url()?>images/logos/hopqua.png"></span></div>
              <div class="right"><a href="#" class="menuheader expandable">Nạp Giftcode</a><a href="#" id="helper_giftcode-dis">?</a></div>
              <div class="categoryitems" id="giftcodeboxid">
                  <!--gift code -->
                  <div id='giftcodebox'>
                    <div class="drop giftcodebox">
                      <div class="title-bx">
                        <h1>Nhập GIFTCODE của bạn</h1>
                      </div>
                      <div class="content-bx">
                        <ul class="formDrop">

                          <li class="smallfield">
                            <input name="giftcode" placeholder="" type="text" value="" style="width:99.6%;" />
                          </li>

                        </ul>
                        <div class="buttonPanel"> 
                            <a href="javascript:chargeGiftcode();" class="white button w50">Xác nhận</a> 
                            <a href="#" class="white button w50" onclick="ddaccordion.collapseall('expandable'); return false">Hủy bỏ</a>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
          </li>
        </ul>
   
  </div>
  <div class="card"> 
    <?php if($package_open): ?>
    <span class="name">Gói xem phim</span>
    <ul>
        <?php if(!$package_expired) {?>
          <li><div class="left"> <span class="title">Hạn sử dụng: </span><span class="point"><?=date('d/m/Y', $userPack->package_expired)?></span></div><div class="right"> <a href="#" id="registerid-film" >Gia hạn</a><a href="#" id="helper_film1-dis">?</a></div></li>
        <?php } else { ?>
          <li><div class="left"> <span class="title">Bạn chưa đăng ký gói</span></div><div class="right"> <a href="#" id="registerid-film" >Gia hạn</a><a href="#" id="helper_film1-dis">?</a></div></li>
        <?php } ?>
    </ul>
    <?php endif?>
    <!--
    <span class="name">Gói tải VIP FShare</span>
    <ul>
          <li><div class="left"> <span class="title">Hạn sử dụng: </span><span class="point">hết hạn</span></div><div class="right"> <a href="javascript:alert('Chức năng đang được xây dựng');" >Gia hạn</a><a href="#" id="helper_film1-dis">?</a></div></li>
         
    </ul>
    <span class="name">Gói tải VIP Megashare</span>
    <ul>
          <li><div class="left"> <span class="title">Bạn chưa đăng ký gói VIP </span></div><div class="right">  <a href="javascript:alert('Chức năng đang được xây dựng');" >Đăng ký</a><a href="#" id="helper_film1">?</a></div></li>
        
    </ul>
    -->
  </div>
</div>

<!--Popup-->
<div style='display:none'>
  <!--help-->
  <div id='helpbox'>
    <div class="dialog sms">
          <div class="title-bx">
        <h1>Tỷ lệ quy đổi hiện tại</h1>
      </div>
          <div class="content-bx">
        <div class="listsms">
          <ul>
            <li>
                <?php $t1_tym = 1/$rate['rate1_2']; ?>
                <p>Hiện tại <?=$t1_tym?> <span class="tymRed">♥</span> → 1 <span class="tymPurple">♥</span></p>
            </li>
            <li>
                <?php $t1_tym = 1/$rate['rate1_3']; ?>
                <p>Hiện tại <?=$t1_tym?> <span class="tymRed">♥</span> → 1 <span class="tymGreen">♥</span></p>
            </li>
          </ul>
            </div>
       
      </div>
    </div>
  </div> 
     
  <!---->
  
  <!--film -->
  <div id='helpbox-film'>
    <div class="dialog giftcodebox">
      <div class="title-bx">
        <h1>Đăng ký/Gia hạn gói xem phim</h1>
      </div>
    <div class="content-bx">
        <ul class="formDialog">
            <li class="smallfield">
            <div class="type-field">
                <ul>
                    <?php foreach($packages as $package => $value): ?>    
                    <li>
                        <a href="javascript:registerPackage('p<?=$package?>','<?=$package?>');" class="white button w100" style="color:green;font-size:14px;">
                            Đăng kí gói <?=$package?> ngày: <?=number_format($value, 0, ',', '.');?> tym <font color="purple"> ♥ </font>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!--End Popup-->
</body>
</html>
