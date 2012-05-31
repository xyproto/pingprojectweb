<?php
  include 'header.inc';
  include 'body.inc';
  include 'logosearchmenu.inc';

  $gitname = trim(escapeshellcmd(strip_tags($_GET["gitname"])));
  if (empty($gitname)) {
    die("error: missing git name");
  }
  if (strpos($gitname, ".") !== false) {
      $projectname = explode(".", $gitname)[0];
  } else {
      $projectname = $gitname;
  }
?>
  <div id="content">
  <h3>Draw a cover image for the <font id="orange"><?php echo $projectname; ?></font> project</h3>
  <hr color="#303030">
  <div id=drawbg>
  <script 
    src="http://zwibbler.com/component.js#width=700&height=400" 
    type="text/javascript"></script>
  </div>
  <script type="text/javascript">
  function saveToTemporaryFile()
  {
      zwibbler.saveToTemporaryFile(
	  "png",
	  function(url) {
	      document.location.href = "saveimage.php?gitname=<?php echo $gitname; ?>&url=" + encodeURIComponent(url);
      	  });
  }
  </script>
  </br>
  <button onClick="saveToTemporaryFile();">Save image</button>
  </div>

<?php
include 'backlink.inc';
include 'footer.inc';
?>
