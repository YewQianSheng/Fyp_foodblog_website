<?php
// include database connection
include 'config/database.php';
try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] :  die('ERROR: Record ID not found.');

    // Fetch the image filename
    $image_query = "SELECT image FROM post WHERE id=?";
    $image_stmt = $con->prepare($image_query);
    $image_stmt->bindParam(1, $id);
    $image_stmt->execute();
    $image = $image_stmt->fetch(PDO::FETCH_ASSOC);

    // Delete query
    $delete_query = "DELETE FROM post WHERE id = ?";
    $delete_stmt = $con->prepare($delete_query);
    $delete_stmt->bindParam(1, $id);

    // Execute the delete query
    if ($count > 0) {
        header("Location: admin_dashboard.php?action=failed");
    } else {
        if ($delete_stmt->execute()) {
            if ($image['image'] != "") {
                if (file_exists($image['image'])) {
                    unlink($image['image']);
                }
            }
            // redirect to read records page and
            // tell the user record was deleted
            header('Location: admin_dashboard.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    }
} catch (PDOException $exception) {
    echo "<div class='alert alert-danger'>";
    echo $exception->getMessage();
    echo "</div>";
}
