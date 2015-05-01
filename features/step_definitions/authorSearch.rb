Given(/^the PaperFloat page has loaded$/) do
	visit 'http://localhost/PaperFloat/'
end

Given(/^I have entered a valid author, Norihiro Yoshida, in the input box$/) do
	fill_in 'searchterm', :with => 'Norihiro Yoshida'
	fill_in 'searchlimit', :with => '2'
end

Given(/^I choose Author$/) do
	choose('radio_author')
end

When(/^I press the Submit button$/) do
	click_button('Submit')
end

Then(/^I should get a word cloud, with the word code$/) do
	assert_text('code')
end

When(/^I click on a word, code$/) do
	click_link('code')
end

Then(/^I should get a list of articles, which includes Norihiro Yoshida as an author$/) do
	assert_text('Norihiro Yoshida')
end
