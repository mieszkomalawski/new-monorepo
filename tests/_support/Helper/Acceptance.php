<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{
    public function login(\AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('_username', 'tester');
        $I->fillField('_password', 'testpass');
        $I->click('_submit');
    }
}
