<?php

namespace AdminModule\BlogModule;

/**
 * Description of BlogPresenter
 *
 * @author Jakub Šanda <jakub.sanda at webcook.cz>
 */
class BlogPresenter extends \AdminModule\BasePresenter {
	
	private $repository;
	
	private $blogPost;
	
	protected function startup() {
		parent::startup();
		
		$this->repository = $this->em->getRepository('WebCMS\BlogModule\Doctrine\BlogPost');
	}

	protected function beforeRender() {
		parent::beforeRender();
		
	}
	
	public function actionDefault($idPage){
	}
	
	protected function createComponentBlogGrid($name){
		
		$grid = $this->createGrid($this, $name, 'WebCMS\BlogModule\Doctrine\BlogPost', array(array('by' => 'date', 'dir' => 'DESC')), array('page =' . $this->actualPage->getId()));
		
		$grid->addColumnText('title', 'Name')->setSortable()->setFilterText();
		$grid->addColumnDate('date', 'Date')->setSortable();
		
		$grid->addActionHref("updateBlog", 'Edit', 'updateBlog', array('idPage' => $this->actualPage->getId()))->getElementPrototype()->addAttributes(array('class' => array('btn' , 'btn-primary', 'ajax')));
		$grid->addActionHref("deleteBlog", 'Delete', 'deleteBlog', array('idPage' => $this->actualPage->getId()))->getElementPrototype()->addAttributes(array('class' => array('btn', 'btn-danger'), 'data-confirm' => 'Are you sure you want to delete this item?'));

		return $grid;
	}
	
	public function renderDefault($idPage){
		$this->reloadContent();
		
		$this->template->idPage = $idPage;
	}
	
	public function actionUpdateBlog($idPage, $id){
		$this->reloadContent();
		
		if(is_numeric($id)){
			$this->blogPost = $this->repository->find($id);
		}else{
			$this->blogPost = new \WebCMS\BlogModule\Doctrine\BlogPost;
		}
	}
	
	public function actionDeleteBlog($id){

		$post = $this->repository->find($id);
		$this->em->remove($post);
		$this->em->flush();
		
		$this->flashMessage('Blog has been removed.', 'success');
		
		if(!$this->isAjax()){
			$this->redirect('default', array(
				'idPage' => $this->actualPage->getId()
			));
		}
	}
	
	public function createComponentBlogForm(){
		$form = $this->createForm();
		
		$form->addText('title', 'Title')->setRequired('Fill in title.');
		$form->addTextArea('perex', 'Perex')->setAttribute('class', array('editor'));
		$form->addTextArea('text', 'Text')->setAttribute('class', array('editor'));
		
		$form->addSubmit('send', 'Save')->setAttribute('class', array('btn btn-success'));
		$form->onSuccess[] = callback($this, 'blogFormSubmitted');

		$form->setDefaults($this->blogPost->toArray());
		
		return $form;
	}
	
	public function blogFormSubmitted($form){
		$values = $form->getValues();
		
		
		
		$this->blogPost->setTitle($values->title);
		$this->blogPost->setPerex($values->perex);
		$this->blogPost->setText($values->text);
		$this->blogPost->setPage($this->actualPage);
		
		
		if(!$this->blogPost->getId()){
			$this->em->persist($this->blogPost);
			
			$author = $this->em->find('WebCMS\Entity\User', $this->getUser()->getId());
			$this->blogPost->setUser($author);
		}else{

			// delete old photos and save new ones
			$qb = $this->em->createQueryBuilder();
			$qb->delete('WebCMS\BlogModule\Doctrine\Photo', 'l')
					->where('l.blogPost = ?1')
					->setParameter(1, $this->blogPost)
					->getQuery()
					->execute();
		}
			
		if(array_key_exists('files', $_POST)){
			$counter = 0;
			if(array_key_exists('fileDefault', $_POST)) $default = intval($_POST['fileDefault'][0]) - 1;
			else $default = -1;
			
			foreach($_POST['files'] as $path){

				$photo = new \WebCMS\BlogModule\Doctrine\Photo;
				$photo->setTitle($_POST['fileNames'][$counter]);
				
				if($default === $counter){
					$photo->setDefault(TRUE);
				}else{
					$photo->setDefault(FALSE);
				}
				
				$photo->setPath($path);
				$photo->setBlogPost($this->blogPost);

				$this->em->persist($photo);

				$counter++;
			}
		}
		
		$this->em->flush();
		
		$this->flashMessage('Blog has been saved.', 'success');
		$this->redirect('default', array(
			'idPage' => $this->actualPage->getId()
		));
	}
	
	public function renderUpdateBlog($idPage){
		
		$this->template->blogPost = $this->blogPost;
		$this->template->idPage = $idPage;
	}
}
