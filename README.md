# webMet
Requires DataTables-1.9.4

found that at datatables.net

Modified in 2017-05 to use datatables js and css from newer CDN version which
allow resolved some display issues.  Only bumped up to 1.10.15, but could easily
explore bumping that up higher :-)

## Lost the original code during the rebuild of discovery
Was able to recover some form of the code from an intermediate copy.
Cleaned up what was there, got it into this repo and did some additional development/cleanup.

## Met station issues
Power outages in late 2017 seems to have messed up met1 meteobridge unit.
Should document this elsewhere, but starting here for the time being.

## 2018-02-26 looking for meteobridge config info
There is some stuff here in deprecated/old/gen.php

''' //echo "insert into metdata (date,time,station,temp,humidity,dewpt,heat,press,seapress,windspeed,windavg,winddir,windch
ill,rainrate,raintotal) VALUES ('[YYYY]-[MM]-[DD]','[hh]:[mm]:[ss]','met1','[th0temp-act=F.1:-9999]','[th0hum-act=.1:-99
99]','[th0dew-act.1:-9999]', '[th0heatindex-act.1:-9999]','[thb0press-act.1:-9999]','[thb0seapress-act.1:-9999]','[wind0
wind-act.1:-9999]','[wind0avgwind-act.1:-9999]','[wind0dir-act.1:-9999]','[wind0chill-act.1:-9999]','[rain0rate-act.1:-9
999]','[rain0total-act.1:-9999]','[uv0index-act.1:-9999]','[sol0rad-act.1:-9999]')";'''
