<?php include "validate_login.php"; ?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <!-- Latest compiled and minified Bootstrap CSS -->
    <style>
        body {
            background: linear-gradient(90deg, rgba(169, 169, 169, 1) 0%, rgba(0, 0, 0, 1) 100%);

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
    <div class="container-fluid p-0">
        <?php include 'nav/navbar.php'; ?>
    </div>
    <div class="container">
        <div style="height: 110px;"></div>
        <div class="page-header">
            <h1>Read All User Post</h1>
        </div>

        <form class="d-flex" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="GET">
            <input class="form-control me-2 mb-2" type="text" name="search" placeholder="Search" aria-label="Search" value="<?php echo isset($_GET['search_keyword']) ? htmlspecialchars($_GET['search_keyword'], ENT_QUOTES) : ''; ?>">
            <button class="btn btn-outline-success mb-2" type="submit">Search</button>
        </form>

        <!-- PHP code to read records will be here -->
        <?php
        // include database connection
        include 'config/database.php';

        // delete message prompt will be here
        $action = isset($_GET['action']) ? $_GET['action'] : "";

        // if it was redirected from delete.php
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        }

        if ($action == 'failed') {
            echo "<div class='alert alert-danger'>This customer make a order.</div>";
        }

        // select all data
        // ?前面是condition '' if else
        $searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
        $query = "SELECT id, author, name, created, categorys_id, image FROM post";
        if (!empty($searchKeyword)) {
            // $query 继续后面
            $query .= " WHERE author LIKE :keyword OR name LIKE :keyword OR categorys_id LIKE :keyword";
            $searchKeyword = "%{$searchKeyword}%";
        }
        $query .= " ORDER BY id DESC";
        $stmt = $con->prepare($query);
        if (!empty($searchKeyword)) {
            $stmt->bindParam(':keyword', $searchKeyword);
        }

        $stmt->execute();

        // this is how to get number of rows returned
        $num = $stmt->rowCount();

        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Author</th>";
            echo "<th>Title</th>";
            echo "<th>Created</th>";
            echo "<th>Category</th>";
            echo "<th>Image</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td>{$author}</td>";
                echo "<td><a href='read.php?id={$id}' class='link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover text-dark link-dark'>{$name}</a></td>";
                echo "<td>{$created}</td>";
                echo "<td>{$categorys_id}</td>";
                if ($image != "") {
                    echo '<td><img src="' . ($image) . '"width="100"></td>';
                }
                echo "<td>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_post({$id});'  class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            // end table
            echo "</table>";
        } else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>


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

    <!-- confirm delete record will be here -->
    <script type='text/javascript'>
        // confirm record deletion
        function delete_post(id) {

            if (confirm('Are you sure?')) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'admin_delete.php?id=' + id;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>