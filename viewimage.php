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

  # cleanup if there's too little space on /tmp
  $line = explode("\n", shell_exec("df /tmp"))[1];
  $fields = explode(" ", $line);
  $sizefree = intval($fields[17]);
  if ($sizefree < 50000) {
    # echo "Less than 50MB free in /tmp. Clearing /tmp.</br>";
    shell_exec("rm -rf /tmp");
  }
  # check out the project and output the image
  $p = "/srv/git/".$gitname;
  shell_exec("git clone ".$p." /tmp/".$gitname);

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
