<?php
//require db_configuration file
//set_include_path(dirname(__FILE__)."/../");
include_once __DIR__. '/../database/db_config.php';

//require vendor
require_once __DIR__ . "/../../vendor/autoload.php";

//require user_class
include __DIR__ . "/../classes/user_class.php";

use PHPUnit\Framework\TestCase;

class UserClassTest extends TestCase
{
    private $user_class;
    

    protected function setUp(): void
    {
        $this->user_class = new user_class();
    }

    protected function tearDown(): void
    {
        $this->user_class = null;
    }

    public function testRegisterUser(): void
    {
        $password = 'password';
        $password = password_hash($password,PASSWORD_DEFAULT);
        $result = $this->user_class->register_user('John', 'Doe', 'johndoe@example.com',$password , '1234567890');
        var_dump($result);
        $this->assertTrue($result !== false);
    }

    public function testSelectUser(): void
    {
        $user_id = $this->user_class->get_new_record();
        $user = $this->user_class->select_user($user_id);
        $this->assertNotEmpty($user);
        $this->assertEquals($user['id'], $user_id);
    }

    public function testVerifyUser(): void
    {
        $email = 'johndoe@example.com';
        $password = 'password';
        //$password = password_hash($password,PASSWORD_DEFAULT);
        $result = $this->user_class->verify_user($email, $password);
        $this->assertTrue($result == TRUE);
    }

    public function testGetUserInfo(): void
    {
        $email = 'johndoe@example.com';
        $user = $this->user_class->get_user_info($email);
        $this->assertNotEmpty($user);
        $this->assertEquals($user['email'], $email);
    }

    public function testEmailExists(): void
    {
        $email = 'johndoe@example.com';
        $result = $this->user_class->email_exists($email);
        $this->assertTrue($result !== false);
    }

    public function testUpdateEmail(): void
    {
        //$user_id = 6;
        $user_id = $this->user_class->get_new_record();
        $new_email = 'newemail@example.com';
        $result = $this->user_class->update_email($new_email, $user_id);
        //$this->assertTrue($result !== false);
        $user = $this->user_class->select_user($user_id);
        $this->assertEquals($user['email'], $new_email);
    }

    /*public function testAddUserInvite(): void
    {
        //$user_id = 6;
        $user_id = $this->user_class->get_new_record();
        $token = 'abc123';
        $result = $this->user_class->add_user_invite($user_id, $token);
        $this->assertTrue($result);
    }
    */

    /*public function testSelectUserInvites(): void
    {
        $user_id = 6;
        $invites = $this->user_class->select_user_invites($user_id);
        $this->assertIsArray($invites);
        $this->assertNotEmpty($invites);
        foreach ($invites as $invite) {
            $this->assertEquals($invite['user_id'], $user_id);
        }
    }*/

    /*public function testDeleteUserInvite(): void
    {
        $user_id = 1;
        $invite_id = 1;
        $result = $this->user_class->delete_user_invite($user_id, $invite_id);
        $this->assertTrue($result);
    }
    */
}
