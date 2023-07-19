<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
	exit("POST request method required");
}

if (empty($_FILES)) {
	exit('$_FILES is empty - is file_uploads enabled in php.ini?');
}

if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
	switch ($_FILES["image"]["error"]) {
	case UPLOAD_ERR_PARTIAL:
		exit("File only partially uploaded");
		break;
	case UPLOAD_ERR_NO_FILE:
		exit("No file was uploaded");
		break;
	case UPLOAD_ERR_EXTENSION:
		exit("File upload stopped by a PHP extension");
		break;
	default:
		exit("Unknown upload error");
		break;
	}
}

// if ($_FILES["image"]["size"] > 102400) {
// 	exit("Image File too large (max 1MB)");
// }

// if ($_FILES["file2"]["size"] > 102400) {
// 	exit("File too large (max 1MB)");
// }

$finfo = new finfo(FILEINFO_MIME_TYPE);

$mime_type = $finfo->file($_FILES["image"]["tmp_name"]);

exit($mime_type);

$mime_types = ["image/gif", "image/png", "image/jpeg"];

if (!in_array($_FILES["image"]["type"], $mime_types)) {
	exit("Invalid file type");
}

$filename = $_FILES["image"]["name"];

$destination = __DIR__ . "/upload/" . $filename;

if (!move_uploaded_file($_FILES["image"]["tmp_name"], $destination)) {
	exit("Can't move uploaded file");
}

print_r($_FILES);
