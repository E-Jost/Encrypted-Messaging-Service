<?php#Ewan Joat
	include 'crypto.php';

	$mysqli = getDBconnection();

	if($mysqli->connect_error)
	{
		die("Connection failed");
	}

	session_start();
	$uname = $_SESSION['uname'];

	$recipient = htmlspecialchars($_POST['recipient']);
	$message = htmlspecialchars($_POST['message']);

	$stmt = $mysqli->prepare("SELECT EXISTS(SELECT * FROM users WHERE uname = ?)");
	$stmt->bind_param("s", $recipient);

	$stmt->execute();

	$res = $stmt->get_result();

	$row = $res->fetch_array(MYSQLI_NUM);
	if($row[0])
	{
		$stmt = $mysqli->prepare("SELECT ekey FROM ekeys WHERE uname = ?");
		$stmt->bind_param("s", getHash($recipient));
		$stmt->execute();
		$res = $stmt->get_result();
		$row = mysqli_fetch_assoc($res);
		$ekey = $row['ekey'];

		$stmt = $mysqli->prepare("INSERT INTO messages VALUES(?,?,?,?)");
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-256-ctr"));
		$ciphertext = encrypt($message, $ekey, $iv);
		$stmt->bind_param("ssss", $recipient, $ciphertext, $uname, $iv);
		$stmt->execute();
		header("Location: /inbox.php");
		exit;
	}
	else
	{
		echo "No such user: ", $recipient;
	}
?>
