<?php
//require db_configuration file
//set_include_path(dirname(__FILE__)."/../");
include_once __DIR__. '/../database/db_config.php';

//require vendor
require_once __DIR__ . "/../../vendor/autoload.php";

//require user_class
include __DIR__ . "/../classes/group_class.php";

use PHPUnit\Framework\TestCase;

class GroupClassTest extends TestCase
{
    private $group_class;

    protected function setUp(): void
    {
        $this->group_class = new group_class();
    }

    public function testAddGroup()
    {
        $group_name = "Test Group";
        $group_desc = "This is a test group";

        $result = $this->group_class->add_group($group_name, $group_desc);

        $this->assertTrue($result !== false);
    }

    public function testUserGroups()
    {
        $user_id = 32;

        $result = $this->group_class->user_groups($user_id);

        $this->assertIsArray($result);
    }

    public function testSelectGroup()
    {
        $group_id = 11;

        $result = $this->group_class->select_group($group_id);

        $this->assertIsArray($result);
    }

    public function testCountGroup()
    {
        $group_id = 11;

        $result = $this->group_class->count_group($group_id);

        $this->assertIsInt($result);
    }

    public function testGroupExists()
    {
        $group_name = "Test Group";

        $result = $this->group_class->group_exists($group_name);

        $this->assertTrue($result);
    }

    public function testAddUserGroup()
    {
        $user_id = 32;
        $group_id = 11;

        $result = $this->group_class->add_user_group($user_id, $group_id);

        $this->assertTrue($result !== false);
    }

    public function testAddGroupAdmin()
    {
        $user_id = 32;
        $group_id = 11;

        $result = $this->group_class->add_group_admin($user_id, $group_id);

        $this->assertTrue($result !== false);
    }

    public function testGetGroupId()
    {
        $group_name = "Test Group";

        $result = $this->group_class->get_group_id($group_name);

        $this->assertIsInt($result);
    }

    public function testIsMember()
    {
        $user_id = 32;
        $group_id = 11;

        $result = $this->group_class->is_member($group_id, $user_id);

        $this->assertTrue($result);
    }

    public function testIsAdmin()
    {
        $user_id = 32;
        $group_id = 11;

        $result = $this->group_class->is_admin($group_id, $user_id);

        $this->assertTrue($result);
    }

    public function testAllMembers()
    {
        $group_id = 11;

        $result = $this->group_class->all_members($group_id);

        $this->assertIsArray($result);
    }

}
