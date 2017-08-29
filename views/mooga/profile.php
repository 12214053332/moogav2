 
  

	 <?php include("wedget/user-header.php") ?>

     <div id="breadcrum-inner-block">
         <div class="container">
             <div class="row">
                 <div class="col-sm-12 text-center">
                     <div class="breadcrum-inner-header">
                         <h1>الملف الشخصى</h1>
                         <a href="">الرئيسية</a> <i class="fa fa-circle"></i> <a href="?page=profile"><span>الملف الشخصى</span></a> </div>
                 </div>
             </div>
         </div>
     </div>

     <div id="vfx-product-inner-item">
         <div class="container">
             <div class="row">
                 <div class="col-md-12 contact-heading-title text-center bt_heading_3">
                 <h1>
                     بروفايل
                     <span><?php echo $user->name; ?></span>
                 </h1>
                 <div class="blind line_1"></div>
                 <div class="flipInX-1 blind icon"><span class="icon"><i class="fa fa-stop"></i>&nbsp;&nbsp;<i class="fa fa-stop"></i></span></div>
                 <div class="blind line_2"></div>
             </div>
                 <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 grey-border round-corner">
                     <div class="col-md-10 col-lg-10 col-xs-12 col-sm-12 center-table">
                         <div class="from-list-lt">
                             <div class="row">
                                 <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 grey-border round-corner def-padding btm-mrg-sm">
                                     <div class="row">
                                         <div class="col-md-5 col-lg-5 col-xs-6 col-sm-6 ">
                                             <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                                 <img src="<?php echo $currentuser->profilepic; ?>" class="">
                                                 <a class="sm-border-btn round-corner grey-border" href="?page=newmessage&uid=<?php echo $currentuser->id; ?>"><i class="message-box"></i></a>
                                                 <a class="sm-border-btn round-corner grey-border"><i class="call-icon"></i></a>
                                             </div>
                                             <div class="col-md-8 col-lg-8 col-xs-12 col-sm-12 def-padding zero-top-padding ">


                                                 <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                                     <p class="md-font  orange-font zero-bottom-margin cst-right-alignment">الاسم : </p>
                                                     <p class="md-font grey-font zero-bottom-margin"> <?php echo $currentuser->name; ?></p>
                                                 </div>

                                                 <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                                     <p class="md-font  orange-font zero-bottom-margin cst-right-alignment">المسمى الوظيفى : </p>
                                                     <p class="md-font grey-font zero-bottom-margin"> <?php echo $currentuser->job_title; ?></p>
                                                 </div>
                                                 <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                                     <p class="md-font  orange-font zero-bottom-margin cst-right-alignment">العنوان: </p>
                                                     <p class="md-font grey-font zero-bottom-margin"> <?php echo $currentuser->countryname .' , '. $currentuser->statename .' , '. $currentuser->cityname; ?></p>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>



		