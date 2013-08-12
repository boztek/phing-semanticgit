<?php

require_once 'phing/Task.php';

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
class GitSemanticTagTask extends Task {

  /**
   * The path to the git executable.
   * @var string
   */
  private $git = "git";

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
	 * Main entry point.
	 */
	public function main() {
	  // Get the current version so we can split it out and generate an increment.
    $tags = $this->getTags();

	  // Tag the repository.
    if (!empty($tags)) {
      $this->explodeTags($tags);

      // Ensure we have a tag to increment. Otherwise lets initial commit for this major.
      if (!empty($tags[$this->major][$this->minor])) {
        $this->patch = $tags[$this->major][$this->minor];
        $this->patch++;
        $tag = $this->buildTag();
        $this->setTag($tag, 'Incremental semantic commit.');
      }
      else {
        $tag = $this->buildTag();
        $this->setTag($tag, 'Initial commit');
      }
    }
    else {
      $tag = $this->buildTag();
      $this->setTag($tag, 'Initial commit');
    }
	}

  /**
   * Utility.
   */

  /**
   * Sets the tag in the repository.
   */
  private function setTag($tag, $comment) {
    $command = $this->git . ' tag -a ' . $tag . ' -m "' . $comment . '"';
    exec($command, $return);
    print('Tagged the repository with version ' . $tag);
  }

  /**
   * Get tags from Git using 'tag -l' command.
   */
  private function getTags() {
    $logs = array();
    exec('git show-ref --tags', $logs);
    array_unshift($logs, '');
    reset($logs);
    $tags = $this->parseTags($logs);
    return $tags;
  }

  /**
   * Parse the tag list output from Git.
   */
  private function parseTags(&$logs) {
    $tags = array();
    while (($line = next($logs)) !== FALSE) {
      $tags[] = $branches[] = substr(trim($line), 51);
    }
    return $tags;
  }

  /**
   * Explodes the tags out into an array structure.
   */
  private function explodeTags(&$tags) {
    $new = array();
    foreach ($tags as $tag) {
      $split = explode ('.' , $tag);

      // Ensure we only take numeric items.
      if (is_numeric($split[0]) && is_numeric($split[1]) && is_numeric($split[2])) {
        $major = $split[0];
        $minor = $split[1];
        $patch = $split[2];
        $new[$major][$minor] = $patch;
      }
    }
    $tags = $new;
  }

  /**
   * Getters and setters.
   */

  public function buildTag() {
    return $this->major . '.' . $this->minor . '.' . $this->patch;
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

}
