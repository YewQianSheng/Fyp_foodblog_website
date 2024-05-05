<?php
session_start();

$logout = isset($_GET['logout']) ? $_GET['logout'] : "";
if ($logout == "true") {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
} ?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Gourmet Food</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>

<style>
    body {
        background: linear-gradient(90deg, rgba(169, 169, 169, 1) 0%, rgba(0, 0, 0, 1) 100%);
    }

    .card-img-top {
        height: 350px !important;
        /* Set the desired height for the images */
        object-fit: contain;
        /* This property ensures that the image covers the entire container */
    }

    .welcome-container {
        position: absolute;
        bottom: 50%;
        left: 50%;
        transform: translate(-50%, 50%);
        text-align: center;
        color: black;
        width: 80%;
        /* Adjust the width based on your design */
        max-width: 600px;
        /* Set a maximum width for larger screens */
        padding: 20px;
        box-sizing: border-box;
    }

    .welcome-heading {
        font-size: 2.5rem;
        margin-bottom: 0;
        background-color: white;
        /* Set your desired background color for the heading */
        padding: 10px;
        /* Adjust padding for the heading */
        border-radius: 8px;
    }

    @media screen and (max-width: 600px) {

        /* Adjust styles for smaller screens */
        .welcome-container {
            width: 90%;
        }

        .welcome-heading {
            font-size: medium;
        }
    }

    .card {
        transition: transform 0.1s ease-in-out;
        /* Add a transition for the transform property */
    }

    .card:hover {
        background-color: #f8f9fa;
        /* Change this color to your desired hover effect */
        transform: scale(1.05);
        /* Increase the scale on hover for a slight zoom effect */
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


<body>
    <!-- container -->
    <div class="container-fluid">
        <div class="color p-3">
            <?php
            include 'nav/navbar.php';
            ?>
            <div class="row" style="position: relative;">
                <img src="img/gf.jpg" alt="logo" style="width: 100%; height: auto;">

                <div class="welcome-container">
                    <h1 class="welcome-heading">Gourmet Food</h1>
                </div>
            </div>
            <div style="height: 50px;"></div>
            <!-- PHP code to read records will be here -->
            <?php
            // include database connection
            include 'config/database.php';
            $action = isset($_GET['action']) ? $_GET['action'] : "";

            // if it was redirected from delete.php
            if ($action == 'deleted') {
                echo "<div class='alert alert-success'>Record was deleted.</div>";
            }

            if ($action == 'failed') {
                echo "<div class='alert alert-danger'>The Product has been ordered.</div>";
            }
            // delete message prompt will be here
            $searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';

            $query = "SELECT * FROM post WHERE categorys_id = 'gourmet food'";
            if (!empty($searchKeyword)) {
                $query .= " WHERE name LIKE :keyword";
                $searchKeyword = "%{$searchKeyword}%";
            }
            $query .= " ORDER BY id DESC";
            $stmt = $con->prepare($query);
            if (!empty($searchKeyword)) {
                $stmt->bindParam(':keyword', $searchKeyword);
            }
            // select all data
            $stmt->execute();

            // this is how to get number of rows returned
            $num = $stmt->rowCount();

            // link to create record form

            //check if more than 0 record found
            if ($num > 0) {
                echo "<div class='row row-cols-1 row-cols-md-4 g-4'>";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);

                    // Wrap the entire card with an anchor tag
                    echo "<a href='read.php?id={$id}' class='text-decoration-none text-dark'>";
                    echo "<div class='col mb-4'>";
                    echo "<div class='card h-100 shadow'>";

                    // Add a class to the img element for styling
                    echo "<img src='" . ($image == "" ? 'image/CS_image.jpg' : $image) . "' class='card-img-top img-fluid custom-card-image' alt='Product Image'>";

                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>";

                    $wordLimit = 3;

                    // Split the description into words
                    $words = explode(' ', $name);

                    // Check if the number of words is greater than the limit
                    if (count($words) > $wordLimit) {
                        // Slice the array to get the first N words
                        $shortDescription = implode(' ', array_slice($words, 0, $wordLimit));

                        // Display the first N words followed by "..."
                        echo $shortDescription . '...';
                    } else {
                        // If the description is already N words or less, display it as is
                        echo $name;
                    }

                    echo "</h5>";
                    echo "<p class='text-break'>";

                    $wordLimit = 10;

                    // Split the description into words
                    $words = explode(' ', $description);

                    // Check if the number of words is greater than the limit
                    if (count($words) > $wordLimit) {
                        // Slice the array to get the first N words
                        $shortDescription = implode(' ', array_slice($words, 0, $wordLimit));

                        // Display the first N words followed by "..."
                        echo $shortDescription . '...';
                    } else {
                        // If the description is already N words or less, display it as is
                        echo $description;
                    }

                    echo "</p>";

                    echo "</div>";
                    echo "<div class='card-footer'>";
                    echo "<small class='text-muted'>Posted on " . date("F j, Y", strtotime($created)) . "</small>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</a>";
                }
                echo "</div>";
            } else {
                echo "<div class='alert alert-danger'>No records found.</div>";
            }

            ?>
            <div class="border-bottom mt-3 border-1 border-white"></div>
        </div> <!-- end .container -->
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

    <!-- confirm delete record will be here -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"></script>

</body>

</html>