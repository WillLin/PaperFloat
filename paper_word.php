<?php

class Word
{
	public $word;
	public $title;
	public $authors;
	public $conference;
	public $link;
	public $frequency;

	function __construct()
	{
		$this->authors = array();
		
	}

	function setWord($word)
	{
		$this->word = $word;
	}

	function setTitle($title)
	{
		$this->title = $title;
	}

	function setAuthor($author)
	{
		$this->authors[] = $author;
	}

	function copyAuthors($authors)
	{
		$this->authors = $authors;
	}

	function setConference($conference)
	{
		$this->conference = $conference;
	}

	function setLink($link)
	{
		$this->link = $link;
	}

	function setFrequency($frequency)
	{
		$this->frequency = $frequency;
	}

	function getTitle(){
		return $this->title;
	}

	function getAuthors(){
		return $this->authors;
	}
	function getText(){
		return $this->text;
	}
	function getLink(){
		return $this->link;
	}
	function getConference(){
		return $this->conference;
	}

	function getFrequency($word){
		return substr_count($this->text, $word);
	}
}

?>