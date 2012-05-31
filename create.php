<?php
include('header.inc');
include('body.inc');
include("logosearchmenu.inc");
?>
  <script language="javascript">
    document.getElementById("new").setAttribute("class", "current");
  </script>
  <div id="content">
    <h2>Create new project</h2>
    <form id="create" method="post" action="new.php">
     <label>Project name:&nbsp;</label>
     <input name="project" size=22 /></br></br>
     <button type=submit>Create</button>
    </form>
  </div>
<?php
include 'backlink.inc';
include 'footer.inc';
?>
