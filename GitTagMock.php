<?php

require_once "GitTagInterface.php";

/**
 * Mock tagging for GitTag.
 * @codeCoverageIgnore
 *
 * @author Nick Schuch nick at previousnext dot com dot au
 */
class GitTagMock implements GitTagInterface {
  
  /**
   * The list of tags.
   * @var array
   */
  private $tag = '';

  /**
   * Get tags from Git using 'tag -l' command.
   */
  public function getLatestTag() {
    return $this->tag;
  }

  /**
   * Sets the tag in the repository.
   */
  public function setTag($tag, $comment) {
    $this->tag = $tag;
    return 'Tagged the repository with version ' . $tag;
  }

}
