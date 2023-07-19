<?php

print_r($_GET);

include 'addpost.php';

$id = $_GET['id'];
$sql = "DELETE FROM comment where id=$id";
$res = $mysqli->query($sql);
if ($res) {
	header("Location:index.php");
} else {
	echo "fail";
}

?>