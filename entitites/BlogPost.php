<?php

namespace WebCMS\BlogModule\Doctrine;

use Doctrine\ORM\Mapping as orm;
use Gedmo\Mapping\Annotation as gedmo;

/**
 * Description of BlogPost
 * @orm\Entity
 * @orm\Table(name="Blog")
 * @author Jakub Šanda <jakub.sanda at webcook.cz>
 */
class BlogPost extends \WebCMS\Entity\Entity {
	/**
	 * @orm\Column
	 */
	private $title;
	
	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	
}