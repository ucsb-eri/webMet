<html>
 <head>
 <meta charset="UTF-8"/>
  <title>Process Met SQL</title>
  </head>
 
<body>
 <h1 align="center">Earth Research Institute</h1>
 <h2 align="center">Data From Met Stations</h2>
 
 <h3>Met 1 Station Processed Data</h3>
 
<?php
 	//set dates to pull date from(default to today if invalid or no POST)
 	date_default_timezone_set('America/Los_Angeles'); 
 	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 		$startDate = date("Y-m-d", strtotime($_POST["from"]));
 		$endDate = date("Y-m-d", strtotime($_POST["to"]));
 		$every=$_POST["every"];
 		$day=$_POST["day"];
 	}
 	else{
 		$startDate = date("Y-m-d");
 		$endDate = date("Y-m-d");
 		$every="60";
 	}

 
 	// Create connection to mysql
 	$user = 'browse';
 	$columns=array();
try {	
	$conn = new PDO('mysql:host=localhost;dbname=webmet', $user, '');
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
} catch(PDOException $e) {	echo 'ERROR: ' . $e->getMessage();	}
    

	
try {
	//get columns from table
	$stmt = $conn->prepare('SHOW columns FROM metdata');
	$stmt->execute();
			while($temp = $stmt->fetch()) {
				$columns[]=$temp['Field'];
			}
	
	//search database for dates(startdate + enddate + mod with every varaible)
	$stmt = $conn->prepare('SELECT * FROM metdata WHERE date BETWEEN :startDate AND :endDate AND mod(minute(time),:every) = 0');
	$stmt->execute(array('startDate' => $startDate, 'endDate' => $endDate, 'every' => $every));
	$row = $stmt->fetchAll();
	$iTotal=count($row);
		
	//output array	
	$output = array();
	for ( $j=0 ; $j<count($row) ; $j++ ){	
		$aRow=array();
		for ( $i=0 ; $i<count($columns) ; $i++ ){
			$aRow[] = $row[$j][ $columns[$i] ];
		}
		$output[] = $aRow;
	}
	//test list
	$list = array (
    array('aaa', 'bbb', 'ccc', 'dddd'),
    array('123', '456', '789'),
    array('"aaa"', '"bbb"')
);
	
	$fp = fopen('/var/www/vhosts/webmet.eri.ucsb.edu/admin/test/file_test.csv', 'w');
	//$fp = fopen('php://output', 'w');

	foreach ($output as $fields) {
		fputcsv($fp, $fields);
		echo '\r\n';
	}
	fclose($fp);
	
	//return json encoded data for DataTable
	//echo print_r($list);
	//echo $iTotal;
	//echo json_encode( $output );
		
} catch(PDOException $e) {	echo 'ERROR: ' . $e->getMessage();	}

?>
</body>
</html>
