<script>
function genGiftcode() {
    var phone = $('input[name=phone]').val();
    var regex = /^[0-9]+/;
    var captcha = $('input[name=captcha]').val();
    if(captcha == '') {
        alert('Vui lòng nhập ảnh xác nhận');
        return;
    }
    if(!regex.test(phone)) {
        alert('Vui lòng nhập đúng định dạng điện thoại (VD: 0978239123)');
        return;
    }
    $.ajax({
        url: "<?=site_url('home/genGiftcode')?>",
        data: "phone=" + phone + "&captcha=" + captcha + "&event_id=" + <?=$event->event_id?>,
        type: "POST",
        beforeSend: function(){},
        success: function(data) {
            if(data == 0) {
                alert('Fake request');
                return;
            } else if(data == 1) {
                alert('Vui lòng đăng nhập');
                return;
            } else if(data == 2) {
                alert('Mã xác nhận không đúng');
                return;
            } else if(data == 3) {
                alert('SDT ' + phone + ' đã được kích hoạt trên AppstoreVN nên không được tham gia vào chương trình này. Bạn vui lòng nhập một SDT khác.');
                return;
            } else if(data == 4) {
                alert('TK của bạn chưa kích hoạt bằng SDT nên không tạo được giftcode. Vui lòng soạn tin APP KH tênTK gửi tới 8161 để kích hoạt TK');
                return;
            } else if(data == 5) {
                alert('Vui lòng nhập đúng định dạng điện thoại (VD: 0978239123)');
                return;
            } else {
                var result = data.split('@@');
                $('#codehere').html(result[0]);
                $('#step2').show('slow');
                $('#step3').show('slow');
                //thay captcha
                $('.Captcha').find('img').attr('src', result[1]);
                $('input[name=captcha]').val('');
                $('input[name=phone]').val('');
                $('#phone-s1').html(phone);
                $('#phone-s2').html(phone);
                $('#phone-s3').html(phone);
            }
        }
    });
}
</script>
<div class="giftCode">
        <ul>
            <li>
                <p class="titleStep"><strong>Bước 1:</strong> Nhập SĐT của người bạn muốn giới thiệu để tạo giftcode. Lưu ý SĐT này phải chưa từng được kích hoạt trên AppstoreVN lần nào</p>
            <input name="phone" type="text" class="ipSDT" />
            <p class="noteStep">SĐT có dạng 09xxx..., 01xxx...</p>
            <p class="Captcha">
                    <span class="tlCaptcha">Nhập mã an toàn</span>&nbsp;
                <?=$captcha['image']?>
<input name="captcha" type="text" class="ipCaptcha" />
            </p>
            <p><input name="create" onclick="genGiftcode();" type="submit" value="Tạo giftcode" /></p>

        </li>
        <li id="step2" style="display:none;">
            <p class="titleStep"><strong>Bước 2:</strong> Gửi mã sau tới SĐT <b id="phone-s1"></b></p>
            <p class="createdCode" id="codehere"></p>
            <p class="noteStep">Nhấn vào text và giữ 2 giây để COPY mã giftcode trên</p>

        </li>
        <li id="step3" style="display:none;">
        <p class="titleStep"><strong>Bước 3:</strong>  Chủ của SĐT <b id="phone-s2"></b> cần tạo 1 tài khoản trên AppstoreVN và kích hoạt bằng đúng số điện thoại này. Sau đó vào mục Hộp quà để nhập giftcode bên trên.<br/><br/>

            <strong>Sau khi kích hoạt Giftcode:</strong><br/>
<?php
    $color = array(
        't1' => 'Red',
        't2' => 'Purple',
        't3' => 'Green',
        't4' => 'Yellow'
    );
?>
Phần thưởng cho bạn: <?=$sender_tym_value->value?> <b class="tym<?=$color[$sender_tym_type->value]?>">♥</b><br/>
Phần thưởng cho SDT <b id="phone-s3"></b>: <?=$receiver_tym_value->value?> <b class="tym<?=$color[$receiver_tym_type->value]?>">♥</b>
</p>
        </li>
    </ul>
</div>