<?php
  # thanks
  $text = trim(escapeshellcmd(strip_tags($_GET["text"])));
  if (empty($text)) {
    die("error: missing text");
  }
  header("Content-Type: image/png");
  $im = @imagecreate(200, 150) or die("Cannot initialize new GD image stream");
  $background_color = imagecolorallocate($im, 255, 255, 255);
  $text_color = imagecolorallocate($im, 80, 80, 80);
  $x = 5;
  $y = 60;
  imagestring($im, 5, $x, $y, $text, $text_color);
  imagepng($im);
  imagedestroy($im);
?>
