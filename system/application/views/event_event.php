<!--MP3-->
<div style="display:none">
    <div id='mp3box'>
        <div class="dialog mp3box">
          <div class="title-bx">
            <h1>Mở quà</h1>
          </div>
          <div class="content-bx">
         <ul class="formDialog">
              <li class="smallfield"> <div style="text-align:center;">
      <div style="text-align:center;"> <img src="<?=base_url()?>mp3/tet_phao10.gif" style="border:0;" width="250"> </div>
      <div style="text-align:center;">
        <audio id="playerx" src="1" loop preload="auto"></audio>
        <button onclick="document.getElementById('playerx').play()" class="btAudioPlay">Play</button>
        <button onclick="document.getElementById('playerx').pause()" class="btAudio">Pause</button>
      </div>
    </div></li>

        </ul>
      </div>
        </div>
    </div>
    <div id='picbox'>
    <div class="dialog picbox">
      <div class="title-bx">
        <h1>Mở quà</h1>
      </div>
      <div class="content-bx">
        <ul class="formDialog">
          <li class="smallfield">
            <div class="picEvent">
            <img src="pics/pe1.jpg" id="imgsrc" /></div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<script>
function openGiftbox(box_id) {
    $.ajax({
        url: "<?=site_url('home/openGiftbox')?>/" + box_id,
        beforeSend: function() {
            $('#show-gift').hide();
            $('#show-text').show();
        },
        success: function(data) {
            $('#show-gift').show();
            $('#show-text').hide();
           /*
             * 0 - chua dang nhap
             * 1 - trượt lô
             * type@@value
             */
            if(data == 0) {
                alert('Vui lòng đăng nhập');
                eventLogin();
            } else if(data == 2) {
                alert('Bạn không đủ tym để tham gia');
            } else if(data.search('@@') == -1) {
                alert(data);
            } else {
                var result = data.split('@@');
                var type = result[0];
                var value = result[1];
                var first_text = result[2];
                var gift = '';
                if(first_text != '') {
                    gift = first_text + '. ';
                }
                
                if(type == 't1' || type == 't2' || type == 't3' || type == 't4') {
                    var color = new Array();
                        color['t1'] = ' ♥ đỏ';
                        color['t2'] = ' ♥ tím';
                        color['t3'] = ' ♥ xanh';
                        color['t4'] = ' ♥ vàng';
                    gift += 'Chúc mừng bạn đã trúng thưởng ' + value + color[type];
                } else if(type == 'giftcode') {
                    gift += 'Chúc mừng bạn đã trúng thưởng mã giftcode ' + value;
                } else if(type == 'card') {
                    gift += 'Chúc mừng bạn đã trúng thưởng mã thẻ cào ' + value;
                } else if(type == 'mp3') {
                    $('#playerx').attr('src', value);
                    $.fn.colorbox({inline:true,width:"100%", height:"100%", inline:true, href:"#mp3box",transition:"fade"});
                    return;
                } else if(type == 'img') {
                    $('#imgsrc').attr('src', value);
                    $.fn.colorbox({inline:true,width:"100%", height:"100%", inline:true, href:"#picbox",transition:"fade"});
                    return;
                } else {
                    gift += value;
                }
                alert(gift);
                $.fn.colorbox.close();
            }
        }
    });
}

</script>
<ul>
    <?php if($giftboxs):$i=0;foreach($giftboxs as $giftbox):?>
    <?php 
        $gifts = $this->event_model->getGift($giftbox->box_id); 
        if($giftbox->input_tym) {
            $price = $giftbox->input_tym;
            $tymColor = array(
                't1' => 'Red',
                't2' => 'Purple',
                't3' => 'Green',
                't4' => 'Yellow'
            );
            $color = $tymColor[$giftbox->tym_type];
            $price.= ' <font color="'.$color.'">♥</font>';
        } else {
            $price = 'Free';
        }
    ?>
    <li>
    <a href="#"  id="alert_btt<?=$i?>" class="clickGift">
        <div class="tag"><span><?=$price?></span></div>
        <img src="<?=$giftbox->image?>" alt=""/>
        <div class="titleGift"><?=$giftbox->name?></div>
    </a>
    <div class="listGift">
        <?php if($gifts):foreach($gifts as $gift):?>
        <div class="selectGift">
            <img src="<?=$gift->image?>" alt="" />
            <span>SL: <?=$gift->quantity?></span>
        </div>
        <div style="display:none;">
        <!--Alert-->
          <div id='alertbox<?=$i?>'>
            <div class="dialog alertbox">
              <div class="title-bx">
                <h1>Mở quà</h1>
              </div>
              <div class="content-bx" id="show-gift">
               <ul class="formDialog">
                   <?php if($price != 'Free'):?>
                   <li class="smallfield"> Phí mở hộp quà này là <?=$price?>
                   </li>
                   <?php endif?>
                  <li class="smallfield"> Bạn chắc chắn muốn mở hộp quà này?
                  </li>
                </ul>
                <div class="buttonPanel"> <a href="javascript:openGiftbox(<?=$giftbox->box_id?>)" class="white button w50" id="gift_bttss" >Có</a> <a href="javascript:closeColorbox();" class="white button w50">Không</a> </div>
              </div>
                
                <div class="content-bx" id="show-text" style="display:none;text-align:center;">
                    <ul class="formDialog">
                        <li class="smallfield">
                            <img src="<?=base_url()?>images/loading.gif" align="center" />
                        </li>
                    </ul>
                </div>
                    
            </div>
          </div>    
        </div>
        <script>
            $(document).ready(function(){
                $("#alert_btt<?=$i?>").colorbox({inline:true, width:"100%",href:"#alertbox<?=$i?>",transition:"fade"});
            });
        </script>
        <?php $i++;endforeach;endif?>
    </div>
    </li>
    <?php endforeach;endif;?>
</ul>
