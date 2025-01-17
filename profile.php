<?php
session_start();
include("connect.php");
KZZZZZZ
$error = "";

// Ensure the user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['userID']; // Get the user ID from session

// Fetch user details from the database
$selectQuery = "SELECT firstName, lastName, userName, profilePicture, coverPhoto FROM users WHERE userID = '$user_id'";
$userResult = executeQuery($selectQuery);
$user = mysqli_fetch_assoc($userResult);

// Check if user data exists
if (!$user) {
    $error = "User not found.";
    exit;
}

// Count the number of followers for the current user
$followerQuery = "SELECT COUNT(*) AS followerCount FROM follows WHERE followedID = '$user_id'";
$followerResult = executeQuery($followerQuery);
$followerData = mysqli_fetch_assoc($followerResult);
$followerCount = $followerData['followerCount'] ?? 0; // Default to 0 if no followers

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);

    // Handle file uploads
    $uploadDir = "uploads/";
    $backgroundPhoto = $_FILES['backgroundPhoto'];
    $profilePhoto = $_FILES['profilePhoto'];
    $backgroundPhotoPath = null;
    $profilePhotoPath = null;

    // Upload background photo if provided
    if (!empty($backgroundPhoto['name'])) {
        $backgroundPhotoPath = $uploadDir . basename($backgroundPhoto['name']);
        move_uploaded_file($backgroundPhoto['tmp_name'], $backgroundPhotoPath);
    }

    // Upload profile photo if provided
    if (!empty($profilePhoto['name'])) {
        $profilePhotoPath = $uploadDir . basename($profilePhoto['name']);
        move_uploaded_file($profilePhoto['tmp_name'], $profilePhotoPath);
    }

    // Update database
    $updateQuery = "UPDATE users SET userName = '$username', firstName = '$fullName'";
    if ($backgroundPhotoPath) {
        $updateQuery .= ", coverPhoto = '$backgroundPhotoPath'";
    }
    if ($profilePhotoPath) {
        $updateQuery .= ", profilePicture = '$profilePhotoPath'";
    }
    $updateQuery .= " WHERE userID = '$user_id'";

    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: profile.php");
        exit;
    } else {
        $_SESSION['error'] = "Failed to update profile. Please try again.";
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

<style>
    .custom-card {
        width: 100%;
        max-width: auto;
        margin: auto;
        background-color: #C9F6FF;
        border-color: white;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        border-radius: 50px;
        border-color: transparent;
        height: 30px;
        font-size: 15px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-family: "Helvetica Rounded";
        background-color: #7091E6;
    }

    .create-post-button {
        position: absolute;
        bottom: 50px;
        right: 50px;
    }

    @media (max-width: 768px) {
        .profile-dropdown {
            top: 10px;
            right: 20px;
        }
    }

    @media (max-width: 1000px) {
        .left-column {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100vh;
            background-color: #fff;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
            transition: left 0.3s ease;
            z-index: 1;
        }

        .middle-column {
            width: 100%;
        }

        .left-column.show {
            left: 0;
        }
    }

    .hamburger-btn {
        display: block;
        font-size: 25px;
        width: 25px;
        cursor: pointer;
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 999;
    }

    @media (min-width: 1000px) {
        .hamburger-btn {
            display: none;
        }
    }
</style>

<body>
    <div class="container">
        <div class="row">
            <!-- Hamburger Button -->
            <div class="hamburger-btn" onclick="toggleLeftColumn()">&#9776;</div>

            <!-- Left Column -->
            <div class="col-md-3 left-column">
                <div class="logo">
                    <img src="../assets/icons/wordMark big.svg" alt="NowUKnow Logo" width="100" height="100">
                </div>
                <div class="sidebar">
                    <ul>
                        <li>
                            <a href="../index.html"
                                style="display: flex; align-items: center; gap: 8px; text-decoration: none; color: #06080F;">
                                <i class="fa-solid fa-house nav-icon" style="font-size: 24px; color: #06080F;"></i>
                                <span class="nav-title"
                                    style="font-family: Helvetica, Arial, sans-serif; font-weight: normal; font-size: 20px;">Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="../users/profile.html"
                                style="display: flex; align-items: center; gap: 8px; text-decoration: none; color: #06080F;">
                                <i class="fa-solid fa-user nav-icon" style="font-size: 24px; color: #06080F;"></i>
                                <span class="nav-title"
                                    style="font-family: Helvetica, Arial, sans-serif; font-weight: normal; font-size: 20px;">Profile</span>
                            </a>
                        </li>
                        <!-- Add other navigation items here -->
                    </ul>
                </div>
                <div class="logout-container">
                    <button class="btn-logout">Log Out</button>
                </div>
            </div>

            <!-- Middle Column -->
            <div class="col-md-6 middle-column">
                <!-- Search Bar -->
                <div class="middle-search-bar">
                    <input type="text" class="form-control" placeholder="Search...">
                </div>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error']; ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- User Profile Section -->
                <div class="profile-container col-md-12">
                    <div class="profile-section position-relative">
                        <div class="background">
                            <!-- Display cover photo dynamically -->
                            <img src="uploads/<?php echo $user['coverPhoto'] ?: 'default_cover.jpg'; ?>" alt="Background Picture">
                        </div>
                        <div class="profile-pic1" style="margin-top: -85px;">
                            <!-- Display profile picture dynamically -->
                            <img src="uploads/<?php echo $user['profilePicture'] ?: 'default_profile.jpg'; ?>" alt="Profile Picture" style="width: 150px; border-radius: 50%;">
                        </div>

                        <!-- Edit and Delete Dropdown -->
                        <div class="prof-edit-delete pt-8 text-end">
                            <div class="dropdown" style="width: 100%;">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px; font-family: 'Helvetica Rounded'; border-color: #FFFF; background-color: #FFFF; color: #808080;">
                                    <span class="ellipsis">...</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</a></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deleteProfileModal">Delete Account</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Profile Information -->
                        <div class="profile-info">
                            <div class="profile-data">
                                <h1 class="fullname mt-3"><?php echo $user['firstName'] . ' ' . $user['lastName']; ?></h1>
                                <h2 class="username">@<?php echo $user['userName']; ?></h2>
                                <p class="followers"><?php echo htmlspecialchars($followerCount) ?: 0; ?> followers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Post Button -->
            <div class="me-3">
                <div class="create-post-button">
                    <button class="btn-create-post" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <img src="../assets/icons/edit.svg" alt="Icon" class="button-icon"> Create Post
                    </button>
                </div>
            </div>
        </div>

        <!-- Modals for Edit Profile and Delete Account -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color: #f8f9fa;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editProfileForm" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['userName']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo $user['firstName'] . ' ' . $user['lastName']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="backgroundPhoto" class="form-label">Background Photo</label>
                                <input type="file" class="form-control" id="backgroundPhoto" name="backgroundPhoto">
                            </div>
                            <div class="mb-3">
                                <label for="profilePhoto" class="form-label">Profile Photo</label>
                                <input type="file" class="form-control" id="profilePhoto" name="profilePhoto">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS and dependencies -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function toggleLeftColumn() {
                document.querySelector('.left-column').classList.toggle('show');
            }
        </script>
    </div>
</body>

</html>
