<html>
<head>
</head>
<body>
<pre>
<?php
 include_once('../gimmie.php');
 
 define("GIMMIE_CONSUMER_KEY", "7be295f5c136fb42ce4d9de20659");
 define("GIMMIE_SECRET_KEY", "c4e62a533da9e0a5f6993ceca211");
 
 $gimmie = new Gimmie(GIMMIE_CONSUMER_KEY, GIMMIE_SECRET_KEY);
 
 /* user id should be authenticate. this is hardcoded for demo purpose. */
 $gimmie->set_user('demo_user');

 if ($_POST["event_name"])
 {
   print_r($gimmie->trigger($_POST["event_name"])); 
 }

?>
</pre>

<form method="post">
  Event Name: <input type="text" name="event_name">
  <br />
  <input type="submit" value="Submit">
</form>

</body>
</html>