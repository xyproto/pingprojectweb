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
  $gitname = trim(escapeshellcmd(strip_tags($_GET["gitname"])));
  if (empty($gitname)) {
    # Empty gitname is okay
    $gitname = "";
  }

  # Check if there's a cover image on the server
  $filename = "/srv/git/".$gitname."/cover.png";
  if (shell_exec("ls ".$filename)) {
      header("Content-Type: image/png");
      echo shell_exec("cat ".$filename);
  } else {
      header("Content-Type: image/png");
      $im = imagecreate(200, 150) or die("Cannot initialize new GD image stream");
      # Check if there's a cover image on the server
      $background_color = imagecolorallocate($im, 255, 128, 0);
      $text_color = imagecolorallocate($im, 20, 20, 20);
      $x = 5;
      $y = 60;
      imagestring($im, 5, $x, $y, $text, $text_color);
      imagestring($im, 5, $x+5, $y+20, $text2, $text_color);
      imagepng($im);
      imagedestroy($im);
  }
?>
