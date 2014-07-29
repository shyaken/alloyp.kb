<?php
//log search
//khanhpt - 29-12-2011
//time|store|userid|username|keyword|gender

//deteach store
$store = substr(base_url(), -2, 1);
$logContent = time() . "|" . $store . "|";
if (isset($username) && isset($userid)){
    $logContent .= $userid . "|" . $username . "|";
}
$logContent .= $keyword . "|";
file_put_contents('search.log', $logContent, FILE_APPEND);
?>
<script>
    var page = 0;
    function showMore() {
        $('#showmore').hide();
        $('#loader').show();
        page++;
        $.ajax({
            url: "<?=site_url('home/moreSearchResult/'.$keyword.'/'.$filter)?>/" + page,
            success: function(data){
                window.location.hash = '#' + page;
                $('#newitem').append(data);
                $('#loader').hide();
                $('#showmore').show();
            }
        });
    }

    $(document).ready(function(){
        curPage = window.location.hash;
        page = 0;
        currentPage = curPage.substr(1);
        if (currentPage>0) {
            for (i=1; i<=currentPage; i++) {
                showMore();
            }
        }
    });
</script>
<div class="scrolltop">
	<marquee behavior="scroll">
	<?php if($headertext) echo $headertext->code?>
	</marquee> </div>
<div id="content">
<div class="pathbar path">
    <div class="leftpath"> 
        <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a>
        <a href="<?=site_url('home/searchApp')?>">Tìm kiếm</a>
    </div>
    <div class="rightpath">
        <a href="#bottompage" name="toppage"><img src="<?=base_url()?>images/pagebottom.png"></a>
    </div>
  </div>    
	<div id="appdetail"></div>
    <ul class="pageitem">
        <?php if ($apps!='0') { echo $apps;?>
        
        <li id="newitem"></li>
        <li class="store">
            <div id="loader" style="display: none; text-align: center; margin: 5px;"><img src="<?=base_url()?>/images/loading.gif"/></div>
            <a id="showmore" href="#pageitem"><span class="more" onclick="showMore();">Xem tiếp...</span></a>
        </li>
        <?php }?>
    </ul>
    <div class="pathbar path">
        <div class="leftpath"> 
            <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a>
            <a href="<?=site_url('home/searchApp')?>">Tìm kiếm</a>
        </div>
        <div class="rightpath">
            <a href="#toppage" name="bottompage"><img src="<?=base_url()?>images/pagetop.png"></a>
        </div>
    </div>
        <?php if(!$apps) {?>
        <center style="color:red;">
            Không tìm thấy kết quả phù hợp
        </center>
        <?php } ?>
        <!-- More Games -->
        
</div>
