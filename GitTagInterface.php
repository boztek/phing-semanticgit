<?php

/**
 * Standard interface for GitTag.
 */
interface GitTagInterface {
  
  /**
   * Returns a the latest tag in an array.
   *
   * @return array
   *   A list of tags.
   */
  public function getLatestTag();

  /**
   * Commits the tag to the repository.
   *
   * @param string $tag
   *   The tag in which is being committed.
   *
   * @param string $comment
   *   The comment which is associated with the tag.
   *
   * @return string
   *   The string that will be committed to the repository.
   */
  public function setTag($tag, $comment);
  
}
