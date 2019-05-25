<?php

	$dbhost = "localhost";
	$dbkullanici = "root";
	$dbsifre = "mysql";
	$dbadi = "berkayyildiz";

	$baglan = @new mysqli($dbhost, $dbkullanici, $dbsifre, $dbadi);	//Disable wae

	if (mysqli_connect_errno()) {
		echo "<br>";
		echo mysqli_connect_error() . "<br>";
		if(mysqli_connect_errno() == 1049){
			echo "<b>Please run install.php and complate installation!</b><br>";
			echo "<button onclick=\"window.location.href='./install.php'\">Install</button>";
		}
		exit();
	}

	$baglan->set_charset("utf8");

?>