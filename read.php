<?php
session_start();

$logout = isset($_GET['logout']) ? $_GET['logout'] : "";
if ($logout == "true") {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
$isLogged = isset($_SESSION['id']);

// Function to redirect to the login page
function redirectToLogin()
{
    header("Location: login.php?error=Please login first to like this post.");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the like button is clicked
    if (isset($_POST['like'])) {
        // If user is not logged in, redirect to login page
        if (!$isLogged) {
            redirectToLogin();
        }

        // Process like action here
        // ...
    }
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Read post details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            background: linear-gradient(90deg, rgba(169, 169, 169, 1) 0%, rgba(0, 0, 0, 1) 100%);
        }

        .post-meta li a {
            color: black;
            font-size: 13px;
        }

        .post-meta li a:hover {
            color: #4782d3;
        }

        .post-img {
            max-height: 150px;
            object-fit: contain;
            width: 100%;
        }

        .post-meta li:after {
            content: "/";
            margin-left: 10px;
        }

        .post-meta li:last-child:after {
            display: none;
        }

        p {
            text-align: justify;
        }

        #likeButton {
            border: none;
            background-color: transparent;
            cursor: pointer;
            /* Add this line to indicate it's clickable */
        }

        #likeIcon {
            color: black;
        }

        #likeIcon.clicked {
            color: blue;
        }

        .post {
            display: flex;
            background-color: #FFFBE9;

        }

        .content {
            width: 50%;
            margin: auto;
        }

        @media screen and (max-width: 1000px) {
            .post {
                flex-direction: column;
                width: auto;
                justify-content: center;
                text-align: center;
                height: auto;
            }

            .post img {
                width: 100%;
                height: auto;
            }

            .flex {
                flex-direction: column;
                gap: 20px;
            }

            .border {
                width: 80%;
                height: auto;
                margin: auto;
            }

            .text {
                font-size: small;
            }

            .text h1 {
                font-size: large;
            }

            .content h2 {
                padding-top: 5%;
            }

        }

        .card {
            position: relative;
            overflow: hidden;
        }

        .card img {
            transition: transform 0.3s ease-in-out;
        }

        .card:hover img {
            transform: scale(1.1);
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            background: rgba(0, 0, 0, 0.7);
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .card:hover .overlay {
            opacity: 1;
        }

        .card-body {
            position: relative;
            z-index: 1;
            /* Bring the text to the front */
        }

        .card:hover {
            background-color: white !important;
            /* Change to your desired hover color */
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

        @media screen and (max-width: 760px) {
            .post {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .content {
                width: 100%;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <?php include 'nav/navbar.php'; ?>
        <div style="height: 50px;"></div>
        <?php


        // Check if the user is logged in
        $isLogged = isset($_SESSION['id']);

        // Display message if user is not logged in


        if (isset($_GET['id'])) {
            $postId = $_GET['id'];
            $userId = $_SESSION['id'];

            // Check if the user has already liked the post
            $checkQuery = "SELECT * FROM post_likes WHERE post_id = :post_id AND user_id = :user_id";
            $checkStmt = $con->prepare($checkQuery);
            $checkStmt->bindParam(":post_id", $postId);
            $checkStmt->bindParam(":user_id", $userId);
            $checkStmt->execute();
            $isLiked = ($checkStmt->rowCount() > 0);

            if (!isset($_SESSION['id'])) {
                $isLogged = false;
            } else {
                $isLogged = true;
            }

            // Check if the form is submitted and user is logged in
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like']) && $isLogged) {
                if ($isLiked) {
                    // User already liked, remove the like (unlike)
                    $deleteQuery = "DELETE FROM post_likes WHERE post_id = :post_id AND user_id = :user_id";
                    $deleteStmt = $con->prepare($deleteQuery);
                    $deleteStmt->bindParam(":post_id", $postId);
                    $deleteStmt->bindParam(":user_id", $userId);
                    $deleteStmt->execute();
                    $isLiked = false;
                } else {
                    // User hasn't liked, add the like
                    $insertQuery = "INSERT INTO post_likes (post_id, user_id) VALUES (:post_id, :user_id)";
                    $insertStmt = $con->prepare($insertQuery);
                    $insertStmt->bindParam(":post_id", $postId);
                    $insertStmt->bindParam(":user_id", $userId);
                    $insertStmt->execute();
                    $isLiked = true;
                }

                // Update the likes count in the post table
                $updateLikesQuery = "UPDATE post SET likes = (SELECT COUNT(id) FROM post_likes WHERE post_id = :post_id) WHERE id = :post_id";
                $updateLikesStmt = $con->prepare($updateLikesQuery);
                $updateLikesStmt->bindParam(":post_id", $postId);
                $updateLikesStmt->execute();
            }

            // Fetch post details from the database
            try {
                $query = "SELECT id, name,author_id ,author, description, image, created, likes FROM post WHERE id = :id ";
                $stmt = $con->prepare($query);
                $stmt->bindParam(":id", $postId);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $name = $row['name'];
                $description = $row['description'];
                $image = $row['image'];
                $created = $row['created'];
                $author = $row['author'];
                $author_id = $row['author_id'];
                $likes = $row['likes'];
                $isAuthor = ($userId == $author_id);
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        } else {
            die('ERROR: Record ID not found.');
        }
        ?>

        <div class="post mt-3 bg-secondary-subtle mt-5" style="border: 5px ; padding: 25px ; margin-left: 100px; margin-right: 100px; position: relative;">
            <div class="img">
                <?php if ($image != "") : ?>
                    <img src="<?= htmlspecialchars($image, ENT_QUOTES) ?>" width="400" class="mt-4 img-fluid" alt="Image">
                <?php else : ?>
                    <img src="img/comingsoon.jpg" alt="Coming Soon" width="100" class="img-fluid">
                <?php endif; ?>

                <ul class="post-meta list-inline mt-2">
                    <li class="list-inline-item">
                        <i class="fa fa-user-circle-o"></i> <a href="#"><?= $author; ?></a>
                    </li>
                    <li class="list-inline-item">
                        <i class="fa fa-calendar-o"></i> <a href="#"><?= date("M d, Y", strtotime($created)); ?></a>
                    </li>
                    <li class="list-inline-item">
                        <form method="post">
                            <button type="submit" name="like" id="likeButton">
                                <i id="likeIcon" class="fa fa-thumbs-up <?= $isLiked ? 'clicked' : ''; ?>"></i>
                            </button>
                            <span id="likesCount"><?= $likes; ?></span> likes
                        </form>
                    </li>
                </ul>
            </div>

            <div class="content">
                <div class="page-header">
                    <h1><?= $name; ?></h1>
                </div>

                <?php
                $paragraphs = explode("\n", htmlspecialchars($description, ENT_QUOTES));

                foreach ($paragraphs as $paragraph) : ?>
                    <p class="text-break"><?= $paragraph; ?></p>
                <?php endforeach; ?>


            </div>

            <?php
            // Display the edit button if the current user is the author
            if ($isAuthor) {
                echo '<div class="btn-group position-absolute top-0 end-0">';
                echo '<button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
                echo '<i class="fa-solid fa-list"></i>';
                echo ' </button>';
                echo '<ul class="dropdown-menu">';
                echo '<a href="edit_post.php?id=' . $postId . '" class="dropdown-item btn btn-primary">Edit Post</a>';
                echo '<a href="delete_post.php?id=' . $postId . '" class="dropdown-item btn btn-primary">Delete</a>';
            }
            echo '</ul>';
            echo '</div>';
            echo '</div>';
            ?>
            <div class="border-bottom mt-5 border-1 border-white"></div>


        </div>

        <div class="container">

            <h1 class="text-center mb-2 pt-2 text-light"><strong>Food Discovery Guide</strong></h1>
            <!-- PHP code to read records will be here -->
            <div class="color">
                <?php
                // include database connection
                include 'config/database.php';
                $action = isset($_GET['action']) ? $_GET['action'] : "";

                // if it was redirected from delete.php
                if ($action == 'deleted') {
                    echo "<div class='alert alert-success'>Record was deleted.</div>";
                }

                // delete message prompt will be here
                $searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
                $query = "SELECT id,name,image FROM post ";
                if (!empty($searchKeyword)) {
                    $query .= " WHERE name LIKE :keyword";
                    $searchKeyword = "%{$searchKeyword}%";
                }
                $query .= " ORDER BY id DESC LIMIT 6";
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
                    echo "<div class='row g-4 m-auto'>";
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // extract row
                        // this will make $row['firstname'] to just $firstname only
                        extract($row);
                        // creating new table row per record
                        echo "<div class='col-md-4'>"; // Adjust the column size based on your design
                        echo "<div class='card text-center pt-3 bg-secondary-subtle' >"; // Assuming you want to use cards for each record 
                        echo "<a class='link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-dark text-dark'  href='read.php?id={$id}'>";
                        echo ($image == "")
                            ? "<img src='image/CS_image.jpg' style='width: 200px; height: 200px;'>"
                            : "<img src='" . ($image) . "'  style='width: 200px; height: 200px;'>";
                        echo "<div class='card-body'>";
                        echo "{$name}";
                        echo "</div>";
                        echo "</a>";
                        // Inside the while loop where you display products
                        echo "</div>";
                        echo "</div>";
                    }
                    echo "<div class='col-md-12 text-center mt-3'>";
                    echo "<a href='readpost.php'>";
                    echo "<button type='button' class='btn bg-secondary text-light'>See All</button>";
                    echo "</a>";
                    echo "</div>";

                    echo "</div>";
                } else {
                    echo "<div class='alert alert-danger'>No records found.</div>";
                }
                ?></div>
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
        </div> <!-- end .container -->



        <script>
            var isLiked = <?php echo $isLiked ? 'true' : 'false'; ?>;

            function toggleLike() {
                isLiked = !isLiked;
                var likeIcon = document.getElementById('likeIcon');
                likeIcon.classList.toggle('clicked', isLiked);

                // Update the likes count
                var likesCount = document.getElementById('likesCount');
                likesCount.textContent = isLiked ? parseInt(likesCount.textContent) + 1 : parseInt(likesCount.textContent) - 1;
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"></script>
        <!-- Modify the like button to disable it if user is not logged in -->
        <form method="post">
            <button type="submit" name="like" id="likeButton" <?php if (!$isLogged) echo 'disabled'; ?>>
                <i id="likeIcon" class="fa fa-thumbs-up <?= $isLiked ? 'clicked' : ''; ?>"></i>
            </button>
            <span id="likesCount"><?= $likes; ?></span> likes
        </form>

        <!-- Display a message if user is not logged in -->

</body>

</html>









<!-- <?php
        $paragraphs = explode("\n", htmlspecialchars($description, ENT_QUOTES));

        foreach ($paragraphs as $paragraph) : ?>
                    <p class="text-break"><?= $paragraph; ?></p>
                <?php endforeach; ?> -->