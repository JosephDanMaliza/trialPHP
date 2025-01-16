<?php
include("connect.php");
session_start();

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch current user data from the database
$query = "SELECT * FROM users WHERE userID = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Handle form submission for updating the profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editProfile'])) {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);

    // Default to existing file paths
    $profilePhotoPath = $user['profilePicture'];
    $backgroundPhotoPath = $user['coverPhoto'];

    // Allowed file types for images
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    // Handle profile photo upload
    if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === 0) {
        $profilePhoto = $_FILES['profilePhoto'];
        if (in_array($profilePhoto['type'], $allowedTypes)) {
            $profilePhotoPath = 'uploads/' . basename($profilePhoto['name']);
            move_uploaded_file($profilePhoto['tmp_name'], $profilePhotoPath);
        } else {
            echo "Invalid file type for profile photo.";
        }
    }

    // Handle background photo upload
    if (isset($_FILES['backgroundPhoto']) && $_FILES['backgroundPhoto']['error'] === 0) {
        $backgroundPhoto = $_FILES['backgroundPhoto'];
        if (in_array($backgroundPhoto['type'], $allowedTypes)) {
            $backgroundPhotoPath = 'uploads/' . basename($backgroundPhoto['name']);
            move_uploaded_file($backgroundPhoto['tmp_name'], $backgroundPhotoPath);
        } else {
            echo "Invalid file type for background photo.";
        }
    }

    // Update user details in the database
    $updateQuery = "UPDATE users SET 
                    userName = '$username', 
                    firstName = '$fullName', 
                    profilePicture = '$profilePhotoPath', 
                    coverPhoto = '$backgroundPhotoPath' 
                    WHERE userID = '$user_id'";

    if (mysqli_query($conn, $updateQuery)) {
        echo "Profile updated successfully!";
        header("Location: profile.php"); // Redirect to the profile page
        exit;
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Edit Profile</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['userName']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo htmlspecialchars($user['firstName']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="profilePhoto" class="form-label">Profile Photo</label>
                <input type="file" class="form-control" id="profilePhoto" name="profilePhoto" accept="image/*">
            </div>
            <div class="mb-3">
                <label for="backgroundPhoto" class="form-label">Background Photo</label>
                <input type="file" class="form-control" id="backgroundPhoto" name="backgroundPhoto" accept="image/*">
            </div>
            <button type="submit" name="editProfile" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
