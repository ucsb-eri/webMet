# var folder is for the processing script to write data to 

Should set up a cron job on the system to purge this data at regular intervals
Something like the following should work; make sure to modify the path to suit your needs.

```
54 2 * * * root find /var/www/vhosts/webMet/var -type f -name '*.csv' -mtime +1 -exec /bin/rm {} \; 
```
