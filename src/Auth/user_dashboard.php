<?php
session_start();


  ?>
   <html>
  <head>
  	<title>dashboard</title>
  </head>
  <body>
  <h1>session and auth working !</h1>
  Welcome, <?php
  echo $_SESSION['user_name'];
  // ending sessions here now....will be extended later.
  session_unset();
  ?> 
  </body>
  </html>