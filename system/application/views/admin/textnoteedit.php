<div id="content" class="container_16 clearfix">
    <?php
		if(isset($success)) {
	?>
	<p class="success"><?=$success?></p>
	<?php }?>
    <style>
        #editnote{width:100%;}
        #input-text{width:600px;}
    </style>
    <form action="<?php echo site_url('admin/textnote/edit/' . $textnote->id . '/' . $ckeditor) ?>" method="post">
    <table id="editnote">
        <tr>
            <td>Key</td>
            <td>
                <input type="text" id="input-text" value="<?=$textnote->key?>" disabled="disabled" />
            </td>
        </tr>
        <tr>
            <td>Group</td>
            <td>
                <input type="text" id="input-text" value="<?=$textnote->group?>" disabled="disabled" />
            </td>
        </tr>
        <tr>
            <td>Comment</td>
            <td>
                <input type="text" id="input-text" value="<?=$textnote->comment?>" name="comment" />
            </td>
        </tr>
        <tr valign="top">
            <td width="40%">Value</td>
            <td>             
                <textarea id="input-text" cols="60" rows="5" name="value"><?=$textnote->value?></textarea>
            </td>
        </tr>
        <tr>
            <td>Hướng dẫn</td>
            <td style="color:green;">
                <p>Dùng {TYM} sẽ được thay bằng số tym người dùng được cộng vào</p>
                <p>Dùng {USERNAME} sẽ được thay thế bằng tên tài khoản người dùng</p>
                <p>Dùng {PASSWORD} sẽ được thay thế bằng mật khẩu mới của người người dùng</p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="update" value="Cập nhật" />
                <input type="reset" value="Hủy bỏ" />
            </td>
        </tr>
    </table>
    </form>
</div>

