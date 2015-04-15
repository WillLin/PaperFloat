<?php

class Paper
{
	public $title;
	public $authors;
	public $conference;
	public $link;
	public $text;

	function __construct()
	{
		$this->authors = array();
		
	}

	function setTitle($title)
	{
		$this->title = $title;
	}

	function setAuthor($author)
	{
		$this->authors[] = $author;
	}

	function setConference($conference)
	{
		$this->conference = $conference;
	}

	function setLink($link)
	{
		$this->link = $link;
	}

	function setText($text)
	{
		$this->text = $text;
	}
}

	?>