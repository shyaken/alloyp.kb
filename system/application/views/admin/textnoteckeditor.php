<div id="content" class="container_16 clearfix">
    <?php
		if(isset($success)) {
	?>
	<p class="success"><?=$success?></p>
	<?php }?>
    <style>
        #editnote{width:100%;}
        #input-text{width:700px;}
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
                <b>Hãy upload ảnh trong bài viết:</b><br />
                <div id="file-uploaded"></div>
                <div id="swfupload-control-2" style="clear:both;">
                <input type="file" id="file_upload" name="file_upload" />
                <a href="javascript:$('#file_upload').uploadifyUpload();">Upload Files</a>
                <textarea id="input-text" cols="60" rows="10" name="value"><?=$textnote->value?></textarea>
                <?php echo $editor->replace('value'); ?>
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
<link href="<?php echo base_url(); ?>js/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo base_url(); ?>js/uploadify/swfobject.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/uploadify/jquery.uploadify.v2.1.4.min.js"></script>

<?php $PHPSESSID = $this->session->userdata('session_id'); ?>
<script type="text/javascript">
    $('#file_upload').uploadify({
        'uploader'    : "<?php echo base_url(); ?>js/uploadify/uploadify.swf",
        'script'      : '<?php echo base_url(); ?>js/uploadify/uploadify.php',
        'cancelImg'   : '<?php echo base_url(); ?>js/uploadify/cancel.png',
        'folder'      : '../../../../../help-images',
        'multi'       : true,
        'onComplete'  : function(event, ID, fileObj, response, data) {
            var thumbImage = '<div style="float:left;margin-right:10px">' 
                             + '<img height="70" src="' + response + '"><br />'
                             + '<a rel="' + response + '" onclick="removeImageUploaded(this)" '
                             + 'href="javascript:void(0)">[Xóa]</a>'
                             +'&nbsp;<a href="javascript:void(0);" rel=\'<img src="' + response +'"/>\' onclick="insertIntoEditor(this);">[Chèn vào bài]</a></div>';
                    $('#file-uploaded').append(thumbImage);
        }
    });
    function removeImageUploaded(element) {
        $(element).parent().remove();
    }
    function insertIntoEditor(element) {
        var oEditor = CKEDITOR.instances['input-text'];
        var value = $(element).attr('rel');

        // Check the active editing mode.
            oEditor.insertHtml( value );
    }

    </script>
