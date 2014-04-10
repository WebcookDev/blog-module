<?php

namespace FrontendModule\BlogModule;

/**
 * Description of BlogPresenter
 *
 * @author Jakub Šanda <jakub.sanda at webcook.cz>
 */
class BlogPresenter extends \FrontendModule\BasePresenter {

    private $repository;
    private $blogPosts;

    protected function startup() {
	parent::startup();

	$this->repository = $this->em->getRepository('WebCMS\BlogModule\Doctrine\BlogPost');
    }

    protected function beforeRender() {
	
    }

    public function actionDefault($id) {

	$this->blogPosts = $this->repository->findBy(array(
	    'page' => $this->actualPage
	    ), array('date' => 'DESC'));
    }

    public function renderDefault($id) {

	$detail = $this->getParameter('parameters');
	$blogPost = NULL;

	if (count($detail) > 0) {
	    $blogPost = $this->repository->findOneBySlug($detail[0]);

	    if (!is_object($blogPost)) {
		$this->redirect('default', array(
		    'path' => $this->actualPage->getPath(),
		    'abbr' => $this->abbr
		));
	    }

	    if ($this->isAjax()) {
		$this->payload->title = $this->template->seoTitle;
		$this->payload->url = $this->link('default', array(
		    'path' => $this->actualPage->getPath(),
		    'abbr' => $this->abbr,
		    'parameters' => array(\Nette\Utils\Strings::webalize($blogPost->getTitle()))
			));
		$this->payload->nameSeo = \Nette\Utils\Strings::webalize($blogPost->getTitle());
		$this->payload->name = $blogPost->getTitle();
	    }
	    
	    $this->addToBreadcrumbs($this->actualPage->getId(), 'Blog', 'Blog', $blogPost->getTitle(), $this->actualPage->getPath() . '/' . $blogPost->getSlug()
	    );
	}

	parent::beforeRender();

	$this->template->blogPost = $blogPost;
	$this->template->blogPosts = $this->blogPosts;
	$this->template->id = $id;
    }


    public function blogBox($context, $fromPage) {

		
	$repository = $context->em->getRepository('WebCMS\BlogModule\Doctrine\BlogPost');
	
	//TODO - limit presunout do nastaveni Blogu
	$limit = 3;
	$blogPosts = $repository->findBy(array(), array('date' => 'DESC'), $limit);

	$template = $context->createTemplate();
	$template->setFile('../app/templates/blog-module/Blog/box.latte');
	$template->blogPosts = $blogPosts;
	$template->link = $context->link(':Frontend:Blog:Blog:default', array(
	    'id' => $fromPage->getId(),
	    'path' => $fromPage->getPath(),
	    'abbr' => $context->abbr
	));

	return $template;
    }
}
