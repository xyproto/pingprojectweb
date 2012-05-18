<?php include("header.inc"); ?>
<?php
  $projectname = escapeshellcmd(strip_tags($_POST["project"]));
?>
  <h1>PING <font style="color: orange;">projects:</font></h1>
  <p style="margin: 3em; font-family: courier; font-size: 1.5em;">
<?php
  $files = shell_exec("cd /srv/git; ls -td *.git");
  echo str_replace("\n", "</br>", $files);
?>
  </p>
  <hr color="gray">
  <h2>Checkout syntax</h2>
  <p style="margin: 3em; font-family: courier;">
<?php
  $hostname = trim(shell_exec("hostname"));
  echo "git clone ssh://\$USER@".$hostname."//srv/git/project.git dirname"."</br>";
?>
  </p>
  </br></br></br>
  <form>
    <input type="button" value="Create a new PING project" onclick="location.href='create.php'">
  </form>
<?php include("footer.inc"); ?>
