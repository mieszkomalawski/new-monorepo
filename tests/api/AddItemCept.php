<?php
$I = new ApiTester($scenario);
$I->wantTo('Add item to backlog');

$I->authenticate();

$randomName = 'new_test_item_' . uniqid();
$I->sendPOST('/items.json', ['name' => $randomName]);

$I->canSeeResponseCodeIs(201);

