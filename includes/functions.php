<?php

require_once ___APIS___;

/**************************************DATABASE RELATED FUNCTIONS***********************************************************************/

function get_conn()
{
    require_once ___INC___ . 'config.php';

    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD) or die('Error connecting to MySQL server.');

    mysqli_select_db($conn, DB) or die('Error selecting database.');

    return $conn;
}

function runQuery($query)
{
    $run_query = mysqli_query(get_conn(), $query);
    return $run_query;
}

function systemVariable($variable_name)
{
    $query = "SELECT * FROM `systemv` WHERE `systemv`.`variable` = '$variable_name'";
    // $query = WHERE('systemv', 'variable', $variable_name);
    $run_query = runQuery($query);
    $fetch = mysqli_fetch_assoc($run_query);
    return $fetch['value'];
}

if (!defined('SITE_HOME')) {
    define('SITE_HOME', systemVariable('SITE_HOME'));
}

if (!defined('SITE_DIR')) {
    define('SITE_DIR', systemVariable('SITE_DIR'));
}

function SELECT($table, $show = ''){
    $all = "SELECT * FROM `$table`;";
    $restricted = "SELECT * FROM `$table` WHERE `$table`.`is_active` = 1 AND `$table`.`is_deleted` = 0;";
    $query = $show == 'all' ? $all : $restricted;
    return runQuery($query);
}

function WHERE($table, $assoc_array, $order_by = 'id', $sort_type = ASC, $show = ''){
    if($show == 'all'){
        $assoc_array['is_deleted'] = 0;
        $assoc_array['is_active'] = 1;
    }
    $query = "SELECT * FROM `$table` WHERE ";
    $count = count($assoc_array);
    $in = 1;
    foreach ($assoc_array as $key => $value) {
        $query .= $in == $count ? "`$table`.`$key` = '$value'" : "`$table`.`$key` = '$value' AND ";
        $in++;
    }
    $query .= " ORDER BY `$table`.`$order_by` $sort_type;";
    return runQuery($query);
}

function INSERT($table, $assoc_array){

    $count = count($assoc_array);

    $query = "INSERT INTO `$table` ";
    $keys = "(";
    $values = "(";
    $in = 1;

    // adding keys
    foreach ($assoc_array as $key => $value) {
        $keys .= $in == $count ? "`$key`)" : "`$key`, ";
        $in++;
    }

    $in = 1;

    // adding values
    foreach ($assoc_array as $key => $value) {
        $values .= $in == $count ? "'$value') " : "'$value', ";
        $in++;
    }

    $query = $query . $keys . " VALUES " . $values . ";";

    return runQuery($query);
}

function UPDATE($table, $assoc_array, $target_key, $target_key_value){

    $query = "UPDATE `$table` SET ";

    $in = 1;
    $count = count($assoc_array);

    foreach ($assoc_array as $key => $value) {
        $query .=  $in == $count ? "`$key` =  '$value' " : "`$key` = '$value', ";
        $in++;
    }

    $query .= "WHERE `$table`.`$target_key` = '$target_key_value';";

    return runQuery($query);
}

function DELETE($table, $target_key, $target_key_value){
    return UPDATE(
        $table,
        array(
            "is_deleted" => 1
        ),
        $target_key,
        $target_key_value
    );
}

function RESTORE($table, $target_key, $target_key_value){
    return UPDATE(
        $table,
        array(
            "is_deleted" => 0
        ),
        $target_key,
        $target_key_value
    );
}

function ACTIVATE($table, $target_key, $target_key_value){
    return UPDATE(
        $table,
        array(
            "is_active" => 1
        ),
        $target_key,
        $target_key_value
    );
}

function DEACTIVATE($table, $target_key, $target_key_value){
    return UPDATE(
        $table,
        array(
            "is_active" => 0
        ),
        $target_key,
        $target_key_value
    );
}

function FETCH($mysql_object){
    return mysqli_fetch_assoc($mysql_object);
}

function FETCH_ALL($mysql_object){
    $array = array();
    if(mysqli_num_rows($mysql_object)){
        while ($row = mysqli_fetch_assoc($mysql_object)) {
            array_push($array, $row);
        }
    } else {
        array_push($array, "No rows found");
    }
    return $array;
}

function IS_ACTIVE($table, $target_key, $target_value){
    $query = "SELECT * FROM `$table` WHERE `$table`.`$target_key` = '$target_value';";
    $obj = runQuery($query);
    $obj = FETCH($obj);
    return $obj['is_active'];
}

function IS_DELETED($table, $target_key, $target_value){
    $query = "SELECT * FROM `$table` WHERE `$table`.`$target_key` = '$target_value';";
    $obj = runQuery($query);
    $obj = FETCH($obj);
    return $obj['is_deleted'];
}

// password hashing and checking
function PASSWORD($password_input, $check_password = ''){
    if($check_password == ''){
        return password_hash($password_input, PASSWORD_DEFAULT);
    } else {
        if(password_verify($password_input, $check_password) == 1){
            return true;
        } else {
            return false;
        }
    }
}

function LOGIN($auth_table, $auth_id_key, $auth_id_value, $auth_password){
    $user = WHERE($auth_table, array(
        $auth_id_key => $auth_id_value
    ));
    $user = FETCH($user);
    return PASSWORD($auth_password, $user['password']);
}

function secure_API_KEY($request)
{
    if (array_key_exists('api_key', $request)) {
        unset($request['api_key']);
    }
    return $request;
}

function getAjaxRequester()
{
    $requester_script = "
        <script>
            function ajax_request(data, is_serialized = true) {

                const api_url = \"" . ___INC___ . "api.php\";
                const api_key = \"FJGCP-4DFJD-GJY49-VJBQ7-HYRR2-KH2J9-PC326-T44D4-39H6V-TVPBY\";
            
            
                data += \"&api_key=\" + api_key;
            
                var type_of_data = is_serialized ? 'json' : 'text';
            
                return $.ajax({
                    url: api_url,
                    method: \"post\",
                    data: data,
                    // dataType: \"json\"
                    dataType: type_of_data
                });
            
            }
        </script>
    ";

    return $requester_script;
}

/**************************************DATABASE RELATED FUNCTIONS***********************************************************************/























function AdminLogin($userid, $password){
    return LOGIN(ADMIN, "userid", $userid, $password);
}