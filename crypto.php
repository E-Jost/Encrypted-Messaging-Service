<?php#Ewan Joat
function encrypt($plaintext, $key, $iv)
{
	$cipher = "aes-256-ctr";
	return openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv);
}

function decrypt($ciphertext, $key, $iv)
{
	$cipher = "aes-256-ctr";
	return openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv);
}

function getHash($uname)
{
	return hash("sha256", $uname, false);
}

function getDBconnection()
{
	return new mysqli("localhost", "root", "crypto", "hidden_service");
}
?>
