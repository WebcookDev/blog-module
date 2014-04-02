<?php

namespace WebCMS\BlogModule;

/**
 * WebCMS2 blog module
 *
 * @author Jakub Šanda <sanda at webcook.cz>
 */
class Blog extends \WebCMS\Module {
	
	protected $name = 'Blog';
	
	protected $author = 'Jakub Šand';
	
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
	}
	
}