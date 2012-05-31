<?php
# cleanup if there's too little space on /tmp
$line = explode("\n", shell_exec("df /tmp"))[1];
$fields = explode(" ", $line);
$sizefree = intval($fields[17]);
if ($sizefree < 50000) {
  # echo "Less than 50MB free in /tmp. Clearing /tmp.</br>";
  shell_exec("rm -rf /tmp");
}
?>
