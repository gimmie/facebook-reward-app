<?php
  include_once('Gimmie.sdk.php');
    
  $country_code = COUNTRY_CODE;
  
  if ($country_code == 'global')
  {
    $ip_info = ip_details(getRealIpAddr());
    
    $country_code = $ip_info->country;
  }
  
  function ip_details($ip) {
      $json = file_get_contents("http://ipinfo.io/{$ip}");
      $details = json_decode($json);
      return $details;
  }
  
  function friendly_name($string){
    $string = preg_replace("`\[.*\]`U","",$string);
    $string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i','-',$string);
    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
    $string = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i","\\1", $string );
    $string = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $string);
    return strtolower(trim($string, '-'));
  }
  
  function time_elapsed_string($ptime)
  {
      $etime = time() - $ptime;

      if ($etime < 1)
      {
          return '0 seconds';
      }

      $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                  30 * 24 * 60 * 60       =>  'month',
                  24 * 60 * 60            =>  'day',
                  60 * 60                 =>  'hour',
                  60                      =>  'minute',
                  1                       =>  'second'
                  );

      foreach ($a as $secs => $str)
      {
          $d = $etime / $secs;
          if ($d >= 1)
          {
              $r = round($d);
              return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
          }
      }
  }
  
  function getRealIpAddr()
  {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }

?>