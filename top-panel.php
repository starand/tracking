<?
    include_once "common/headers.php";
    $admin = $user && $user['u_login'] == 'starand' ? 'trash' : '';

    echo "<TABLE cellspacing='0' cellpadding='0' style='width:100%;'>
            <TR style='height:50px;'>
                <TD class ='top-title' id='top_logo' style='width:130px;'><div class='top-logo'>&nbsp; Tracking &nbsp;</div></TD>";
                if ($user) {
                    echo hasPermission(VIEW.DRIVERS) ? "<TD class='top-title' id='drivers' style='width:60px;'> &nbsp; Водії &nbsp; </TD>" : "";
                    echo hasPermission(VIEW.MECHANICS) ? "<TD class='top-title' id='mechanics' style='width:60px;'> &nbsp; Слюсарі &nbsp; </TD>" : "";
                    echo hasPermission(VIEW.CARS) ? "<TD class='top-title' id='cars' style='width:80px;'> &nbsp; Машини &nbsp; </TD>" : "";
                    echo hasPermission(VIEW.POS) ? "<TD class='top-title' id='pos' style='width:90px;'> &nbsp; Підприємці &nbsp; </TD>" : "";
                    echo hasPermission(VIEW.SALARY) ? "<TD class='top-title' id='salary' style='width:90px;'> &nbsp; Зарплата &nbsp; </TD>" : "";

                    echo "<TD class='top-title'> &nbsp; </TD>";
                    echo hasPermission(VIEW.USERS) ? "<TD class='top-title' id='users' style='width:70px;'> &nbsp; Користувачі &nbsp; </TD>" : "";
                    echo "<TD class='top-title' id='$admin' style='width:130px;font-weight:bold;color:yellow;text-align:right;'> &nbsp; {$user['u_login']} </TD>";
                    echo "<TD class='top-title' id='logout' style='width:70px;'> &nbsp; Вихід &nbsp; </TD>";
                } else {
                    echo "<TD class='top-title'> &nbsp; </TD>
                        <TD class='top-title' id='login' style='width:80px;'> &nbsp; Вхід &nbsp; </TD>";
                }
        echo "</TR>
        </TABLE>";
?>

<script>
$(document).ready(function() {
    $("#login").on("click", function() {
        $("#content").load("login.php");
    });
    $("#logout").on("click", function() {
        $("#main").load("index.php?logout=");
    });
    $("#drivers").on("click", function() {
        load_main_hist("drivers.php");
    });
    $("#mechanics").on("click", function() {
        load_main_hist("mechanics.php");
    });
    $("#cars").on("click", function() {
        load_main_hist("cars.php");
    });
    $("#pos").on("click", function() {
        load_main_hist("pos.php");
    });
    $("#top_logo").on("click", function() {
        $("#left_menu").load("locations.php");
    });
    $("#trash").on("click", function() {
        load_main_hist("adm.php");
    });
    $("#salary").on("click", function() {
        load_main_hist("salary.php");
    });
    $("#users").on("click", function() {
        load_main_hist("users.php");
    });
});
</script>