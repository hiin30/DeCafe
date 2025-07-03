<?php
session_start();

// Function to load environment variables from .env file
function loadEnvVariables() {
    $envFile = dirname(__DIR__) . '/.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

// Load environment variables
loadEnvVariables();

// Check if the Google API Client library is installed
if (!file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    die('Google API Client library not found. Please install it using Composer.');
}

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Database connection
include "../proses/connect.php";

// Check if the code parameter exists
if (!isset($_GET['code'])) {
    die('No authorization code provided');
}

// Create Google client - using the appropriate class name based on your installation
$client = new \Google\Client();

$client->setAuthConfig(dirname(__DIR__) . '/google_client_secret.json');
$client->setRedirectUri('http://127.0.0.1:80/decafe/proses/proses_google_oauth_callback.php');
$client->addScope('email');
$client->addScope('profile');

try {
    // Exchange the authorization code for an access token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Get user info - using the appropriate class name based on your installation
    $oauth2 = new \Google\Service\Oauth2($client);
    
    $userInfo = $oauth2->userinfo->get();

    // Use the user information for authentication
    $email = $userInfo->getEmail();
    $name = $userInfo->getName();
    $picture = $userInfo->getPicture();

    // Check if user exists in database
    $checkUser = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$email'");
    
    if (mysqli_num_rows($checkUser) > 0) {
        // User exists, fetch user data
        $userData = mysqli_fetch_assoc($checkUser);
        
        // Set session variables
        $_SESSION['username_decafe'] = $userData['username'];
        $_SESSION['level'] = $userData['level'];
        $_SESSION['id_decafe'] = $userData['id'];
        $_SESSION['name_decafe'] = $userData['nama']; // Use name from database
        $_SESSION['profile_picture'] = $picture;
        $_SESSION['login_method'] = 'google';
    } else {
        // User doesn't exist, register new user
        // Default level (adjust based on your system)
        $defaultLevel = 2; // Assuming 2 is for regular users
        $status = 1; // Active status
        $isdeleted = 0; // Not deleted
        $currentDateTime = date('Y-m-d H:i:s');
        
        // Insert new user with password as NULL
        $insertUser = mysqli_query($conn, 
            "INSERT INTO tb_user (nama, username, password, level, status, isdeleted, createdby, createddate) 
             VALUES ('$name', '$email', NULL, $defaultLevel, $status, $isdeleted, 'Google OAuth', '$currentDateTime')"
        );

        if (!$insertUser) {
            throw new Exception("Failed to register user: " . mysqli_error($conn));
        }
        
        // Get the new user ID
        $newUserId = mysqli_insert_id($conn);
        
        // Set session variables for the new user
        $_SESSION['username_decafe'] = $email;
        $_SESSION['level'] = $defaultLevel;
        $_SESSION['id_decafe'] = $newUserId;
        $_SESSION['name_decafe'] = $name;
        $_SESSION['profile_picture'] = $picture;
        $_SESSION['login_method'] = 'google';
    }

    // Redirect to the home page
    header('Location: ../home');
    exit();
} catch (Exception $e) {
    // Handle authentication error
    echo "Authentication error: " . $e->getMessage();
    echo "<br><a href='../login.php'>Back to login</a>";
} 