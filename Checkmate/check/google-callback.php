<?php
session_start();
require 'config.php';

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);
        $oauth = new Google\Service\Oauth2($client);
        $google_account = $oauth->userinfo->get();

        $first_name = $google_account->givenName;
        $last_name = $google_account->familyName;
        $email = $google_account->email;

        $stmt = $conn->prepare("SELECT * FROM user WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // existing user, daretso sa dashboard.php
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['user_firstname'];
            $_SESSION['user_email'] = $user['user_email'];

            header('Location: dashboard.php');
            exit();
        } else {
            // daretso sa set-password.php
            $stmt = $conn->prepare("INSERT INTO user (user_firstname, user_lastname, user_email, oauth_provider) VALUES (?, ?, ?, 'Google')");
            $stmt->bind_param("sss", $first_name, $last_name, $email);
            $stmt->execute();
            $user_id = $stmt->insert_id;

            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $first_name;
            $_SESSION['user_email'] = $email;

            header('Location: set-password.php');
            exit();
        }
    }
}
?>
