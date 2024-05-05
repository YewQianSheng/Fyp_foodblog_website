<?php include "validate_login.php"; ?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Create post</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(90deg, rgba(169, 169, 169, 1) 0%, rgba(0, 0, 0, 1) 100%);

        }

        #image-preview {
            max-width: 100%;
            max-height: 200px;
            margin-top: 10px;
        }

        .color {

            padding-bottom: 3%;
        }

        #productForm {

            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 1);
            max-width: 90%;
            margin: auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .custom-upload-btn {
            display: inline-block;
            padding: 10px 15px;
            cursor: pointer;
            margin-left: 50px;
            background-color: #3498db;
            color: black;
            border-radius: 4px;
            background-color: white;
        }

        .custom-upload-btn:hover {
            background-color: #2980b9;
        }

        #imageInput {
            display: none;
        }

        #fileName {
            margin-top: 10px;
            font-style: italic;
        }

        #imagePreview img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
        }

        .form-control {
            background-color: whitesmoke;
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 5px;
        }

        .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 5px;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: white;
            color: whitesmoke;
        }

        .footerrow {
            padding: 2%;
            display: flex;
            text-align: center;

            color: white;
        }

        .column2 {
            width: 30%;
        }

        .column3 {
            width: 20%;
            display: grid;
        }

        .column3 a {
            color: white;
            text-decoration: none;
        }

        .column3 a:hover {
            color: white;
        }

        div.sticky {
            position: -webkit-sticky;
            position: sticky;
            z-index: 100;
            top: 0;
            background-color: white;
        }

        @media screen and (max-width: 700px) {


            .footerrow {
                flex-direction: column;
            }

            .column2 {
                margin-bottom: 2%;
                width: 100%;
            }

            .column3 {
                margin-bottom: 2%;
                width: 100%;
            }

        }
    </style>
</head>

<body>
    <!-- container -->

    <div class="container-fluid"> <?php
                                    include 'nav/navbar.php';
                                    ?>
        <div style="height: 70px;"></div>
        <!-- <div class="page-header">
            <h1>Create Post</h1>
        </div> -->
        <!-- HTML form to create product will be here -->
        <!-- PHP insert code will be here -->
        <?php
        date_default_timezone_set('asia/Kuala_Lumpur');
        // Include database connection
        include 'config/database.php';
        if ($_POST) {
            try {
                $name = $_POST['name'];
                $description = $_POST['description'];
                $categorys_id = $_POST['categorys_id'];
                $image = !empty($_FILES["image"]["name"])
                    ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
                    : "";
                $image = htmlspecialchars(strip_tags($image));
                $target_file = '';
                $errors = array();

                $author_id = isset($_SESSION['id']) ? $_SESSION['id'] : '';
                $author = ''; // Initialize variable to store the username

                // Retrieve the username from the database based on the user ID
                $query_username = "SELECT user_name FROM user WHERE id = :author_id LIMIT 1";
                $stmt_username = $con->prepare($query_username);
                $stmt_username->bindParam(':author_id', $author_id);
                $stmt_username->execute();
                $row_username = $stmt_username->fetch(PDO::FETCH_ASSOC);
                if ($row_username) {
                    $author = $row_username['user_name'];
                }

                // Now, if the image is not empty, try to upload the image
                if ($image) {
                    // Upload file to folder
                    $target_directory = "uploads/";
                    $target_file = $target_directory . $image;
                    // Get file type
                    $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
                    $check = getimagesize($_FILES["image"]["tmp_name"]);
                    // Make sure submitted file is not too large, can't be larger than 1 MB
                    if ($_FILES['image']['size'] > (524288)) {
                        $errors[] = "Image must be less than 512 KB in size.";
                    }
                    if ($check == false) {
                        // Make sure that the file is a real image
                        $errors[] = "Submitted file is not an image.";
                    }
                    // Make sure certain file types are allowed
                    $allowed_file_types = array("jpg", "jpeg", "png", "gif", "webp");
                    if (!in_array($file_type, $allowed_file_types)) {
                        $errors[] = "Only JPG, JPEG, PNG,WEBP GIF files are allowed.";
                    }
                    // Make sure the file does not exist
                    if (file_exists($target_file)) {
                        $errors[] = "Image already exists. Try to change file name.";
                    }
                }
                if (empty($name)) {
                    $errors[] = 'Product name is required.';
                }

                if (empty($description)) {
                    $errors[] = 'Description is required.';
                }

                if (empty($image) && empty($_FILES["image"]["name"])) {
                    $errors[] = 'Image is required.';
                }

                if (!empty($errors)) {
                    echo "<div class='alert alert-danger'>";
                    foreach ($errors as $displayError) {
                        echo $displayError . "<br>";
                    }
                    echo "</div>";
                } else {
                    // Insert query
                    $query = "INSERT INTO post SET name=:name, description=:description, image=:image, created=:created,categorys_id=:categorys_id,author=:author,author_id=:author_id ";
                    // Prepare query for execution
                    $stmt = $con->prepare($query);
                    // Bind the parameters
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':image', $target_file);
                    $created = date('Y-m-d H:i:s'); // Get the current date and time
                    $stmt->bindParam(':created', $created);
                    $stmt->bindParam(':author_id', $author_id);
                    $stmt->bindParam(':categorys_id', $categorys_id);
                    $stmt->bindParam(':author', $author);

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success mt-4 mb-1'>Record was saved.</div>";
                        // Make sure the 'uploads' folder exists
                        // If not, create it
                        if ($image) {
                            // Make sure the 'uploads' folder exists
                            // If not, create it
                            if (!is_dir($target_directory)) {
                                mkdir($target_directory, 0777, true);
                            }
                            // If $file_upload_error_messages is still empty
                            if (empty($file_upload_error_messages)) {
                                // It means there are no errors, so try to upload the file
                                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                    // It means the photo was uploaded
                                } else {
                                    echo "<div class='alert alert-danger'>";
                                    echo "<div>Unable to upload photo.</div>";
                                    echo "<div>Update the record to upload a photo.</div>";
                                    echo "</div>";
                                }
                            }

                            // If $file_upload_error_messages is NOT empty
                            else {
                                // It means there are some errors, so show them to the user
                                echo "<div class='alert alert-danger'>";
                                echo "<div>{$file_upload_error_messages}</div>";
                                echo "<div>Update the record to upload a photo.</div>";
                                echo "</div>";
                            }
                        }
                        $_POST = array();
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save the record.</div>";
                    }
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <!-- HTML form here where the product information will be entered -->
        <div class="color pt-5">
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data" id="productForm">


                <div class="form-group">
                    <label for="imageInput">Photo :</label>
                    <label for="imageInput" class="custom-upload-btn">Select Image </label>
                    <input type="file" name="image" accept="image/*" id="imageInput" onchange="previewImage()" />
                    <div class="file-name" id="fileName"></div>
                    <div id="imagePreview"></div>
                </div>


                <div class="form-group">

                    <input type='text' name='name' class='form-control' placeholder="Title" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" />
                </div>

                <div class="form-group">
                    <label for="categorys_id">Type :</label>
                    <select class="form-select" name="categorys_id">
                        <?php
                        // Fetch categories from the database
                        $query = "SELECT type FROM type";
                        $stmt = $con->prepare($query);
                        $stmt->execute();
                        $categorys_id = $stmt->fetchAll(PDO::FETCH_COLUMN);

                        // Generate select options
                        foreach ($categorys_id as $categorys_id) {
                            echo "<option value='$categorys_id'>$categorys_id</option>";
                        }
                        ?></select>
                </div>
                <div class="form-group">
                    <label for="description">Description :</label>
                    <textarea class="form-control" placeholder="Add Text" name="description" id="floatingTextarea"><?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <input type='submit' value='Save' class='btn btn-light' />
                </div>
        </div>
        </form>

        <!-- end .container -->
        <script>
            function previewImage() {
                var input = document.getElementById('imageInput');
                var preview = document.getElementById('imagePreview');

                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        preview.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail" width="500" alt="Uploaded Image">';
                    };

                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.innerHTML = '';
                }
            }
        </script>
        <div class="border-bottom mt-3 border-1 border-white"></div>
        <div class="footerrow">
            <div class="column2">
                <img src="img/fblogo.png" alt="" width="180">
            </div>
            <div class="column3">
                <h4><strong>MENU</strong></h4>
                <a href="index.php">Home</a>
                <a href="readpost.php">Blog</a>
                <a href="createpost.php">Add Post</a>
                <a href="contact.php">Contact</a>

            </div>
            <div class="column3">
                <h4><strong>CATEGORIES</strong></h4>
                <a href="recipe.php">Recipe</a>
                <a href="vegetarian.php">Vegetarian</a>
                <a href="gourmet.php">Gourmet Food</a>

            </div>
            <div class="column2">
                <h4><strong>SOCIAL</strong></h4>
                <a href="https://www.facebook.com" target="new">
                    <img src="img/fb.png" alt="" width="30"></a>
                <a href="https://www.instagram.com" target="new">
                    <img src="img/ig.png" alt="" width="30"></a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"></script>
</body>

</html>