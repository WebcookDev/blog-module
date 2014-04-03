<?php

namespace AdminModule\BlogModule;

/**
 * Description of SettingsPresenter

 * @author Jakub Šanda <jakub.sanda at webcook.cz>
 */
class SettingsPresenter extends \AdminModule\BasePresenter {
	
	private $repository;
	
	
	protected function startup() {
		parent::startup();
		
		$this->repository = $this->em->getRepository('WebCMS\BlogModule\Doctrine\BlogPost');
	}

	protected function beforeRender() {
		parent::beforeRender();
		
	}
	
	public function actionDefault($idPage){
	}
	
	public function createComponentSettingsForm(){
		
		$settings = array();
		
		return $this->createSettingsForm($settings);
	}
	
	public function renderDefault($idPage){
		$this->reloadContent();
		
		$this->template->config = $this->settings->getSection('blogModule');
		$this->template->idPage = $idPage;
	}
	
	
}