Given (/^I am logged in$/) do

  # be sure to use the right browser session
  Capybara.session_name = 'test'

  # logout - just to be sure
  visit '/index.php?logout=true'
  visit '/'
  fill_in 'user', with: 'test'
  fill_in 'password', with: "test"
  click_button 'submit'

  # if visibility is not set to false it will fail
  page.should have_selector('a#logout', :visible => false)
end

Given (/^I am in the "([^"]+)" app$/) do |app|
  visit "/index.php/apps/#{app}/"
  page.should have_selector('a#logout', :visible => false)
end

When (/^I go to "([^"]+)"$/) do |path|
  visit "#{path}"
  page.should have_selector('a#logout', :visible => false)
end