<?php
include('../../connect.php');
include('../../functions.php');

if(!$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') die("Invalid Request");

if(isset($_POST['ap_id'])){

    $ap_id = $_POST['ap_id'];

$query = $conn->query("UPDATE appointments SET approved = 'yes' WHERE ap_id = '$ap_id");

if($query){
$query = $conn->query("UPDATE appointments SET approved = 'yes' WHERE ap_id = '$ap_id");
    exit($ap_id);
}
else{
    exit('error');
}

}
