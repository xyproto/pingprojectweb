<?php
  $text = trim(escapeshellcmd(strip_tags($_GET["text"])));
  if (empty($text)) {
    die("error: missing text");
  }
  header("Content-Type: image/png");
  $im = @imagecreate(110, 20) or die("Cannot initialize new GD image stream");
  $background_color = imagecolorallocate($im, 0, 0, 0);
  $text_color = imagecolorallocate($im, 233, 14, 91);
  imagestring($im, 1, 5, 5, $text, $text_color);
  imagepng($im);
  imagedestroy($im);
?>
