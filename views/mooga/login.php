
<div id="breadcrum-inner-block">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <div class="breadcrum-inner-header">
                    <h1>تسجيل الدخول</h1>
                    <a href="/">الرئيسية</a> <i class="fa fa-circle"></i> <a href="?page=login"><span>تسجيل الدخول</span></a> </div>
            </div>
        </div>
    </div>
</div>



<div id="vfx-product-inner-item">
<div class="container">
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3 listing-modal-1">
            <div class="text-center">
                <h4 class="title-login-form">
                    تسجيل الدخول
                </h4>

            </div>
            <p>إبدأ رحلتك مع منصة موجة بتسجيل الدخول أو تفعيل إشتراكك الذي كونته</p>
            <div class="clearfix"></div>
            <div class="clearfix"></div>
            <div class="clearfix" style="height: 20px"></div>
            <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
                <a class="btn facebook-btn" href="fblogin/fbconfig.php">من خلال حسابك في</a>
            </div>
            <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
                <a class="btn linkedin-btn" href="linkedinuser/process.php">من خلال حسابك في</a>
            </div>
            <div class="clearfix"></div>
            <div class="clearfix" style="height: 20px"></div>
            <div class="listing-login-form">
                <form action="?page=_usersaction&action=login" id="login-form" name="login-form">
                    <div class="listing-form-field"> <i class="fa fa-user blue-1"></i>
                        <input class="form-field bgwhite" type="email" id="uemail" value="<?php echo $rememberme->username; ?>" name="email" placeholder="البريد الإلكترونى" required />
                        <label for="uemail"></label>
                    </div>
                    <div class="listing-form-field"> <i class="fa fa-lock blue-1"></i>
                        <input class="form-field bgwhite" type="password" name="password" value="<?php echo $rememberme->password; ?>" id="upassword" placeholder="كلمة المرور" required  />
                        <label for="upassword"></label>
                    </div>
                    <div class="listing-form-field clearfix margin-top-20 margin-bottom-20">
                        <input type="checkbox" id="checkbox-1-1" class="regular-checkbox" />
                        <label for="checkbox-1-1"></label>
                        <label name="remember_me" value="1" class="checkbox-lable">تذكرني</label>
                        <a href="?page=forgetpassword">نسيت كلمة السر</a> </div>
                    <div class="listing-form-field">
                        <input class="form-field submit"  type="submit" value="تسجيل الدخول" />
                    </div>
                    <div class="form-group">
                        <div id="login-response">
                        </div>
                    </div>
                </form>

                <div class="bottom-links">
                    <p>ليس لديك حساب<a href="?page=signup">مستخدم جديد</a></p>
                </div>
            </div>
        </div>

    </div>
</div>

</div>
