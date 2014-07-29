<?php if($logs) { foreach($logs as $log) {?>
<li>            	
    <div class="dateEvent">
        <span class="timeOpen">[<?=date('H:i', $log->time)?>]</span>
        <?php if($log->tym_price == 0) {?>
            Bạn mở quà free
            -
            <?php if($log->receive_status == 0) { ?>
            Bạn nhận được một lời chúc may mắn
            <?php 
            } else {
                $type = $log->receive_type;
                switch ($type) {
                    case "t1":
                    case "t2":
                    case "t3":
                    case "t4":
                        $color = array('Red', 'Purple', 'Green', 'Yellow');
                        $i = substr($type,-1,1);
                        $txt = "Bạn trúng ".$log->receive_value." <b class='tym".$color[$i-1]."'>♥</b>";
                        break;
                    case "giftcode":
                        $txt = "Bạn trúng mã giftcode ".$log->receive_value;
                        break;
                    case "card": 
                        $txt = "Bạn trúng mã thẻ cào ".$log->receive_value;
                        break;
                    case "text":
                        $txt = "Nhận được lời chúc ".$log->receive_value;
                        break;
                    default: 
                        $txt = "ha ha ha";
                        break;
                }
                echo $txt;
            } 
            ?>
        <?php 
        } else {
            $type = $log->tym_type;
            $color = array('Red', 'Purple', 'Green', 'Yellow');
            $i = substr($type,-1,1);
            $class = "tym".$color[$i-1];
            $txt = "Bạn mở hộp quà loại ".$log->tym_price." <b class='$class'>♥</b>";
            $txt .= " - ";
            if($log->receive_status == 0) {
                $txt .= "Nhận được một lời chúc may mắn";
            } else {
                $type = $log->receive_type;
                switch ($type) {
                    case "t1":
                    case "t2":
                    case "t3":
                    case "t4":
                        $color = array('Red', 'Purple', 'Green', 'Yellow');
                        $i = substr($type,-1,1);
                        $txt1 = "Bạn trúng ".$log->receive_value." <b class='tym".$color[$i-1]."'>♥</b>";
                        break;
                    case "giftcode":
                        $txt1 = "Bạn trúng mã giftcode ".$log->receive_value;
                        break;
                    case "card": 
                        $txt1 = "Bạn trúng mã thẻ cào ".$log->receive_value;
                        break;
                    case "text":
                        $txt1 = "Nhận được lời chúc ".$log->receive_value;
                        break;
                    default: 
                        $txt1 = "ha ha ha";
                        break;
                }
                $txt .= $txt1;
            }
            echo $txt;
        }?>
    </div>
</li>
<?php }} ?>