<?php

include "init.php";
$db = new DB();

if (isset($_GET['email']) && isset($_GET['reset_token'])) {
    $email = $_GET['email'];
    $reset_token = $_GET['reset_token'];
}

if (isset($_POST['reset'])) {
    // Hat mit line 13 und 14 ein Problem
    $email = $_GET['email'];
    $reset_token = $_GET['reset_token'];
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($new_password) && !empty($confirm_password)) {
        if ($new_password === $confirm_password) {
            $query = "UPDATE users SET password = ?, reset_token = null WHERE email = ? AND reset_token = ?";
            $stmt = $db->con->prepare($query);
            if ($stmt->execute([hash("sha256", $_POST['new_password']), $email, $reset_token])) {
                echo "Passwort erfolgreich geändert!";
            } else {
                echo "Fehler beim Aktualisieren des Passworts";
            }
        } else {
            echo "Die Passwörter stimmen nicht überein";
        }
    } else {
        echo "Bitte füllen Sie alle Felder aus";
    }

}

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort ändern</title>
    <link rel="stylesheet" href="styles_2/style_2.css">
</head>

<body>
    <?php include "header.php" ?><br>
    <h1>Passwort ändern</h1>
    <hr>
    <div id="box">
        <form method="post">
            <?php
        if (isset($errors) && count($errors) > 0) {
            // Wenn es Fehler gibt, werden die Elemente des Arrays $errors als $error ausgegeben
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        }
        ?>
            <input id="text" type="password" name="new_password" placeholder="Neues Passwort"><br>

            <input id="text" type="password" name="confirm_password" placeholder="Neues Passwort bestätigen"><br>



            <input id="button" type="submit" value="Bestätigen" name="reset">
        </form>
    </div>
</body>

</html>