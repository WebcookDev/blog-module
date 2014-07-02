<?php

namespace WebCMS\BlogModule;

/**
 * WebCMS2 blog module
 *
 * @author Jakub Šanda <jakub.sanda at webcook.cz>
 */
class Blog extends \WebCMS\Module {
	
	protected $name = 'Blog';
	
	protected $author = 'Jakub Šanda';
	
	protected $presenters = array(
		array(
			'name' => 'Blog',
			'frontend' => TRUE,
			'parameters' => TRUE
			),
		array(
			'name' => 'Photogallery',
			'frontend' => FALSE
			),
		array(
			'name' => 'Settings',
			'frontend' => FALSE
			)
	);
	
	protected $params = array(
		
	);
	
	public function __construct(){
		$this->addBox('Blog box', 'Blog', 'blogBox');
		$this->addBox('Blog header', 'Blog', 'blogBoxHeader');
		$this->addBox('Blog previous', 'Blog', 'blogBoxPrevious');
		$this->addBox('Blog comments', 'Blog', 'blogBoxComments');
	}
	
}
