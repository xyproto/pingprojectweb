<?php
  $gitname = trim(escapeshellcmd(strip_tags($_GET["gitname"])));
  if (empty($gitname)) {
    die("error: missing git name");
  }
  $indirname = trim(escapeshellcmd(strip_tags($_GET["indirname"])));
  if (empty($indirname)) {
    $indirname = ".";
  }
  // Prevent .. in "indirname"
  $indirname = str_replace("..", "OST", $indirname);

  $filename = trim(escapeshellcmd(strip_tags($_GET["filename"])));
  if (empty($filename)) {
    die("error: missing filename");
  }
  $filename_full = str_replace("/./", "/", "/tmp/".$gitname."/".$indirname."/".$filename);

# thanks stackoverflow
function endsWith($haystack,$needle,$case=true)
{
  $expectedPosition = strlen($haystack) - strlen($needle);

  if($case)
      return strrpos($haystack, $needle, 0) === $expectedPosition;

  return strripos($haystack, $needle, 0) === $expectedPosition;
}

  include 'tmpcleanup.inc';

  # check out the project and output the image
  $p = "/srv/git/".$gitname;
  shell_exec("git clone ".$p." /tmp/".$gitname);
  shell_exec("cd /tmp/".$gitname."; git reset --hard HEAD; git pull");

  $fp = fopen($filename_full, 'rb');

  if (endsWith($filename_full, ".png")) {
    header("Content-type: image/png");
  }
  if (endsWith($filename_full, ".gif")) {
    header("Content-type: image/gif");
  }
  if (endsWith($filename_full, ".jpg")) {
    header("Content-type: image/jpg");
  }
  header("Content-length: ".filesize($filename_full));

  fpassthru($fp);

  fclose($fp);
?>
