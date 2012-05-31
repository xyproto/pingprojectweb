<?php
include 'header.inc';
include 'body.inc';
include 'logosearchmenu.inc';

$url = urldecode($_GET["url"]);
if (empty($url)) {
    die("error: missing url");
}
$gitname = trim(escapeshellcmd(strip_tags($_GET["gitname"])));
if (empty($gitname)) {
    die("error: missing git name");
}


?>
  <div id="content">
    <h2>Cover art complete</h2>
<?php
   # This worked:
   $filename = "/srv/git/".$gitname."/cover.png";
   shell_exec("wget -O ".$filename." ".$url);
   # This didn't work:
   #$contents = file_get_contents($url);
   #echo $filename."</br>";
   #$fh = fopen($filename, 'w');
   #fwrite($fh, $contents);
   #fclose($fh);
?>
    <img src="<?php echo $url; ?>"></br>
    <button onClick='document.location.href="index.php";'>See it live!</button>
  </div>

<?php
include 'backlink.inc';
include 'footer.inc';
?>
