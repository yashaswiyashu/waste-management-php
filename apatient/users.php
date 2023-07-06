<?php include 'head.php';
include 'aaside.php'; ?>

<?php
$con = mysqli_connect("localhost", "root", "", "gabbbbbage");
error_reporting(E_ALL ^ E_NOTICE);
$username_error = "";
$password_error = "";

// Initialize form values
$fullname_value = "";
$ucat_value = "";
$contact_value = "";
$address_value = "";
$username_value = "";

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fullname = $_POST['fullname'];
    $ucat = $_POST['ucat'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $acstatus = $_POST['acstatus'];
    $date = $_POST['date'];

    $file = $_FILES['image']['name'];
    $filename = $_FILES['image']['tmp_name'];
    $destination = 'contract/';
    $realname = $destination . $file;
    if (move_uploaded_file($filename, $realname)) {
        echo "SUCCESSFULLY ";
    } else {
        echo "upload error";
    }

    // Perform validation
    $validation_passed = true;

    if (empty($username)) {
        $username_error = "Please enter a valid username";
        $validation_passed = false;
    } elseif (strlen($username) < 5) {
        $username_error = "Username should be at least 5 characters";
        $validation_passed = false;
    }

    if (empty($password)) {
        $password_error = "Please enter a valid password";
        $validation_passed = false;
    } elseif (
        strlen($password) > 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[^a-zA-Z0-9]/', $password) ||
        !preg_match('/[0-9]/', $password)
    ) {
        $password_error = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one special character, and one number";
        $validation_passed = false;
    }

    if ($validation_passed) {
        // Validation passed, insert data into the database
        $query = mysqli_query($con, "SELECT * FROM admin WHERE username='$username'");
        if (mysqli_num_rows($query) > 0) {
            echo "<script>alert('The username you entered is already in use. Please enter another username');</script>";
        } else {
            $query = mysqli_query($con, "INSERT INTO admin (username, password, fullname, ucat, address, contact, acstatus, date, image) VALUES ('$username', '$password', '$fullname', '$ucat', '$address', '$contact', '$acstatus', '$date', '$file')");
            if ($query) {
                echo "<script>alert('User registered successfully'); window.location='users1.php';</script>";
                exit; // Prevent the rest of the page from executing
            } else {
                echo "<script>alert('Error occurred');</script>";
            }
        }
    } else {
        // Preserve form values in case of validation errors
        $fullname_value = $fullname;
        $ucat_value = $ucat;
        $contact_value = $contact;
        $address_value = $address;
        $username_value = $username;
    }
}
?>
<div class="page-wrapper">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="btn-list">
                    <a href="users.php?user=<?php echo $_SESSION['username']; ?>">
                        <button type="button" class="btn waves-effect waves-light btn-rounded btn-primary">Add User Accounts</button>
                    </a>
                    <a href="users1.php?user=<?php echo $_SESSION['username']; ?>">
                        <button type="button" class="btn waves-effect waves-light btn-rounded btn-primary">View User Accounts</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"></h4>
                        <form enctype="multipart/form-data" method="post" action="" autocomplete="off">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <input type="text" title="What is the name of this user? " name="fullname" required class="form-control" placeholder="Enter the name of the user you are registering">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>User type</label>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <select name="ucat" class="form-control show-tick" required data-live-search="true">
                                                <option selected value="">---Choose---</option>
                                                <option value="admin">Admin</option>
                                                <option value="collector">Collector</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Contact</label>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <input type="number" name="contact" required class="form-control" placeholder="Enter the contact of the user you are registering">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Address</label>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <input type="text" title="What is the address of this user? " name="address" required class="form-control" placeholder="Enter the address of the user you are registering">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Username</label>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">

                                            <input type="text" title="What is the address of this user? " name="username" required class="form-control" placeholder="Enter the username of the user you are registering">
                                            <?php if (isset($username_error)) { ?>
                                                <span style="color: red;">
                                                    <p><?php echo $username_error ?></p>
                                                </span>
                                            <?php } ?>
                                            <input type="hidden" name="acstatus" required class="form-control" value="active">
                                            <input type="hidden" name="date" required class="form-control" value="<?php echo date('Y-m-d') ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Password</label>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <input type="password" title="What is the address of this user? " name="password" required class="form-control" placeholder="Enter the password of the user you are registering">
                                            <?php if (isset($password_error)) { ?>
                                                <span style="color: red;">
                                                    <p><?php echo $password_error ?></p>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>ID/Passport Copy</label>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <input type="file" name="image" required class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="text-right">
                                        <button type="submit" name="submit" class="btn btn-info">REGISTER USER</button>
                                        <button type="reset" class="btn btn-dark">Reset</button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

       

        <?php include 'footer.php'; ?>