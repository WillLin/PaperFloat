Feature: Access through Web Browser
	In order for a user to access the product
	As a user
	I want to access the internet and get to the web application

Scenario: View PaperFloat/index.php
	Given I have access to a web browser with internet
	When I enter PaperFloat/index.php
	Then I should see the PaperFloat homepage 

Feature: Get a word cloud for a key word
	A valid key word will generate a word cloud

Scenario: Enter a valid key word
	Given the PaperFloat home page has loaded
	And I have entered a valid key word, Turing, in the input box
	When I press the 'Submit' button
	Then I should get a word cloud, with the word back