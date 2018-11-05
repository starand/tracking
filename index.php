<?
    include_once "common/headers.php";

    // Login
    if (isset($_POST['t_login']) && isset($_POST['t_pswd'])) {
        $login = addslashes($_POST['t_login']);
        $pswd = $_POST['t_pswd'];

        $user = get_user_by_login($login);
        if (!$user || $user['u_pswd'] != md5($pswd)) {
            echo "<center><B>Не вірний користувач або пароль!</B>";
            include_once "login.php";
            die();
        }

        setUser($user);
    }

    // LogOut
    if (isset($_GET['logout'])) {
        clearSession();
        $user = getUser();
    }
?><center>
<div id='main' style='width:100%;'>
    <TABLE cellspacing='0' cellpadding='0' style='width:1024px;'>
        <TR>
            <TD class='top-title' id='top-menu'>
                <? include_once "top-panel.php"; ?>
            </TD>
        </TR>
        <TR><TD id='content'>
            <? include_once $user ? "main.php" : "login.php"; ?>
        </TD></TR>
    </TABLE>

    <BR><BR><BR><BR><BR><BR><BR><BR><BR>
    <div style='$divStyle'><iframe name='submit_frame' src='' style='width: 0px; height: 0px;'></iframe></div>
</div>
