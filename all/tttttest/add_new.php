<?php

if (isset($_POST['submit'])) {
	$mysqli = require __DIR__ . "/conn.php";
	$first_name = $_POST['first-name'];
	$last_name = $_POST['last-name'];
	$email = $_POST['email'];
	$gender = $_POST['gender'];

	$sql = "INSERT INTO crud (first_name, last_name, email, gender) VALUES (?,?,?,?)";

	$stmt = $mysqli->stmt_init();

	if (!$stmt->prepare($sql)) {
		die("SQL error: " . $mysqli->error);
	}

	$stmt->bind_param("ssss",
		$first_name,
		$last_name,
		$email,
		$gender);

	if ($stmt->execute()) {
		header("Location: index.php?msg=New record created successfully");
		exit;
	} else {
		echo $mysqli->error . "failed" . $mysqli->errno;
	}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<div class="text-center mb-4">
			<h3>Add New User</h3>
			<p class="text-muted">Complete the foem below to add a new user</p>
		</div>
		<div class="container d-flex justify-content-center">
			<form action="" method="post" style="width: 50vw; min-width: 300px;">
				<div class="row mb-3">
					<div class="col">
						<label class="form-label">First Name:</label>
						<input type="text" class="form-control" name="first-name" placeholder="Albert">
					</div>

					<div class="col">
						<label class="form-label">First Name:</label>
						<input type="text" class="form-control" name="last-name" placeholder="bert">
					</div>
				</div>

				<div class="mb-3">
					<label class="form-label">First Name:</label>
						<input type="email" class="form-control" name="email" placeholder="name@example.com">
				</div>

				<div class="form-group mb-3">
					<label>Gender:</label> &nbsp;
					<input type="radio" class="form-check-input" name="gender" id="male" vale="male">
					<label for="male" class="form-input-label">Male</label>
					&nbsp;
					<input type="radio" class="form-check-input" name="gender" id="male" vale="female">
					<label for="female" class="form-input-label">Female</label>
				</div>

				<div>
					<button type="submit" class="btn btn-success" name="submit">save</button>
					<a href="index.php" class="btn btn-danger">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</body>
</html>