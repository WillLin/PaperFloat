require "watir-webdriver"

Before do
    @browser = Watir::Browser.new
end

Given(/^I have access to a web browser with internet$/) do
    @browser.goto "http://google.com"
end

When(/^I enter PaperFloat\/index\.php$/) do
    @browser.goto "http://localhost/introducingphp/paperfloat/PaperFloat/PaperFloat/index.php"
end

Then(/^I should see the PaperFloat homepage$/) do
    @browser.refresh
end

Given(/^the PaperFloat home page has loaded$/) do
  visit 'http://localhost/introducingphp/paperfloat/PaperFloat/PaperFloat/'
end

Given(/^I have entered a valid key word, Turing, in the input box$/) do
  fill_in 'key_word', :with => 'Turing'
end

When(/^I press the 'Submit' button$/) do
  click_button('Submit')
end

Then(/^I should get a word cloud, with the word back$/) do
  assert_text('back')
end

