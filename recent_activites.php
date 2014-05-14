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
       $gimmie_user_activities = $gimmie->recent_activites();       
       
     } catch(FacebookApiException $e) {
       $requireconnect = true;
     }   
   } else {
     $requireconnect = true;
   } 
   
   if (!$requireconnect) {
     if ($gimmie_user_activities['response']['recent_activities'])
     {
       $i = 0;
       foreach ($gimmie_user_activities['response']['recent_activities'] as &$recent_activity) {
?>
       <li>
         <div class="row"> 
           <div class="col-xs-8"><small><?php echo $recent_activity['content']; ?></small></div>
           <div class="col-xs-4 right"><small><?php echo time_elapsed_string($recent_activity['created_at']); ?></small></div>
         </div>
       </li>
<?php
        $i++;
        if ($i > 2) {
          break;
        }
      }
    }
    else
    {
?>
      <li class="middle">No activity yet.</li>
<?php      
    }
   }
?>