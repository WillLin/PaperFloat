
require "watir-webdriver"

browser = Watir::Browser.new

Given(/^the PaperFloat home page has loaded$/) do
  @browser.goto "http://localhost:8888/PaperFloat/index.php"
end

Given(/^I have entered a valid key word, Turing, in the input box$/) do
	@browser.text_field(:name => "searchterm").set("Turing")
	#browser.refresh
end

When(/^I press the 'Submit' button$/) do
  #browser.input(:id =>"Submit").click
  @browser.refresh
end

Then(/^I should get a word cloud, with the word back$/) do
  @browser.refresh
end
