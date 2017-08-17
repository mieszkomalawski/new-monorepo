<?php


class SprintCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
   /* public function canCreateSprint(AcceptanceTester $I)
    {
        $I->wantTo('create new sprint');

        $I->login($I);

        $I->amOnPage('/backlog');

        $I->click('Create new sprint');

        $I->waitForElement('form', 5);

        $now = new DateTime();
        $now->add(new DateInterval('P1D'));
        $I->fillField('form[duration]', 1);
        $I->click('Save');

        $I->canSeeInCurrentUrl('/sprints');
    }*/
}
