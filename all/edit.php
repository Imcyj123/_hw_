<?php

session_start();

if (isset($_SESSION["user_id"])) {
	$mysqli = require __DIR__ . "/signup.php";
	$sql = "SELECT * from user
			WHERE id = {$_SESSION["user_id"]}";
	$result = $mysqli->query($sql);
	$user = $result->fetch_assoc();
	$inactive = 900;
	if (!isset($_SESSION['timeout'])) {
		$_SESSION['timeout'] = time() + $inactive;
	}
	$session_life = time() - $_SESSION['timeout'];
	if ($session_life >= $inactive) {
		session_destroy();
		header("Location:login.php");
	}
	$_SESSION['timeout'] = time();
}
// print_r($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Home</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
	<h1>Home</h1>
	<?php if (isset($_SESSION["user_id"])): ?>
		<p>Hello <?=htmlspecialchars($user["name"])?> </p>
		<p><a href="logout.php">Log out</a></p>
		<hr>
		<h3>Comment Board</h3>
		<div class="top">
			<div class="id">id</div>
			<!-- <div class="user">user</div> -->
			<div class="title">title</div>
			<div class="content">message</div>
			<div class="cur_time">time</div>
		</div>
		<hr>
		<style type="text/css">
			.top{display: flex;}
			.id{width: 5%;}
			.user{width: 10%;}
			.title{width: 10%;}
			.content{width: 20%;}
			.cur_time{width: 20%;}
			.delete{margin: 10px;width:10%;}
		</style>
	<!-- <?php
$mysqli = require __DIR__ . "/addpost.php";
$sql = "SELECT * from comment";
$res = $mysqli->query($sql);
while ($row = $res->fetch_assoc()) {
	foreach ($row as $each) {
		echo " | " . "$each";
	}
	echo "<hr>";
}
?> -->


<?php $mysqli = require __DIR__ . "/addpost.php";
$sql = "SELECT * from comment";
$res = $mysqli->query($sql);
while ($row = $res->fetch_assoc()) {
	?>
	<div class="top">
		<div class="id"><?php echo $row["id"] ?></div>
		<!-- <div class="user"><?php echo $row["name"] ?></div> -->
		<div class="title"><?php echo $row["title"] ?></div>
		<div class="content"><?php echo $row["content"] ?></div>
		<div class="cur_time"><?php echo $row["cur_time"] ?></div>
		<?php if ($user["name"] === $row["name"]): ?>
			&nbsp;&nbsp;
			<a href="edit.php?id=<?php echo $row['id']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

			&nbsp;&nbsp;
			<a href="delete.php?id=<?php echo $row['id']; ?>"><i class="fa fa-trash"></i></a>
		<?php endif;?>


	</div>
			<hr>
		<?php }?>
<br><br><br><br>
	<div>

		<form method="post" action="process-addpost.php">
		    <label for="title">Title:</label>
		    <input type="text" name="title" id="title" required>
		    <br>
		    <label for="message">Message:</label>
		    <textarea name="message" rows="5" required></textarea>
		    <br>
		    <input type="submit" value="post!!">
		</form>
	</div>
	<?php else: ?>
		<!-- <form action="login.php">
			<input type="submit" value="login">
		</form>
		<form action="signup.html">
			<input type="submit" value="singup">
		</form> -->
		<a href="login.php"><button>login</button></a>
		<a href="signup.html"><button>signup</button></a>
	<?php endif;?>
</body>
</html>