<?php
include 'header.inc';
include 'body.inc';
include 'logosearchmenu.inc';
?>
  <script language="javascript">
    document.getElementById("new").setAttribute("class", "current");
  </script>

<?php
  $projectname = trim(escapeshellcmd(strip_tags($_POST["project"])));
  if (empty($projectname)) {
    die("error: missing project name");
  }
?>
  <div id="content">
  <p>
  <?php
    $p = "/srv/git/".$projectname.".git";
    #echo "path: ".$p."</br>";
    shell_exec("mkdir ".$p);
    shell_exec("chmod 755 ".$p);
    shell_exec("cd ".$p."; git --bare init --shared")."</br>";
    shell_exec("git clone ".$p." /srv/git/_tmp")."</br>";
    shell_exec("touch /srv/git/_tmp/README")."</br>";
    shell_exec("echo A project named ".$projectname.". > /srv/git/_tmp/README")."</br>";
    shell_exec("cd /srv/git/_tmp; git add README")."</br>";
    shell_exec("cd /srv/git/_tmp; git commit -m \"OST\"")."</br>";
    shell_exec("cd /srv/git/_tmp; git push origin master")."</br>";
    shell_exec("rm -r /srv/git/_tmp")."</br>";
    $hostname = trim(shell_exec("hostname"));
    shell_exec("chmod -R a+rwX ".$p);
    echo "<h2><font id=\"orange\">".$projectname."</font> created</h2>";
    #echo "git clone ssh://\$USER@".$hostname."/".$p." ".$projectname."</br>";
    echo "<a href=\"view.php?gitname=".$projectname.".git\"><img src=\"img/buuf_file.png\"></br>View project</a></br>";
  ?>
    </p>
  </div>

<?php
include 'backlink.inc';
include 'footer.inc';
?>
