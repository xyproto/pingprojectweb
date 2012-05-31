<?php
  include 'header.inc';
  include 'body.inc';
  include("logosearchmenu.inc");

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
  <div id="content">
  <h3>Viewing project: <font id="orange"><?php echo $gitname; ?></font></h3>
  <hr color="#303030">
  <h3>To checkout</h3>
  <font id="code">
<?php
  $hostname = trim(shell_exec("hostname"));
  $dirname = explode(".", $gitname, -1)[0];
  echo "git clone ssh://\$USER@".$hostname."//srv/git/".$gitname." ".$dirname."</br>";
?>
  </font>
  <hr color="#303030">
  <h3>Files</h3>
  <p id="filelist">
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

  include 'tmpcleanup.inc';

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
      echo "<a id=dirname href=\"view.php?gitname=".$gitname."&indirname=".$indirname."/".$f."\"><img src=\"img/buuf_dir.png\" id=icon>$f</a></br>";
    } else {
      $filename = str_replace("/./", "/", $filename);
      echo "<a id=filename href=\"viewfile.php?gitname=".$gitname."&indirname=".$indirname."&filename=".$f."\"><img src=\"img/buuf_file.png\" id=icon>$f</a>";
      echo "</br>";
      $output = shell_exec("cd /tmp/".$gitname."/".$indirname."; git log -n1 --date=iso --pretty=format:\"%an, %ci%n\"".$filename);

      $fields = explode(" ", fixEncoding($output), -1);
      $info = implode(" ", $fields);
      echo "<font id=filedesc>";
      if (empty($info)) {
        echo "no log info"."</br>";
      } else {
        echo $info;
      }
      echo ", ".filesize($filename)." bytes";
      echo "</font></br>";
    }
    echo "</br>";
  }
?>
  </p>
  </div>

<?php
include 'backlink.inc';
include 'footer.inc';
?>
