<label for="contact_phone" class="control-label md-font bold black-font col-lg-12 col-md-12 col-xs-12 col-sm-12">هاتف الاتصال <span class="asterisc">*</span> </label><div class="col-lg-8 col-md-8 col-xs-8 col-sm-8"><input type="number" class="form-control" id="contact_phone" name="contact_phone" placeholder="هاتف الاتصال"  maxlength="11" minlength="9" value="<?php printvalue('contact_phone'); ?>"></div><div class="col-lg-4 col-md-4 col-xs-4 col-sm-4"><select class="form-control chosen-select" id="country_1" name="country_1" required><option value="">اختر كود الدولة</option><?php $codes=getvalue('country_1'); foreach ($countries as $country) {$id=$country['id'];$name=$country['name'];$code=$country['code'];if ($codes==$code) {$selected='selected';} else {$selected='';}echo ' <option value="'.$code.'"  data-code="'.$code.'"'. $selected. ' >'.$code.' - '.$name .'</option> ';} ?></select> </div></div>