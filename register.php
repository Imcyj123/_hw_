<title>Sign up</title>
<body>
     <div class="flex-center position-ref full-height">
                <div class="top-right home">
                        <a href="view.php?name="$_GET['name']"">View</a>
                        <a href="index.php">Login</a>
                        <a href="signup.php">Register</a>
                </div>
      <div class="content">
                <div class="m-b-md">
                    <form name="signup" action="signup.php" method="post">
                        <p>Username : <input type=text name="name"></p>
                        <p>Password : <input type=password name="password"></p>
                        <p><input type="submit" name="submit" value="Sign up">
                    <style>
                        input {padding:5px 15px; background:#ccc; border:0 none;
                        cursor:pointer;
                        -webkit-border-radius: 5px;
                        border-radius: 5px; }
                    </style>
                        <input type="reset" name="Reset" value="Reset">
                    <style>
                        input {
                            padding:5px 15px;
                            background:#FFCCCC;
                            border:0 none;
                            cursor:pointer;
                            -webkit-border-radius: 5px;
                            border-radius: 5px;
                            font-family: 'Nunito', sans-serif;
                            font-size: 19px;
                        }
                    </style>
                    </form>
                </div>

</body>
</html>
<!--留言者按下Signup後接著會執行以下程式碼-->
<?php
header("Content-Type: text/html; charset=utf8");
if (isset($_POST['submit'])) {
	include 'db.php';
	$name = $_POST['name'];
	$password = $_POST['password'];
	if ($name && $password) {
		$sql = "select * from user where username = '$name'";
		$result = mysqli_query($db, $sql);
		$rows = mysqli_num_rows($result);
		if (!$rows) {
			//若這個username還未被使用過
			$sql = "insert user(id,username,password) values (null,'$name','$password')";
			mysqli_query($db, $sql);

			if (!$result) {
				die('Error: ' . mysqli_error());
			} else {
				echo '<div class="success">Sign up successfully ！</div>';
				echo "
                    <script>
                    setTimeout(function(){window.location.href='view.php?name=" . $name . "';},2000);
                    </script>";
			}
		} else {
			//這個username已被使用

			echo '<div class="warning">The Username has already been used ！</div>';
			echo "
                <script>
                setTimeout(function(){window.location.href='signup.php';},1000);
                </script>";
		}
	} else {

		echo '<div class="warning">Incompleted form！ </div>';
		//以下為javascript語法，註冊成功後會自動跳轉到登入頁面
		echo "
<script>
setTimeout(function(){window.location.href='login.php';},2000);
</script>";
	}
}

mysqli_close($db);
?>