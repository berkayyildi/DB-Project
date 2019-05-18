<?php

function sqlfileimport($conn, $sqlfilename, $servername, $username, $password, $database){

	mysqli_select_db($conn, $database) or die('Error selecting MySQL database: ' . mysqli_error($conn));// Select database
	$templine = '';
	$lines = file($sqlfilename);
	foreach ($lines as $line)
	{
		if (substr($line, 0, 2) == '--' || $line == ''){// Skip it if it's a comment
			$templine = '';
			continue;
		}
		$templine .= $line; // Add this line to the current segment
		if (substr(trim($line), -1, 1) == ';') // If it has a semicolon at the end, it's the end of the query
		{
			mysqli_query($conn, $templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysqli_error($conn) . '<br /><br />');    // Perform the query
			$templine = '';    // Reset temp variable to empty
		}
	}
	 echo "<br><font color='green'>Tables imported successfully</font><br>";
}

//------------------------------------\ VERITABANINI OLUSTUR /------------------------------------
$servername = "localhost";
$username = "root";
$password = "mysql";

$conn = new mysqli($servername, $username, $password);// Create connection
mysqli_set_charset($conn, 'utf8');	//Set charset
if ($conn->connect_error) { // Check connection
    die("<font color='red'>Connection failed: </font>" . $conn->connect_error);
} 

// Create database
$sql = "CREATE DATABASE market";

if ($conn->query($sql) === TRUE) {
	echo "<font color='green'>Database created successfully</font> <br>";

	$conn->query("SET FOREIGN_KEY_CHECKS=0;");
	sqlfileimport($conn, 'market.sql', $servername ,$username, $password, 'market');
	$conn->query("SET FOREIGN_KEY_CHECKS=1;");

} else{
	echo "Database already exist please delete install.php after install for your security.<br>";
	echo "Database will be deleted and regenerated because of re run! install.php<br>";

	$sql = "DROP DATABASE market";
	if ($conn->query($sql) === TRUE) {
		echo "Database DROP successfully. Refresh Page To Install";
	} else{
		echo "<font color='red'>Database DROP error! Please Delete Database manually and Refresh Page To Install</font>";
	}

	
	//die();
}

$conn->close();
//------------------------------------------------------------------------------------------------


require_once("sqliconnect.php");

$nameArray = array();
$surnameArray = array();

//-------------------------------------------\ SEHIRLERI AL /-------------------------------------------

$row = 0;
$cityid=0;
$filename = "csv/cities.csv";
if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
	while (($row = fgetcsv($handle, 1000, ';')) !== FALSE)
	{
		if(!$header)
			$header = $row;
		else{

			$name = mysqli_real_escape_string($baglan, $row[1]);
			$district_id = mysqli_real_escape_string($baglan, $row[2]);

			$sql = "INSERT INTO Cities (name,disctinct_id)
					VALUES ('$name','$district_id')";

			if (!$baglan->query($sql) === TRUE) {
				echo "Error: " . $sql . "<br>" . $baglan->error;
			}

		}
	}
	fclose($handle);

	echo "<br><font color='green'>Customers Table random data created</font><br>";
}
//------------------------------------------------------------------------------------------------

//-------------------------------------------\ ISIMLERI AL /-------------------------------------------

	$row = 0;
	$filename = "csv/500names.csv";
	if(!file_exists($filename) || !is_readable($filename))
		return FALSE;
	
	$header = NULL;
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		while (($row = fgetcsv($handle, 1000, ';')) !== FALSE)
		{
			if(!$header)
				$header = $row;
			else{
				$namearray[] = $row[0];	//CSV den alınan veriyi array e ekle
			}
		}
		fclose($handle);
	}
//------------------------------------------------------------------------------------------------

//-------------------------------------------\ SOYADLARI AL /-------------------------------------------

	$row = 0;
	$filename = "csv/500surnames.csv";
	if(!file_exists($filename) || !is_readable($filename))
		return FALSE;
	
	$header = NULL;
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		while (($row = fgetcsv($handle, 1000, ';')) !== FALSE)
		{
			if(!$header)
				$header = $row;
			else{
				$surnameArray[] = $row[0];	//CSV den alınan veriyi array e ekle
			}
		}
		fclose($handle);
	}

//------------------------------------------------------------------------------------------------

//-------------------------------- | Urunleri AL| --------------------------------------------------

$row = 0;
$filename = "csv/products.csv";
if(!file_exists($filename) || !is_readable($filename))
	return FALSE;


$prodid = 0;
$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
	while (($row = fgetcsv($handle, 1000, ';')) !== FALSE)
	{
		if(!$header)
			$header = $row;
		else{
			$product[$prodid]['name'] = $row[0];	//CSV den alınan veriyi array e ekle
			$product[$prodid]['price'] = $row[1];
			$product[$prodid]['category'] = $row[2];
			$prodid++;
		}
	}
	fclose($handle);
}

shuffle($product);	//Sırasını Randomize Et
//----------------------------------------------------------------------------------------------------------

//---------------------------Create Random Unique 2K Customers---------------------------
$CustomerNamesSurnamesArray = array();
for ($x = 0; $x <= 2000; $x++) {		//Unique garantilemek için fazladan isim oluştur
	$CustomerNamesSurnamesArray[] = $namearray[rand(0, 500)] . " " . $surnameArray[rand(0, 500)]; //Random isim soyisimle oluştur
} 
$CustomerNamesSurnamesArray = array_unique($CustomerNamesSurnamesArray);	//Duplicate sil
//----------------------------------------------------------------------------

//---------------------------Create Random Unique 2K Salesmans---------------------------
$SalesmansNamesSurnamesArray = array();
for ($x = 0; $x <= 2000; $x++) {		//Unique garantilemek için fazladan isim oluştur
	$SalesmansNamesSurnamesArray[] = $namearray[rand(0, 500)] . " " . $surnameArray[rand(0, 500)]; //Random isim soyisimle oluştur
} 
$SalesmansNamesSurnamesArray = array_unique($SalesmansNamesSurnamesArray);	//Duplicate sil
//----------------------------------------------------------------------------


//------------------------------------INSERT Custormers TO Table------------------------------------
for( $i = 0; $i<1620; $i++ ) {
	$iname = mysqli_real_escape_string($baglan, $CustomerNamesSurnamesArray[$i]);

	$sql = "INSERT INTO Customers (customername)
			VALUES ('$iname')";

	if (!$baglan->query($sql) === TRUE) {
		echo "Error: " . $sql . "<br>" . $baglan->error;
	}

}
echo "<br><font color='green'>Customers Table random data created</font><br>";
//----------------------------------------------------------------------------

//------------------------------------INSERT 1215 Salesmans TO Table------------------------------------
for( $i = 0; $i<1215; $i++ ) {
	$iname = mysqli_real_escape_string($baglan, $SalesmansNamesSurnamesArray[$i]);

	$sql = "INSERT INTO Salesmans (salesmanname)
			VALUES ('$iname')";

	if (!$baglan->query($sql) === TRUE) {
		echo "Error: " . $sql . "<br>" . $baglan->error;
	}

}
echo "<br><font color='green'>Salesmans Table random data created</font><br>";
//----------------------------------------------------------------------------


//------------------------------------INSERT 200 RANDOM PRODUCTS TO Products Table------------------------------------
for( $i = 0; $i<200; $i++ ) {
	$iname = mysqli_real_escape_string($baglan, $product[$i]['name']);
	$iprice= mysqli_real_escape_string($baglan, $product[$i]['price']);
	$iprice= str_replace(",",".",$iprice);	//Float insert , yerine . istiyor
	$icategory= mysqli_real_escape_string($baglan, $product[$i]['category']);

	$sql = "INSERT INTO Products (productname, price, category_id)
			VALUES ('$iname', '$iprice', '$icategory')";

	if (!$baglan->query($sql) === TRUE) {
		echo "Error: " . $sql . "<br>" . $baglan->error;
	}

}
echo "<br><font color='green'>Products Table random data created</font><br>";
//----------------------------------------------------------------------------



//------------------------------------\ CREATE RANDOM 5 UNIQUE MARKET FOREACH CITY/------------------------------------
for( $i = 1; $i<82; $i++ ) {

	//CREATE 1 to 10 array and shuffle it to get random first 5
	$numbers = range(1, 10);
	shuffle($numbers);

	for( $j = 0; $j<5; $j++ ){

		$marketid = $numbers[$j];

		$sql = "INSERT INTO Init_Markets (city_id, market_id)
				VALUES ('$i', '$marketid')";

		if (!$baglan->query($sql) === TRUE) {
			echo "Error: " . $sql . "<br>" . $baglan->error;
		}
	}

}
echo "<br><font color='green'>MID-Market Table random data created</font><br>";
//-------------------------------------------------------------------------------------------


//------------------------------------\ CREATE RANDOM 3 SALESMANS AND SET THEM TO INIT MARKET'S INIT WORKS/------------------------------------

	$allsalesmans = array();
	for($x=1;$x<1216;$x++){
		$allsalesmans[] = $x;
	}

	for( $i = 1; $i<406; $i++ ){

		for( $j = 0; $j<3; $j++ ){	//3 Kez insert et

			$randsalesman = $allsalesmans[array_rand($allsalesmans)];	//Element id = element value supposed
			$randcustomerid_index = array_search($randsalesman, $allsalesmans);
			unset( $allsalesmans[$randcustomerid_index] );	//Arrayden sil

			$sql = "INSERT INTO Init_Works (market_id, salesman_id)
					VALUES ('$i', '$randsalesman')";

			if (!$baglan->query($sql) === TRUE) {
				echo "Error: " . $sql . "<br>" . $baglan->error;
			}

		}

	}

echo "<br><font color='green'>Set 3 Workers foreach Market with random data MID-Workers table created</font><br>";
//-------------------------------------------------------------------------------------------

//------------------------------------\ FILL SALES TABLE RANDOM (1 to 5 random ITEM SALES FOREACH customer)/------------------------------------
for( $i = 1; $i<=1620; $i++ ){	//$i means customerid

	$numofboughtproduct = rand(1, 5);
	for( $j = 0; $j<$numofboughtproduct; $j++ ){	//1-5 sayıda ürünü sales tablosuna insert et

		$product_id = rand(1, 200);
		$salesman_id = rand(1, 1215);
		$rand_date = date('Y-m-d', strtotime( '-'.mt_rand(0,30).' days'));	//30 Gün içinde random data oluşturur
		$sql = "INSERT INTO Sales (product_id, customer_id, salesman_id, sale_date)
				VALUES ('$product_id', '$i', '$salesman_id', '$rand_date')";
		
		if (!$baglan->query($sql) === TRUE) {
			echo "Error: " . $sql . "<br>" . $baglan->error;
		}

	}

}
echo "<br><font color='green'>Filled Sales table with random items</font><br>";
//-------------------------------------------------------------------------------------------

echo "<br><br><a href='./'>Go Mainpage</a>";
?>