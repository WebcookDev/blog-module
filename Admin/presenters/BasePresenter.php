<?php

namespace AdminModule\BlogModule;

/**
 * Description of BasePresenter
 *
 * @author Jakub Šanda <jakub.sanda at webcook.cz>
 */
class BasePresenter extends \AdminModule\BasePresenter {
	
	private $repository;
	
	protected function startup() {
		parent::startup();
		
		$this->repository = $this->em->getRepository('WebCMS\BlogModule\Doctrine\BlogPost');
	}

	protected function beforeRender() {
		parent::beforeRender();
		
	}
	
	public function actionDefault(){
		
	}
	
	public function renderDefault($idPage){
		$this->reloadContent();
		
		$this->template->idPage = $idPage;
	}
	
	
}