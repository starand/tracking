<?
    include_once "common/headers.php";

    checkAuthorizedUser();
    require_permission(VIEW.SALARY);

    if (isMechanicMode()) {
        include_once "salary-mechanic.php";
    } else { //if (isMechanicMode()) {
        include_once "salary-driver.php";
    }
?>