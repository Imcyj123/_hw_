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
<html>
<head>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <title>Message Board</title>
</head>
<body>
	<h1>Home</h1>
	<?php if (isset($_SESSION["user_id"])): ?>
		<p>Hello <?=htmlspecialchars($user["name"])?> </p>
		<p><a href="logout.php">Log out</a></p>
		<hr>
		<?php else: ?>
		<a href="login.php"><button>login</button></a>
		<a href="signup.html"><button>signup</button></a>
		<?php endif;?>

<?php if (isset($_SESSION["user_id"])) {?>
<h2>Add a Message</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="title">title:</label>
        <input type="text" name="title" required><br>

        <label for="message">Message:</label>
        <textarea name="message" rows="4" required></textarea><br>

        <label for="file">File:</label>
        <input type="file" name="file[]" multiple><br>

        <input type="submit" name="submit" value="Post Message">
    </form>
<?php }?>


    <h1>Message Board</h1>

<?php

$mysqli = new mysqli("localhost", "root", "", "message_boards");

if ($mysqli->connect_errno) {
	echo "連接資料庫失敗：" . $mysqli->connect_error;
	exit();
}

// 檢查是否有新增留言的請求

if (isset($_POST['submit'])) {
	$title = $_POST['title'];
	$message = $_POST['message'];

	// 檢查是否有檔案上傳
	if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
		if ($_FILES['file']["size"] > 102400) {
			exit("File too large (max 1MB)");
		} else {
			$file_name = $_FILES['file']['name'];
			$file_tmp = $_FILES['file']['tmp_name'];
			$file_path = 'uploads/' . $file_name;

			// 移動上傳的檔案到目標資料夾
			move_uploaded_file($file_tmp, $file_path);
		}
	} else {
		$file_path = null;
	}

	// 插入留言到資料庫
	// $sql = "INSERT INTO messages (username, title, message, file_path, timestamp) VALUES (?	, ?, ?, ?, NOW())";
	// $stmt = $mysqli->prepare($sql);
	// $stmt->bind_param("sss", $username, $message, $file_path);
	// $stmt->execute();
	// $stmt->close();

	$sql = "INSERT INTO messages (username, title, message, file_path, timestamp) VALUES (?, ?, ?, ?, NOW())";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("ssss", $user["name"], $title, $message, $file_path);
	$stmt->execute();
	$stmt->close();

}

// 檢查是否有刪除留言的請求

if (isset($_POST['delete'])) {
	if ($_POST["username"] === $user["name"]) {
		$message_id = $_POST['message_id'];

		// 刪除檔案
		$sql = "SELECT file_path FROM messages WHERE id = ?";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param("i", $message_id);
		$stmt->execute();
		$stmt->bind_result($file_path);
		$stmt->fetch();
		$stmt->close();

		if ($file_path !== null && file_exists($file_path)) {
			unlink($file_path);
		}

		// 刪除留言
		$sql = "DELETE FROM messages WHERE id = ?";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param("i", $message_id);
		$stmt->execute();
		$stmt->close();
	} else {
		exit("this is not your comment");
	}
}

// 檢查是否有更新留言的請求
if (isset($_POST['update'])) {
	$message_id = $_POST['message_id'];
	$new_title = $_POST['new_title'];
	$new_message = $_POST['new_message'];

	// 更新留言
	$sql = "UPDATE messages SET title = ?, message = ? WHERE id = ?";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("ssi", $new_title, $new_message, $message_id);
	$stmt->execute();
	$stmt->close();
}

// 顯示留言列表
$sql = "SELECT * FROM messages ORDER BY timestamp DESC";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		echo "<p>Author  :  " . "<strong>" . $row["username"] . "</strong></p>";
		echo "<p><strong>" . $row['title'] . ":</strong> " . $row['message'] . "</p>";
		if ($row['file_path'] !== null) {
			echo "<p><a href='" . $row['file_path'] . "' target='_blank'>查看檔案</a></p>";
		}
		if (isset($_SESSION["user_id"]) && $row["username"] === $user["name"]) {
			echo "<form method='POST' action=''>
                    <input type='hidden' name='username' value='" . $user['name'] . "'>
                    <input type='hidden' name='message_id' value='" . $row['id'] . "'>
                    <input type='submit' name='delete' value='刪除'>
                    </form>";
		}

		if (isset($_SESSION["user_id"]) && $row["username"] === $user["name"]) {
			echo "<form method='POST' action=''>
                    <input type='hidden' name='username' value='" . $user['name'] . "'>
                    <input type='hidden' name='message_id' value='" . $row['id'] . "'>
                    <textarea name='new_title' rows='4' required></textarea>
                    <textarea name='new_message' rows='4' required></textarea>
                    <input type='submit' name='update' value='更新'>
                    </form>";
			echo "<hr>";
		}
	}
} else {
	echo "<p>No messages yet.</p>";
}

$mysqli->close();
?>


</body>
</html>
