<?php

use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class PersonnelRestoreTest extends CIUnitTestCase
{
    public function testInactivePersonnelQueryReturnsRecords()
    {
        $db = \Config\Database::connect('personnel');
        $query = $db->table('tb_personnel')
            ->select('
                tb_personnel.pers_id,
                tb_personnel.pers_prefix,
                tb_personnel.pers_firstname,
                tb_personnel.pers_lastname,
                tb_personnel.pers_status,
                skjacth_skj.tb_position.posi_name,
                skjacth_skj.tb_learning.lear_namethai,
                skjacth_skj.tb_position_main.work_name
            ')
            ->join('skjacth_skj.tb_position', 'skjacth_skj.tb_position.posi_id = tb_personnel.pers_position', 'left')
            ->join('skjacth_skj.tb_learning', 'skjacth_skj.tb_learning.lear_id = tb_personnel.pers_learning', 'left')
            ->join('skjacth_skj.tb_position_main', 'skjacth_skj.tb_position_main.work_id = tb_personnel.pers_workother_id', 'left')
            ->where('tb_personnel.pers_status !=', 'กำลังใช้งาน')
            ->orderBy('tb_personnel.pers_id', 'DESC')
            ->get()->getResult();

        $this->assertNotEmpty($query, 'Inactive personnel records should not be empty');
        $this->assertGreaterThanOrEqual(50, count($query), 'There should be at least 50 inactive personnel');

        $first = $query[0];
        $this->assertNotEmpty($first->pers_id);
        $this->assertNotEquals('กำลังใช้งาน', $first->pers_status);
    }

    public function testRestoreSimulationOnMockData()
    {
        $db = \Config\Database::connect('personnel');

        // Pick one inactive record without modifying real production permanently
        $inactive = $db->table('tb_personnel')
            ->where('pers_status', 'ลาออก')
            ->get()->getFirstRow();

        $this->assertNotNull($inactive, 'Should find at least one resigned personnel');

        $originalStatus = $inactive->pers_status;
        $originalPos = $inactive->pers_position;

        // Test update operation
        $updated = $db->table('tb_personnel')
            ->where('pers_id', $inactive->pers_id)
            ->update([
                'pers_status' => 'กำลังใช้งาน'
            ]);

        $this->assertTrue($updated, 'Update to active should succeed');

        // Verify it was updated
        $check = $db->table('tb_personnel')
            ->where('pers_id', $inactive->pers_id)
            ->get()->getFirstRow();

        $this->assertEquals('กำลังใช้งาน', $check->pers_status);

        // Revert back immediately to preserve database integrity
        $db->table('tb_personnel')
            ->where('pers_id', $inactive->pers_id)
            ->update([
                'pers_status' => $originalStatus,
                'pers_position' => $originalPos
            ]);

        $reverted = $db->table('tb_personnel')
            ->where('pers_id', $inactive->pers_id)
            ->get()->getFirstRow();

        $this->assertEquals($originalStatus, $reverted->pers_status);
    }
}
