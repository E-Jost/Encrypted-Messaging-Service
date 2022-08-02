<?php#Ewan Jost
	#Script I used to insert new users into the database.
	#This code has to be manually executed and is never used by the site/server

	# username and password for new user
	$uname = "cryptostudent2";
	$psw = "crypto";

	include 'crypto.php';
	
	$mysqli = getDBconnection();
	if($mysqli->connect_errno)
	{
		die("Connection failed");
	}

	$ekey = openssl_random_pseudo_bytes(32);

	$stmt = $mysqli->prepare("INSERT INTO ekeys VALUES(?,?)");
	$stmt->bind_param("ss", getHash($uname), $ekey);
	$stmt->execute();
	
	$ivlen = openssl_cipher_iv_length("aes-256-ctr");
	$iv = openssl_random_pseudo_bytes($ivlen);
	$cipherpsw = encrypt($psw, $ekey, $iv);

	$stmt = $mysqli->prepare("INSERT INTO users VALUES(?,?,?)");
	$stmt->bind_param("sss", $uname, $cipherpsw, $iv);
	$stmt->execute();
?>
