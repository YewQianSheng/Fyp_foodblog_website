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
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <title>Home</title>
    <style>
        body {
            background: linear-gradient(90deg, rgba(169, 169, 169, 1) 0%, rgba(0, 0, 0, 1) 100%);

        }

        /* Add your custom styles here */
        button.like-button {
            background-color: black;
            color: white;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
        }

        .container2 {
            text-align: center;
            padding-top: 30px;
            color: white;
            padding-left: 40px;
            padding-right: 40px;
            background: linear-gradient(90deg, rgba(169, 169, 169, 1) 20%, rgba(0, 0, 0, 1) 100%);

        }

        .flex {
            display: flex;

        }

        .flex img {
            padding-bottom: 1%;
            padding-top: 4%;
        }

        .flex a {
            text-decoration: none;
            color: white;
        }

        .col-md-4 img,
        .col-md-4 h5 {
            transition: transform 0.1s ease;
        }

        .col-md-4 a:hover img {
            transform: scale(1.1);
        }

        .col-md-4 a:hover h5 {
            transform: translateY(-50%);
            color: white !important;
        }

        .post-meta li a {
            color: black;
            font-size: 13px;
        }

        .post-meta li a:hover {
            color: #4782d3;
        }

        .post-img {
            height: 150px;
            object-fit: cover;
            width: 100%;
        }

        .post-meta li:after {
            content: "/";
            margin-left: 10px;
        }

        .post-meta li:last-child:after {
            display: none;
        }

        @media (max-width: 820px) {
            .card {
                max-width: 100%;
                text-align: center;
                /* Make the card take full width on smaller screens */
            }

            .mt-1 {
                text-align: center !important;
            }

            .col-12.col-sm-3 {
                width: 100%;
                /* Make the first column take full width on smaller screens */
                margin-bottom: 15px;
                /* Add some spacing between the image and the content */
            }

            .col.ms-5 {
                width: 100%;
                /* Make the second column take full width on smaller screens */
                margin-right: 50px;

                /* Remove left margin to align with the first column */
            }

            .post-meta {
                text-align: center;
                /* Center the meta information */
            }
        }

        footer {
            padding: 1px;
            text-align: center;
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
        }

        .card:hover {
            /* Add your desired styles for the hover effect here */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        /* Additional styles for the hover effect */
        .card:hover .post-img {
            /* Add styles for the image on hover if needed */
            opacity: 0.8;
        }

        @media (max-width: 768px) {

            .welcome-container {
                /* Adjust styles for smaller screens, e.g., increase padding */
                padding: 10px;
            }

            .welcome-heading {
                font-size: medium;
            }
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
    <?php
    include 'config/database.php';
    ?>


    <div class="container-fluid mb-5">
        <?php
        include 'nav/navbar.php';
        ?>
        <div class="row" style="position: relative;">
            <img src="img/hbg.jpg" alt="logo" style="width: 100%; height: auto;">

            <div class="welcome-container">
                <h1 class="welcome-heading">Welcome to YamYam Food Blog</h1>
            </div>
        </div>

        <div class="container2">
            <h1 class="text-center mb-4"><strong>Food Discovery Guide</strong></h1>
            <div class="row">
                <div class="col-md-4">
                    <a href="recipe.php" class="text-center text-decoration-none">
                        <div class="aspect-ratio ratio-16x9">
                            <img src="img/recipe.jpg" alt="food1" class="img-fluid rounded-5" style="object-fit: cover; width: 80%;">
                        </div>
                        <h5 class="mt-2 text-light">Recipe</h5>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="vegetarian.php" class="text-center text-decoration-none">
                        <div class="aspect-ratio ratio-16x9">
                            <img src="img/vege.jpg" alt="food2" class="img-fluid rounded-5" style="object-fit: cover; width: 80%;">
                        </div>
                        <h5 class="mt-2 text-light">Vegetarian</h5>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="gourmet.php" class="text-center text-decoration-none">
                        <div class="aspect-ratio ratio-16x9">
                            <img src="img/gourmetfood.jpg" alt="food3" class="img-fluid rounded-5" style="object-fit: cover; width: 80%;">
                        </div>
                        <h5 class="mt-2 text-light">Gourmet Food</h5>
                    </a>
                </div>
            </div>
            <div class="border-bottom mt-3 border-1 border-white"></div>
        </div>




        <div class="container-fluid pt-3 pb-3 c2">
            <h4 class="sidebar-title text-center pb-3 text-light">Most Popular</h4>

            <?php
            $latestQuery = "SELECT id, name, description, image, created, date_updated, likes, author
                    FROM post
                    ORDER BY likes DESC
                    LIMIT 3";
            $latestStmt = $con->prepare($latestQuery);
            $latestStmt->execute();

            echo '<div class="container3 justify-content-center">';

            while ($latestRow = $latestStmt->fetch(PDO::FETCH_ASSOC)) {
                extract($latestRow);
            ?>
                <div class="container">
                    <div class="card bg-secondary-subtle mx-auto mb-5 rounded-5" style="max-width: 90%;">
                        <div class="row">
                            <div class="col-12 col-sm-3">
                                <a href="read.php?id=<?php echo $id; ?>" class="text-dark">
                                    <?php
                                    $imageSrc = $image != "" ? htmlspecialchars($image, ENT_QUOTES) : 'img/comingsoon.jpg';
                                    echo '<img src="' . $imageSrc . '" class="post-img img-fluid rounded-5" alt="Image" style="height: 210px;">';
                                    ?>
                                </a>
                            </div>
                            <div class="col ms-5">
                                <ul class="post-meta list-inline mt-2">
                                    <li class="list-inline-item">
                                        <i class="fa fa-user-circle-o"></i> <a><?= $author; ?></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="fa fa-calendar-o"></i> <a><?= date("M d, Y", strtotime($created)); ?></a>
                                    </li>
                                    <li class="list-inline-item mt-2">
                                        <i class="fa fa-thumbs-up"></i>
                                        <span id="likesCount" class="ml-1"><?php echo $likes; ?></span> Likes
                                    </li>
                                </ul>

                                <div class="content">
                                    <div class="page-header">
                                        <h5 class="mt-1 mb-1" style="text-align: left;">
                                            <a href="read.php?id=<?php echo $id; ?>" class="text-dark"><?php echo $name; ?></a>
                                        </h5>
                                    </div>
                                    <div>
                                        <p class="text-break">
                                            <?php
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
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php
            }

            echo '</div>';
            ?>
            <div class="border-bottom mt-3 border-1 border-white"></div>

        </div>

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