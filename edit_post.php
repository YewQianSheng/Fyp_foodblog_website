<?php
session_start();

$logout = isset($_GET['logout']) ? $_GET['logout'] : "";
if ($logout == "true") {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <title>Edit Post</title>
    <style>
        body {
            font-family: "Lucida Console", "Courier New", monospace;
            background: linear-gradient(90deg, rgba(169, 169, 169, 1) 0%, rgba(0, 0, 0, 1) 100%);

        }

        .navbar-nav a {
            color: white;
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
    <div class="container-fluid p-0">
        <?php
        include 'nav/navbar.php';
        ?>

    </div>

    <div class="container">
        <div style="height: 130px;"></div>
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT id,author_id ,author, name, description, created, categorys_id, image, likes FROM post WHERE id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $author_username = $row['author'];
            $name = $row['name'];
            $description = $row['description'];
            $image = $row['image'];
            $created = $row['created'];
            $category = $row['categorys_id'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <?php
        // check if form was submitted
        if ($_POST) {
            try {
                if (isset($_POST['delete_image'])) {
                    $empty = "";
                    $delete_query = "UPDATE post
                    SET image=:image  WHERE id = :id";
                    $delete_stmt = $con->prepare($delete_query);
                    $delete_stmt->bindParam(":image", $empty);
                    $delete_stmt->bindParam(":id", $id);
                    $delete_stmt->execute();
                    unlink($image);
                    echo "<script>
                    window.location.href = 'read.php?id={$id}&action=record_updated';
                  </script>";
                } else {
                    // write update query
                    // in this case, it seemed like we have so many fields to pass and
                    // it is better to label them and not use question marks
                    $query = "UPDATE post
                SET name=:name, description=:description, categorys_id=:categorys_id, image=:image WHERE id = :id";
                    // prepare query for excecution
                    $stmt = $con->prepare($query);
                    // posted values
                    $name = htmlspecialchars(strip_tags($_POST['name']));
                    $description = htmlspecialchars(strip_tags($_POST['description']));
                    $category = htmlspecialchars(strip_tags($_POST['categorys_id']));
                    // new 'image' field
                    $image = !empty($_FILES["image"]["name"])
                        ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
                        : "";
                    $image = htmlspecialchars(strip_tags($image));
                    // upload to file to folder
                    $target_directory = "uploads/";
                    $target_file = $target_directory . $image;
                    //pathinfo找是不是.jpg,.png
                    $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
                    $errors = array();

                    // now, if image is not empty, try to upload the image
                    if ($image) {
                        $check = getimagesize($_FILES["image"]["tmp_name"]);
                        // make sure submitted file is not too large, can't be larger than 1 MB
                        if ($_FILES['image']['size'] > (524288)) {
                            $errors[] = "<div>Image must be less than 512 KB in size.</div>";
                        }
                        if ($check == false) {
                            // make sure that file is a real image
                            $errors[] = "Submitted file is not an image.";
                        }
                        // make sure certain file types are allowed
                        $allowed_file_types = array("jpg", "jpeg", "png", "gif");
                        if (!in_array($file_type, $allowed_file_types)) {
                            $errors[] = "Only JPG, JPEG, PNG, GIF files are allowed.";
                        }
                        // make sure file does not exist
                        if (file_exists($target_file)) {
                            $errors[] = "<div>Image already exists. Try to change file name.</div>";
                        }
                    }

                    if (empty($name)) {
                        $errors[] = 'Product name is required.';
                    }

                    if (empty($description)) {
                        $errors[] = 'Description is required.';
                    }

                    if (!empty($errors)) {
                        echo "<div class='alert alert-danger'>";
                        foreach ($errors as $displayError) {
                            echo $displayError . "<br>";
                        }
                        echo "</div>";
                    } else {

                        // bind the parameters
                        $stmt->bindParam(':id', $id);
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':categorys_id', $category);
                        if ($image == "") {
                            $stmt->bindParam(":image", $row['image']);
                        } else {
                            $stmt->bindParam(':image', $target_file);
                        }
                        // Execute the query
                        if ($stmt->execute()) {
                            echo "<script>
                            window.location.href = 'read.php?id={$id}&action=record_updated';
                          </script>";
                            // make sure the 'uploads' folder exists
                            // if not, create it
                            if ($image) {
                                if ($target_file != $row['image'] && $row['image'] != "") {
                                    unlink($row['image']);
                                }

                                // make sure the 'uploads' folder exists
                                // if not, create it
                                if (!is_dir($target_directory)) {
                                    mkdir($target_directory, 0777, true);
                                }
                                // if $file_upload_error_messages is still empty
                                if (empty($file_upload_error_messages)) {
                                    // it means there are no errors, so try to upload the file
                                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                        // it means photo was uploaded
                                    } else {
                                        echo "<div class='alert alert-danger'>";
                                        echo "<div>Unable to upload photo.</div>";
                                        echo "<div>Update the record to upload photo.</div>";
                                        echo "</div>";
                                    }
                                }

                                // if $file_upload_error_messages is NOT empty
                                else {
                                    // it means there are some errors, so show them to user
                                    echo "<div class='alert alert-danger'>";
                                    echo "<div>{$file_upload_error_messages}</div>";
                                    echo "<div>Update the record to upload photo.</div>";
                                    echo "</div>";
                                }
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                        }
                    }
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        } ?>



        <!-- HTML form to update record will be here -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea rows="5" name='description' class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></textarea></td>
                </tr>
                <tr>
                    <td>Categories</td>
                    <td><select class="form-select" name="categorys_id">
                            <?php
                            $query = "SELECT type FROM type";
                            $stmt = $con->prepare($query);
                            $stmt->execute();
                            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($categories as $category) {
                                $all_category = $category['type'];

                                $selected = ($all_category == $row['categorys_id']) ? "selected" : "";
                                echo "<option value='" . $all_category . "' $selected>" . htmlspecialchars($all_category) . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Photo</td>
                    <td>
                        <?php
                        if ($image != "") {
                            echo '<img src="' . htmlspecialchars($image, ENT_QUOTES) . '" width="100">';
                        } else {
                            echo '<img src="img/comingsoon.jpg" alt="image" width="100">';
                        }
                        ?>
                        <br>
                        <div id="image-preview"></div>
                        <input type="file" class="mt-2" name="image" class="form-control-file" accept="image/*" onchange="previewImage(this)">


                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <?php if ($image != "") { ?>
                            <input type="submit" value="Delete Image" class="btn btn-danger" name="delete_image">
                        <?php } ?>
                        <a href='readpost.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>
        <script>
            function previewImage(input) {
                var preview = document.getElementById('image-preview');
                preview.innerHTML = '';

                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var img = document.createElement("img");
                        img.src = e.target.result;
                        img.style.maxWidth = "100%";
                        img.style.maxHeight = "200px";
                        preview.appendChild(img);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"></script>
</body>

</html>