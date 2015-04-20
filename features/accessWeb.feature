Feature: Access through Web Browser
	In order for a user to access the product
	As a user
	I want to access the internet and get to the web application

Scenario: View PaperFloat/index.php
	Given I have access to a web browser with internet
	When I enter PaperFloat/index.php
	Then I should see the PaperFloat homepage 