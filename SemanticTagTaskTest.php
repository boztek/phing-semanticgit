<?php

include_once "SemanticTagTask.php";
include_once "GitTagMock.php";

/**
 * PHPUnit test coverage for GitSemanticTagTask.
 */
class SemanticTagTaskTest extends PHPUnit_Framework_TestCase {
  
  /**
   * @var GitSemanticTagTask
   */
  protected $object;

  /**
   * @var GitTagMock
   */
  protected $tagTask;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->tagTask = new GitTagMock;
    $this->object = new SemanticTagTask($this->tagTask);
  }

  /**
   * @covers SemanticTagTask::main
   * @todo   Implement testMain().
   */
  public function testMain() {
    // Test initial commit. This is where no semantic tags are present.
    // Expecting "0.0.0".
    $this->object->main();
    $latest_tag = $this->tagTask->getLatestTag();
    $this->assertEquals('0.0.0', $latest_tag, 'Create initial commit.');

    // Test incrementing "0.0.0" to "0.0.1" this will ensure patch number 0
    // is not treated as an empty value.
    $this->object->main();
    $latest_tag = $this->tagTask->getLatestTag();
    $this->assertEquals('0.0.1', $latest_tag, 'Increment patch tag.');

    // Increment the minor version. Expecting "0.1.0".
    $this->object->setMinor('1');
    $this->object->main();
    $latest_tag = $this->tagTask->getLatestTag();
    $this->assertEquals('0.1.0', $latest_tag, 'Increment minor tag.');

    // Increment the patch version. Expecting "0.1.1".
    $this->object->main();
    $latest_tag = $this->tagTask->getLatestTag();
    $this->assertEquals('0.1.1', $latest_tag, 'Increment patch on minor tag.');

    // Increment the major. Expecting "1.0.0".
    $this->object->setMajor('1');
    $this->object->main();
    $latest_tag = $this->tagTask->getLatestTag();
    $this->assertEquals('1.1.0', $latest_tag, 'Increment major tag.');

    // Increment the minor. Expecting "1.1.0".
    $this->object->setMinor('2');
    $this->object->main();
    $latest_tag = $this->tagTask->getLatestTag();
    $this->assertEquals('1.2.0', $latest_tag, 'Increment minor on major tag.');
  }

  /**
   * @covers SemanticTagTask::buildTag
   */
  public function testBuildTag() {
    $tag = $this->object->buildTag(1, 2, 3);
    $this->assertEquals('1.2.3', $tag, 'Tags are being generated correctly.');
  }

  /**
   * @covers SemanticTagTask::setGit
   * @covers SemanticTagTask::getGit
   */
  public function testGit() {
    $set = 'foo';
    $this->object->setGit($set);
    $get = $this->object->getGit();
    $this->assertEquals($set, $get, 'Git executable path can be set and returned.');
  }

  /**
   * @covers SemanticTagTask::setMajor
   * @covers SemanticTagTask::getMajor
   */
  public function testMajor() {
    $set = 1;
    $this->object->setMajor($set);
    $get = $this->object->getMajor();
    $this->assertEquals($set, $get, 'Major numbers can be set and returned.');
  }

  /**
   * @covers SemanticTagTask::setMinor
   * @covers SemanticTagTask::getMinor
   */
  public function testMinor() {
    $set = 1;
    $this->object->setMinor($set);
    $get = $this->object->getMinor();
    $this->assertEquals($set, $get, 'Minor numbers can be set and returned.');
  }

  /**
   * @covers SemanticTagTask::setPatch
   * @covers SemanticTagTask::getPatch
   */
  public function testPatch() {
    $set = 1;
    $this->object->setPatch($set);
    $get = $this->object->getPatch();
    $this->assertEquals($set, $get, 'Patch numbers can be set and returned.');
  }

  /**
   * @covers SemanticTagTask::setLogOutput
   * @covers SemanticTagTask::getLogOutput
   */
  public function testLogOutput() {
    $set = TRUE;
    $this->object->setLogOutput($set);
    $get = $this->object->getLogOutput();
    $this->assertEquals($set, $get, 'Log can be set and returned.');
  }

  /**
   * @covers SemanticTagTask::setPushTag
   * @covers SemanticTagTask::getPushTag
   */
  public function testPushTag() {
    $set = 1;
    $this->object->setPushTag($set);
    $get = $this->object->getPushTag();
    $this->assertEquals($set, $get, 'Push tags can be set and returned.');
  }

}
