<?php

namespace api\tests\api;

use api\tests\ApiTester;

class HomeCest
{
    public function mainPage(ApiTester $I)
    {
        $I->sendGet('/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
