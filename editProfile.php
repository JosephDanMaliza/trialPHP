<?php
session_start();
include("connect.php");

$error = "";

$user_id = $_SESSION['userID'];

$selectQuery = "SELECT * FROM users WHERE userID = '$user_id'";
$userResult = executeQuery($selectQuery);
$user = mysqli_fetch_assoc($userResult);

//<--Back to login page if not logged in-->
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
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
                    <img src="path_to_your_image_folder/<?php echo $user['backgroundImage']; ?>" alt="Background Picture">
                </div>
                <div class="profile-pic1" style="margin-top: -85px;">
                    <!-- Dynamically display profile image -->
                    <img src="path_to_your_image_folder/<?php echo $user['profileImage']; ?>" alt="Profile Picture"
                        style="width: 150px; border-radius: 50%;">
                </div>

                <div class="prof-edit-delete pt-8 text-end">
                    <div class="dropdown" style="width: 100%;">
                        <button class="btn btn-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false"
                            style="border-radius: 20px; font-family: 'Helvetica Rounded'; border-color: #FFFF; background-color: #FFFF; color: #808080;">
                            <span class="ellipsis">...</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#editProfileModal">Edit Profile</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#deleteProfileModal">Delete Account</a></li>
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
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
            aria-hidden="true">
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
                                <input type="text" class="form-control" name="userName" value="<?php echo htmlspecialchars($_SESSION['userName']); ?>" placeholder="Enter new username">
                            </div>
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="firstName" value="<?php echo htmlspecialchars($_SESSION['firstName']); ?>" placeholder="Enter full name">
                            </div>
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="lastName" value="<?php echo htmlspecialchars($_SESSION['lastName']); ?>" placeholder="Enter last name">
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

    <!-- Delete Profile Modal -->
    <div class="modal fade" id="deleteProfileModal" tabindex="-1" aria-labelledby="deleteProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: #f8f9fa;">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProfileModalLabel">Delete Account</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-secondary"
                        data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteProfileModal2" data-bs-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteProfileModal2" tabindex="-1" aria-labelledby="deleteProfileModal2Label"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: #f8f9fa;">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProfileModal2Label">Are You Sure?</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>This is your final chance to cancel. Do you want to proceed?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn btn-primary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn btn-primary btn-danger">Yes, Delete
                        Permanently</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Post -->
    <script src="../assets/js/post.js"></script>
    <!-- JS Left column -->
    <script src="../assets/js/leftcolumn.js"></script>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
