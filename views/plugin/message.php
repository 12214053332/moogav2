       
<?php
 
include 'views/user/userprofilepar.php'; ?>
<div class="result"></div>
<div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 panel panel-default panel-custom padding-custom mrg-btm-lg bg-gry">
                    <div class="panel-body no-pad-bor">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 bg-gry lg-pdg no-brd-lft mobile-headr">
                            <h3 class="panel-title custom-line">Read message</h3>
                           
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#msgs-collapse" aria-expanded="false">
                                <span class="sr-only"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>

                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 bg-wht no-brd-top no-brd-lft scroll-div-lg rt" id="msgs-collapse">
                            <?php $user2=0;
                             
                              foreach ($msglist as $message_element) {
                                  
                                  if ($message_element['msg_id_reply']==0)
                                  {
                                        $user2= $message_element['user2_id'] ;
                                        if ($user2==$companyid)
                                        {
                                          $user2= $message_element['user1_id'] ;  
                                        }
                                        $user2title= $message_element['title'];
                                        $user2messageid= $message_element['id'];
                                  }
                                $id =$message_element['id'];
                                 $user1_id= $message_element['user1_id'] ;
                                 $firstname= $message_element['firstname'];
                                 $lastname=$message_element[ 'lastname' ];
                                 $title=$message_element[ 'title' ];
                                $message= $message_element['message'];
                                 $msgtime=$message_element[ 'msgtime' ];
                                 $readmsg=$message_element['readmsg' ];
                                $profilepic=$message_element['profilepic' ];
                                 $fullname=$firstname. ' ' .$lastname; 
                                      
                                    ?>
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 msg-thread">
                                <div class="row">
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xs-2">
                                        <img src="<?php echo $profilepic ?>" class="img-responsive">
                                    </div>
                                    <div class="col-md-10 col-lg-10 col-sm-10 col-xs-10 no-pdg-lft">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                                <p class="pnl-name sm-title-name"><?php echo $fullname ?></p>
                                                <p class="pnl-right message-date sm-title-name"><?php echo $msgtime ?></p>
                                            </div>
                                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                                <p class="message-body">
                                                  <?php echo $message ?>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                              <?php }?>
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <div class="result"></div>
                                <form class="form-horizontal reply-form" id="MessageReplyForm" action="#" method="post">
                                   
                                      <input type="hidden" name="user2_id" value="<?php echo $user2 ?>" />   
                                        <input type="hidden" name="title" value="<?php echo $user2title ?>" />   
                                        <input type="hidden" name="msg_id_reply" value="<?php echo $user2messageid ?>" /> 
                                    <div class="form-group">
                                        <div class="col-sm-2">
                                            <img src="<?php echo $photopath ?> " class="img-responsive">
                                        </div>
                                        <div class="col-sm-10">
                                            <textarea required="" class="form-control" name="message" id="MessageMessage" placeholder="Reply to " style="min-height: 100px"></textarea>
                                        </div>
                                    </div>
                                        <a href="#" onclick="$('#MessageReplyForm').submit()" id="SubmitContactUs"  class="btn btn-green send-msg-btn-lead pull-right">Send</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
