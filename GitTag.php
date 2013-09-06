<?php

require_once "GitTagInterface.php";

/**
 * Handles getting and setting of git tags.
 * @codeCoverageIgnore
 *
 * @author Nick Schuch nick at previousnext dot com dot au
 */
class GitTag implements GitTagInterface {
  
  /**
   * The path to the git executable.
   * @var string
   */
  private $git = "git";

  /**
   * To push the tag automatically.
   * @var boolean
   */
  private $push = FALSE;

  /**
   * @param string $git
   *   The path to the git executable.
   * @param boolean $push
   *   Determine whether to push the tag or not.
   */
  public function __construct($git = "git", $push = FALSE) {
    $this->git = $git;
    $this->push = $push;
  }

  /**
   * Get tags from Git using 'tag -l' command.
   */
  public function getLatestTag() {
    $logs = array();
    exec('git show-ref --tags', $logs);
    array_unshift($logs, '');
    reset($logs);
    $tags = $this->parseTags($logs);

    // Loop and order the tags correctly.
    natsort($tags);

    return array_pop($tags);
  }

  /**
   * Sets the tag in the repository.
   */
  public function setTag($tag, $comment) {
    $command = $this->git . ' tag -a ' . $tag . ' -m "' . $comment . '"';
    exec($command, $return);

    // Push tags if set.
    if ($this->pushTag) {
      $command = $this->git . ' push origin ' . $tag;
      exec($command, $return);
    }

    return 'Tagged the repository with version ' . $tag;
  }

  /**
   * Parse the tag list output from Git.
   */
  private function parseTags(&$logs) {
    $tags = array();
    while (($line = next($logs)) !== FALSE) {
      $tags[] = substr(trim($line), 51);
    }
    return $tags;
  }

  /**
   * Sort the tags into numbers.
   */

  /**
   * Getters and setters.
   */

  public function setGit($git) {
    $this->git = (string)$git;
  }

  public function getGit() {
    return $this->git;
  }

  public function setPush($push) {
    $this->push = (boolean)$push;
  }

  public function getPush() {
    return $this->push;
  }

}