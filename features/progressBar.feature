Feature: Display Progress Bar
	In order for a user to see the progress of the search
	As a user
	I want to click search and wait

Scenario: Search on PaperFloat
	Given the PaperFloat home page
	And I have entered a valid key word, Clone, in the input box
	When I press the 'Submit' button or enter key
	Then I should see a progress bar indicating the status of the search