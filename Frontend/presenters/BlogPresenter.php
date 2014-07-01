<?php

namespace FrontendModule\BlogModule;

/**
 * Description of BlogPresenter
 *
 * @author Jakub Šanda <jakub.sanda at webcook.cz>
 * @author Tomas Voslar <tomas.voslar at webcook.cz>
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

    public function actionDefault($id) 
    {
	    $parameters = $this->getParameter('parameters');
	    //$user = $this->em->getRepository('WebCMS\Entity\User')->findOneByUsername($parameters[0]);
	    if (!empty($user)) {
	    	
	    	if (empty($user)) {
	    		
	    		$this->redirect('default', array(
				    'path' => $this->actualPage->getPath(),
				    'abbr' => $this->abbr
				));
	    	}

	    	$this->blogPosts = $this->repository->findBy(array(
			    'page' => $this->actualPage,
			    'hide' => 0,
			    'user' => $user->getId()
			    ), array('published' => 'DESC')
		    );
	    } else {
	    	$this->blogPosts = $this->repository->findBy(array(
			    'page' => $this->actualPage,
			    'hide' => 0
			    ), array('published' => 'DESC')
		    );	
	    }

	
    }

    public function renderDefault($id) {

	$detail = $this->getParameter('parameters');
	$blogPost = NULL;

	if (count($detail) === 1) {
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
	    
	    $this->actualPage->setClass($this->settings->get('Detail body class', 'blogModule' . $this->actualPage->getId(), 'text', array())->getValue());
	    $this->template->seoTitle = $blogPost->getMetaTitle();
	    $this->template->seoDescription = $blogPost->getMetaDescription();
	    $this->template->seoKeywords = $blogPost->getMetaKeywords();

		$qb = $this->em->createQueryBuilder();
		$qb->select('b')
		    ->from('WebCMS\BlogModule\Doctrine\BlogPost', 'b')
		    ->where('b.published < :actualDate')
		    ->andWhere('b.hide = 0')
		    ->orderBy('b.published', 'DESC')
		    ->setMaxResults(1)
		    ->setParameters(array('actualDate' => $blogPost->getPublished()));
        $prev = $qb->getQuery()->getResult();

        if (count($prev) > 0) {
        	$this->template->previousBlog = $prev[0];	
        } else {
        	$this->template->previousBlog = false;	
        }
	    
	    $this->addToBreadcrumbs($this->actualPage->getId(), 'Blog', 'Blog', $blogPost->getTitle(), $this->actualPage->getPath() . '/' . $blogPost->getSlug()
	    );
	}

	parent::beforeRender();

	$this->template->blogPost = $blogPost;
	$this->template->blogPosts = $this->blogPosts;
	$this->template->id = $id;
    }

    public function blogBox($context, $fromPage) 
    {	
		$repository = $context->em->getRepository('WebCMS\BlogModule\Doctrine\BlogPost');
		
		$limit = $context->settings->get('Box posts count', 'blogModule' . $fromPage->getId(), 'text', array())->getValue();
		$blogPosts = $repository->findBy(array(), array('published' => 'DESC'), $limit);

		$template = $context->createTemplate();
		$template->setFile('../app/templates/blog-module/Blog/box.latte');
		$template->blogPosts = $blogPosts;
		$template->abbr = $context->abbr;
		$template->actualPage = $context->actualPage;
		$template->fromPage = $fromPage;
		$template->link = $context->link(':Frontend:Blog:Blog:default', array(
		    'id' => $fromPage->getId(),
		    'path' => $fromPage->getPath(),
		    'abbr' => $context->abbr
		));

		return $template;
    }

    public function blogBoxHeader($context, $fromPage) 
    {	
		$repository = $context->em->getRepository('WebCMS\BlogModule\Doctrine\BlogPost');
		
		$parameters = $context->getParameter('parameters');

		if (count($parameters) > 0) {
			$blogPost = $repository->findOneBySlug($parameters[0]);

			$template = $context->createTemplate();
			$template->setFile('../app/templates/blog-module/Blog/box2.latte');
			$template->blogPost = $blogPost;
			$template->abbr = $context->abbr;
			$template->actualPage = $context->actualPage;
			$template->fromPage = $fromPage;
			$template->link = $context->link(':Frontend:Blog:Blog:default', array(
			    'id' => $fromPage->getId(),
			    'path' => $fromPage->getPath(),
			    'abbr' => $context->abbr
			));

			return $template;
		} else {
			return false;
		}
    }
}
