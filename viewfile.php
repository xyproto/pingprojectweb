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
?>
  <h1>TO BE IMPLEMENTED!</h1>
<!--
  <h1>Viewing project: <font style="color: orange;"><?php echo $gitname; ?></font></h1>
  <h2>To checkout</h2>
  <p style="margin-left: 3em; font-family: courier;">
<?php
  $hostname = trim(shell_exec("hostname"));
  $dirname = explode(".", $gitname, -1)[0];
  echo "git clone ssh://\$USER@".$hostname."//srv/git/".$gitname." ".$dirname."</br>";
?>
  </p>
  <h2>Files</h2>
  <p style="margin-left: 3em; font-family: courier;">
<?php
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
  $files = explode("\n", shell_exec("ls -t /tmp/".$gitname."/".$indirname));
  foreach ($files as $f) {
    if (empty($f)) {
      continue;
    }
    $filename = "/tmp/".$gitname."/".$indirname."/".$f;
    if (is_dir($filename)) {
      echo "&lt;DIR&gt;&nbsp;<a href=\"/view.php?gitname=".$gitname."&indirname=".$indirname."/".$f."\">$f</a></br>";
    } else {
      $filename = str_replace("/./", "/", $filename);
      echo "&lt;FILE&gt;&nbsp;<a href=\"/viewfile.php?gitname=".$gitname."&filename=".$f."\">$f</a>";
      echo "</br>";
      $fields = explode(" ", shell_exec("cd /tmp/".$gitname."/".$indirname."; git log -n1 --date=iso --pretty=format:\"%an %ci%n\"".$filename), -1);
      $info = implode(" ", $fields);
      if (empty($info)) {
        echo "no log info"."</br>";
      } else {
        echo $info."</br>";
      }
    }
    echo "</br>";
  }
?>
  </p>
  <hr color="gray">
  <a href="/">Go back</a>
-->
<?php include("footer.inc"); ?>
