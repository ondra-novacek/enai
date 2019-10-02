<?php
print 'ahoj';
try {
  $exitCode = Artisan::call('config:cache', []);
  print 'jupi';
}
catch(Exception $e){
print 'fail ';
 
}


?>