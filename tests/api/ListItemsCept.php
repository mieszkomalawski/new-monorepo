<?php
$I = new ApiTester($scenario);
$I->wantTo('list backlog items');

$I->authenticate();

$I->sendGET('/items/list.json');

$I->seeResponseMatchesJsonType([
    'id' => 'integer',
    'name' => 'string',
    'created_at' => 'string',
    'estimate' => 'integer',
    'priority' => 'integer',
    'status' => 'string',
    'image_name' => 'string',

], '$.[0]');

