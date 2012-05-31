<?php
include 'header.inc';
include 'body.inc';
include 'logosearchmenu.inc';
?>
  <script language="javascript">
    document.getElementById("home").setAttribute("class", "current");
  </script>

  <p>

  <div class="ContentFlow">
    <div class="loadIndicator"><div class="indicator"></div></div>
    <div class="flow">

<?php
  $files = explode("\n", shell_exec("cd /srv/git; ls -rtd *.git"));
  foreach ($files as $f) {
    if (empty($f)) {
      continue;
    }
    $last_modified = shell_exec("stat /srv/git/".$f." --format=%z | cut -d\" \" -f1");
    echo "<img class=\"item\" href=\"view.php?gitname=".$f."\" src=\"logo.php?gitname=".$f."&text=".$f."&text2=".$last_modified."\" title=\"".$f."\"/>";
  }
?>
    </div>
    <div class="globalCaption"></div>
    <div class="scrollbar"><div class="slider"><div class="position"></div></div></div>
  </div>
  </p>

<?php include 'footer.inc'; ?>
