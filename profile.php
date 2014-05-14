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
  
  $page_id = $_SESSION["pid"];

  $gimmie = new Gimmie(GIMMIE_CONSUMER_KEY, GIMMIE_SECRET_KEY);

  $user_id = $facebook->getUser();

  if($user_id) {
    
    
     try {
       $user = $facebook->api("/{$user_id}",'GET');

       $user_email = $user["email"];
       $user_name = $user["name"];

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

   if (!$requireconnect && $page_id) {
     if ($fb_referer && $fb_referer != $user_id)
      {
        if ($_SESSION['referer'])
        {
          $gimmie->set_user($_SESSION['referer']);
          $gimmie->trigger(GIMMIE_REFERRER_EVENT, $page_id.'_'.$user_id, 'name='.$user_name);
          unset($_SESSION['referer']);
        }
        else
        {
          $gimmie->set_user($gimmie_referer_id);
          $gimmie->trigger(GIMMIE_REFERRER_EVENT, $page_id.'_'.$user_id, 'name='.$user_name);
        }

      }
          
      $user_id_secret = md5($gimmie_user_id.COMPANY_NAME);

      $gimmie->set_user($gimmie_user_id);
      $gimmie_user = $gimmie->trigger(GIMMIE_LIKE_EVENT, $page_id);

      $availablepoint = $gimmie_user["response"]["user"]["awarded_points"] - $gimmie_user["response"]["user"]["redeemed_points"];

      $pos = strrpos($facebook_app_url, "?");

      if ($pos === false) {
        $sharelink = "https://www.facebook.com/sharer/sharer.php?u=".urlencode($facebook_app_url."?app_data=".$user_id);
      }
      else
      {
        $sharelink = "https://www.facebook.com/sharer/sharer.php?u=".urlencode($facebook_app_url."&app_data=".$user_id);
      }
?>

  <div class="profile-info">

    <div class="row">

      <div class="col-xs-12 middle">
        <h2>Hi <?php echo $user_name; ?>,<br />you have <span class="point-container"><?php echo $availablepoint; ?></span> points to redeem rewards <span class="fa-gift"></span></h2>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 middle">
        Share the page with your friends<br />
        Earn 10 points for every friend that likes our page and join the program
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 middle">
        <a class="btn btn-lg btn-facebook">share with friends</a>
        <hr />
      </div>
    </div>

    <div class="row profile-activites-redemptions">

      <div class="col-xs-6">
        <h3>Recent Activities</h3>
        <ul class="activitylist" id="recentactivity">
          <li class="middle"><img src="img/loader.gif" class="loader" /></li>
        </ul>

        <a class="right" id="view-activity-list">See All Recent Activities <span class="fa-arrow-right"></span></a>
      </div>

      <div class="col-xs-6">
        <h3>Redemptions</h3>
        <ul class="redemptionlist" id="redemptionlist">
          <li class="middle"><img src="img/loader.gif" class="loader" /></li>
        </ul>

        <a class="right" id="view-redemption-list">See All Redemptions <span class="fa-arrow-right"></span></a>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 middle title">
       <span class="fa-gift"></span> Reward Catalog <span class="fa-gift"></span>
      </div>
    </div>


    <div class="row" id="rewardlist">

      <div class="col-xs-12 middle">
        <img src="img/loader.gif" class="loader" />
      </div>


    </div>
  </div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script>
  $(function () {

    $.get( "recent_activites.php", function( data ) {
      $( "#recentactivity" ).html( data );
    });

    $.get( "recent_redemptions.php", function( data ) {
      $( "#redemptionlist" ).html( data );
    });

    $.get( "reward_list.php", function( data ) {
      $( "#rewardlist" ).html( data );
    });

     $('.btn-facebook').click(function (e) {

       window.open($('#content').attr('sharelink'), '_blank');

      });

    $('#view-activity-list').click(function (e) {

      $( "#content" ).html('<div class="middle"><img src="img/loader.gif" class="loader"></div>');
      FB.Canvas.scrollTo(0,0);

      $.get( "activity_list.php", function( data ) {
        $( "#content" ).html( data );
      });

     });

     $('#view-redemption-list').click(function (e) {

       $( "#content" ).html('<div class="middle"><img src="img/loader.gif" class="loader"></div>');
       FB.Canvas.scrollTo(0,0);

       $.get( "redemption_list.php", function( data ) {
         $( "#content" ).html( data );
       });

      });

  });
</script>
<?php
   }
?>


