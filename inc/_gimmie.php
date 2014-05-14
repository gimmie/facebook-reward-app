<?php
include_once('Gimmie_OAuth.php');
  
class Gimmie {
  
  private static $instance;
  private $gimmie_root = 'https://api.gimmieworld.com';
  
  public static function getInstance($key, $secret) {
    if (!self::$instance) {
      self::$instance = new Gimmie($key, $secret);
    }
    return self::$instance;
  }
  
  function __construct($key, $secret) {
    $this->key = $key;
    $this->secret = $secret;
  }
  
  public function set_user($user_id) {
    $this->user_id = $user_id;
  }

  public function categories() {
    $parameters = array(
    );
    return $this->invoke('categories', $parameters);
  }
  
  public function rewards($reward_id) {
    $parameters = array(
      'reward_id' => $reward_id
    );
    return $this->invoke('rewards', $parameters);
  }  
  
  public function profile() {
    $parameters = array(
    );
    return $this->invoke('profile', $parameters);
  }

  public function claims($claim_id) {
    $parameters = array(
      'claim_id' => $claim_id
    );
    return $this->invoke('claims', $parameters);
  }

  public function events($event_id) {
    $parameters = array(
      'event_id' => $event_id
    );
    return $this->invoke('events', $parameters);
  }
  
  public function badges($progress = 0) {
    $parameters = array(
      'progress' => $progress
    );
    return $this->invoke('badges', $parameters);
  }  

  public function trigger($event_name, $source_uid = "") {
    $parameters = array(
      'event_name' => $event_name,
      'source_uid' => $source_uid
      
    );
    return $this->invoke('trigger', $parameters);
  }

  public function check_in($mayorship_id, $venue) {
    $parameters = array(
      'venue' => $venue
      
    );
    return $this->invoke('check_in/'.$mayorship_id, $parameters);
  }

  public function redeem($reward_id) {
    $parameters = array(
      'reward_id' => $reward_id
    );
    return $this->invoke('redeem', $parameters);
  }

  public function gift($reward_id) {
    $parameters = array(
      'reward_id' => $reward_id
    );
    return $this->invoke('gift', $parameters);
  }

  public function top20points() {
    $parameters = array(
    );
    return $this->invoke('top20points', $parameters);
  }

  public function top20prices() {
    $parameters = array(
    );
    return $this->invoke('top20prices', $parameters);
  }

  public function top20redemptions_count() {
    $parameters = array(
    );
    return $this->invoke('top20redemptions_count', $parameters);
  }

  public function top20points_past_7_days() {
    $parameters = array(
    );
    return $this->invoke('top20points/past_7_days', $parameters);
  }
  
  public function top20prices_past_7_days() {
    $parameters = array(
    );
    return $this->invoke('top20prices/past_7_days', $parameters);
  }  
  
  public function top20redemptions_count_past_7_days() {
    $parameters = array(
    );
    return $this->invoke('top20redemptions_count/past_7_days', $parameters);
  }  

  public function top20points_past_week() {
    $parameters = array(
    );
    return $this->invoke('top20points/past_week', $parameters);
  }
  
  public function top20prices_past_week() {
    $parameters = array(
    );
    return $this->invoke('top20prices/past_week', $parameters);
  }  
  
  public function top20redemptions_count_past_week() {
    $parameters = array(
    );
    return $this->invoke('top20redemptions_count/past_week', $parameters);
  }
  
  public function top20points_this_week() {
    $parameters = array(
    );
    return $this->invoke('top20points/this_week', $parameters);
  }
  
  public function top20prices_this_week() {
    $parameters = array(
    );
    return $this->invoke('top20prices/this_week', $parameters);
  }  
  
  public function top20redemptions_count_this_week() {
    $parameters = array(
    );
    return $this->invoke('top20redemptions_count/this_week', $parameters);
  }  
  
  public function top20points_today() {
    $parameters = array(
    );
    return $this->invoke('top20points/today', $parameters);
  }
  
  public function top20prices_today() {
    $parameters = array(
    );
    return $this->invoke('top20prices/today', $parameters);
  }  
  
  public function top20redemptions_count_today() {
    $parameters = array(
    );
    return $this->invoke('top20redemptions_count/today', $parameters);
  }  
  
  public function top20points_past_30_days() {
    $parameters = array(
    );
    return $this->invoke('top20points/past_30_days', $parameters);
  }
  
  public function top20prices_past_30_days() {
    $parameters = array(
    );
    return $this->invoke('top20prices/past_30_days', $parameters);
  }  
  
  public function top20redemptions_count_past_30_days() {
    $parameters = array(
    );
    return $this->invoke('top20redemptions_count/past_30_days', $parameters);
  }  
  
  public function top20points_past_month() {
    $parameters = array(
    );
    return $this->invoke('top20points/past_month', $parameters);
  }
  
  public function top20prices_past_month() {
    $parameters = array(
    );
    return $this->invoke('top20prices/past_month', $parameters);
  }  
  
  public function top20redemptions_count_past_month() {
    $parameters = array(
    );
    return $this->invoke('top20redemptions_count/past_month', $parameters);
  } 
  
  public function top20points_this_month() {
    $parameters = array(
    );
    return $this->invoke('top20points/this_month', $parameters);
  }
  
  public function top20prices_this_month() {
    $parameters = array(
    );
    return $this->invoke('top20prices/this_month', $parameters);
  }  
  
  public function top20redemptions_count_this_month() {
    $parameters = array(
    );
    return $this->invoke('top20redemptions_count/this_month', $parameters);
  }   

  public function change_points($change, $description = "") {
    $parameters = array(
      'points' => $change,
      'description' => $description
    );
    return $this->invoke('change_points', $parameters);
  }
  
  public function recent_activites() {
    $parameters = array(
    );
    return $this->invoke('recent_activities', $parameters);
  }

  public function login($old_uid) {
    $parameters = array(
      'old_uid' => $old_uid
    );
    return $this->invoke('login', $parameters);
  }

  
  private function invoke($action, $parameters) {
    // Don't run anything if user doesn't login
    if (!isset($this->user_id)) return;
  
    $gimmie_root = $this->gimmie_root;
    $endpoint = "$gimmie_root/1/$action.json?";
    foreach ($parameters as $name => $value) {
      $endpoint .= "$name=$value&";
    }
    $endpoint = rtrim($endpoint, '&');
    
    $key = $this->key;
    $secret = $this->secret;
    
    $access_token = $this->user_id;
    $access_token_secret = $secret;
    
    $sig_method = new OAuthSignatureMethod_HMAC_SHA1();
    $consumer = new OAuthConsumer($key, $secret, NULL);
    $token = new OAuthConsumer($access_token, $access_token_secret);
    
    $acc_req = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $endpoint, $parameters);
    $acc_req->sign_request($sig_method, $consumer, $token);
    
    $json = file_get_contents($acc_req);
    
    $json_output = json_decode($json, TRUE);
    
    return $json_output;
  }
  
}