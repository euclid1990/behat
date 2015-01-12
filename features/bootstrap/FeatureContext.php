<?php
// Behat Class & Trait
use Behat\Behat\Context\Context,
    Behat\Behat\Context\SnippetAcceptingContext,
    Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
// Behat Suite and Scenario
use Behat\Behat\Event\SuiteEvent,
    Behat\Behat\Event\ScenarioEvent;
// Behat BeforeScenario & AfterScenario
use Behat\Behat\Hook\Scope\BeforeFeatureScope,
    Behat\Behat\Hook\Scope\AfterFeatureScope;

// MinkContext Class
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Tests\Driver;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Mink\Session;
use Behat\Mink\Driver\DriverInterface;
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
     * Find the element with the provided CSS Selector
     * @When /^I found the element "([^"]*)"$/
     */
    public function iFoundTheElement($cssSelector)
    {
        $session = $this->getSession()->wait(1000);
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('css', $cssSelector) // just changed xpath to css
        );
        if (is_null($element)) {
            throw new \InvalidArgumentException(sprintf('Could not found element: "%s"', $cssSelector));
        }
    }

    /**
     * @Given /^I found the element "([^"]*)" has text "([^"]*)"$/
     */
    public function iFoundTheElementHasText($cssSelector, $text)
    {
        $session = $this->getSession();
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('css', $cssSelector) // just changed xpath to css
        );
        if ($element->getText() != $text) {
            throw new \InvalidArgumentException(sprintf('Could not found element: "%s" has text : "%s"', $cssSelector, $text));
        }
    }

    /**
     * @Given /^I fill field "([^"]*)" with "([^"]*)"$/
     */
    public function iFillFieldWith($field, $value)
    {
        $session = $this->getSession();
        $element = $session->getPage()->find(
            'css',
            'input[name="'.$field.'"]'
        );
        $element->setValue($value);
        if (is_null($element)) {
            throw new \InvalidArgumentException(sprintf('Could not found input: "%s"', $field));
        }
    }


    /**
     * Click on the element with the provided CSS Selector
     * @When /^I click on the element "([^"]*)" number "([^"]*)"$/
     */
    public function iClickOnTheElement($cssSelector, $number)
    {
        $session = $this->getSession();
        $elements = $session->getPage()->findAll(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('css', $cssSelector) // just changed xpath to css
        );
        $element = NULL;
        for ($i = 0; $i < $number; $i++) {
            $element = array_shift($elements);
        }
        if (is_null($element)) {
            throw new \InvalidArgumentException(sprintf('Could not click element: "%s"', $cssSelector));
        }
        $element->click();
    }

    /**
     * Click on the button with the value of button
     * @When /^I click on the button has value "([^"]*)"$/
     */
    public function iClickOnTheButtonHasValue($value)
    {
        $session = $this->getSession();
        $element = $session->getPage()->find(
            'css',
            'input[value="'.$value.'"]'
        );
        if (is_null($element)) {
            throw new \InvalidArgumentException(sprintf('Could not click button: "%s"', $value));
        }
        $element->click();
    }

    /**
     * @Then I wait for :arg1 seconds
     * @param int $senconds
     */
    public function iWaitForSeconds($seconds)
    {
        $this->getSession()->wait(1000*$seconds);
    }

}