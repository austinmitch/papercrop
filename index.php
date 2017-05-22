<?php
  require('papercrop.php');
 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>PaperCrop</title>
    <link rel="stylesheet" href="css/main.css">
  </head>
  <body>
    <section class="content">
      <div class="uploadForm">
        <form enctype="multipart/form-data" action="index.php" method="post">
          <label class="customUpload" for="wallpaper">Choose Files
            <input type="file" multiple="multiple" name="wallpaper[]" id="wallpaper">
          </label>
          <label for="prefix">File Name Prefix</label>
          <input type="text" name="prefix" id="prefix">
          <input type="submit" name="submit" value="Resize" class="submit">
        </form>
      </div>

      <div class="downloadLink">
        <?php print($download) ?>
      </div>
    </section>
  </body>
</html>
