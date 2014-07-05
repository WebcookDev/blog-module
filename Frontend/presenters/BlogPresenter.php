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

	    if (count($parameters) > 0) {
	    	$user = $this->em->getRepository('WebCMS\Entity\User')->findOneByUsername($parameters[0]);
		}

	    if (!empty($user)) {
	    	
	    	$this->blogPosts = $this->repository->findBy(array(
			    'page' => $this->actualPage,
			    'hide' => 0,
			    'user' => $user->getId()
			    ), array('published' => 'DESC')
		    );
	    } else if (count($parameters) === 0) {
	    	$this->blogPosts = $this->repository->findBy(array(
			    'page' => $this->actualPage,
			    'hide' => 0
			    ), array('published' => 'DESC')
		    );	
	    }
    }

    /**
     * Renders RSS with blog posts.
     * @return void
     */
    private function renderRss()
    {
    	$rssItems = array();

    	foreach ($this->blogPosts as $blogPost) {
    		$rssItem = new \WebCMS\Helpers\RssItem;
    		$rssItem->setTitle($blogPost->getTitle());
    		$rssItem->setDescription($blogPost->getPerex());
    		$rssItem->setLink($this->link('//this', array(
    				'id' => $this->actualPage->getId(),
				    'path' => $this->actualPage->getPath(),
				    'abbr' => $this->abbr,
    				'parameters' => array($blogPost->getSlug())
    			)));
    		$rssItem->setPublishDate($blogPost->getPublished());

    		$rssItems[] = $rssItem;
    	}

    	$rssRenderer = new \WebCMS\Helpers\RssRenderer($rssItems);
    	$rssRenderer->render();
    }

    public function renderDefault($id) 
    {
		$detail = $this->getParameter('parameters');
		$rss = $this->getParameter('rss');
		$blogPost = NULL;

		if (!empty($rss)) {
			$this->renderRss();
		}

		if (count($detail) === 1 && empty($this->blogPosts)) {
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
		    
	        $this->em->detach($this->actualPage);
		    $this->actualPage->setClass($this->settings->get('Detail body class', 'blogModule' . $this->actualPage->getId(), 'text', array())->getValue());
		    $this->template->seoTitle = $blogPost->getMetaTitle();
		    $this->template->seoDescription = $blogPost->getMetaDescription();
		    $this->template->seoKeywords = $blogPost->getMetaKeywords();
		    $this->template->previous = $this->getPrevious($blogPost);

		    $this->addToBreadcrumbs($this->actualPage->getId(), 'Blog', 'Blog', $blogPost->getTitle(), $this->actualPage->getPath() . '/' . $blogPost->getSlug()
		    );
		}

		parent::beforeRender();

		$this->template->blogPost = $blogPost;
		$this->template->blogPosts = $this->blogPosts;
		$this->template->id = $id;
    }

    /**
     * [blogBox description]
     * @param  [type] $context  [description]
     * @param  [type] $fromPage [description]
     * @return [type]           [description]
     */
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

    /**
     * [getPrevious description]
     * @param  [type] $blogPost [description]
     * @return [type]           [description]
     */
    public function getPrevious($blogPost) 
    {	
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
			return $prev[0];
        }

		return false;
	}
}
