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
class BlogPost extends \WebCMS\Entity\Seo {
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
	 * @gedmo\Timestampable(on="create")
	 * @orm\Column(type="datetime")
	 */
	private $created;
	
	/**
	 * @orm\Column(type="datetime")
	 */
	private $published;

	/**
	 * @orm\ManyToOne(targetEntity="WebCMS\Entity\Page")
	 * @orm\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $page;
	
	/**
	* @orm\ManyToOne(targetEntity="WebCMS\Entity\User")
	* @orm\JoinColumn(name="user_id", referencedColumnName="id")
	*/
	private $user;
	
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

	/**
     * @orm\Column(type="boolean", nullable=true)
     */
	private $hide;
	
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
	
	public function getUser() {
		return $this->user;
	}

	public function setUser($user) {
		$this->user = $user;
	}
	
	public function getCreated() {
		return $this->created;
	}

	public function setCreated($created) {
		$this->created = $created;
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

	public function getPublished() {
		return $this->published;
	}

	public function setPublished($published) {
		$this->published = $published;
	}

	public function getHide()
	{
		return $this->hide;
	}

	public function setHide($hide)
	{
		$this->hide = $hide;
	}
}