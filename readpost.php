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
    <title>Read Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>

<style>
    body {
        background: linear-gradient(90deg, rgba(169, 169, 169, 1) 0%, rgba(0, 0, 0, 1) 100%);

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
        background-color: Gray !important;
        /* Change to your desired hover color */
    }

    footer {
        padding: 1px;
        text-align: center;
    }

    @media (max-width: 768px) {
        h1.media {
            font-size: medium !important;
            width: 70%;
            position: absolute;
            top: 10px;
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
</style>


<body>
    <!-- container -->
    <div class="container-fluid">

        <?php
        include 'nav/navbar.php';
        ?>
        <div class="row" style="position: relative;">
            <img src="img/bgg.jpg" alt="logo" style="width: 100%; ">
            <div class="welcome-container">
                <h1 class="welcome-heading">Welcome to Happy Supper Mart</h1>
            </div>

        </div>
        <form class="d-flex pt-3 justify-content-center" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="GET">
            <input class="form-control me-2 w-25" type="text" name="search" placeholder="Search" value="<?= isset($_GET['search_keyword']) ? htmlspecialchars($_GET['search_keyword'], ENT_QUOTES) : ''; ?>">
            <button class="btn btn-light" type="submit">Search</button>
        </form>

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
            $query = "SELECT id,name,image FROM post";
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
            } else {
                echo "<div class='alert alert-danger'>No records found.</div>";
            }
            ?></div>
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

    <!-- confirm delete record will be here -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"></script>

</body>

</html>