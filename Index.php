<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset="UTF-8"/>
  <title>Met Station Requests</title>
  <link rel="stylesheet" type="text/css" href="styling.css">
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(function() {
    $( "#from" ).datepicker({
      defaultDate: new Date(2013, 11 - 1, 21),
      changeMonth: false,
      numberOfMonths: 1,
      minDate: new Date(2013, 11 - 1, 21),
      maxDate: "+0D",
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#to" ).datepicker({
      defaultDate: "+0d",
      changeMonth: false,
      numberOfMonths: 1,
      minDate: new Date(2013, 11 - 1, 21),
      maxDate: "+0D",
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
  });

  $(function(){
   $(" #Stime ").click(function(){
    if (this.checked) {
     $("#Shour").attr("disabled", false); $("#Smin").attr("disabled", false); $("#Sday").attr("disabled", false);
    }
    else {
     $("#Shour").attr("disabled", true); $("#Smin").attr("disabled", true); $("#Sday").attr("disabled", true);
    }
   });
  });

   $(function(){
   $(" #Ftime ").click(function(){
    if (this.checked) {
     $("#Fhour").attr("disabled", false); $("#Fmin").attr("disabled", false); $("#Fday").attr("disabled", false);
    }
    else {
     $("#Fhour").attr("disabled", true); $("#Fmin").attr("disabled", true); $("#Fday").attr("disabled", true);
    }
   });
  });
 </script>

</head>

<body>
<div id="wrapper">
<div id="header"></div>
<div id="nav"><a href="http://www.eri.ucsb.edu">ERI Home</a></div>

<section id="main">
  <h1 align="center">Data From Met Stations</h1>
  <h2>Met Stations</h2>
  <p>
  <form method="post" action="process.php">

  <label for="station">Select Station:</label>
  	<select name="station" id="station">
<?php
foreach($metstations as $key => $desc){
    echo "<option value=\"$key\">$desc</option>\n";
}
 ?>
 <!--
  		<option value="met1">Met 1: Ellsion Hall</option>
                  <option value="met2">Met 2: MSB</option>
              -->
  	</select>
  <label for="from"><br>Choose Dates:<br>From:</label>
  	<input type="text" id="from" name="from">
  <label for="Stime">Start Time:</label>
     <input name="Stime" type="checkbox" id="Stime" value="tcheck">
  	<select name="Shour" id="Shour" disabled="disabled">
  		<option value="0">12</option>
  		<option value="1">1</option>
  		<option value="2">2</option>
  		<option value="3">3</option>
  		<option value="4">4</option>
  		<option value="5">5</option>
  		<option value="6">6</option>
  		<option value="7">7</option>
  		<option value="8">8</option>
  		<option value="9">9</option>
  		<option value="10">10</option>
  		<option value="11">11</option>
          </select>
  <label for="Smin">:</label>
          <select name="Smin" id="Smin" disabled="disabled"><option value="00">00</option><option value="15">15</option>
          <option value="30">30</option><option value="45">45</option></select>
  <label for="Sday">:</label>
          <select name="Sday" id="Sday" disabled="disabled"><option value="am">am</option><option value="pm">pm</option></select>

  <label for="to"><br>To:</label>
  	<input type="text" id="to" name="to">
  <label for="Ftime">End Time:</label>
     <input name="Ftime" type="checkbox" id="Ftime" value="fcheck">
          <select name="Fhour" id="Fhour" disabled="disabled">
  		<option value="0">12</option>
  		<option value="1">1</option>
  		<option value="2">2</option>
  		<option value="3">3</option>
  		<option value="4">4</option>
  		<option value="5">5</option>
  		<option value="6">6</option>
  		<option value="7">7</option>
  		<option value="8">8</option>
  		<option value="9">9</option>
  		<option value="10">10</option>
  		<option value="11">11</option>
          </select>
  <label for="Fmin">:</label>
          <select name="Fmin" id="Fmin" disabled="disabled"><option value="00">00</option><option value="15">15</option>
          <option value="30">30</option><option value="45">45</option></select>
  <label for="Fday">:</label>
          <select name="Fday" id="Fday" disabled="disabled"><option value="am">am</option><option value="pm">pm</option></select>

  <label for="every"><br>Every:</label>
  	<select name="every" id="every"><option value="60">60 Minutes</option><option value="30">30 Minutes</option>
  	<option value="5">5 Minutes</option><option value="1">1 Minute</option></select>

  <input type="submit" value="Send">

  </form>
  </p>
  </section>
</div>
</body>
</html>
