<?php
session_start();
if (isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
</head>
<style>
    body {
        background-image: url(img/pexels-pixabay-326279.jpg);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;
        background-attachment: fixed;
    }
</style>

<body>
    <div class="page-header p-2">
        <h1 class="text-white">Log In</h1>
    </div>
    <?php
    if ($_POST) {
        include "config/database.php";

        $username = $_POST['user_name'];
        $password = htmlspecialchars(strip_tags($_POST['password']));

        if (empty($username)) {
            $user_input_err = "Please enter the User Name field.";
        }
        if (empty($password)) {
            $password_input_err = "Please enter the Password field";
        }

        if (empty($user_input_err) && empty($password_input_err)) {
            try {
                $login_query = "SELECT * FROM user where user_name=:username or email=:username";
                $login_stmt = $con->prepare($login_query);
                $login_stmt->bindParam(':username', $username);

                $login_stmt->execute();
                $login = $login_stmt->fetch(PDO::FETCH_ASSOC);
                if ($login) {
                    if (password_verify($password, $login['password'])) {
                        $_SESSION['id'] = $login['id'];
                        $_SESSION['status'] = $login['status'];
                        if ($login['status'] === "admin") {
                            header("Location: admin_dashboard.php");
                        } else {
                            header("Location: index.php");
                        }
                        exit();
                    } else {
                        $password_input_err = "Incorrect Password";
                    }
                } else {
                    $user_input_err = "User Name Not Found";
                }
            } catch (PDOException $exception) {
                $exception->getMessage();
            }
        }
    }
    ?>
    <div class="container">
        <?php
        $action = isset($_GET['action']) ? $_GET['action'] : "";
        if ($action == "register_success") {
            echo "<div class='alert alert-info'>Register Success. Please Log In to continue browsing.</div>";
        }
        if ($action == "warning") {
            echo "<div class='alert alert-danger'>Please Log In to continue browsing.</div>";
        }
        ?>
        <div class="container bg-warning d-flex flex-column align-items-center w-50 mt-5 p-4">
            <div class="container text-center">
                <img class="w-50" src="img/fblogo.png" alt="EcoMart">
            </div>
            <div class="container">
                <h2 class="my-4 text-center">Welcome to Yamayam Blog</h2>

                <form action="" method="post" class="m-3">
                    <div class="form-group">
                        <input type="text" name="user_name" id="user_name" class="form-control" placeholder="User Name">
                        <span class="text-danger"><?php echo isset($user_input_err) ? "<strong>" . $user_input_err . "</strong>" : ""; ?></span>
                    </div>
                    <br>
                    <div class="form-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                        <span class="text-danger"><?php echo isset($password_input_err) ? "<strong>" . $password_input_err . "</strong><br>" : ""; ?></span>
                    </div>
                    <br>
                    <div class="text-center">
                        <button class="btn btn-primary m-2" name="submit" type="submit">Login</button>
                        <a href="register.php" class="btn btn-outline-dark m-2">Register</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>