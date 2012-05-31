<?php
include('header.inc');
include('body.inc');
include("logosearchmenu.inc");

  $sort = trim(escapeshellcmd(strip_tags($_GET["sort"])));
  if (empty($sort)) {
		# default to sort by name
    $sort = "name";
  }
  $descending = trim(escapeshellcmd(strip_tags($_GET["descending"])));
  if (empty($descending)) {
		# default to ascending order
    $descending = "no";
	}
	if ($descending == "yes") {
		$next_descending = "no";
	} else {
		$next_descending = "yes";
	}

?>
  <script language="javascript">
    document.getElementById("list").setAttribute("class", "current");
  </script>
  <div id="content">
    <h2>Project list</h2>
    <table id=table>
      <tr>
			<th><a href="list.php?sort=name&descending=<?php echo $next_descending;?>">Repo name</a></th>
			<th><a href="list.php?sort=date&descending=<?php echo $next_descending;?>">Last modified on server</a></th>
			<th><a href="list.php?sort=size&descending=<?php echo $next_descending;?>">Size</a></th>
      </tr>
<?php
  $names = [];
  $dates = [];
  $sizes = [];

  # Get the repo names, last modified dates and sizes

  $files = explode("\n", shell_exec("cd /srv/git; ls -rtd *.git"));
  foreach ($files as $f) {
    if (empty($f)) {
      continue;
    }
		# Append $f to the array $names
    array_push($names, $f);
		# Make $f point to $last_modified in $dates[]
    $last_modified = shell_exec("stat /srv/git/".$f." --format=%y | cut -d. -f1");
    $dates[$f] = $last_modified;
		# Make $f point to $du_size in $sizes[]
    $du_size = shell_exec("du -bs /srv/git/".$f." | cut -d/ -f1");
    $sizes[$f] = $du_size;
  }

  # Sort the keys in the desired order

	$keys = [];
	if ($sort == "size") {
		# $sizes has name -> size
		asort($sizes, SORT_NUMERIC);
		$keys = array_keys($sizes);
	} else if ($sort == "date") {
		# $dates has name -> date
		asort($dates);
		$keys = array_keys($dates);
	} else if ($sort == "name") {
		# $names i different, with N -> name
		$keys = array_values($names);
		natcasesort($keys);
	}
	if ($descending == "yes") {
		$keys = array_reverse($keys);
	}

  # Output the data as tables

	$oddeven = "odd";
	foreach ($keys as $key) {
    $f = $key;
		$last_modified = $dates[$key];
		$du_size = $sizes[$key];

		if ($oddeven == "odd") {
			$oddeven = "even";
		} else {
			$oddeven = "odd";
		}
		echo "<tr>";

		# Repo name and link
    echo "<td id=".$oddeven."><a id=blacklink href=\"view.php?gitname=".$f."\">".$f."</a></td>";

    # Last modified on server
    echo "<td id=".$oddeven." align=right>".$last_modified."</td>";

    # Size
    echo "<td id=".$oddeven." align=right>".round($du_size/1000000, 1)." MB</td>";

    echo "</tr>";
  }
?>
    <tr id=endrow><td id=endrow></td></tr>
    </table>
  </div>
<?php
include 'backlink.inc';
include 'footer.inc';
?>
