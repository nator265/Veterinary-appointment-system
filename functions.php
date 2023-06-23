<?php



function checkSQL($db, $qry){
    if(!$qry){
        die(mysqli_error($db));
    }
}

?>