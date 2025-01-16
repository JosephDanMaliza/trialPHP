<?php
session_start();
include("connect.php");

$error = "";
$user_id = $_SESSION['userID'];

if (isset($_POST['btnDelete']) && $_POST['btnDelete'] == 'yes') {
    // Delete user from the database
    $deleteQuery = "DELETE FROM users WHERE userID = '$user_id'";
    
    if (executeQuery($deleteQuery)) {
        // Destroy session and log out the user
        session_unset();
        session_destroy();
        
        header("Location: index.php");
    } else {
        $error = "Account deletion failed.";
    }
}
?>

<h3>Are you sure you want to delete your account?</h3>
<form action="delete_profile.php" method="POST">
    <button type="submit" name="btnDelete" value="yes">Yes, delete my account</button>
    <button type="button" onclick="window.location.href='profile.php'">Cancel</button>
</form>
<?php if ($error != "") { echo "<p style='color: red;'>$error</p>"; } ?>
