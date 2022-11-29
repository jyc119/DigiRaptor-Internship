<?php
error_reporting(0);

$msg = "";

// If upload button is clicked ...
if (isset($_POST['upload']) && isset($_POST['staffid'])) {

  $filename = $_FILES["uploadfile"]["name"];
  $staffid = $_POST['staffid'];
  $tempname = $_FILES["uploadfile"]["tmp_name"];
  $folder = "./images/" . $filename;

  $db = mysqli_connect('localhost', 'jordan', 'test1234', 'jordandb');

  // Get all the submitted data from the form
  $sql = "UPDATE staff SET imgfile = '".$_FILES['uploadfile']['name']."' WHERE id = '" . $_POST['staffid'] . "' ";

  // Execute query
  mysqli_query($db, $sql);

  // Now let's move the uploaded image into the folder: image
  if (move_uploaded_file($tempname, $folder)) {
    echo "<h3>  Image uploaded successfully!</h3>";
  } else {
    echo "<h3>  Failed to upload image!</h3>";
  }
}else{
  echo "error";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Image Upload</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
    <div id="content">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
              <input class="form-control" type="file" name="uploadfile" value="" />
            </div>
            <div class="form-group">
              <label for="usrid">ID:</label>
              <input type="text" class="form-control" name="staffid" id="usrid">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit" name="upload">UPLOAD</button>
            </div>
        </form>
    </div>
    <div id="display-image">
        <?php
        $query = " select * from staff ";
        $result = mysqli_query($db, $query);

        while ($data = mysqli_fetch_assoc($result)) {
        ?>
          <img src="./images/<?php echo $data['imgfile']; ?>">

        <?php
        }
        ?>
    </div>
</body>

</html>