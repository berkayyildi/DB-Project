<?php

	$dbhost = "localhost";
	$dbkullanici = "root";
	$dbsifre = "mysql";
	$dbadi = "market";

	$baglan = new mysqli($dbhost, $dbkullanici, $dbsifre, $dbadi);

	if (mysqli_connect_errno()) {
		echo "<br>";
		echo mysqli_connect_error() . "<br>";
		if(mysqli_connect_errno() == 1049){
			echo "<b>Please run install.php and complate installation!</b><br>";
			echo "<a href='install.php'>Run install.php</a>";
		}
		exit();
	}

	$baglan->set_charset("utf8");

?>