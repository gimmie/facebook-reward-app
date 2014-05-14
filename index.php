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
  
  $requireconnect = false;
  
  $signed_request = $facebook->getSignedRequest();
  
  $like_status = $signed_request["page"]["liked"];
  
  $page_id = $signed_request["page"]["id"];
  
  $fb_referer = $signed_request['app_data'];

  $facebook_app_url = "https://www.facebook.com/pages/-/".$page_id."?sk=app_".FACEBOOK_ID."&app_data=".$fb_referer;

  $_SESSION["pid"] = $page_id;
  
  if ($fb_referer && $fb_referer != $user_id)
  {
    try {
      $referrer = $facebook->api("/{$fb_referer}",'GET');
      $referrer_name = $referrer['name'];

      if ($referrer["email"])
      {
        $gimmie_referer_id = $referrer["email"];
        $_SESSION['referer']=$gimmie_referer_id;
      }
      else
      {
        $gimmie_referer_id = 'guest:'.$fb_referer;
      }
            
    } catch(FacebookApiException $e) {
      
    }    
    
  }
  
?>

<! DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="img/favicon.ico">

  <title><?php echo COMPANY_NAME; ?></title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/notifIt.js"></script>
  <script src="js/date.js"></script>
  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel='stylesheet' id='font-awesome-css'  href='css/font-awesome.min.css?ver=3.8.1' type='text/css' media='all' />
  <link href="css/notifIt.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <!-- Just for debugging purposes. Don't actually copy this line! -->
  <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script>
    var facebook_url = 'https://www.facebook.com/<?php echo $page_id; ?>';
  </script>
  
</head>

<body>
  <script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-24247316-2']);
    _gaq.push(['_setDomainName', 'gimmieworld.com']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

  </script>
  <div id="fb-root"></div>
      <script>
        window.fbAsyncInit = function() {
          FB.init({
            appId      : '<?php echo FACEBOOK_ID; ?>',
            status     : true,
            xfbml      : true
          });
          
          FB.Canvas.setAutoGrow();
        };

        (function(d, s, id){
           var js, fjs = d.getElementsByTagName(s)[0];
           if (d.getElementById(id)) {return;}
           js = d.createElement(s); js.id = id;
           js.src = "//connect.facebook.net/en_US/all.js";
           fjs.parentNode.insertBefore(js, fjs);
         }(document, 'script', 'facebook-jssdk'));
      </script>
      
      
  <div class="container">
      
  <?php
    if ($like_status) {
      
    $user_id = $facebook->getUser();

    $params = array(
      'scope' => 'email',
      'redirect_uri' => $facebook_app_url
    );

    if($user_id) {
      try {
        $user = $facebook->api('/me','GET');
        
        $user_email = $user["email"];
        $user_name = $user["name"];
       
        if ($user["email"])
        {
          $gimmie_user_id = $user["email"];
          //$gimmie->set_user($gimmie_user_id);
          //$gimmie->login('guest:'.$user_id);

        }
        else
        {
          $gimmie_user_id = 'guest:'.$user_id;
          //$gimmie->set_user($gimmie_user_id);
          //echo "We couldn't find any email in your Facebook account. Enjoy more benefit when you add an email in your Facebook account setting.<br /><br />";
        }
        
        
        
      } catch(FacebookApiException $e) {
        $requireconnect = true;
        $login_url = $facebook->getLoginUrl($params); 
        include("inc/join_content.php");
      }   
    } else {
      $requireconnect = true;
      $login_url = $facebook->getLoginUrl($params);
      include("inc/join_content.php");
    }
  
    if (!$requireconnect) { 
      if ($fb_referer && $fb_referer != $user_id)
       {
         if ($_SESSION['referer'])
         {
           //$gimmie->set_user($_SESSION['referer']);
           //$gimmie->trigger(GIMMIE_REFERRER_EVENT, $page_id.'_'.$user_id, 'name='.$user_name);
           unset($_SESSION['referer']);
         }
         else
         {
           //$gimmie->set_user($gimmie_referer_id);
           //$gimmie->trigger(GIMMIE_REFERRER_EVENT, $page_id.'_'.$user_id, 'name='.$user_name);
         }

       }

        $user_id_secret = md5($gimmie_user_id.COMPANY_NAME);

        //$gimmie->set_user($gimmie_user_id);
        //$gimmie_user = $gimmie->trigger(GIMMIE_LIKE_EVENT, $page_id);

        $availablepoint = $gimmie_user["response"]["user"]["awarded_points"] - $gimmie_user["response"]["user"]["redeemed_points"];

        $pos = strrpos($facebook_app_url, "?");

        if ($pos === false) {
          $sharelink = "https://www.facebook.com/sharer/sharer.php?u=".urlencode($facebook_app_url."?app_data=".$user_id);
        }    
        else
        {
          $sharelink = "https://www.facebook.com/sharer/sharer.php?u=".urlencode($facebook_app_url."&app_data=".$user_id);
        }

        include("inc/reward_content.php");
      }    
    }
    else
    {
      include("inc/like_content.php");
    } 
  ?>

  <div class="footer-poweredby"><a href="http://www.gimmieworld.com/facebook-plugin/?utm_source=facebookfanpage&utm_medium=gimmiewidget&utm_campaign=fbplugin" target="_blank">get free rewards for your facebook fan page</a></div>
</div>





</body>
</html>