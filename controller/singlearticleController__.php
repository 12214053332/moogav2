<?php

Class singlearticleController Extends baseController {

public function index() 
{         
		
		// $this->checkuser();
		 $article_id=get('id');
        $article_id=$this->registry->encryption->decode($article_id);
		
		$result =$this->registry->articles->getarticle_byid($article_id);
	    $this->registry->template->article_data= $result;
		
		$result_cat =$this->registry->articles->getarticles_category();
	    $this->registry->template->allarticles_cat= $result_cat;
		
        $this->registry->template->page_body = getviewslink().'/mooga/singlearticle';
        $this->registry->template->show('index_home');
}


}

?>
