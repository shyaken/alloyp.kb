<div id="tabmenu">
	<ul class="individual">
        <li><a href="<?=site_url('home/topDownload')?>">Tải nhiều</a></li>
		<li><a href="<?=site_url('home/newest')?>">Mới nhất</a></li>
        <li><a href="<?=site_url('home/promotion')?>">Khuyến mãi</a></li>
	</ul>
</div>
    
<div class="searchbox">
	<div class="searchbox-in">
        <form action="<?=site_url('home/searchApp')?>" method="post" id="formsearch">
			<fieldset><input name="keyword" id="search" placeholder="Nhập từ khóa để tìm kiếm" type="text" />
			<input id="submit" type="submit" value="Tìm kiếm" /></fieldset>
		</form>
	</div>
</div>

<div class="scrolltop">
	<marquee behavior="scroll" scrollamount="5">
	<?php if($headertext) echo $headertext->code?>
	</marquee> 
</div>

<div id="content">
    <div class="title_app">
      <div class="pathbar path">
        <div class="leftpath"> 
            <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a>
        </div>
        <div class="rightpath">
            <a href="#bottompage" name="toppage"><img src="<?=base_url()?>images/pagebottom.png"></a>
        </div>
      </div>
    </div>
	<ul class="pageitem">
        <?php 
            if ($categories != false) {
                foreach ($categories as $category) {
                    if ($category->publish==1) {
                        if(file_exists($category->image) || file_exists('.'.$category->image)) {
                            $categoryThumb = $category->image;
                        } else {
                            $defaultThums = array(
                                'http://appstore.vn/a/' => 'images/android-default.png',
                                'http://appstore.vn/b/' => 'images/bb-default.png',
                                'http://appstore.vn/c/' => 'images/comic-default.png',
                                'http://appstore.vn/e/' => 'images/ebook-default.png',
                                'http://appstore.vn/f/' => 'images/film-default.png',
                                'http://appstore.vn/i/' => 'images/ios-default.png',
                            );
                            if(!array_key_exists(base_url(), $defaultThums)) {
                                $defaultThums[base_url()] = 'images/ios-default.png';
                            }
                            $categoryThumb = $defaultThums[base_url()];
                            //$categoryThumb = $category->image;
                        }
        ?>

        <li class="menu">
        <a href="<?=site_url('home/category/'.$category->category_id)?>">
        <span class="shadown">
		<img alt="list" src="<?=base_url().$categoryThumb?>" />
        </span>
        <span class="name"><?=$category->category_name?></span>
        <span class="comment">
        <?php
            $ribbon = "";
            $text = "";
            if ($newApps[$category->category_id]!=0) {
                $ribbon = "ribbon_new";
                $text = $newApps[$category->category_id];
            //echo $newApps[$category->category_id];
        } ?>
        </span><span class="<?=$ribbon?>" style="color: white; padding-top: 10px;"><b><?=$text?></b></span></a>
        </li>
        <?php }}} ?>
	</ul>
    <div class="pathbar path">
        <div class="leftpath"> 
            <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a>
        </div>
        <div class="rightpath">
            <a href="#toppage" name="bottompage"><img src="<?=base_url()?>images/pagetop.png"></a>
        </div>
    </div>
</div>
