<script type="text/javascript" src="<?=base_url()?>js/countdown/jquery.countdown.js"></script>

<script>
    var page = 0;
    function showMore() {
        $('#moregift').hide();
        $('#loader').show();
        page++;
        $.ajax({
            url: "<?=site_url('home/moreEvents/')?>/" + page + "/<?=$filter?>",
            success: function(data){
                window.location.hash = '#' + page;
                $('#newitem').append(data);
                $('#loader').hide();
                $('#moregift').show();
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
                alert('Giftcode không tồn tại');
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
                //data = type@@success_text
                alert(result[1]);
                $('input[name=giftcode]').val('');
                $.fn.colorbox.close();
                return;
            }
        }
    });
}
</script>

<div class="scrolltop">
	<marquee behavior="scroll">
	<?php if($headertext) echo $headertext->code?>
	</marquee> 
</div>
<div class="giftBox">
<div class="title_app">
  <div class="pathbar path">
    <div class="leftpath"> 
        <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a>
        <a href="<?=site_url('home/eventlist')?>">Sự kiện</a>
    </div>
    <div class="rightpath">
        <a href="#bottompage" name="toppage"><img src="<?=base_url()?>images/pagebottom.png"></a>
    </div>
  </div>
</div>
  <div class="sendCode">
        <input name="giftcode" type="text" placeholder="Nhập mã giftcode"/><input name="Xác nhận" onclick="chargeGiftcode();" type="submit" value="Xác nhận Giftcode" />
    </div>  
  <ul class="listGift">
    <?=$events?>
    <li id="newitem"></li>
    <li>
        <div id="loader" style="display:none; text-align:center; margin-top:20px;"><img src="<?=base_url()?>/images/loading.gif"/></div>
        <a id="moregift" href="javascript:showMore();" style="text-decoration:none;"><span class="more">Xem tiếp...</span></a>
    </li>
  </ul>
<div class="title_app">
  <div class="pathbar path">
    <div class="leftpath"> 
        <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a>
        <a href="<?=site_url('home/eventlist')?>">Sự kiện</a>
    </div>
    <div class="rightpath">
        <a href="#toppage" name="bottompage"><img src="<?=base_url()?>images/pagetop.png"></a>
    </div>
  </div>
</div>    
</div>