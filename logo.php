<?php

  $text = trim(escapeshellcmd(strip_tags($_GET["text"])));
  if (empty($text)) {
    die("error: missing text");
  }
  $text2 = trim(escapeshellcmd(strip_tags($_GET["text2"])));
  if (empty($text2)) {
    # Empty text2 is okay
    $text2 = "";
  }
  header("Content-Type: image/png");
  $im = imagecreate(200, 150) or die("Cannot initialize new GD image stream");
  $background_color = imagecolorallocate($im, 255, 255, 255);
  $text_color = imagecolorallocate($im, 80, 80, 80);
  $x = 5;
  $y = 60;
  imagestring($im, 5, $x, $y, $text, $text_color);
  imagestring($im, 5, $x+5, $y+20, $text2, $text_color);
  imagepng($im);
  imagedestroy($im);
?>
