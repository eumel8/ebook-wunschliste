<?php

class AuthorKeys {
function author_save($keyword = false, $session = false) {

  $db = new SQLite3('author.db') or die('Unable to open database');
  $query = <<<EOD
CREATE TABLE IF NOT EXISTS authors (
  author STRING PRIMARY KEY,
  session STRING )
EOD;
  $db->exec($query) or die('Create db failed');

  $count = $db->querySingle("SELECT count(*) as count FROM authors WHERE author='$keyword'");
  if ($count < 1) {
    $query = <<<EOD
INSERT INTO authors VALUES ( '$keyword','$session' )
EOD;
    $db->exec($query) or die("Unable to add author $keyword");
  }

return;
}

function author_get ($session = false) {

  $db = new SQLite3('author.db') or die('Unable to open database');
  $query = <<<EOD
CREATE TABLE IF NOT EXISTS authors (
  author STRING PRIMARY KEY,
  session STRING )
EOD;
  $db->exec($query) or die('Create db failed');

  $result = $db->query("SELECT author FROM authors where session='$session'");
  while ($row = $result->fetchArray())
  {
     $return[] =  $row['author'];
  }

  return $return;

}


}

?>
