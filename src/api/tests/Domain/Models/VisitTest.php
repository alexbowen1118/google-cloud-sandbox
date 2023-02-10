<?php
declare(strict_types=1);

namespace Tests\Domain\Models;

use DPR\API\Domain\Models\Visit;
use Tests\TestCase;

class VisitTest extends TestCase {

    public static function visitProvider() {
        $data = [
            [1, 218, 2, "2022-01-01 08:00:00", 4, 10, "", 1]
        ];
        return $data;
    }

    /**
    * @dataProvider visitProvider
    * @param $id
    * @param $par
    * @param $devId
    * @param $timestamp
    * @param $count
    * @param $countCalculated
    * @param $comments
    * @param $status
    */
    public function testConstructor($id, $parId, $devId, $timestamp, $count, $countCalculated, $comments, $status): void {
        // Test constructing with DB array
        $testVisitFromDB = new Visit(['vis_id' => $id,'vis_par_id' => $parId, 'vis_dev_id' => $devId, 'vis_timestamp' => $timestamp, 'vis_count' => $count,
                'vis_count_calculated' => $countCalculated, 'vis_comments' => $comments, 'vis_status' => $status]);
        // Test constructing with FE JSON
        $testVisitFromFE = new Visit(json_encode(['id' => $id, 'par_id' => $parId, 'dev_id' => $devId, 'timestamp' => $timestamp, 'count' => $count,
                'count_calculated' => $countCalculated, 'comments' => $comments, 'status' => $status]));

        $this->assertSame($id, $testVisitFromDB->getId());
        $this->assertSame($parId, $testVisitFromDB->getParId());
        $this->assertSame($devId, $testVisitFromDB->getDevId());
        $this->assertSame($timestamp, $testVisitFromDB->getTimestamp());
        $this->assertSame($count, $testVisitFromDB->getCount());
        $this->assertSame($countCalculated, $testVisitFromDB->getCountCalculated());
        $this->assertSame($comments, $testVisitFromDB->getComments());
        $this->assertSame($status, $testVisitFromDB->getStatus());

        $this->assertSame($id, $testVisitFromFE->getId());
        $this->assertSame($parId, $testVisitFromFE->getParId());
        $this->assertSame($devId, $testVisitFromFE->getDevId());
        $this->assertSame($timestamp, $testVisitFromFE->getTimestamp());
        $this->assertSame($count, $testVisitFromFE->getCount());
        $this->assertSame($countCalculated, $testVisitFromFE->getCountCalculated());
        $this->assertSame($comments, $testVisitFromFE->getComments());
        $this->assertSame($status, $testVisitFromFE->getStatus());
    }

}
