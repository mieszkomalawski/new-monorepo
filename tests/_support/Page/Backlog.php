<?php
namespace Page;

class Backlog
{
    // include url of current page
    public static $URL = '/backlog';

    /**
     * @var \AcceptanceTester
     */
    private $tester;

    /**
     * Backlog constructor.
     * @param \AcceptanceTester $tester
     */
    public function __construct(\AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * @return string
     */
    public function getCreateItemUrl()
    {
        return self::$URL . '/new';
    }


    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

    public function editItemOnList()
    {
        $this->tester->click('//table/tbody/tr[1]/td[8]/a');
    }

    public function createFormFillName($name)
    {
        $this->tester->fillField('create_item[name]', $name);
    }

    public function createFormSave()
    {
        $this->tester->click('Save');
    }

    public function updateFormSave()
    {
        $this->tester->click('Save');
    }

    public function updateFormFillEstimate($estimate)
    {
        $this->tester->fillField('update_item[estimate]', $estimate);
    }

    public function assertEstimateValue($estimate)
    {
        $this->tester->see( $estimate, '//table/tbody/tr[1]/td[4]');
    }

    public function updateFormFillStatus($status)
    {
        $this->tester->selectOption('update_item[status]', $status);
    }

    public function assertStatusValue($estimate)
    {
        $this->tester->see( $estimate, '//table/tbody/tr[1]/td[7]');
    }
}
