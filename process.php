<html>
 <head>
 <meta charset="UTF-8"/>
 <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
 <script type="text/javascript" charset="utf-8" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
 <!--
 <script type="text/javascript" charset="utf-8" src="DataTables-1.9.4/media/js/jquery.dataTables.js"></script>
 -->
  <title>Process Met SQL</title>
  <style type="text/css" title="currentStyle">
    @import "DataTables-1.9.4/media/css/demo_table.css";
  </style>
  <link rel="stylesheet" type="text/css" href="styling.css">
</head>

<body>
<?php
require_once 'config.php';
$output['aaData'] = array();

//echo "From: {$_POST['from']} <br/>\n";
//echo "startDate: $startDate<br/>\n";
date_default_timezone_set('America/Los_Angeles');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$startDate = ( isset($_POST["from"]) && $_POST['from'] != '' ) ? date("Y-m-d", strtotime($_POST["from"])) : date("Y-m-d");
	$endDate = ( isset($_POST["to"]) && $_POST['to'] != '' ) ? date("Y-m-d", strtotime($_POST["to"])) : date("Y-m-d");
	//if ( isset($_POST["to"]) )	{	$endDate = date("Y-m-d", strtotime($_POST["to"]));	}
	//else { $endDate = date("Y-m-d"); }

	if ( isset($_POST["Stime"])){
		if ( $_POST["Sday"] == "pm" ){	$startTime = ($_POST["Shour"] + 12);	$startTime .= ":";	$startTime .= $_POST["Smin"];	$startTime .= ":00";	}
		else	{	$startTime = ($_POST["Shour"]);	$startTime .= ":";	$startTime .= $_POST["Smin"];	$startTime .= ":00";	}
        }
	else { $startTime = "00:00:00"; }

	if ( isset($_POST["Ftime"])){
		if ( $_POST["Fday"] == "pm" ){	$endTime = ($_POST["Fhour"] + 12);	$endTime .= ":";	$endTime .= $_POST["Fmin"];	$endTime .= ":59";	}
		else {	$endTime = ($_POST["Fhour"]);	$endTime .= ":";	$endTime .= $_POST["Fmin"];	$endTime .= ":59";	}
	}
	else { $endTime = "24:00"; }

	$every=$_POST["every"];
	$station = $_POST["station"];
}
else{
	$startDate = date("Y-m-d");
	$endDate = date("Y-m-d");
	$every="60";
	$startTime = "00:00";
	$endTime = "24:00";
	$station = "met1";
}

// Create connection to mysql
$user = 'browse';
$columns=array();
try {
	$conn = new PDO('mysql:host=localhost;dbname=webmet', $user, '');
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {	echo 'ERROR: ' . $e->getMessage();	}

if( ! is_null($conn)){
    try {
        //get columns from table
        $stmt = $conn->prepare('SHOW columns FROM metdata');
        $stmt->execute();
        while($temp = $stmt->fetch()) {
            if ( $temp['Field'] != "rowid" && $temp['Field'] != "station" ){
                $columns[]=$temp['Field'];
            }
        }
        //search database for dates(startdate + enddate + time + mod with every varaible)
        //$stmt = $conn->prepare('SELECT date, time, temp, humidity, dewpt, heat, press, seapress, windspeed, windavg, winddir, windchill, rainrate, raintotal FROM metdata WHERE date BETWEEN :startDate AND :endDate AND mod(minute(time),:every) = 0');
        //$stmt->execute(array('startDate' => $startDate, 'endDate' => $endDate,'every' => $every));
        $stmt = $conn->prepare('SELECT date, time, temp, humidity, dewpt, heat, press, seapress, windspeed, windavg, winddir, windchill, rainrate, raintotal FROM metdata WHERE station=:station AND date BETWEEN :startDate AND :endDate AND time between :startTime AND :endTime AND mod(minute(time),:every) = 0');
        $stmt->execute(array('station' => $station, 'startDate' => $startDate, 'endDate' => $endDate, 'startTime' => $startTime, 'endTime' => $endTime,'every' => $every));
        $row = $stmt->fetchAll();

        //encode results into json for dataTables
        $iTotal=count($row);
        $output = array(
            "sEcho" => 1,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );
        for ( $j=0 ; $j<count($row) ; $j++ ){
            $aRow=array();
            for ( $i=0 ; $i<count($columns) ; $i++ ){
                $aRow[] = $row[$j][ $columns[$i] ];
            }
            $output['aaData'][] = $aRow;
        }
        //$json_output =  json_encode( $output );
        //$name = '/var/www/vhosts/webmet.eri.ucsb.edu';
        //$filename ='/admin/test/' . $station . "_" . $startDate . "_" . $endDate . "_" . uniqid() . ".csv";
        //$filename ='/admin/test/' . $station . "_" . $startDate . "_" . $endDate . "_" . uniqid() . ".csv";
        //$name .= $filename;

        $name ='./var/' . $station . "_" . $startDate . "_" . $endDate . "_" . uniqid() . ".csv";

        $fp = fopen($name, 'w');
        if(!is_resource($fp)){
            echo "ERROR: CSV RESOURCE NOT AVAILABLE";
        }
        else{
            //take json results and put into downloadable csv file
            foreach ($output['aaData'] as $fields) {
                fputcsv($fp, $fields);
            }
        }

        if( $fp === TRUE ){	fclose($fp);	}

    } catch(PDOException $e) {	echo 'ERROR: ' . $e->getMessage();	}
}
?>

<div id="header"></div>
  <div id="nav">
    <a href="http://www.eri.ucsb.edu">ERI Home</a><br/>
    <a href="./">Request Page</a><br/>
    <a href="http://met1.eri.ucsb.edu">Met1 Station</a>
  </div>
</div>

<section id="main">
<h1>Data From Met Stations</h1>
<h2><?php echo $metstations[$station]; ?> Station Processed Data</h2>

<div id="info">
<h3>Download Information</h3>
<div id="left">
<p>Requested Data:</br>StartDate:<?php echo $startDate; ?></br>StartTime:<?php echo $startTime; ?></br>Every:<?php echo $every . " Minutes"; ?></p>
</div>
<div id="right">
<p></br>EndDate:<?php echo $endDate; ?></br>EndTime:<?php echo $endTime; ?></p>
</div>
<div class="clear"></div>
<form method="get" action=<?php echo $name; ?>>
<button type="submit">Download Met Data</button>
</form>
</div>

<div id="dynamic"><table cellpadding="0" cellspacing="0" border="0" class="display" id="table_id"></table></div>

<script>
$(document).ready(function() {
 $('#table_id').dataTable( {
        "aaData": [
    <?php
	$x=0;
	foreach ($output['aaData'] as $value){
		if ( $x == 0) {	echo '["';	$x = 1;	}
		else {	echo ',["';	}
                echo implode('", "', $value);
                echo '"]';
	}
	?>
	],
        "aoColumns": [
        /*
            { "sTitle": "date", "sWidth":"10%" },
            { "sTitle": "time", "sWidth":"10%" },
            { "sTitle": "temp", "sWidth":"3%" },
            { "sTitle": "hum" , "sWidth":"3%" },
            { "sTitle": "dew" , "sWidth":"3%" },
            { "sTitle": "heat" , "sWidth":"3%" },
            { "sTitle": "press" , "sWidth":"3%" },
            { "sTitle": "seapress" , "sWidth":"3%" },
            { "sTitle": "wspeed" , "sWidth":"3%" },
            { "sTitle": "wavg" , "sWidth":"3%" },
            { "sTitle": "wdir" , "sWidth":"3%" },
            { "sTitle": "wchill" , "sWidth":"3%" },
            { "sTitle": "rrate" , "sWidth":"3%" },
            { "sTitle": "rtotal", "sWidth":"3%"  }
            */
            { "sTitle": "date"},
            { "sTitle": "time"},
            { "sTitle": "temp"},
            { "sTitle": "hum" },
            { "sTitle": "dew" },
            { "sTitle": "heat"  },
            { "sTitle": "press" },
            { "sTitle": "seapress" },
            { "sTitle": "wspeed" },
            { "sTitle": "wavg" },
            { "sTitle": "wdir" },
            { "sTitle": "wchill" },
            { "sTitle": "rrate" },
            { "sTitle": "rtotal"}
        ],
        "sScrollY": "40%",
        "bPaginate": false,
        "sScrollX": "70%",
        "sScrollXInner": "100%",
	"bScrollCollapse": false,
	"bAutoWidth": false

    } );
} );
</script>

</section>
<div id="fillerWrapper">
    <div id="filler">&nbsp;</div>
</div>
</body>
</html>
