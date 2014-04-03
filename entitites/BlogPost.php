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
	private $date;
	
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

	
}