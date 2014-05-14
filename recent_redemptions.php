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
       $gimmie_user_profile = $gimmie->profile();       
       
     } catch(FacebookApiException $e) {
       $requireconnect = true;
     }   
   } else {
     $requireconnect = true;
   } 
   
   if (!$requireconnect) {
     if ($gimmie_user_profile['response']['claims'])
     {
       $i = 0;
       foreach ($gimmie_user_profile['response']['claims'] as &$recent_claim) {
         $claim_url = $recent_claim['reward']['url'];
         $claim_url = str_replace("=NAME", "=".$user["name"], $claim_url);
         $claim_url = str_replace("=EMAIL", "=".$user["email"], $claim_url);
?>
     <li class="claim-reward">
       <a href="<?php echo $claim_url; ?>" target="_blank"><img src="<?php echo $recent_claim['reward']['image_url']; ?>" /></a>
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
    <li class="middle">No redemption yet.</li>
<?php
      }
   }
?>