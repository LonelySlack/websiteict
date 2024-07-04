<?php
$host = "localhost/XE";
$user = "ICTPROJECT2";
$pass = "ICTPROJECT2";
$conn = oci_connect($user, $pass, $host);

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
} 

function CloseConn($conn): void
{
    oci_close($conn);
}
?>