<?php


use Page\Backlog;

class BacklogCest
{

    public function requiresLogin(AcceptanceTester $I)
    {

        $I->wantTo('Access backlog without logging in');

        $I->amOnPage('/en/logout');

        $I->amOnPage(Backlog::$URL);

        $I->seeInCurrentUrl('/login');
    }

    // tests
    public function canAddItemsToBacklog(AcceptanceTester $I, Backlog $backlog)
    {
        $I->wantTo('Add black item with name: Add backlog feature');
        $I->login($I);

        $I->amOnPage(Backlog::$URL);
        $I->click('Add Item');
        $backlog->createFormFillName('Add backlog feature');
        $backlog->createFormSave();

        $I->seeInCurrentUrl(Backlog::$URL);
        $I->canSee('Add backlog feature');
    }

    // tests
    public function cannotAddInvalidItem(AcceptanceTester $I, Backlog $backlog)
    {
        $I->wantTo('Try to add invalid item and get rejected');
        $I->login($I);

        $I->amOnPage(Backlog::$URL);
        $I->click('Add Item');
        $backlog->createFormFillName('a');
        $backlog->createFormSave();

        $I->seeInCurrentUrl($backlog->getCreateItemUrl());
        $I->canSee('This value is too short');
    }

    public function canEstimateItem(AcceptanceTester $I, Backlog $backlog)
    {
        $I->wantTo('Add item and then estimate it');
        $I->login($I);

        $I->amOnPage(Backlog::$URL);
        $backlog->editItemOnList();

        $I->waitForElement('form', 5);
        $I->scrollTo('#update_item_save');

        $backlog->updateFormFillEstimate(3);
        $backlog->updateFormSave();

        $backlog->assertEstimateValue(3);
    }

    public function canSetItemStatus(AcceptanceTester $I, Backlog $backlog)
    {
        $I->wantTo('Change item status');
        $I->login($I);

        $I->amOnPage(Backlog::$URL);

        // first item edit
        $backlog->editItemOnList();

        $I->waitForElement('form', 5);
        $I->scrollTo('#update_item_save');

        $backlog->updateFormFillStatus('In progress');
        $backlog->updateFormSave();

        $backlog->assertStatusValue('in_progress');

    }

    public function canAddSubTask(AcceptanceTester $I, Backlog $backlog)
    {
        $I->wantTo('Add sub task to task');
        $I->login($I);

        $I->amOnPage(Backlog::$URL);

        // first item edit
        $backlog->editItemOnList();

        $I->waitForElement('form', 5);

        $I->click('Add sub task');

        $I->waitForElement('form', 5);

        $I->fillField('create_sub_item[name]', 'New sub task');
        $backlog->updateFormSave();

        // see that sub task is added to main task

    }
}
