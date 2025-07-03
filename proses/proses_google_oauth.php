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

// Load client configuration from JSON file
$clientSecret = json_decode(file_get_contents(dirname(__DIR__) . '/google_client_secret.json'), true);

// Create Google client - using the appropriate class name based on your installation
$client = new \Google\Client();

$client->setAuthConfig(dirname(__DIR__) . '/google_client_secret.json');
$client->addScope('email');
$client->addScope('profile');

// The callback URL must match the one in google_client_secret.json
$client->setRedirectUri('http://127.0.0.1:80/decafe/proses/proses_google_oauth_callback.php');

// Generate the URL for Google OAuth login
$authUrl = $client->createAuthUrl();

// Redirect to Google's OAuth page
header('Location: ' . $authUrl);
exit(); 