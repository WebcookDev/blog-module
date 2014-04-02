<?php

namespace WebCMS\NewsModule\Doctrine;

use Doctrine\ORM\Mapping as orm;
use Gedmo\Mapping\Annotation as gedmo;

/**
 * Description of BlogPost
 * @orm\Entity
 * @orm\Table(name="Blog")
 * @author Jakub Šanda <sanda at webcook.cz>
 */
class BlogPost extends \WebCMS\Entity\Entity {
	/**
	 * @orm\Column
	 */
	private $title;
	
	/**
	 * @orm\Column(type="text")
	 */
	private $perex;
	
	/**
	 * @orm\Column(type="text")
	 */
	private $text;
	
	/**
	 * @orm\Column(type="date")
	 */
	private $date;
	
	/**
	 * @orm\ManyToOne(targetEntity="WebCMS\Entity\Page")
	 * @orm\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $page;
	
	/**
     * @gedmo\Slug(fields={"title"})
     * @orm\Column(length=64)
     */
	private $slug;
	
	/**
	 * @orm\OneToMany(targetEntity="Photo", mappedBy="blogPost") 
	 * @var Array
	 */
	private $photos;
	
	public function getTitle() {
		return $this->title;
	}

	public function getPerex() {
		return $this->perex;
	}

	public function getText() {
		return $this->text;
	}

	public function getPage() {
		return $this->page;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function setPerex($perex) {
		$this->perex = $perex;
	}

	public function setText($text) {
		$this->text = $text;
	}

	public function setPage($page) {
		$this->page = $page;
	}
	
	public function getDate() {
		return $this->date;
	}

	public function setDate($date) {
		$this->date = $date;
	}
	
	public function getSlug() {
		return $this->slug;
	}

	public function setSlug($slug) {
		$this->slug = $slug;
	}
	
	public function getPhotos() {
		return $this->photos;
	}

	public function setPhotos(Array $photos) {
		$this->photos = $photos;
	}
	
	public function getDefaultPhoto(){
		foreach($this->getPhotos() as $photo){
			if($photo->getDefault()){
				return $photo;
			}
		}
		
		return NULL;
	}
}