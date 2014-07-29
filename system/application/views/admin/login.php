<html>
<head>
    <title>Admin Control Panel</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
</head>
<body>
<div style="float:none; width: 380px; margin: 100px auto;">
    <form method="post" action="<?php echo site_url('admin/login')?>" id="adminlogin">
        <table>
            <tr>
                <td>
                    Tên đăng nhập
                </td>
                <td>
                    <input type="text" name="username" />
                </td>
            </tr>
            <tr>
                <td>
                Mật khẩu
                </td>
                <td>
                    <input type="password" name="password" />
                </td>
            </tr>
            <tr>
                <td colspan="2">    
                    <input type="submit" value="Login" name="submit" />
                    <?php if(isset($error)){echo '<font color="red">' . $error . '</font>';} ?>
                </td>
            </tr>
        </table>
    </form>    
</div>    
</body>
</html>