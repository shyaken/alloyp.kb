<script>
var curActive = '<?=$user->active_by?>';
function checkForm() {
    var newActive = $('select[name=active_by]').val();
    var email = $('input[name=email]').val();
    if(curActive == 'inactive' && newActive != 'inactive' && email == '') {
        alert('Vui lòng nhập email');
        return false;
    }
    return true;
}
</script>
<div id="content" class="container_16 clearfix">

	<?php if(isset($success)):?>
		<p class="success"><?=$success?></p>
	<?php endif;?>
    <?php if(isset($error)):?>
		<p class="error"><?=$error?></p>
	<?php endif;?>

	<form name="edituser" action="<?php echo site_url('admin/user/edit/' . $user->user_id) ?>" method="POST" onsubmit="return checkForm();">
	<div class="grid_16">
		<h2>
            Chỉnh sửa user '<?=$user->username?>'
            - <a href="<?php echo site_url('admin/user/downloadLog/' . $user->user_id)?>">Download Logs</a>
            - <a href="<?php echo site_url('admin/user/paymentLog/' . $user->user_id)?>">Payment Logs</a>
                
        </h2>
	</div>
	
	<div class="grid_4"><p>Username:</p></div>
	<div class="grid_12">
		<input type="text" name="username" value="<?=$user->username?>" disabled="disabled" />
		</p>
	</div>
	
	<div class="grid_4"><p>Password:</p></div>
	<div class="grid_12">
		<input type="text" name="password" />
		</p>
	</div>
    
    <?php if($user->phone): ?>
	<div class="grid_4"><p>Phone:</p></div>
	<div class="grid_12">
		<input type="text" name="phone" disabled="disabled" />
		</p>
	</div>    
	<?php endif; ?>
    
	<div class="grid_4"><p>Email:</p></div>
	<div class="grid_12"><p>
		<input type="text" name="email" value="<?=$user->email?>" />
		</p>
	</div>
    
	<div class="grid_4"><p>Active By:</p></div>
	<div class="grid_12"><p>
        <select name="active_by">
            <option value="inactive" <?php if($user->active_by == 'inactive') echo 'selected="selected"';?>>inactive</option>
            <option value="sms" <?php if($user->active_by == 'sms') echo 'selected="selected"';?>>sms</option>
            <option value="email" <?php if($user->active_by == 'email') echo 'selected="selected"';?>>email</option>
        </select>
		</p>
	</div>    
	
	<div class="grid_4"><p>Birthday( yyyy-mm-dd ):</p></div>
	<div class="grid_12"><p>
		<input type="text" name="birthday" value="<?=$user->birthday?>" />
		</p>
	</div>
    
	<div class="grid_4"><p>Tỉnh thành phố:</p></div>
	<div class="grid_12"><p>
<select class="txt" name="city" id="cityselect">
<option value="0">Chưa khai báo</option>
<option value="Hà Nội">Hà Nội</option>
<option value="TP Hồ Chí Minh">TP Hồ Chí Minh</option>
<option value="Thừa thiên Huế">Thừa thiên Huế</option>
<option value="Đà Nẵng">Đà Nẵng</option>
<option value="An Giang">An Giang</option>
<option value="Bà Rịa Vũng Tàu">Bà Rịa Vũng Tàu</option>
<option value="Bắc Kạn">Bắc Kạn</option>
<option value="Bắc Giang">Bắc Giang</option>
<option value="Bạc Liêu">Bạc Liêu</option>
<option value="Bắc Ninh">Bắc Ninh</option>
<option value="Bến Tre">Bến Tre</option>
<option value="Bình Định">Bình Định</option>
<option value="Bình Dương">Bình Dương</option>
<option value="Bình Phước">Bình Phước</option>
<option value="Bình Thuận">Bình Thuận</option>
<option value="Cà Mau">Cà Mau</option>
<option value="Cần Thơ">Cần Thơ</option>
<option value="Cao Bằng">Cao Bằng</option>
<option value="Đắc Nông">Đắc Nông</option>
<option value="Đắc Lắc">Đắc Lắc</option>
<option value="Điện Biên">Điện Biên</option>
<option value="Đồng Nai">Đồng Nai</option>
<option value="Đồng Tháp">Đồng Tháp</option>
<option value="Gia Lai">Gia Lai</option>
<option value="Hà Giang">Hà Giang</option>
<option value="Hà Nam">Hà Nam</option>
<option value="Hà Tây">Hà Tây</option>
<option value="Hà Tĩnh">Hà Tĩnh</option>
<option value="Hải Dương">Hải Dương</option>
<option value="Hải Phòng">Hải Phòng</option>
<option value="Hậu Giang">Hậu Giang</option>
<option value="Hoà Bình">Hoà Bình</option>
<option value="Hưng Yên">Hưng Yên</option>
<option value="Khánh Hoà">Khánh Hoà</option>
<option value="Kiên Giang">Kiên Giang</option>
<option value="Kon Tum">Kon Tum</option>
<option value="Lai Châu">Lai Châu</option>
<option value="Lâm Đồng">Lâm Đồng</option>
<option value="Lạng Sơn">Lạng Sơn</option>
<option value="Lào Cai">Lào Cai</option>
<option value="Long An">Long An</option>
<option value="Nam Định">Nam Định</option>
<option value="Nghệ An">Nghệ An</option>
<option value="Ninh Bình">Ninh Bình</option>
<option value="Ninh Thuận">Ninh Thuận</option>
<option value="Phú Thọ">Phú Thọ</option>
<option value="Phú Yên">Phú Yên</option>
<option value="Quảng Bình">Quảng Bình</option>
<option value="Quảng Nam">Quảng Nam</option>
<option value="Quảng Ngãi">Quảng Ngãi</option>
<option value="Quảng Ninh">Quảng Ninh</option>
<option value="Quảng Trị">Quảng Trị</option>
<option value="Sóc Trăng">Sóc Trăng</option>
<option value="Sơn La">Sơn La</option>
<option value="Tây Ninh">Tây Ninh</option>
<option value="Thái Bình">Thái Bình</option>
<option value="Thái Nguyên">Thái Nguyên</option>
<option value="Thanh Hoá">Thanh Hoá</option>
<option value="Tiền Giang">Tiền Giang</option>
<option value="Trà Vinh">Trà Vinh</option>
<option value="Tuyên Quang">Tuyên Quang</option>
<option value="Vĩnh Long">Vĩnh Long</option>
<option value="Vĩnh Phúc">Vĩnh Phúc</option>
<option value="Yên Bái">Yên Bái</option>
</select>
<script>
var cityselect = document.getElementById('cityselect');

for(var i=0; i < cityselect.options.length; i++){
	if(cityselect.options[i].value == '<?=$user->city?>') cityselect.options[i].selected='selected';
}
</script>		
		</p>
	</div>    
	
	<div class="grid_4"><p>Register date:</p></div>
	<div class="grid_12"><p>
		<?=$user->registered_date?>
		</p>
	</div>
	
	<hr />
	
	<div class="grid_4"><p>Tym 1 ( đỏ ):</p></div>
	<div class="grid_12"><p>
		<input type="text" name="t1" value="<?=$user->t1?>" disabled="disabled" />
		</p>
	</div>
	<div class="grid_4"><p>Tym 2:</p></div>
	<div class="grid_12"><p>
		<input type="text" name="t2" value="<?=$user->t2?>" disabled="disabled" />
		</p>
	</div>
	<div class="grid_4"><p>Tym 3:</p></div>
	<div class="grid_12"><p>
		<input type="text" name="t3" value="<?=$user->t3?>" disabled="disabled" />
		</p>
	</div>
	<div class="grid_4"><p>Tym 4:</p></div>
	<div class="grid_12"><p>
		<input type="text" name="t4" value="<?=$user->t4?>" disabled="disabled" />
		</p>
	</div>
<!--
	<div class="grid_4"><p>Package Type(7 | 14 | 30 | 90):</p></div>
	<div class="grid_12"><p>
		<input type="text" name="package_type" value="<?=$user->package_type?>" />
		</p>
	</div>
	<div class="grid_4"><p>Package Expired(dd/mm/yyyy h:i:s):</p></div>
	<div class="grid_12"><p>
		<?php
            if($user->package_expired) echo date('d/m/Y H:i:s', $user->package_expired);
            else echo "Chưa đăng kí gói";
        ?>
		</p>
	</div>
-->
	
	<div class="grid_4"><p>Package:</p></div>
	<div class="grid_12">
		<p>
			<?php 
				$CI =& get_instance();
				$CI->load->model('user_model');
				$packages = $CI->user_model->getAllUserPack($user->user_id);
				if($packages):foreach($packages as $package):
			?>
				<p>Kho <?=$package->store?> có hạn dùng <?=date('d/m/Y H:i:s', $package->package_expired);?></p>
			<?php 
				endforeach;endif;
				if(!$packages) {
					echo "Chưa đăng kí gói nào";
				}
			?>
		</p>
	</div>
	
	<div class="grid_4"><p>Action:</p></div>
	<div class="grid_12">
		<p>
			<input type="reset" value="reset" />
			<input type="submit" value="update" name="update" />
		</p>
	</div>
	</form>
</div>
