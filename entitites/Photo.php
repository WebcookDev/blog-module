<?php

namespace WebCMS\BlogModule\Doctrine;

use Doctrine\ORM\Mapping as orm;

/**
 * Description of Photo
 * @orm\Entity
 * @orm\Table(name="blogPhoto")
 * @author Jakub Šanda <jakub.sanda at webcook.cz>
 */
class Photo extends \WebCMS\Entity\Entity{
	
	/**
	 * @orm\Column(type="text")
	 */
	private $title;
	
	/**
	 * @orm\ManyToOne(targetEntity="blogPost")
	 * @orm\JoinColumn(name="blogPost_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $blogPost;

	/**
	 * @orm\Column(type="text")
	 */
	private $path;
	
	/**
	 * @orm\Column(name="`default`",type="boolean")
	 */
	private $default;
	
	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getBlogPost() {
		return $this->blogPost;
	}

	public function setBlogPost($blogPost) {
		$this->blogPost = $blogPost;
	}

	public function getPath() {
		return $this->path;
	}

	public function setPath($path) {
		$this->path = $path;
	}
	
	public function getDefault() {
		return $this->default;
	}

	public function setDefault($default) {
		$this->default = $default;
	}
}