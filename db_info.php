<?php 

  $dbh = new PDO('mysql:host=localhost;dbname=test;charset=utf8','root','',array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));


?>