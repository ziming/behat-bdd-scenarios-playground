<?php

use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
#This will be needed if you require "behat/mink-selenium2-driver"
#use Behat\Mink\Driver\Selenium2Driver;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given I am not logged in
     */
    public function iAmNotLoggedIn()
    {
        return;
    }

    /**
     * @Given there is a :arg1 Lesson
     */
    public function thereIsALesson($title)
    {
        // generate slug from title
        // Use factory to create 1 lesson
        $slug = str_slug($title);
        factory(App\Lesson::class)->create(compact('title', 
            'slug'));
    }

    /**
     * @When I go to this lesson page
     */
    public function iGoToThisLessonPage()
    {
        $this->visit('/lessons/example-lesson');    
    }

    /**
     * @When I purchase the video
     */
    public function iPurchaseTheVideo()
    {

        $this->pressButton('watch this video for $4');


        // Make the browser wake 5000 ms so I can see it
        // $this->getSession()->wait(100);

    }

    /**
     * @Then I should be able to download it
     */
    public function iShouldBeAbleToDownloadIt()
    {
        $this->visit('/admin/purchases');
        $this->assertPageContainsText('Example Lesson');
    }

    /**
     * @Then I should be asked to sign up
     */
    public function iShouldBeAskedToSignUp()
    {
        $this->assertPageContainsText('3 Ways to watch this video');
        $this->assertPageContainsText('Purchase it for $4');
    }

    /**
     * @Given I am logged in without a subscription
     */
    public function iAmLoggedInWithoutASubscription() {
        $this->signup('?plan=none');
    }

    public function signup($querystring = null) {
        // note that selenium server need to be running for the code below or else it fail.
        $this->visit('/signup' . $querystring);

        $this->fillField('username', 'someuser');
        $this->fillField('email', 'someuser@example.com');
        $this->fillFIeld('password', 'foobar');
        $this->fillField('password_confirmation', 'foobar');

        $this->fillFIeld('cc-number', 1234123412341234);
        $this->selectOption('cc-expiration-month', 'December');
        $this->selectOption('cc-expiration-year', 2029);
        $this->fillFIeld('cvv', 123);

        $this->pressButton('Create Account');
        // $this->getSession()->wait(1000);
    }


    /**
     * @Given I have purchased :arg1
     */
    public function iHavePurchased($lessonName)
    {
        $this->thereIsALesson($lessonName);
        $this->iGoToThisLessonPage();
        $this->iPurchaseTheVideo();
    }

    /**
     * @When I go to the payments page
     */
    public function iGoToThePaymentsPage()
    {
        $this->visit('/admin/subscription/payments');
    }

    /**
     * @Then I should see a :arg1 charge
     */
    public function iShouldSeeACharge($amount)
    {
        $this->assertPageContainsText($amount);
    }
}
