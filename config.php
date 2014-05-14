<?php

date_default_timezone_set('Asia/Singapore'); //Set the timezone

define("GIMMIE_CONSUMER_KEY", "<CONSUMER KEY>"); //Get this from Gimmie portal after creating Game
define("GIMMIE_SECRET_KEY", "<SECRET KEY>"); //Get this from Gimmie portal after creating Game
define("FACEBOOK_ID", "<FACEBOOK ID>"); //Create a Facebook app and get this from Facebook
define("FACEBOOK_SECRET", "<FACEBOOK_SECRET>"); //Create a Facebook app and get this from Facebook
define("COMPANY_NAME", "<COMPANY NAME>"); //A name for the company
define("LOCALE_CODE", "en_US"); //Locale for the reward content
define("COUNTRY_CODE", "global"); //Set the reward country (global mean it will detect user IP to list reward. *this can slow down the page)
define("GIMMIE_REFERRER_EVENT", "did_fanpage_referral"); //Create the event "did_fanpage_referral" for your Game in Gimmie (trigger when user refer someone to join. Set frequency to 1)
define("GIMMIE_LIKE_EVENT", "did_fanpage_like"); //Create the event "did_fanpage_like" for your Game in Gimmie (trigger when LIKE fan page. Set frequency to 1)

?>