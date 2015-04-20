
require "watir-webdriver"

browser = Watir::Browser.new

Given(/^the PaperFloat home page$/) do
  @browser.goto "http://localhost:8888/PaperFloat/index.php"
end

Given(/^I have entered a valid key word, Clone, in the input box$/) do
	@browser.text_field(:name => "searchterm").set("Clone")
	#browser.refresh
end

When(/^I press the 'Submit' button or enter key$/) do
  #browser.input(:id =>"Submit").click
  @browser.refresh
end

Then(/^I should see a progress bar indicating the status of the search$/) do
  @browser.refresh
end
