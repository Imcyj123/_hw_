<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
	<div class="container">
		<a href="add_new.php" class="btn btn-dark">add new post</a>
		<table class="table table-hover text-center mb-3">
			<thead class="table-dark">
				<tr>
					<th scope="col">ID</th>
					<th scope="col">First Name</th>
					<th scope="col">Last Name</th>
					<th scope="col">Email</th>
					<th scope="col">Gender</th>
					<th scope="col">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
$mysqli = require __DIR__ . "/conn.php";
$sql = "SELECT * from crud";
$res = $mysqli->query($sql);
while ($row = $res->fetch_assoc()) {
	?>
				<tr>
					<td><?php echo $row['id'] ?></td>
					<td><?php echo $row['first_name'] ?></td>
					<td><?php echo $row['last_name'] ?></td>
					<td><?php echo $row['email'] ?></td>
					<td><?php echo $row['gender'] ?></td>
					<td>
						&nbsp;
						<a href="edit.php?id=<?php echo $row['id']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

						&nbsp;
						<a href="delete.php?id=<?php echo $row['id']; ?>"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>