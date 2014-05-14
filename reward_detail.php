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
       $gimmie_user = $gimmie->profile();
       $availablepoint = $gimmie_user["response"]["user"]["awarded_points"] - $gimmie_user["response"]["user"]["redeemed_points"];
       $gimmie_reward = $gimmie->rewards($_GET["reward_id"]);       
       
     } catch(FacebookApiException $e) {
       $requireconnect = true;
     }   
   } else {
     $requireconnect = true;
   } 
   
   if (!$requireconnect) {
?>
  <div class="content-info">
    <div id="message"></div>
    <div class="row" style="position:relative;">
      <div class="col-xs-12 right">
        <h1><?php echo $gimmie_reward['response']['rewards'][0]['name']; ?></h1>
        <h2 class="storename"><?php echo $gimmie_reward['response']['rewards'][0]['store_name']; ?></h2>
      </div>
      
      <div id="back-button"><span class="fa-arrow-left"></span>Back</div>
    </div>    

    <div class="row">
      <div class="col-xs-7">
        <div class="gimmie-reward-image">
          
          <?php if ($reward['feature']) { ?>
           <img class="gimmie-featured" src="//api.gimmieworld.com/cdn/client-featured-ribbon.png">
          <?php } ?>
          
          <img class="gimmie-reward" src="<?php echo $gimmie_reward['response']['rewards'][0]['image_url_retina']; ?>">
        </div>        
      </div>
      <div class="col-xs-5">
        <div class="right">
          <div class="availablepoint">Available Point: <span class="gimmie-point"><?php echo $availablepoint?></span></div>
          <div class="redeem-container ">
          <?php if ($gimmie_reward['response']['rewards'][0]['total_quantity'] > $gimmie_reward['response']['rewards'][0]['claimed_quantity'] || $gimmie_reward['response']['rewards'][0]['total_quantity'] == -1) { ?>
        <a href="#" class="btn btn-primary btn-lg" role="button" id="gimmie-redeem-button" gimmie-reward="<?php echo $gimmie_reward['response']['rewards'][0]['id']; ?>">Redeem with <?php echo $gimmie_reward['response']['rewards'][0]['points']; ?> pts</a>            
          <?php } ?>
          </div>
      </div>
        <div class="row">
          <h3 id="gimmie-description-header">Description</h3>
          <p id="gimmie-description-content"><?php echo nl2br($gimmie_reward['response']['rewards'][0]['description']); ?></p>
          <h3 id="gimmie-fineprint-header">Fine Print</h3>
          <p id="gimmie-fineprint-content"><?php echo nl2br($gimmie_reward['response']['rewards'][0]['fine_print']); ?></p>
        </div>        
      
      </div>      
    </div>

  
  </div>

<script>
$(function () {
  $('.content-info #back-button').click(function (e) {
    
    $("#ui_notifIt").hide();
    
    $( "#content" ).html('<div class="middle"><img src="img/loader.gif" class="loader"></div>');
    FB.Canvas.scrollTo(0,0);
    
    $.get( "profile.php", function( data ) {
      $( "#content" ).html( data );
    });

   });
   
   $('.content-info #gimmie-redeem-button').click(function (e) {
     
     $( ".redeem-container" ).html('<div class="middle"><img src="img/loader.gif" class="loader"></div>');
      
     $.getJSON( "redeem_action.php?reward_id="+$(this).attr('gimmie-reward'), function( data ) {
       if (data.response.success) {
         notif({
            msg: "Congratulation! You just successfully redeemed this reward",
            type: "success",
            position: "center",
            width: 500,
            height: 35,
            autohide: false
          });
          
          var reward_url = data.response.claim.reward.url;
          reward_url = reward_url.replace("=EMAIL", "=<?php echo $user["email"]; ?>");
          reward_url = reward_url.replace("=NAME", "=<?php echo $user["name"]; ?>");
          
          var available_point = data.response.user.awarded_points - data.response.user.redeemed_points;
          
          $( ".redeem-container" ).html('<a href="'+reward_url+'" target="_blank" class="btn btn-primary btn-lg" role="button" id="gimmie-use-button">Use Reward Now</a><div class="expiry-info">Expiry Date: '+$.format.date(data.response.claim.reward.valid_until, "MMM dd, yyyy")+'</div>');
          
          $( ".gimmie-point" ).text(available_point);
       }
       else
       {
         notif({
            msg: data.error.message,
            type: "success",
            position: "center",
            width: 500,
            height: 35,
            autohide: false
          });
          
          $( ".redeem-container" ).html('<a class="btn btn-primary btn-lg" role="button" id="gimmie-use-button">Insufficient Point</a>');
          $( ".gimmie-point" ).text(available_point);          
       }
     });
     

     
     
    });   
});
</script>
<?php
   }
?>


