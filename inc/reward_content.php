<div class="border">

  <div id="content" sharelink="<?php echo $sharelink; ?>">
    <div class="row">
      <div class="col-xs-12 middle">
       <img src="img/loader.gif" class="loader" />
      </div>
    </div>    
  </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script>
  $(function () {

    $.get( "profile.php", function( data ) {
      $( "#content" ).html( data );
    });

  });
</script>