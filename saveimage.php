<?php
include 'header.inc';
include 'body.inc';
include 'logosearchmenu.inc';

$q = trim(escapeshellcmd(strip_tags($_GET["q"])));
if (empty($q)) {
    $q = "";
}

?>
  <div id="content">
    <h2>Search results</h2>
<?php
  if ($q == "") {
    echo "<h1>NONE</h1>";
  } else {
      echo "<div id=indent>";
      echo "<table id=table>";
      echo "<tr><th>Name</th></tr>";
      $files = explode("\n", shell_exec("cd /srv/git; ls -rtd *.git"));
      if (empty($files)) {
	  echo "<h1>NONE</h1>";
      }
      $oddeven = "even";
      foreach ($files as $f) {
	  if (empty($f)) {
	      continue;
	  }
	  if (false !== strpos($f, $q)) {
	      if ($oddeven == "odd") {
		  $oddeven = "even";
	      } else {
		  $oddeven = "odd";
	      }
	     echo "<tr><td id=".$oddeven."><a id=blacklink href=\"view.php?gitname=".$f."\">".$f."</a></td></tr>";
	  }
      }
  }
?>
    <tr id=endrow><td id=endrow></td></tr>
    </table>
    </div>
  </div>
<?php
include 'backlink.inc';
include 'footer.inc';
?>
