<?php
include "config/database.php";

$user_query = "SELECT * FROM user WHERE id=?";
$user_stmt = $con->prepare($user_query);
$user_stmt->bindParam(1, $_SESSION['id']);
$user_stmt->execute();
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<style>
    .navbar {
        background: linear-gradient(90deg, rgba(169, 169, 169, 1) 0%, rgba(0, 0, 0, 1) 100%);

    }

    @media screen and (max-width: 768px) {
        .container-fluid.fixed-top {

            /* Hide the element */
            position: static;
            /* Reset position to static */
        }
    }
</style>

<body>
    <div class="container-fluid fixed-top">
        <nav class="navbar navbar-expand-lg">

            <div class="container-fluid">
                <a class="navbar-brand" href="index.php"><img src="img/fblogo.png" alt="Navbar Image" style="width: 100px; height: 50px;"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active text-light" aria-current="page" href="index.php">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-light" href="readpost.php">Blog</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-light" href="createpost.php">Add post</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Categories
                            </a>
                            <ul class="dropdown-menu text-light">
                                <li><a class="dropdown-item" href="recipe.php">Recipe</a></li>
                                <li><a class="dropdown-item" href="vegetarian.php">Vegetarian</a></li>
                                <li><a class="dropdown-item" href="gourmet.php">Gourmet food</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="contact.php">Contact</a>
                        </li>

                        <li class="nav-item">
                            <?php
                            if (isset($_SESSION['id'])) {
                                if ($user['status'] == 'admin') {
                                    echo '<a class="nav-link text-light" href="admin_dashboard.php">Dashboard</a>';
                                }
                            }
                            ?>
                        </li>


                        <li class="nav-item">
                            <?php
                            if (isset($_SESSION['id'])) {
                                echo '<a class="nav-link text-light" href="profile.php">My Profile</a>';
                            }
                            ?>
                        </li>

                    </ul>
                    <div class="nav-item m-auto mx-3 justify-content-end text-light">
                        <?php
                        if (isset($_SESSION['id'])) {
                            echo '<a class="nav-link" href="?logout=true"><i class="fa fa-sign-out"></i> ' . $user['user_name'] . '</a>';
                        } else {
                            echo '<a class="nav-link" href="login.php">Log In</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>

        </nav>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>

</html>