Feature: Single Purchases

    In order to avoid subscriptions
    As a viewer
    I want to purchase stand-alone videos

    @javascript
    Scenario: Purchasing a video when I'm logged out
        Given I am not logged in
        And there is a "Example Lesson" Lesson
        When I go to this lesson page
        Then I should be asked to sign up

    @javascript
    Scenario: Purchasing a video when I'm logged in
        Given I am logged in without a subscription
        And there is a "Example Lesson" lesson
        When I go to this lesson page
        And I purchase the video
        Then I should be able to download it

    @javascript
    Scenario: Reviewing my past purchase charges
        Given I am logged in without a subscription
        And I have purchased "Example Lesson"
        When I go to the payments page
        Then I should see a "$4.00" charge
        

