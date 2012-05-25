<html><body>
<?php
  $files = explode("\n", shell_exec("cd /srv/git; ls -rtd *.git"));
  foreach ($files as $f) {
    if (empty($f)) {
      continue;
    }
    echo "Converting ".$f."...";
    echo shell_exec("convert -size 320x100 xc:lightblue -font Courier -pointsize 72 -fill blue -draw \"text 25,65 'Anthony'\" img/".$f.".png")."</br>";
  }
?>
</body></html>
