<?php
/**
 * Created by PhpStorm.
 * User: Naira.Magdy
 * Date: 9/11/2017
 * Time: 1:53 PM
 */
 foreach($project_fields as $type) {
    $type = (object) $type;

     ?>

     <div class="col-md-3  col-sm-6 col-xs-12 grid">
                        <div class="categorie_item">
                            <div class="cate_item_block hi-icon-effect-8">
                                <div class="cate_item_social hi-icon"><a href="?page=projects&action=field&fieldid=<?=$type->id?>"><i class="fa fa-leaf"></i></a></div>
                                <h1><a href="?page=projects&action=field&fieldid=<?=$type->id?>"><?php  echo $type->name; ?></a></h1>
                            </div>
                        </div>
                    </div>

<?php } ?>
