<?php

namespace AdminModule\BlogModule;

/**
 * Description of
 *
 * @author Jakub <Sanda>
 */
class BlogPresenter extends BasePresenter {

    protected function startup() {
	parent::startup();
    }

    protected function beforeRender() {
	parent::beforeRender();

    }

    public function actionDefault($idPage){
    }

    public function renderDefault($idPage){
	$this->reloadContent();

	$this->template->idPage = $idPage;
    }
}