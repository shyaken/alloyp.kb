<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <title>
            Đang chuyển hướng ...
        </title>
    </head>
    <body>
        <center>
        <?php if(isset($error)): ?>
            <?=$error?>
        <?php endif ?>
        
        <?php if(isset($success)): ?>
            <?=$success?>
        <?php endif ?>
            ... đang chuyển hướng ...
        </center>
        <script>
            setTimeout('window.location.href="<?php echo base_url()?>home/tym";',3000);
        </script>
    </body>
</html>