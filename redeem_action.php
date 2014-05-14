<?php
  require 'config.php';
  require 'inc/facebook.php';
  require 'inc/functions.php';

  $app_id = FACEBOOK_ID;
  $app_secret = FACEBOOK_SECRET;
  $facebook = new Facebook(array(
    'appId' => $app_id,
    'secret' => $app_secret,
    'cookie' => true,
    
  ));
  
  $gimmie = new Gimmie(GIMMIE_CONSUMER_KEY, GIMMIE_SECRET_KEY);  
  
  $reward_id = $_GET['reward_id'];
  $user_id = $facebook->getUser();

  if($user_id) {
     try {
       $user = $facebook->api("/{$user_id}",'GET');
       
       $user_email = $user["email"];
       
       if ($user["email"])
       {
         $gimmie_user_id = $user["email"];
       }
       else
       {
         $gimmie_user_id = 'guest:'.$user_id;
       }
       
       $gimmie->set_user($gimmie_user_id);
       
     } catch(FacebookApiException $e) {
       $requireconnect = true;
     }   
   } else {
     $requireconnect = true;
   } 
   
   if (!$requireconnect) {
    
     echo json_encode($gimmie->redeem($reward_id));
   }
?>


