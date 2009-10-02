<?php
/**
 *  Test Case de Model
 *
 *  @license   http://www.opensource.org/licenses/mit-license.php The MIT License
 *  @copyright Copyright 2008-2009, Spaghetti* Framework (http://spaghettiphp.org/)
 *
 */

class User extends AppModel {
    public $table = false;
}

class TestModel extends UnitTestCase {
    public function setUp() {
        $this->User = new User;
    }
    public function tearDown() {
        $this->User = null;
    }
    public function testValidateWithNoRules() {
        $data = array(
            "username" => "spaghettiphp"
        );
        $result = $this->User->validate($data);
        $this->assertTrue($result);
    }
    public function testValidateWithSimpleRule() {
        $this->User->validates = array(
            "username" => "alphanumeric"
        );
        $data = array(
            "username" => "spaghettiphp"
        );
        $result = $this->User->validate($data);
        $this->assertTrue($result);
    }
    public function testErrorsOfValidateWithSimpleRule() {
        $this->User->validates = array(
            "username" => "alphanumeric"
        );
        $data = array(
            "username" => "Spaghetti* Framework"
        );
        $this->User->validate($data);
        $result = $this->User->errors;
        $this->assertEqual($result, array("alphanumeric"));
    }
    public function testValidateWithMultipleRules() {
        $this->User->validates = array(
            "username" => array(
                array("rule" => "alphanumeric"),
                array("rule" => "notEmpty")
            )
        );
        $data = array(
            "username" => ""
        );
        $result = $this->User->validate($data);
        $this->assertFalse($result);
    }
}

?>