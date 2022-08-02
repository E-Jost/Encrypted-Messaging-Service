<?php#Ewan Joat
	include 'crypto.php';

	$mysqli = getDBconnection();
	if($mysqli->connect_errno)
	{
		die("Connection failed");
	}
	
	$uname = htmlspecialchars($_POST['uname']);
	$psw = htmlspecialchars($_POST['psw']);

	session_start();
	$_SESSION['uname'] = $uname;

	$stmt = $mysqli->prepare("SELECT * FROM ekeys WHERE uname = ?");
	$stmt->bind_param("s", getHash($uname));
	$stmt->execute();
	if($row = mysqli_fetch_assoc($stmt->get_result()))
	{
		$ekey = $row['ekey'];
		$_SESSION['ekey'] = $ekey;
		$stmt = $mysqli->prepare("SELECT * FROM users WHERE uname = ?");
		$stmt->bind_param("s", $uname);
		$stmt->execute();
		if($row = mysqli_fetch_assoc($stmt->get_result()))
		{
			$iv = $row['iv'];
			$plaintextpsw = decrypt($row['psw'], $ekey, $iv);
			if($psw == $plaintextpsw)
			{
				header("Location: /inbox.php");
			}
			else
			{
				echo "Login unsuccessful";
				exit;
			}
		}
		else
		{
			echo "Login unsuccessful";
			exit;
		}
	}
	else
	{
		echo "Login unsuccessful";
		exit;
	}
?>
