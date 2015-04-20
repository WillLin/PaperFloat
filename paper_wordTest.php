<?php

/**
*
*/
class testpaper_word extends PHPUnit_Framework_TestCase
{
	
	function test_constructor()
	{
		$word=new Word();
		$this->assertEmpty($word->getAuthors());
		return $word;
	}
	/**
	*@depends test_constructor
	**/
	function test_setWord(Word $word){
		$word->setWord("test");
		$this->assertEquals("test",$word->getWrod());
		return $word
	}
	/**
	*@depends test_setWord
	**/
	function test_setFrequency(Word $word){
		$word->setFrequency(10);
		$this->assertEquals(10,$word->getFrequency());
		return $word;
	}

	/**
	*@depends test_setFrequency
	**/
	function test_setTitle(Word $word){
		$word->setTitle("Title");
		$this->assertEquals("Title",$word->getTitle());
		return $word;
	}
	/**
	*@depends test_setTitle
	**/
	function test_setAuthor(Word $word){
		$authors=new array("Author1","Author2");
		$word->setAuthor($authors);
		$this->assertEquals($authors,$word->getAuthors());
		return $word;
	}
	/**
	*@depends test_setAuthor
	**/
	function test_setConference(Word $word){
		$word->setConference("Conference1");
		$this->assertEquals("Conference1",$word->getConference());
		return $word;
	}
	/**
	*@depends test_setConference
	**/
	function test_setLink(Word $word){
		$word->setTitle("www.link.com");
		$this->assertEquals("www.link.com",$word->getLink());
	}
	
}

?>