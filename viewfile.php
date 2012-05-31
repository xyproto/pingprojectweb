<?php
  include 'header.inc';
  include 'prettybody.inc';
?>
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
  $viewfilename_full = str_replace("/./", "/", $gitname."/".$indirname."/".$filename);
?>
  <h1>Viewing File: <font style="color: orange;"><?php echo $viewfilename_full; ?></font></h1>
  <p style="margin-left: 2em; font-family: courier;">
<?php

# thanks stackoverflow
function endsWith($haystack,$needle,$case=true)
{
  $expectedPosition = strlen($haystack) - strlen($needle);

  if($case)
      return strrpos($haystack, $needle, 0) === $expectedPosition;

  return strripos($haystack, $needle, 0) === $expectedPosition;
}

  include 'tmpcleanup.inc';

  # check out the project and list the files
  $p = "/srv/git/".$gitname;
  shell_exec("git clone ".$p." /tmp/".$gitname);
  if (endsWith($filename_full, ".png") || endsWith($filename_full, ".gif") ||endsWith($filename_full, ".jpg")) {
    # TODO
    echo "<img src=\"/viewimage.php?gitname=".$gitname."&indirname=".$indirname."&filename=".$filename."\"></br>";
  } else {
    echo "<div style=\"border-radius: 15px; padding:1em; margin-left:3em; margin-right:5em; opacity:1.0; background:black;\">";
    echo "<pre class=\"prettyprint\">";
    #echo trim(strip_tags(shell_exec("cat ".$filename_full)))."</br>";
    $ff = fopen($filename_full, "r");
    $output = fread($ff, filesize($filename_full));
    $output = str_replace("<", "&lt;", $output);
    $output = str_replace(">", "&gt;", $output);
    echo $output;
    fclose($ff);
    echo "</pre>";
    echo "</div>";
  }
  echo "</br>";
?>
  </p>

<?php
include 'backlink.inc';
include 'footer.inc';
?>
