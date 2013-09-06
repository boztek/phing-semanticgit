<?php

require_once 'phing/Task.php';
require_once 'GitTag.php';

/**
 * Creates a new tag using sematic version in git.
 * For more information refer to http://semver.org.
 *
 * @todo
 *   - Use core phing git tasks instead of local executable.
 *   - Needs tests.
 *
 * @author Nick Schuch nick at previousnext dot com dot au
 */
class SemanticTagTask extends Task {

  /**
   * The path to the git executable.
   * @var string
   */
  private $git = "git";

  /**
   * Object to interact with git.
   * @var GitTagTask
   */
  private $gitTask;

  /**
   * The major version of the project.
   * @var string
   */
  private $major = 0;

  /**
   * The minor version of the project.
   * @var string
   */
  private $minor = 0;

  /**
   * The patch version of the project.
   * @var string
   */
  private $patch = 0;

  /**
   * The indicator to log to phing
   * @var boolean
   */
  private $logOutput = FALSE;

  /**
   * To push the tag automatically.
   * @var boolean
   */
  private $pushTag = FALSE;

  /**
   * Constructor.
   */
  public function __construct($gitTagTask = FALSE) {
    // We wrap this in an if statement to allow us to unit test the code.
    // This means that we can mock the gitTagTask object.
    if (empty($gitTagTask)) {
      $this->gitTask = new GitTag($this->git, $this->pushTag);
    }
    else {
      $this->gitTask = $gitTagTask;
    }
  }

  /**
   * Main entry point.
   */
  public function main() {
    // A Stub for the new tag.
    $new = '0.0.0';

    // Get the current version so we can split it out and generate an increment.
    $current = $this->gitTask->getLatestTag();

    // Tag the repository.
    if (!empty($current)) {
      // Break out the tag into Major, Minor and Patch.
      // Ensure we only take numeric items.
      $split = explode ('.' , $current);
      if (is_numeric($split[0]) && is_numeric($split[1]) && is_numeric($split[2])) {
        $exploded['major'] = $split[0];
        $exploded['minor'] = $split[1];
        $exploded['patch'] = $split[2];
      }

      // Do we need an initial tag for this Major?
      // Do we need an initial patch for this Minor?
      if (($exploded['major'] != $this->major) ||
          ($exploded['minor'] != $this->minor)) {
        $new = $this->buildTag($this->major, $this->minor, 0);
      }

      // This leads me to believe we just need to increment the current Patch.
      else {
        $exploded['patch']++;
        $new = $this->buildTag($this->major, $this->minor, $exploded['patch']);
      }
    }

    // Apply the patch.
    $return = $this->gitTask->setTag($new, 'Incremental semantic commit.');
    $log = $this->getLogOutput();
    if (!empty($log)) {
      $this->log($return);
    }
  }

  /**
   * Getters and setters.
   */

  public function buildTag($major, $minor, $patch) {
    return $major . '.' . $minor . '.' . $patch;
  }

  public function setGit($git) {
    $this->git = (string)$git;
  }

  public function getGit() {
    return $this->git;
  }

  public function setMajor($major) {
    $this->major = (int)$major;
  }

  public function getMajor() {
    return $this->major;
  }

  public function setMinor($minor) {
    $this->minor = (int)$minor;
  }

  public function getMinor() {
    return $this->minor;
  }

  public function setPatch($patch) {
    $this->patch = (int)$patch;
  }

  public function getPatch() {
    return $this->patch;
  }

  public function setLogOutput($logOutput) {
    $this->logOutput = (boolean)$logOutput;
  }

  public function getLogOutput() {
    return $this->logOutput;
  }

  public function setPushTag($push_tag) {
    $this->pushTag = (boolean)$push_tag;
  }

  public function getPushTag() {
    return $this->pushTag;
  }

}
