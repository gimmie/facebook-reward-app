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
?>
  <div class="content-info">

    <div class="row" style="position:relative;">
      <div class="col-xs-12 right">
        <h1>Recent Activities</h1>
      </div>
      <div id="back-button"><span class="fa-arrow-left"></span>Back</div>      
    </div>    

    <div class="row">
      <div class="col-xs-12">
        
        <ul class="activitylist">
          
          <?php 
        if ($gimmie_user_activities['response']['recent_activities'])
        {          
          foreach ($gimmie_user_activities['response']['recent_activities'] as &$recent_activity) { 
        ?>  
            <li>
              <div class="row"> 
                <div class="col-xs-8"><?php echo $recent_activity['content']; ?></div>
                <div class="col-xs-4 right"><?php echo time_elapsed_string($recent_activity['created_at']); ?></div>
              </div>
            </li>
          <?php 
          } 
        }
        else
        {
        ?>  
          <li class="middle">No activity yet.</li>
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


