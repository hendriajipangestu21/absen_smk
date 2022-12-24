
<?php

$IP = "192.168.1.201"; //ip&key sesuaikan dengan mesin
$Key = "0";

$connect = fsockopen($IP, "80", $errno, $errstr, 1);

if (!$connect) {
   die("Connection to Device Failed!");
} else {
}
?>