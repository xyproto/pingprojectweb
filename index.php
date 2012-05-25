<?php include("header.inc"); ?>
<?php include("body.inc"); ?>
<?php
  $projectname = escapeshellcmd(strip_tags($_POST["project"]));
?>
  <h1 style="font-family: 'Russo One';">PING <font style="color: orange;">projects:</font></h1>
  <p style="margin-left: 3em; font-family: tahoma, arial, sans-serif; font-size: 1.5em;">
<?php

# Thanks
# http://www.anyexample.com/programming/php/php_convert_rgb_from_to_html_hex_color.xml
function rgb2html($r, $g=-1, $b=-1)
{
    if (is_array($r) && sizeof($r) == 3)
        list($r, $g, $b) = $r;

    $r = intval($r); $g = intval($g);
    $b = intval($b);

    $r = dechex($r<0?0:($r>255?255:$r));
    $g = dechex($g<0?0:($g>255?255:$g));
    $b = dechex($b<0?0:($b>255?255:$b));

    $color = (strlen($r) < 2?'0':'').$r;
    $color .= (strlen($g) < 2?'0':'').$g;
    $color .= (strlen($b) < 2?'0':'').$b;
    return '#'.$color;
}


  $files = explode("\n", shell_exec("cd /srv/git; ls -rtd *.git"));
  $yellow = 0;
  $color = "";
  foreach ($files as $f) {
    if (empty($f)) {
      continue;
    }
    $color = rgb2html(255, (255-$yellow), 0);
    echo "<a style=\"text-decoration:none; color:".$color."\" href=\"view.php?gitname=".$f."\">".$f."</a></br>";
    $yellow += (255 / sizeof($files));
    if ($yellow >= 255) {
      $yellow = 255;
    }
  }
?>
  </p>
  <hr color="#303030">
  <h2>Checkout syntax</h2>
  <p style="margin: 3em; font-family: courier;">
<?php
  $hostname = trim(shell_exec("hostname"));
  echo "git clone ssh://\$USER@".$hostname."//srv/git/project.git dirname"."</br>";
?>
  </p>
  </br></br></br>
  <form>
    <input type="button" value="Create a new PING project" onclick="location.href='create.php'">
  </form>
<?php include("footer.inc"); ?>
