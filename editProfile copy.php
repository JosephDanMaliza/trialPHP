<?php
session_start();
include("connect.php");

$error = "";
$success = "";

// Redirect to login if not logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

// Fetch user details from session
$userID = $_SESSION['userID'];

// Handle form submission
if (isset($_POST['btnUpdate'])) {
    // Sanitize inputs
    $newFirstName = htmlspecialchars(trim($_POST['firstName']), ENT_QUOTES, 'UTF-8');
    $newLastName = htmlspecialchars(trim($_POST['lastName']), ENT_QUOTES, 'UTF-8');
    $newUserName = htmlspecialchars(trim($_POST['userName']), ENT_QUOTES, 'UTF-8');

    // Handle photo uploads
    $profilePhoto = null;
    $backgroundPhoto = null;

    if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] == 0) {
        // Save profile photo
        $profilePhoto = 'uploads/' . basename($_FILES['profilePhoto']['name']);
        move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $profilePhoto);
    }

    if (isset($_FILES['backgroundPhoto']) && $_FILES['backgroundPhoto']['error'] == 0) {
        // Save background photo
        $backgroundPhoto = 'uploads/' . basename($_FILES['backgroundPhoto']['name']);
        move_uploaded_file($_FILES['backgroundPhoto']['tmp_name'], $backgroundPhoto);
    }

    // Validate inputs
    if (empty($newFirstName) || empty($newLastName) || empty($newUserName)) {
        $error = "First Name, Last Name, and Username cannot be empty.";
    } else {
        // Check if username already exists (excluding the current user)
        $checkStmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM users WHERE userName = ? AND userID != ?");
        mysqli_stmt_bind_param($checkStmt, "si", $newUserName, $userID);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_bind_result($checkStmt, $count);
        mysqli_stmt_fetch($checkStmt);
        mysqli_stmt_close($checkStmt);

        if ($count > 0) {
            $error = "Username already exists. Please choose a different username.";
        } else {
            // Update user profile using prepared statements
            $stmt = mysqli_prepare($conn, "UPDATE users SET firstName = ?, lastName = ?, userName = ?, profilePicture = ?, coverPhoto = ? WHERE userID = ?");
            mysqli_stmt_bind_param($stmt, "sssssi", $newFirstName, $newLastName, $newUserName, $profilePhoto, $backgroundPhoto, $userID);

            if (mysqli_stmt_execute($stmt)) {
                // Update session variables
                $_SESSION['firstName'] = $newFirstName;
                $_SESSION['lastName'] = $newLastName;
                $_SESSION['userName'] = $newUserName;

                $success = "Profile updated successfully!";
            } else {
                $error = "Failed to update profile: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NowUKnow | Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: Helvetica, sans-serif;
        }
        .profile-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            width: 100%;
            max-width: 500px;
            margin: auto;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-primary:hover,
        .btn-danger:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="profile-card">
            <h2 class="text-center">Your Profile</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo $firstName; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo $lastName; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $email; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phoneNumber" class="form-label">Phone Number</label>
                    <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" value="<?php echo $phoneNumber; ?>" required>
                </div>
                <button type="submit" name="btnUpdate" class="btn btn-primary w-100 mb-3">Update Profile</button>
            </form>

            <form method="POST">
                <button type="submit" name="btnDelete" class="btn btn-danger w-100">Delete Account</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
