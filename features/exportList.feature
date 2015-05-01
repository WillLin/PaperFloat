Feature: Export the list of articles
	In order to get a pdf of given articles
	I want to access a word in word cloud and export list
Scenario: 
	Given I am on the PaperFloat front page
	And I have entered a valid key word, chain, in the input box
	And I have chosen a valid limit, 2, in the limit box
	When I click the Submit button
	Then I should get a word cloud, with the word chain
	Then I should click on 'chain' in the word cloud
	Then I should click on the 'Export list as PDF' button
	Then I should see a pdf document of a list
