<table id="screenshots_sc">
			
</table>
<input type="hidden" name="num_morescreenshot" id="num_morescreenshot" value="0" />
	</div>
	<script type="text/javascript">
	var curSc = 0;
	function addScreenshot()
	{
		var txt = '<tr><td>';
		txt += '<input type="file" name="upload_sc' + curSc + '" /></td></tr>';
		$("#screenshots_sc").append(txt);	
		curSc ++;
		$("#num_morescreenshot").val(curSc);
	}	

	function removeScreenshot()
	{
		if(curSc>0) {
			$("#screenshots_sc tbody>tr:last").remove();
			curSc--;
			$("#num_morescreenshot").val(curSc);
		}	
	}
</script>