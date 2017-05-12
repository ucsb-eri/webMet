<html>
 <head>
 <meta charset="UTF-8"/>
 <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
 <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
 <script type="text/javascript" charset="utf-8" src="/admin/DataTables-1.9.4/media/js/jquery.js"></script>
 <script type="text/javascript" charset="utf-8" src="/admin/DataTables-1.9.4/media/js/jquery.dataTables.js"></script>
  <title>Data gen</title>
  <style type="text/css" title="currentStyle">
    @import "/admin/DataTables-1.9.4/media/css/demo_table.css";
  </style>
 </head>
 
 <body>
 <h1 align="center">Earth Research Institute</h1>
 <h2 align="center">Data From Met Stations</h2>
 

   
 <?php
 	//set dates to pull date from(default to today if invalid or no POST)
 	date_default_timezone_set('America/Los_Angeles'); 
 	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 		$startDate = date("Y-m-d", strtotime($_POST["FROM"]));
 		$endDate = date("Y-m-d", strtotime($_POST["TO"]));
 	}
 	else{
 		$startDate = date("Y-m-d");
 		$endDate = date("Y-m-d");
 	}

 	
 	// Create connection
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
    
		//search database for dates
		$stmt = $conn->prepare('SELECT * FROM metdata WHERE date BETWEEN :startDate AND :endDate');
		$stmt->execute(array('startDate' => $startDate, 'endDate' => $endDate));
 
		/*
		//print columns in table 	//echo '<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default<th>Extra</th></tr>';
		echo '<table cellpadding="0" cellspacing="0" class="db-table">';
		echo '<tr>';
		foreach(array_keys($columns) as $k){
			echo '<th>' . $columns[$k] . '</th>';
		}
		echo '</tr>';
		*/
		/*
		//print data into table
		while($row = $stmt->fetch()) {
			echo '<tr>';
			foreach(array_keys($columns) as $k){
				echo '<th>' . $row[$columns[$k]] . '</th>';
    	
			}
			echo "</tr>";

		}
		*/
		//print columns for DataTable
		echo  '<table id="table_id"><thead><tr>';
		foreach(array_keys($columns) as $k){
			echo '<th>' . $columns[$k] . '</th>';
		}
		echo '</tr></thead>';
		
		//Fill in Data
		echo  '<tbody><tr>';
		while($row = $stmt->fetch()) {
			echo '<tr>';
			foreach(array_keys($columns) as $k){
				echo '<td>' . $row[$columns[$k]] . '</td>';
    	
			}
			echo "</tr>";
		}
		echo '</tr></tbody></table>';
		
   	} catch(PDOException $e) {	echo 'ERROR: ' . $e->getMessage();	}

?>
    <script>
 $(document).ready( function () {
    $('#table_id').dataTable({
    "aoColumns":[
        {"sWidth": '200px', "mDataProp": "rowid"},
        {"sWidth": '300px', "mDataProp": "station"},
        {"sWidth": '200px', "mDataProp": "temp"},
        {"sWidth": '300px', "mDataProp": "hum"},
        {"sWidth": '200px', "mDataProp": "dew"},
        {"sWidth": '300px', "mDataProp": "heat"},
        {"sWidth": '200px', "mDataProp": "wspeed"},
        {"sWidth": '300px', "mDataProp": "wavg"},
        {"sWidth": '200px', "mDataProp": "wtemp"},
        {"sWidth": '300px', "mDataProp": "rrate"},
        {"sWidth": '260px', "mDataProp": null, bSearchable: false, bSortable: false}
    ],
    "sScrollY": "200px",
    "bPaginate": false
    });
 } );
 
 </script>
</body>
</html>