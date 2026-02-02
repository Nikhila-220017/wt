<?php
// Global variable for database connection
$servername = "localhost";
$username   = "root";       // replace with your DB username
$password   = "";           // replace with your DB password
$dbname     = "tables";

// Connect to database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// If connection fails, stop execution
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Function to handle registration
function registerstudent($conn) {
    // Local variables inside function
    $username = $_POST['username'];   // string
    $email    = $_POST['email'];      // string
    $password = $_POST['password'];   // string

    // Example integer (auto-increment user_id in DB, but here we simulate)
    $tables = rand(1, 1000);         // integer

    // Static variable to count registrations in one request
    static $registrationCount = 0;
    $registrationCount++;

    // SQL query
    $sql = "INSERT INTO student(id, username, email, password) 
            VALUES ('$tables', '$username', '$email', '$password')";

    // Boolean flag for success/failure
    $success = false; // boolean

    if (mysqli_query($conn, $sql)) {
        $success = true;
        echo "Registration successful!<br>";
        echo "Total registrations in this request: " . $registrationCount . "<br>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    return $success;
}

// Call the function
registerstudent($conn);

// Close connection
mysqli_close($conn);
?>