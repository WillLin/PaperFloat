Feature: Search for an author
	In order to see a list of papers with an author
	As a user
	I want a valid author to generate a word cloud
	When clicking on any word in the word cloud
	I see a list of papers which include the author

Scenario: Enter a valid author name
	Given the PaperFloat page has loaded
	And I have entered a valid author, Norihiro Yoshida, in the input box
	And I choose Author
	When I press the Submit button
	Then I should get a word cloud, with the word code
	When I click on a word, code
	Then I should get a list of articles, which includes Norihiro Yoshida as an author
	