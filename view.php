<?php include("header.inc"); ?>
<?php include("body.inc"); ?>
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
  <p style="margin-left: 3em; font-family: courier; font-size: 1.2em;">
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

# Thank you
# http://camendesign.com/code/php_directory_sorting
function scandirSorted2($path) {
  //warning: `is_dir` will need you to change to the parent directory of what you are testing
  //see <uk3.php.net/manual/en/function.is-dir.php#70005> for details
  chdir ($path);
  
  //get a directory listing
  $dir = array_diff (scandir ('.'),
    //folders / files to ignore
    array ('.', '..', '.DS_Store', 'Thumbs.db', '.git', '.gitignore')
  );
  
  //sort folders first, then by type, then alphabetically
  usort ($dir, create_function ('$a,$b', '
  	return	is_dir ($a)
  		? (is_dir ($b) ? strnatcasecmp ($a, $b) : -1)
  		: (is_dir ($b) ? 1 : (
  			strcasecmp (pathinfo ($a, PATHINFO_EXTENSION), pathinfo ($b, PATHINFO_EXTENSION)) == 0
  			? strnatcasecmp ($a, $b)
  			: strcasecmp (pathinfo ($a, PATHINFO_EXTENSION), pathinfo ($b, PATHINFO_EXTENSION))
  		))
  	;
  '));

  return $dir;
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
  $files = scandirSorted2("/tmp/".$gitname."/".$indirname);
  foreach ($files as $f) {
    if (empty($f)) {
      continue;
    }
    $filename = "/tmp/".$gitname."/".$indirname."/".$f;
    if (is_dir($filename)) {
      echo "<a style=\"text-decoration: none; color:#aaaaff;\" href=\"/view.php?gitname=".$gitname."&indirname=".$indirname."/".$f."\"><img src=\"img/buuf_dir.png\" style=\"height:64px; width:auto; vertical-align:middle; margin-right: 8px;\">$f</a></br>";
    } else {
      $filename = str_replace("/./", "/", $filename);
      echo "<a style=\"text-decoration: none; color:#aaffaa\" href=\"/viewfile.php?gitname=".$gitname."&indirname=".$indirname."&filename=".$f."\"><img src=\"img/buuf_file.png\" style=\"height:64px; width:auto; vertical-align:middle; margin-right:8px;\">$f</a>";
      echo "</br>";
      $output = shell_exec("cd /tmp/".$gitname."/".$indirname."; git log -n1 --date=iso --pretty=format:\"%an %ci%n\"".$filename);

      $fields = explode(" ", fixEncoding($output), -1);
      $info = implode(" ", $fields);
      if (empty($info)) {
        echo "no log info"."</br>";
      } else {
        echo "<font style=\"color: gray; margin-left: 4em;\">".$info."</font></br>";
      }
    }
    echo "</br>";
  }
?>
  </p>
  <hr color="#303030">
  <a style="text-decoration:none; color:#d0d0d0;" href="/">Go back</a>
<?php include("footer.inc"); ?>
