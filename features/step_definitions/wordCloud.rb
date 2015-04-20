
require "watir-webdriver"

browser = Watir::Browser.new

Given(/^the PaperFloat home page has loaded$/) do
  @browser.goto "http://localhost/PaperFloat/index.php"
end

Given(/^I have entered a valid key word, clone, in the input box$/) do
	@browser.text_field(:name => "searchterm").set("clone")
	@browser.text_field(:id => "searchlimit").set("2")
	#browser.refresh
end

When(/^I press the 'Submit' button$/) do
  @browser.input(:id =>"submitbutton").click
end

Then(/^I should get a word cloud, with the word clone$/) do
  @browser.text.include?("clone").should == true
end
