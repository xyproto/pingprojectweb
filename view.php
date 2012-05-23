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
  // Prevent .. in "indirname"
  $indirname = str_replace("..", "OST", $indirname);
?>
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

# Thank you 
# http://php.net/manual/en/function.utf8-encode.php
function fixEncoding($in_str)
{
  $cur_encoding = mb_detect_encoding($in_str) ;
  if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8"))
    return $in_str;
  else
    return utf8_encode($in_str);
} // fixEncoding 


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
  sort($files);
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
      $output = shell_exec("cd /tmp/".$gitname."/".$indirname."; git log -n1 --date=iso --pretty=format:\"%an %ci%n\"".$filename);

      $fields = explode(" ", fixEncoding($output), -1);
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
  <a style="color:white;" href="/">Go back</a>
<?php include("footer.inc"); ?>
