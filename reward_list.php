<?php
  require 'config.php';
  require 'inc/functions.php';
  
  $gimmie = new Gimmie(GIMMIE_CONSUMER_KEY, GIMMIE_SECRET_KEY);
  $gimmie->set_user('');
  $gimmie_reward = $gimmie->categories();
  
  function in_category($val, $arr){

    foreach ($arr as &$reward) {
      if (in_array($val, $reward['country_codes'])) {
        return true;
      }
      if (in_array('global', $reward['country_codes'])) {
        return true;
      }      
    }
    
    return false;
  } 
?>

<ul class="nav nav-tabs" id="reward-categories">
  <?php
   foreach ($gimmie_reward['response']['categories'] as &$rewardcategory) {
     if ($rewardcategory['rewards'] && in_category($country_code, $rewardcategory['rewards'])) {
  ?>
   <li><a href="#<?php echo friendly_name($rewardcategory['name']); ?>" data-toggle="tab"><?php echo $rewardcategory['name']; ?></a></li>
   <?php
      }
    }
   ?>
                     
 </ul>

 <div class="tab-content">
   <?php
    foreach ($gimmie_reward['response']['categories'] as &$rewardcategory) {
      if ($rewardcategory['rewards']) {
   ?>
   <div class="tab-pane" id="<?php echo friendly_name($rewardcategory['name']); ?>">
    <ul class="gimmie-items">     
      <?php
       shuffle($rewardcategory['rewards']);

       foreach ($rewardcategory['rewards'] as &$reward) {
         if (in_array($country_code, $reward['country_codes']) || in_array('global', $reward['country_codes'])) {          
           if ($reward['total_quantity'] > $reward['claimed_quantity'] || $reward['total_quantity'] == -1) {
      ?>
         <li gimmie-reward="<?php echo $reward['id']; ?>" class="reward-link">
          <div class="gimmie-item">
            <div class="gimmie-reward">
              <div class="gimmie-item-image">
                <img src="<?php echo $reward['image_url']; ?>">
              </div>
              <div class="gimmie-info">
                <div class="gimmie-name"><?php echo $reward['name']; ?></div>
                <div class="gimmie-store"><?php echo $reward['store_name']; ?></div>
              </div>
              <div class="gimmie-points">
                <div>
                  <span><?php echo $reward['points']; ?> points</span>
                  <img src="//api.gimmieworld.com/cdn/client-navigation-arrow.png">
                </div>
              </div>
            </div>
           <?php if ($reward['feature']) { ?>
            <img class="gimmie-featured-ribbon" src="//api.gimmieworld.com/cdn/client-featured-ribbon.png">
           <?php } ?>

          </div>
         </li>
      <?php
          }
         }
       }
      ?>
      
     <?php
      shuffle($rewardcategory['rewards']);
     
      foreach ($rewardcategory['rewards'] as &$reward) {
        if (in_array($country_code, $reward['country_codes']) || in_array('global', $reward['country_codes'])) {          
          if ($reward['total_quantity'] <= $reward['claimed_quantity'] && $reward['total_quantity'] != -1) {
     ?>
        <li gimmie-reward="<?php echo $reward['id']; ?>">
         <div class="gimmie-item">
           <div class="gimmie-reward">
             <div class="gimmie-item-image">
               <img src="<?php echo $reward['image_url']; ?>">
             </div>
             <div class="gimmie-info">
               <div class="gimmie-name"><?php echo $reward['name']; ?></div>
               <div class="gimmie-store"><?php echo $reward['store_name']; ?></div>
             </div>
             <div class="gimmie-points">
               <div>
                 <span><?php echo $reward['points']; ?> points</span>
                 <img src="//api.gimmieworld.com/cdn/client-navigation-arrow.png">
               </div>
             </div>
           </div>

          <div class="gimmie-fully-redeemed">
            <div class="gimmie-overlay-background"></div>
            <div class="gimmie-overlay-border">
              <div class="gimmie-message">FULLY REDEEMED</div>
            </div>
          </div>
                    
         </div>
        </li>
     <?php
          }
        }
      }
     ?>
     </ul>
   </div> 
   <?php
      }
    }
   ?>   
 </div>

 
 <script>
   $(function () {
     _gaq.push(['_trackEvent', 'Facebook', 'View category', facebook_url]);
     
     $('#reward-categories a').click(function (e) {
       e.preventDefault();
       $(this).tab('show');
     }); 

     $('#reward-categories a:first').tab('show');
     
     $('.reward-link').click(function (e) {

       FB.Canvas.scrollTo(0,0);
       $( "#content" ).html('<div class="middle"><img src="img/loader.gif" class="loader"></div>');
       
       $.get( "reward_detail.php?reward_id="+$(this).attr('gimmie-reward'), function( data ) {
         $( "#content" ).html( data );
       });

      });
   });    
 </script>