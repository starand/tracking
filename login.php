<?
    include_once "common/headers.php";
?>

<center>
<table style='width:100%; height:300px;'>
<tr><td><center>

<form action='<?=$PATH;?>/' method='post'>
    <table cellspacing='0' cellpadding='1' class='login'>
        <tr><td colspan='2'><center><h2>Вхід</h2></td></tr>
        <tr><td>Користувач: </td><td><input type='text' name='t_login'></td></tr>
        <tr><td>Пароль: </td><td><input type='password' name='t_pswd'></td></tr>
        <tr><td></td><td><input type='submit' value=' Ввійти '></td></tr>
    </table>
 </form>

</td></tr>
</table>