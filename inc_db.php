<?php
   class MyDB extends SQLite3
   {
      function __construct()
      {
         $this->open('fancyMCU.sqlite');
      }
   }
   $db = new MyDB();
   if(!$db){
      echo $db->lastErrorMsg();
   } else {
      //echo "Opened database successfully\n";
   }

   
?>