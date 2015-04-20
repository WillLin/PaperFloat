Feature: Get a word cloud for a key word
	In order to see a generated word cloud
	As a user
	I want a valid key word to generate a word cloud

Scenario: Enter a valid key word
	Given the PaperFloat home page has loaded
	And I have entered a valid key word, clone, in the input box
	When I press the 'Submit' button
	Then I should get a word cloud, with the word clone