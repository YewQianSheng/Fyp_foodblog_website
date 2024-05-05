<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

include 'config/database.php';

if (isset($_GET['id'])) {
    $postId = $_GET['id'];
    $userId = $_SESSION['id'];

    // Check if the current user is the author of the post
    $checkAuthorQuery = "SELECT author_id, image FROM post WHERE id = :post_id";
    $checkAuthorStmt = $con->prepare($checkAuthorQuery);
    $checkAuthorStmt->bindParam(":post_id", $postId);
    $checkAuthorStmt->execute();
    $row = $checkAuthorStmt->fetch(PDO::FETCH_ASSOC);
    $authorId = $row['author_id'];
    $imagePath = $row['image'];

    if ($authorId == $userId) {
        // Delete the associated image file
        if (!empty($imagePath) && file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete the post from the database
        $deletePostQuery = "DELETE FROM post WHERE id = :post_id";
        $deletePostStmt = $con->prepare($deletePostQuery);
        $deletePostStmt->bindParam(":post_id", $postId);
        $deletePostStmt->execute();

        // Redirect to the main page with a success message
        header("Location: index.php?action=deleted");
        exit();
    } else {
        // Redirect to the main page with an error message
        header("Location: index.php?action=not_author");
        exit();
    }
} else {
    // Redirect to the main page if post ID is not set
    header("Location: index.php");
    exit();
}
