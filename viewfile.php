<?php include("header.inc"); ?>
<?php include("prettybody.inc"); ?>
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

  # cleanup if there's too little space on /tmp
  $line = explode("\n", shell_exec("df /tmp"))[1];
  $fields = explode(" ", $line);
  $sizefree = intval($fields[17]);
  if ($sizefree < 50000) {
    # echo "Less than 50MB free in /tmp. Clearing /tmp.</br>";
    shell_exec("rm -rf /tmp");
  }
  # check out the project and list the files
  $p = "/srv/git/".$gitname;
  shell_exec("git clone ".$p." /tmp/".$gitname);
  if (endsWith($filename_full, ".png")) {
    # TODO
    echo "PNG viewing is to be implemented.</br>";
    echo "<img src=\"/viewimage.php?gitname=".$gitname."&indir=".$indirname."&filename=".$filename."\"></br>";
  } else {
    echo "<div style=\"border-radius: 15px; padding:1em; margin-left:3em; margin-right:5em; opacity:1.0; background:black;\">";
    echo "<pre class=\"prettyprint\">";
    #echo trim(strip_tags(shell_exec("cat ".$filename_full)))."</br>";
    $ff = fopen($filename_full, "r");
    print_r(fread($ff, filesize($filename_full)));
    fclose($ff);
    echo "</pre>";
    echo "</div>";
  }
  echo "</br>";
?>
  </p>
  <hr color="#303030">
  <a style="text-decoration:none; color:#d0d0d0;" href="/view.php?gitname=<?php echo $gitname; ?>">Go back</a>
<?php include("footer.inc"); ?>
