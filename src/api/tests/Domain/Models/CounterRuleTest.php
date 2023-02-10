<?php
declare(strict_types=1);

namespace Tests\Domain\Models;

use DPR\API\Domain\Models\CounterRule;
use Tests\TestCase;

class CounterRuleTest extends TestCase {

    public static function counterRuleProvider() {
        $data = [
            [1, 2, "2022-01-01 00:00:00", "2022-05-01 00:00:00", 2.5, true]
        ];
        return $data;
    }

    /**
    * @dataProvider counterRuleProvider
    * @param $id
    * @param $devId
    * @param $start
    * @param $end
    * @param $multiplier
    * @param $status
    */
    public function testConstructor($id, $devId, $start, $end, $multiplier, $status): void {
        // Test constructing with DB array
        $testCounterRuleFromDB = new CounterRule(['rul_id' => $id, 'rul_dev_id' => $devId, 'rul_start' => $start, 'rul_end' => $end,
                'rul_multiplier' => $multiplier, 'rul_status' => $status]);
        // Test constructing with FE JSON
        $testCounterRuleFromFE = new CounterRule(json_encode(['id' => $id, 'dev_id' => $devId, 'start' => $start, 'end' => $end,
                'multiplier' => $multiplier, 'status' => $status]));

        $this->assertSame($id, $testCounterRuleFromDB->getId());
        $this->assertSame($devId, $testCounterRuleFromDB->getDevId());
        $this->assertSame($start, $testCounterRuleFromDB->getStart());
        $this->assertSame($end, $testCounterRuleFromDB->getEnd());
        $this->assertSame($multiplier, $testCounterRuleFromDB->getMultiplier());
        $this->assertSame($status, $testCounterRuleFromDB->getStatus());

        $this->assertSame($id, $testCounterRuleFromFE->getId());
        $this->assertSame($devId, $testCounterRuleFromFE->getDevId());
        $this->assertSame($start, $testCounterRuleFromFE->getStart());
        $this->assertSame($end, $testCounterRuleFromFE->getEnd());
        $this->assertSame($multiplier, $testCounterRuleFromFE->getMultiplier());
        $this->assertSame($status, $testCounterRuleFromFE->getStatus());
    }

}
