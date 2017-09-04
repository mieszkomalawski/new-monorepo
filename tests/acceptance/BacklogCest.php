<?php


class BacklogCest
{

    public function requiresLogin(AcceptanceTester $I)
    {

        $I->wantTo('Access backlog without logging in');

        $I->amOnPage('/logout');

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
        $I->fillField('create_item[name]', 'Add backlog feature');
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
        $I->fillField('create_item[name]', 'a');
        $I->click('Save');

        $I->seeInCurrentUrl('/backlog/new');
        $I->canSee('This value is too short');
    }

    public function canEstimateItem(AcceptanceTester $I)
    {
        $I->wantTo('Add item and then estimate it');
        $I->login($I);

        $I->amOnPage('/backlog');
        $I->click('//table/tbody/tr/td[7]/a');

        $I->waitForElement('form', 5);

        $I->fillField('update_item[estimate]', 3);
        $I->click('Save');

        $I->see(3, '//table/tbody/tr[1]/td[4]');
    }

    public function canSetItemStatus(AcceptanceTester $I)
    {
        $I->wantTo('Change item status');
        $I->login($I);

        $I->amOnPage('/backlog');

        // first item edit
        $I->click('//table/tbody/tr[1]/td[7]/a');

        $I->waitForElement('form', 5);

        $I->selectOption('update_item[status]', 'In progress');
        $I->click('Save');

        $I->see('in_progress', '//table/tbody/tr[1]/td[6]');

    }

    public function canAddSubTask(AcceptanceTester $I)
    {
        $I->wantTo('Add sub task to task');
        $I->login($I);

        $I->amOnPage('/backlog');

        // first item edit
        $I->click('//table/tbody/tr[1]/td[7]/a');

        $I->waitForElement('form', 5);

        $I->click('Add sub task');

        $I->waitForElement('form', 5);

        $I->fillField('create_sub_item[name]', 'New sub task');
        $I->click('Save');

        // see that sub task is added to main task

    }
}
