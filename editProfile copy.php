<?php
session_start();
include("connect.php");

$error = "";

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['userID'];

// Select the current user details
$selectQuery = "SELECT * FROM users WHERE userID = '$user_id'";
$userResult = executeQuery($selectQuery);
$user = mysqli_fetch_assoc($userResult);

// Handle form submission for profile updates
if (isset($_POST['btnUpdate'])) {
    // Update user profile information
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $userName = mysqli_real_escape_string($conn, $_POST['userName']);

    // Handle file uploads for profile and background images
    $profilePhoto = $_FILES['profilePhoto']['name'];
    $backgroundPhoto = $_FILES['backgroundPhoto']['name'];

    if ($profilePhoto) {
        // Save profile photo to directory
        $profilePhotoTmp = $_FILES['profilePhoto']['tmp_name'];
        $profilePhotoPath = "uploads/" . basename($profilePhoto);
        move_uploaded_file($profilePhotoTmp, $profilePhotoPath);
    } else {
        $profilePhotoPath = $user['profileImage']; // Keep existing image if none uploaded
    }

    if ($backgroundPhoto) {
        // Save background photo to directory
        $backgroundPhotoTmp = $_FILES['backgroundPhoto']['tmp_name'];
        $backgroundPhotoPath = "uploads/" . basename($backgroundPhoto);
        move_uploaded_file($backgroundPhotoTmp, $backgroundPhotoPath);
    } else {
        $backgroundPhotoPath = $user['backgroundImage']; // Keep existing background if none uploaded
    }

    // Update user information in the database
    $updateQuery = "UPDATE users SET 
                    firstName = '$firstName', 
                    lastName = '$lastName', 
                    userName = '$userName', 
                    profileImage = '$profilePhotoPath', 
                    backgroundImage = '$backgroundPhotoPath' 
                    WHERE userID = '$user_id'";

    $updateResult = executeQuery($updateQuery);
    
    if ($updateResult) {
        // If the update was successful, refresh session variables and redirect
        $_SESSION['userName'] = $userName;
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['profileImage'] = $profilePhotoPath;
        $_SESSION['backgroundImage'] = $backgroundPhotoPath;
        header("Location: profile.php"); // Redirect to profile page after successful update
        exit;
    } else {
        $error = "There was an error updating your profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <title>NowUKnow</title>
</head>

<body>

    <!-- Middle Column -->
    <!-- User Profile -->
    <div class="col-md-6 middle-column">
        <div class="middle-search-bar">
            <input type="text" class="form-control" placeholder="Search...">
        </div>
        <div class="profile-container col-md-12">
            <div class="profile-section position-relative">
                <div class="background">
                    <!-- Dynamically display background image -->
                    <img src="uploads/<?php echo $user['backgroundImage']; ?>" alt="Background Picture">
                </div>
                <div class="profile-pic1" style="margin-top: -85px;">
                    <!-- Dynamically display profile image -->
                    <img src="uploads/<?php echo $user['profileImage']; ?>" alt="Profile Picture" style="width: 150px; border-radius: 50%;">
                </div>

                <div class="prof-edit-delete pt-8 text-end">
                    <div class="dropdown" style="width: 100%;">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                            style="border-radius: 20px; font-family: 'Helvetica Rounded'; border-color: #FFFF; background-color: #FFFF; color: #808080;">
                            <span class="ellipsis">...</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deleteProfileModal">Delete Account</a></li>
                        </ul>
                    </div>
                </div>

                <div class="profile-info">
                    <div class="profile-data">
                        <!-- Dynamically display user information -->
                        <h1 class="fullname mt-3"><?php echo $user['firstName'] . ' ' . $user['lastName']; ?></h1>
                        <h2 class="username">@<?php echo $user['userName']; ?></h2>
                        <p class="followers"><?php echo $user['followers_count']; ?> followers</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color: #f8f9fa;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="backgroundPhoto" class="form-label">Background Photo</label>
                                <input type="file" class="form-control" name="backgroundPhoto" accept=".png, .jpg">
                            </div>
                            <div class="mb-3">
                                <label for="profilePhoto" class="form-label">Profile Photo</label>
                                <input type="file" class="form-control" name="profilePhoto" accept=".png, .jpg">
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="userName" value="<?php echo htmlspecialchars($user['userName']); ?>" placeholder="Enter new username">
                            </div>
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="firstName" value="<?php echo htmlspecialchars($user['firstName']); ?>" placeholder="Enter full name">
                            </div>
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="lastName" value="<?php echo htmlspecialchars($user['lastName']); ?>" placeholder="Enter last name">
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="btnUpdate" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
