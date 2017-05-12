<html>
 <head>
  <title>PHP MYSQL Gen</title>
 </head>
 <body>


 <?php 

 //ini_set('display_errors',1);
 
 class sensor{
 	 function __construct(){
 	 }
 	 function add($dbfield,$dbtype,$meteobridge_query){
 	 	 if( $dbtype == "f" ) $dbtype = 'float';
 	 	 $this->dbType[$dbfield] = $dbtype;
 	 	 $this->dbMeteo[$dbfield] = $meteobridge_query;
 	 }
 	 function display(){
 	 	 print "<br />\n";
 	 	 //$junk = array();
 	 	 //$junk2 = array();
 	 	 foreach( array_keys($this->dbType) as $k){
 	 	 	 print $this->dbType[$k] . " => \n";
 	 	 	 print $this->dbMeteo[$k] . "<br />\n";
 	 	 }
 	 }
  	 function displayCreate(){
  	 	 print "create table metdata (";
  	 	 foreach( array_keys($this->dbMeteo) as $i){
  	 	 	 print $i . " ";
  	 	 	 if($i== 'raintotal'){	break;	}
 	 	 	 print $this->dbType[$i] . ",";
  	 	 }
  	 	 print $this->dbType[$i] . ")" . "<br />\n";
  	 }
  	 function displayMeteoBridgeInsert(){
  	 	 print "insert into metdata (";
  	 	 foreach( array_keys($this->dbMeteo) as $i){
  	 	 	 if($i== 'rowid'){	continue;	}
  	 	 	 if($i== 'raintotal'){	break;	}
  	 	 	 print $i . ",";
  	 	 }
  	 	 print $i . ")" . " VALUES (";
  	 	 foreach( array_keys($this->dbMeteo) as $j){
  	 	 	if($j== 'rowid'){	continue;	}
  	 	 	if($j== 'raintotal'){	break;	}
  	 	 	print $this->dbMeteo[$j] . ",";
  	 	 }
  	 	  print $this->dbMeteo[$j] . ")" ;
  	 }
}
 
 //echo 'create table metdata (rowid int not null auto_increment primary key, station varchar(32),date date, time time, temp double, humidity double, dewpt double, heat double, press double, seapress double, windspeed double,windavg double, winddir double, windchill double, rainrate double, raintotal double, uvindex double,solarrad double);\n';
 //echo "insert into metdata (date,time,station,temp,humidity,dewpt,heat,press,seapress,windspeed,windavg,winddir,windchill,rainrate,raintotal) VALUES ('[YYYY]-[MM]-[DD]','[hh]:[mm]:[ss]','met1','[th0temp-act=F.1:-9999]','[th0hum-act=.1:-9999]','[th0dew-act.1:-9999]', '[th0heatindex-act.1:-9999]','[thb0press-act.1:-9999]','[thb0seapress-act.1:-9999]','[wind0wind-act.1:-9999]','[wind0avgwind-act.1:-9999]','[wind0dir-act.1:-9999]','[wind0chill-act.1:-9999]','[rain0rate-act.1:-9999]','[rain0total-act.1:-9999]','[uv0index-act.1:-9999]','[sol0rad-act.1:-9999]')";

 $s = new sensor();
 $s->add('rowid','int not null primary key auto_increment primary key','');
 $s->add('date','date','\'[YYYY]-[MM]-[DD]\'');
 $s->add('time','time','\'[hh]:[mm]:[ss]\'');
 $s->add('station','varchar(32)','\'met1\'');
 $s->add('temp','f','[th0temp-act=F.1:-9999]');
 $s->add('humidity','f','[th0hum-act=.1:-9999]');
 $s->add('dewpt','f','[th0dew-act=F.1:-9999]');
 $s->add('heat','f','[th0heatindex-act=F.1:-9999]');
 $s->add('press','f','[thb0press-act=psi.1:-9999]');
 $s->add('seapress','f','[thb0seapress-act=psi.1:-9999]');
 $s->add('windspeed','f','[wind0wind-act=mph.1:-9999]');
 $s->add('windavg','f','[wind0avgwind-act=mph.1:-9999]');
 $s->add('winddir','f','[wind0dir-act.1:-9999]');
 $s->add('windchill','f','[wind0chill-act=F.1:-9999]');
 $s->add('rainrate','f','[rain0rate-act=in.1:-9999]');
 $s->add('raintotal','f','[rain0total-sumday=in.1:-9999]');
 //$s->display();
 $s->displayCreate();
 $s->displayMeteoBridgeInsert();
 ?>
 
</body>
</html>
