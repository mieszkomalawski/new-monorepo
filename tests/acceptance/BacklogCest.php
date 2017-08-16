<?php


class BacklogCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
        $I->amOnPage('/logout');
    }

    public function requiresLogin(AcceptanceTester $I)
    {
        $I->wantTo('Access backlog without logging in');

        $I->amOnPage('/backlog');

        $I->seeInCurrentUrl('/login');
    }

    // tests
    public function canAddItemsToBacklog(AcceptanceTester $I)
    {
        $I->wantTo('Add black item with name: Add backlog feature');
        $I->login($I);

        $I->amOnPage('/backlog');
        $I->click('Add Item');
        $I->fillField('form[name]', 'Add backlog feature');
        $I->click('Save');

        $I->seeInCurrentUrl('/backlog');
        $I->canSee('Add backlog feature');
    }

    // tests
    public function cannotAddInvalidItem(AcceptanceTester $I)
    {
        $I->wantTo('Try to add invalid item and get rejected');
        $I->login($I);

        $I->amOnPage('/backlog');
        $I->click('Add Item');
        $I->fillField('form[name]', 'a');
        $I->click('Save');

        $I->seeInCurrentUrl('/backlog/new');
        $I->canSee('This value is too short');
    }
}
