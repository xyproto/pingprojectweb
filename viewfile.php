<?php include("header.inc"); ?>
<?php
  $gitname = trim(escapeshellcmd(strip_tags($_GET["gitname"])));
  if (empty($gitname)) {
    die("error: missing git name");
  }
  $indirname = trim(escapeshellcmd(strip_tags($_GET["indirname"])));
  if (empty($indirname)) {
    $indirname = ".";
  }
  $filename = trim(escapeshellcmd(strip_tags($_GET["filename"])));
  if (empty($filename)) {
    die("error: missing filename");
  }
  $filename_full = str_replace("/./", "/", "/tmp/".$gitname."/".$indirname."/".$filename);
?>
  <h1>Viewing File: <font style="color: orange;"><?php echo $filename_full; ?></font></h1>
  <h2>Files</h2>
  <p style="margin-left: 3em; font-family: courier;">
<?php

# thanks stackoverflow
function startsWith($haystack, $needle)
{
  $length = strlen($needle);
  return (substr($haystack, 0, $length) === $needle);
}

# thanks stackoverflow
function endsWith($haystack, $needle)
{
  $length = strlen($needle);
  if ($length == 0) {
      return true;
  }

  $start  = $length * -1; //negative
  return (substr($haystack, $start) === $needle);
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
  #echo "path: ".$p."</br>";
  shell_exec("git clone ".$p." /tmp/".$gitname);
  #echo $filename_full."</br>";
  if endsWith($filename_full, ".png") {
    echo passthru("cat ".$filename_full);
  } else {
    echo trim(escapeshellcmd(strip_tags(shell_exec("cat ".$filename_full))))."</br>";
  }
  echo "</br>";
?>
  </p>
  <hr color="gray">
  <a href="/view.php?gitname=<?php echo $gitname; ?>">Go back</a>
<?php include("footer.inc"); ?>
