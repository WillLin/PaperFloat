require "watir-webdriver"

Before do
    @browser = Watir::Browser.new
end

Given(/^I have access to a web browser with internet$/) do
    @browser.goto "http://google.com"
end

When(/^I enter PaperFloat\/index\.php$/) do
    @browser.goto "http://localhost/PaperFloat/index.php"
end

Then(/^I should see the PaperFloat homepage$/) do
    @browser.refresh
end