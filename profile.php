<?php include "validate_login.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

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
</head>

<body>

    <div class="container-fluid p-0">
        <?php include 'config/database.php'; ?>
        <?php include 'nav/navbar.php'; ?>
    </div>



    <div class="container post-container">
        <div style="height: 100px;"></div>
        <?php
        // Fetch user details
        $user_id = $_SESSION['id'];
        $user_query = "SELECT * FROM user WHERE id = ?";
        $user_stmt = $con->prepare($user_query);
        $user_stmt->bindParam(1, $user_id);
        $user_stmt->execute();
        $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Display author's name and image
            echo '<div class="welcome-container text-center">';
            $imagePath = "img/user3.png";
            echo "<img src='" . $imagePath . "' style='width: 200px; height: 200px;'>";
            echo '<h2 class="welcome-heading">Welcome, ' . $user['user_name'] . '!</h2>';
            echo '</div>';
        }
        ?>
        <h2>My Profile</h2>

        <?php
        // Fetch posts created by the logged-in user
        $user_id = $_SESSION['id'];
        $post_query = "SELECT * FROM post WHERE author_id = ?";
        $post_stmt = $con->prepare($post_query);
        $post_stmt->bindParam(1, $user_id);
        $post_stmt->execute();
        $posts = $post_stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($posts) {
            echo '<div class="row row-cols-1 row-cols-md-4 ">';
            foreach ($posts as $post) {
                echo '<a href="read.php?id=' . $post['id'] . '" style="text-decoration: none; color: inherit;">';
                echo '<div class="col-md mb-4">';
                echo '<div class="card h-100 shadow">';
                echo '<img src="' . $post['image'] . '" class="card-img-top img-fluid custom-card-image" alt="Post Image">';
                echo '<div class="card-body">';
                echo "<h5 class='card-title'>";

                $wordLimit = 2.5;

                // Split the name into words
                $words = explode(' ', $post['name']);

                // Check if the number of words is greater than the limit
                if (count($words) > $wordLimit) {
                    // Slice the array to get the first N words
                    $shortName = implode(' ', array_slice($words, 0, $wordLimit));

                    // Display the first N words followed by "..."
                    echo $shortName . '...';
                } else {
                    // If the name is already N words or less, display it as is
                    echo $post['name'];
                }

                echo "</h5>";


                echo '</div>';
                echo "<div class='card-footer'>";
                echo "<small class='text-muted'>Posted on " . date("F j, Y", strtotime($post['created'])) . "</small>";
                echo "</div>";
                echo '</div>';
                echo '</div>';
                echo '</a>';
            }
            echo '</div>';
        } else {
            echo '<p class="text-center text-light">No posts found.</p>';
        }
        ?>
    </div>
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
</body>

</html>