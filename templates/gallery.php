<h2>Gallery</h2>
<h3>Add new image to gallery</h3>

<div id="uploadImageMessageDiv"></div>

<form action="/api/save-image" method="post" enctype="multipart/form-data">
  <label for="imageFileInput" class="imageFileInputLabel">Select an image</label>
  <input type="file" required name="imageFileInput" id="imageFileInput">
  <span class="imageFileName"></span>
  <input type="submit" name="saveImage" id="saveImage" value="Save to Gallery">
</form>

<?php
  $imageDir = dirname(__DIR__) . "/public/img/gallery/";
?>

<h3>Images</h3>
<div class="galleryImages">
  <?php foreach (new DirectoryIterator($imageDir) as $file): ?>
      <?php
        $imageName = $file->getFilename();
      if($file->isDot() || $imageName === ".gitkeep") {
          continue;
      }
      ?>
      <div>
        <img src="<?= "/img/gallery/" . $imageName ?>" alt="<?= $imageName ?>" class="galleryImage">
        <button class="addImageToPostButton">Add to post</button>
      </div>
  <?php endforeach; ?>
</div>