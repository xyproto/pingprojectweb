<?php include("header.inc"); ?>
<?php include("body.inc"); ?>
<?php
  $projectname = trim(escapeshellcmd(strip_tags($_POST["project"])));
  if (empty($projectname)) {
    die("error: missing project name");
  }
?>
  <h1>New project: <font style="color: orange;"><?php echo $projectname; ?></font></h1>
  <p style="margin: 3em; font-family: courier;">
    <label>name:</label>
<?php echo $projectname; ?>
</br>
</br>
<?php
  $p = "/srv/git/".$projectname.".git";
  echo "path: ".$p."</br>";
  shell_exec("mkdir ".$p);
  shell_exec("chmod 755 ".$p);
  echo shell_exec("cd ".$p."; git --bare init --shared")."</br>";
  shell_exec("git clone ".$p." /srv/git/_tmp")."</br>";
  shell_exec("touch /srv/git/_tmp/README")."</br>";
  shell_exec("echo A project named ".$projectname.". > /srv/git/_tmp/README")."</br>";
  shell_exec("cd /srv/git/_tmp; git add README")."</br>";
  shell_exec("cd /srv/git/_tmp; git commit -m \"OST\"")."</br>";
  shell_exec("cd /srv/git/_tmp; git push origin master")."</br>";
  shell_exec("rm -r /srv/git/_tmp")."</br>";
  $hostname = trim(shell_exec("hostname"));
  shell_exec("chmod -R a+rwX ".$p);
  echo "git clone ssh://\$USER@".$hostname."/".$p." ".$projectname."</br>";
?>
  </p>
  <hr color="gray">
  <a href="index.php">Go back</a>
<?php include("footer.inc"); ?>
