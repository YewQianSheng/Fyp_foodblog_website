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
    <title>Contact</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>

<style>
    body {
        background: linear-gradient(90deg, rgba(169, 169, 169, 1) 0%, rgba(0, 0, 0, 1) 100%);
    }

    .contact {
        padding: 2%;
        background-color: #747474;
    }

    #contactform {
        text-align: center;
        border-radius: 10px;
        border: 1px solid black;
        padding: 2%;
        background-color: #D9D9D9;
        width: 70%;
        margin: auto;
    }

    .rowname {
        display: flex;
        flex-direction: row;
        width: 100%;
        justify-content: space-between;
        padding: 0.8% 0;
        gap: 1%;
    }

    .rowemail {
        display: flex;
        flex-direction: row;
        width: 100%;
        justify-content: space-between;
        padding: 0.8% 0;
    }

    .rowmessage {
        display: flex;
        flex-direction: row;
        width: 100%;
        justify-content: space-between;
        padding: 0.8% 0;
    }

    .fname {
        width: 80%;
        height: 30px;
    }

    .lname {
        width: 80%;
        height: 30px;
    }

    .product select {
        flex-direction: row;
        width: 100%;
        padding: 0.8% 0;
    }

    #email {
        width: 100%;
        height: 30px;
    }

    #message {
        width: 100%;
    }

    .buttonsubmit {
        font-size: 12px;
        color: white;
        border: none;
        text-align: center;
        border-radius: 5px;
        padding: 2% 5% 2% 5%;
        background-color: rgb(48, 48, 255);
        cursor: pointer;
        margin-top: 2%;
    }

    .number {

        border-top: 2px solid black;
        border-bottom: 2px solid black;
        padding: 2%;
        background-color: #D9D9D9;
        align-items: center;
    }

    .email {

        border-bottom: 2px solid black;
        padding: 2%;
        background-color: #D9D9D9;
        align-items: center;
    }

    .column {
        text-align: center;
        width: 40%;
    }


    .column1 a {
        text-decoration: none;
        color: black;
    }

    .toppic {
        position: relative;
        color: white;
    }


    .title {
        position: absolute;
        top: 40%;
        left: 15%;
    }

    .title p {
        font-size: larger;
    }

    @media screen and (max-width: 800px) {
        .number {
            flex-direction: column;
            font-size: 16px;
        }

        .email {
            flex-direction: column;
            font-size: 16px;
        }

        .column {
            width: 100%;
            margin: auto;
            text-align: center;
        }

        .column img {
            order: 1;
        }

        .column1 {
            margin: auto;
            width: 100%;
            text-align: center;
        }

        .column1 img {
            order: 2;
        }

        #contactform {
            width: 100%;
        }

        .title p {
            font-size: medium;
        }

        .column1 a {
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

<body>

    <div class="container-fluid">
        <?php
        include 'nav/navbar.php';
        ?>
        <div class="toppic">
            <img src="img/food.png" alt="" width="100%">
            <div class="title">
                <h1><strong>Contact </strong></h1>
                <p>Any question or remarks? Just write <br>me a message!</p>
            </div>
        </div>

        <!--  -->
        <div class="contact">
            <div id="contactform">
                <h2>Get In Touch</h2>
                <form action="https://formspree.io/f/xwkjejap" method="POST">
                    <div class="rowname">
                        <input class="fname" type="text" name="name" placeholder="First Name" required>
                        <input class="lname" type="text" name="name" placeholder="Last Name" required>
                    </div>
                    <div class="rowemail">
                        <input id="email" type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="rowmessage">
                        <textarea id="message" name="message" rows="4" placeholder="Message" required=""></textarea>
                    </div>

                    <button class="buttonsubmit">SUBMIT</button>
                </form>
            </div>
        </div>
        <!--  -->
        <div class="number">
            <div class="column1 fs-5 text-center">
                <a href="tel:011-36244911">
                    <h5>Phone number</h5>011-36244911
                </a>
            </div>
        </div>

        <div class="email">
            <div class="column1 fs-5 text-center">
                <a href="mailto:yewqiansheng0523@e.newera.edu.my">
                    <h5>Email</h5>yewqiansheng0523@e.newera.edu.my
                </a>
            </div>
        </div>
        <div class="border-bottom mt-3 border-1 border-white"></div>

        <!--  -->
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
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"></script>

</html>