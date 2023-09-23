<?php

    require_once "./constants.php";
    require_once ___FUNCTIONS___;

    $userid = "admin23";
    $password = "abc123";

    // print_r(AdminLogin($userid, $password));
    echo IS_ACTIVE(ADMIN, 'userid', $userid);
?>