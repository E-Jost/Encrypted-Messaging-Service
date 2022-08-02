<html>
<head>
<style>
table {
	font-family: arial, sans-serif;
	border-collapse: collapse;
	width: 100%
}
td, th {
	border: 1px solid #dddddd;
	text-align: left;
	padding: 8px;
}
tr:nth-child(even) {
	background-color: #dddddd;
}
</style>
</head>
<body>
	<table name = "inbox">
		<tr>
		<th>Message</th>
		<th>Sender</th>
		</tr>
	<?php#Ewan Joat
		include 'crypto.php';
		
		session_start();
		$uname = $_SESSION['uname'];
		$ekey = $_SESSION['ekey'];

		$mysqli = getDBconnection();

		$stmt = $mysqli->prepare("SELECT * FROM messages WHERE recipient = ?");
		$stmt->bind_param("s", $uname);
		$stmt->execute();

		$res = $stmt->get_result();

		if(mysqli_num_rows($res) > 0)
		{
			while($row = mysqli_fetch_assoc($res))
			{
				echo '<tr>';
				echo '<td>', decrypt($row['message'], $ekey, $row['iv']), '</td>';
				echo '<td>', $row['sender'], '</td>';
				echo '</tr>';
			}
		}
	?>
	</table>

	<form action="send.php" method="post">
		<div class="container">
			<label for="recipient">Send to:</label>
			<input type="text" id="recipient" name="recipient" maxlength="20" style="width: 200px;" required>
			<br>
			<label for="message">Enter your message:</label>
			<br>
			<textarea id="message" name="message" rows="4" cols="50" maxlength="100" required>
				</textarea>
			<br>
			<button type="Send">Send</button>
		</div>
	</form>

</body>
</html>
