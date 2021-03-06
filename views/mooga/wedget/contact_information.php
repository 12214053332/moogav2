<div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 block-header text-center">
    <div class="col-md-12 contact-heading-title bt_heading_3">
        <h1>بيانات  <span>الاتصال</span></h1>
        <div class="blind line_1"></div>
        <div class="flipInX-1 blind icon"><span class="icon"><i class="fa fa-stop"></i>&nbsp;&nbsp;<i class="fa fa-stop"></i></span></div>
        <div class="blind line_2"></div>
    </div>
</div>

<div class="form-group col-lg-12 col-md-12 col-xs-12">
    <label  class=" control-label md-font bold black-font">طريقة التواصل عبر<span class="asterisc">*</span> </label><label for="contact_type"></label>
    <select class="form-control cust-input-width cust-input-width-three zero-top-margin hi25 valid chosen-select" id="contact_type" name="contact_type" data-placeholder="">
        <option value=""></option>
        <option value="1"  <?php if (getvalue('contact_type') !=2) {
            echo 'selected';
        } ?>>عبر الهاتف</option>
        <option value="2" <?php if (getvalue('contact_type') ==2) {
            echo 'selected';
        } ?>>عبر البريد الإلكترونى</option>
    </select>

</div>
<div class="form-group col-lg-6 col-md-6 col-xs-12 pull-right">
    <label  class=" control-label md-font bold black-font">اسم صاحب الهاتف<span class="asterisc">*</span> </label><label for="contact_name"></label>
    <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder=" * اسم صاحب الهاتف"   value="<?php printvalue('contact_name'); ?>">

</div>
<div class="form-group col-lg-6 col-md-6 col-xs-12 ">
    <label  class=" control-label md-font bold black-font">هاتف الاتصال<span class="asterisc">*</span> </label> <label for="contact_phone"></label>
    <div class="clearfix"></div>
    <div class="col-lg-8 pull-right">
        <input type="number" class="form-control" id="contact_phone" maxlength="11"  minlength="9" name="contact_phone" placeholder="* هاتف الاتصال"   value="<?php printvalue('contact_phone'); ?>">
    </div>
    <div class="col-lg-4 ">

        <select class="form-control cust-input-width cust-input-width-three zero-top-margin hi25 valid <!--chosen-select-->" id="country_1" name="country_1" required data-placeholder=" * اختر الدولة">
            <option value="">اختر كود الدولة</option>
            <?php
            $ids=getvalue('country');
            $select="";
            foreach ($countries as $country) {
                $id=$country['id'];
                $name=$country['name'];
                $code=$country['code'];
                if ($ids==$id) {
                    $select="selected";
                } else {
                    $select="";
                }
                echo " <option value='$id'  data-code='$code' $select>$code - $name</option> ";
            }
            ?>
        </select>
    </div>
</div>

















