
require "watir-webdriver"

browser = Watir::Browser.new

Given(/^the PaperFloat home page$/) do
  @browser.goto "http://localhost/PaperFloat/index.php"
end

Given(/^I have entered a valid key word, Clone, in the input box$/) do
	@browser.text_field(:name => "searchterm").set("Clone")
	@browser.text_field(:id => "searchlimit").set("2")
	#browser.refresh
end

When(/^I press the 'Submit' button or enter key$/) do
  @browser.input(:id =>"submitbutton").click
end

Then(/^I should see a progress bar indicating the status of the search$/) do
	b = @browser.div :id => 'progressbar'
	b.exists?
	b.text	
end
