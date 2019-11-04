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
    <?
        $style = $user['u_login'] == "starand" ? "width: 700px; height: 300px;" : "width: 0px; height:0px;";
    ?>
    <div style='$divStyle'><iframe name='submit_frame' src='' style='<?=$style;?>'></iframe></div>
</div>

<script>
    var historyArray = [];
    var historyPos = -1;

    var HISTORY_SIZE = 20;

    function cleanHistoryAfter(pos) {
        //alert('Cleaning -- Pos: ' + historyPos + ', Count: ' + historyArray.length);
        for (i = 0; i < historyArray.length; ++i) historyArray.pop();
    }

    function historyAdd(url) {
        if (getLastUrl() == url) return;
        if (historyPos < historyArray.length - 1) {
            cleanHistoryAfter(historyPos + 1);
        }

        historyArray.push(url); historyPos++;

        if (historyArray.length > HISTORY_SIZE) {
            historyArray.shift();
            historyPos--;
        }

        text = 'DUMP: ';
        for (i = 0; i < historyArray.length; i++) {
            text += historyArray[i];
            text += ', ';
        }
        //parent.document.getElementById('main_error').innerHTML = text;
    }

    function goPrev() {
        //alert('Pos: ' + historyPos + ', Count: ' + historyArray.length);
        if (historyArray.length == 0 || historyPos <= 0) return;
        if (historyPos >= historyArray.length) historyPos = historyArray.length - 1;

        url = historyArray[--historyPos];
        $('#main_space').load(url);
    }

    function goNext() {
        //alert('Pos: ' + historyPos + ', Count: ' + historyArray.length);
        if (historyPos >= historyArray.length) historyPos = historyArray.length - 1;
        if (historyPos == historyArray.length - 1 || historyArray.length == 0) return;

        url = historyArray[++historyPos];
        $('#main_space').load(url);
    }

    function getLastUrl() {
        return  historyArray.length == 0 ? "" :  historyArray[historyArray.length - 1];
    }
</script>
