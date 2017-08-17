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

    public function canEstimateItem(AcceptanceTester $I)
    {
        $I->wantTo('Add item and then estimate it');
        $I->login($I);

        $I->amOnPage('/backlog');
        $I->click('//table/tbody/tr/td[6]/a');

        $I->waitForElement('form', 5);

        $I->fillField('form[estimate]', 3);
        $I->click('Save');

        $I->see(3, '//table/tbody/tr[1]/td[3]');
    }

    public function canChangeItemPriority(AcceptanceTester $I)
    {
        $I->wantTo('Change item priority');
        $I->login($I);

        $I->amOnPage('/backlog');
        $I->click('Add Item');
        $I->fillField('form[name]', 'low priority item');
        $I->click('Save');

        $itemName = $I->grabTextFrom('//table/tbody/tr[2]/td[1]');
        $I->click('//table/tbody/tr[2]/td[6]/a');

        $I->waitForElement('form', 5);

        /**
         * Use timestamp as it is guarenteed to be always higher than before
         */
        $value = time();
        $I->fillField('form[priority]', $value);
        $I->click('Save');

        $I->see($itemName, '//table/tbody/tr[1]/td[1]');
        $I->see($value, '//table/tbody/tr[1]/td[4]');
    }

    public function canSetItemStatus(AcceptanceTester $I)
    {
        $I->wantTo('Change item status');
        $I->login($I);

        $I->amOnPage('/backlog');

        // first item edit
        $I->click('//table/tbody/tr[1]/td[6]/a');

        $I->waitForElement('form', 5);

        $I->selectOption('form[status]', 'In progress');
        $I->click('Save');

        $I->see('in_progress', '//table/tbody/tr[1]/td[5]');

    }
}
