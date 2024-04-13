<?php
// Database connection parameters
$servername = "localhost"; // Change this to your database server name
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "symposium_db"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if participant is already registered
    $stmt_check = $conn->prepare("SELECT * FROM participants WHERE mobile_number = ?");
    if (!$stmt_check) {
        die("Error: " . $conn->error);
    }
    $stmt_check->bind_param("s", $mobile_number_check);
    $mobile_number_check = $_POST['mobile-number'];
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // Participant is already registered, set registration message
        echo "<script>alert('You are already registered!');</script>";
    } else {
        // Prepare and bind parameters
        $stmt = $conn->prepare("INSERT INTO participants (name, college_name, mobile_number) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Error: " . $conn->error);
        }
        $stmt->bind_param("sss", $name, $college_name, $mobile_number);

        // Set parameters and execute
        $name = $_POST['participant-name'];
        $college_name = $_POST['college-name'];
        $mobile_number = $_POST['mobile-number'];

        if ($stmt->execute()) {
            // Registration successful, set session variable
            session_start();
            $_SESSION['registration_message'] = 'Registration successful. Thank you!';
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }

    // Close statement and connection for check
    $stmt_check->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crack The Tag</title>
    <!-- Your CSS and other meta tags -->
</head>
<body>
    <!-- Your HTML content -->
    <!-- Rest of your HTML content -->

    <!-- JavaScript code for redirection and displaying message -->
    <script>
        // Function to redirect to qustion.html after a delay
        function redirectToQuestionPage() {
            setTimeout(function() {
                window.location.href = 'question.html';
            }, 1500); // Adjust the delay as needed (in milliseconds)
        }

        // Check if registration message is set
        <?php
        if(isset($_SESSION['registration_message'])) {
            // Display registration message using JavaScript alert
            echo "alert('" . $_SESSION['registration_message'] . "');";
            // Unset the session variable after displaying the message
            unset($_SESSION['registration_message']);
            // Redirect to question page after displaying the message
            echo "redirectToQuestionPage();";
        }
        ?>
    </script>
</body>
</html>
