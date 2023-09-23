<?php

    if(!defined('___ABS_PATH___')) {
        define('___ABS_PATH___', __DIR__.'/');
    }
    
    if(!defined('___PAGES___')) {
        define('___PAGES___', ___ABS_PATH___.'pages/');
    }

    if(!defined('___INC___')) {
        define('___INC___', ___ABS_PATH___.'includes/');
    }

    if(!defined('___ASSETS___')) {
        define('___ASSETS___', ___ABS_PATH___.'assets/');
    }

    if(!defined('___IMAGES___')) {
        define('___IMAGES___', ___ABS_PATH___.'assets/images/');
    }

    if(!defined('___FUNCTIONS___')) {
        define('___FUNCTIONS___', ___INC___.'functions.php');
    }

    if(!defined('___APIS___')) {
        define('___APIS___', ___INC___.'api_func.php');
    }

    if(!defined('___DB_CON___')) {
        define('___DB_CON___', ___INC___.'db_con.php');
    }

    if(!defined('GOOGLE_INSIGHTS')){
        define('GOOGLE_INSIGHTS', '');
    }




    // tables
    define('ADMIN', 'admins');

    // asc, desc
    define('ASC', 'ASC');
    define('DESC', 'DESC');

    // code hooks
    define('ALL', 'all');