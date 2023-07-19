<?php

print_r($_POST);

session_start();

if (isset($_SESSION["user_id"])) {
	$mysqli = require __DIR__ . "/signup.php";

	$sql = "SELECT * from user
			WHERE id = {$_SESSION["user_id"]}";

	$result = $mysqli->query($sql);

	$user = $result->fetch_assoc();

	if ($_SESSION['user_id'] === $user["id"]) {
		$name = $user["name"];
	}
}

$mysqli = require __DIR__ . "/addpost.php";

$sql = "INSERT INTO comment (name, title, content, cur_time)
		VALUES (?,?,?,?)";

$stmt = $mysqli->stmt_init();

if (!$stmt->prepare($sql)) {
	die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("ssss",
	$name,
	$_POST["title"],
	$_POST["message"],
	date("Y-m-d H:i:s"));

if ($stmt->execute()) {
	header("Location: index.php");
	exit;
} else {
	die($mysqli->error . " " . $mysqli->errno);
}
