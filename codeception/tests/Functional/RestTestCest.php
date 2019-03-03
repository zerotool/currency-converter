<?php

namespace App\Tests\Functional;

use \FunctionalTester;
use App\Tests\Functional\BaseFunctionalCest;

class HelloFromTestCest extends BaseFunctionalCest
{
    public function runConvertWithoutParameters_GetError(FunctionalTester $I)
    {
        $this->sendConvertRequest([], ['success' => false, 'error' => 'Please set currency code']);
    }

    public function runConvertWithWrongParameters_GetError(FunctionalTester $I)
    {
        $this->sendConvertRequest([
            'from_currency_code' => 'USD',
            'to_currency_code' => 'RUB',
            'aamount' => 1234.5678,
        ], [
            'success' => false,
            'error' => 'Error in parameters: Object(Symfony\Component\Form\Form):
    This form should not contain extra fields. (code 6e5212ed-a197-4339-99aa-5654798a4854)'
        ]);
    }

    public function runConvertWithoutRates_GetError(FunctionalTester $I)
    {
        $I->updateInDatabase('currency_rate', ['currency_code' => 'US0'], ['currency_code' => 'USD']);
        $I->updateInDatabase('currency_rate', ['currency_code' => 'RU0'], ['currency_code' => 'RUB']);
        $this->sendConvertRequest([
            'from_currency_code' => 'USD',
            'to_currency_code' => 'RUB',
            'amount' => 1234.5678,
        ], [
            'success' => false,
            'error' => 'Unable to find currency rate for currency code USD',
        ]);
    }

    public function runConvert_GetSuccess(FunctionalTester $I)
    {
        $I->updateInDatabase('currency_rate', ['currency_code' => 'US0'], ['currency_code' => 'USD']);
        $I->updateInDatabase('currency_rate', ['currency_code' => 'RU0'], ['currency_code' => 'RUB']);
        $I->haveInDatabase('currency_rate', array('id' => time(), 'currency_code' => 'USD', 'rate' => 5));
        $I->haveInDatabase('currency_rate', array('id' => time() + 1, 'currency_code' => 'RUB', 'rate' => 1));
        $this->sendConvertRequest([
            'from_currency_code' => 'USD',
            'to_currency_code' => 'RUB',
            'amount' => 1234.5678,
        ], [
            'success' => true,
            'data' => ['amount_converted' => 246.9136],
        ]);
    }

    private function sendConvertRequest($sendJson, $expectedJson)
    {
        $this->I->sendPOST('/convert', $sendJson);
        $this->I->seeResponseIsJson();
        $this->I->seeResponseContainsJson($expectedJson);
    }
}
