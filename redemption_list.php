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
?>
  <div class="content-info">

    <div class="row" style="position:relative;">
      <div class="col-xs-12 right">
        <h1>Redemptions</h1>
      </div>
      <div id="back-button"><span class="fa-arrow-left"></span>Back</div>      
    </div>    

    <div class="row">
      <div class="col-xs-12">
        <ul class="redemptionlist">
      <?php 
        if ($gimmie_user_profile['response']['claims'])
        {    
          foreach ($gimmie_user_profile['response']['claims'] as &$recent_claim) {
            $claim_url = $recent_claim['reward']['url'];
            $claim_url = str_replace("=NAME", "=".$user["name"], $claim_url);
            $claim_url = str_replace("=EMAIL", "=".$user["email"], $claim_url);
      ?>  
           <li class="gimmie-redemption-row" gimmie-reward="<?php echo $recent_claim['reward']['id'];?>" gimmie-href="<?php echo $claim_url; ?>">
             <div class="row">
               <div class="col-xs-3">
                  <img src="<?php echo $recent_claim['reward']['image_url'];?>">
               </div>
               <div class="col-xs-6">
                 <div class="gimmie-reward-name"><?php echo $recent_claim['reward']['name'];?></div>
                  <div class="gimmie-reward-store"><?php echo $recent_claim['reward']['store_name'];?></div>
                  <div class="gimmie-expires-date">Expires Apr 30, 2014</div>
                  <div class="gimmie-redeemed-date">Redeemed Mar 07, 2014</div>
               </div>
               <div class="col-xs-3 right">
                 <a class="btn btn-primary btn-lg claim-button" href="<?php echo $claim_url; ?>" target="_blank">Use Reward <span class="fa-angle-right"></span></a>
               </div>
             </div>
           </li>
      <?php 
          } 
        }
        else
        {
      ?>
        <li class="middle">No redemption yet.</li>
      <?php    
        }
      ?>
        </ul>
      </div>
    </div>

  
  </div>
  
  <script>
  $(function () {
    $('.content-info #back-button').click(function (e) {

      $( "#content" ).html('<div class="middle"><img src="img/loader.gif" class="loader"></div>');
      FB.Canvas.scrollTo(0,0);

      $.get( "profile.php", function( data ) {
        $( "#content" ).html( data );
      });

    });
  
  });
  </script>
<?php
   }
?>


