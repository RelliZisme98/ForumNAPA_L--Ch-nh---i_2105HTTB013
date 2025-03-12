<?php
require_once 'vendor/autoload.php'; // Include Composer autoload

// Use the fully qualified namespace for the classes
// use Google_Client;
use Google\Service\Oauth2 as Google_Service_Oauth2;

session_start();

// Initialize Google_Client
$client = new Google_Client();
$client->setClientId('709891919508-9coee3hf9v08jj7ooeic68iqpk3tkit8.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/forumNAPA/google_login.php'); // Redirect URI set correctly
$client->addScope('email');
$client->addScope('profile');

if (!isset($_GET['code'])) {
    // If no authentication code, redirect user to Google login page
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit();
} else {
    // If an authentication code exists
    try {
        // Fetch access token using the auth code
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        // Check if there was an error fetching the token
        if (isset($token['error'])) {
            throw new Exception('Error fetching the access token: ' . $token['error_description']);
        }

        // Set the access token
        $client->setAccessToken($token);

        // Retrieve user information
        $oauth2 = new Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        // Store user information in session
        $_SESSION['user_id'] = $userInfo->id;
        $_SESSION['user_name'] = $userInfo->name;
        $_SESSION['user_email'] = $userInfo->email;

        // Redirect to the main page after successful login
        header('Location: index.php');
        exit();
    } catch (Exception $e) {
        // Display error message if there was an issue with fetching the token or user info
        echo 'Error: ' . htmlspecialchars($e->getMessage());
    }
}
