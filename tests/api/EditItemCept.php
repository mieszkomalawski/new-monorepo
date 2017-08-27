<?php
$I = new ApiTester($scenario);
$I->wantTo('Edit item');

$I->authenticate();

$I->sendGET('/items.json');

$item = $I->grabDataFromResponseByJsonPath('$.[0]');
$singleItem = $preUpdate = $item[0];

$singleItem['name'] = 'updated_name';
$singleItem['priority'] += 20;

$I->sendPUT('/items/ ' . $singleItem['id'] . '.json', $singleItem);

$I->seeResponseCodeIs(200);



