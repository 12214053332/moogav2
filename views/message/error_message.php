<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="alert-box error">
                         
                                <span>خطأ: </span><?php echo $errorMessage?> 
								<!--<i class="fa fa-meh-o"></i> -->
							
                        </div>
</div>

<script>

 setTimeout(function(){
	 $(".alert-box.error").hide(1000);
   }, 5000);
</script>
