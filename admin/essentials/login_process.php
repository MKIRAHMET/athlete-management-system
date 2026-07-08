<?php
session_start();
$funnyMessages = array(
    "Σφάλμα! Ο κωδικός σας είναι ασθενέστερος από μια μονοστρώτη χαρτομάντιλα. Ακόμη κι ο Αμιράν, που έχει δυνατό κωδικό, θα το έβρισκε αυτό περίεργο!",
    "Κάτι πήγε στραβά! Η εισαγωγή σας είναι τόσο μπερδεμένη όσο η προσπάθεια μιας γάτας να λύσει ένα Rubik's Cube. Ο Μόχας ξέρει να δημιουργεί ισχυρούς κωδικούς!",
    "Λάθος! Ο κωδικός σας είναι πιο ακατάστατος από έναν μπερδεμένο κουβά νήματος. Ο Αμιράν είναι επικεφαλής στην ασφάλεια δεδομένων και ξέρει πώς να διατηρεί ισχυρούς κωδικούς!",
    "Προσοχή! Η καταχώρησή σας είναι πιο ακατάστατη από το ντουλάπι ενός σκίουρου. Ο Μόχας θα το βρήκε αστείο να δει τέτοιου είδους κωδικούς!",
    "Προσοχή Δάσκαλε Στέφανε! Οι κωδικοί πρέπει να είναι σαν εσάς στην τάξη: πάντα προσεκτικοί και δύσκολοι να καταλάβει κάποιος!",
    "Δάσκαλε Στέφανε, ας μην γράφουμε κωδικούς σαν τις παραλείψεις στα τεστ! Πάντα να είναι προσεκτικά και χωρίς λάθη!",
    "Έλα Δάσκαλε Στέφανε, πρέπει να προσέχετε περισσότερο! Οι κωδικοί πρέπει να είναι σαν τις οδηγίες σας στους μαθητές: σαφείς και ασφαλείς!",
    "Δάσκαλε Στέφανε, οι κωδικοί είναι σαν τα μαθήματά σας: όσο πιο προσεκτικά τα φροντίζετε, τόσο πιο ασφαλείς γίνονται!",
    "Δάσκαλε Στέφανε, προσοχή! Οι κωδικοί σας είναι σαν τα μαθήματα: αν δεν είναι διαβασμένοι καλά, θα έχετε πρόβλημα στην εξέταση της ασφάλειας!",
    "Προσοχή Δάσκαλε Στέφανε! Οι κωδικοί πρέπει να είναι σαν τις συμβουλές σας στους μαθητές: πάντα ασφαλείς και προσεκτικοί!",
);

$randomMessage = $funnyMessages[array_rand($funnyMessages)];
include '../db.php';


$funnyMessages = array(
    // Your funny messages here...
);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ensure database connection ($conn) is properly established

    // Fetch the hashed password from the database based on the username
    $query = "SELECT * FROM admins WHERE username=?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param('s', $username); // 's' indicates a string parameter
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Password verified, proceed with login
                $_SESSION["admin_logged_in"] = true;
                header("Location: ../admin.php"); // Redirect to admin panel after successful login
                exit();
            } else {
                // Invalid password
                $funnyMessage = $randomMessage . " ΠΡΟΣΕΧΕ ΛΙΓΟ ΠΕΡΙΣΣΟΤΕΡΟ ΤΟ ΚΩΔΙΚΟ";
                header("Location: ../login.php?msg=" . urlencode($funnyMessage)); // Redirect back to login page with message
                exit();
            }
        } else {
            // Username not found
            $funnyMessage = $randomMessage . " ΣΙΓΟΥΡΑ ΕΓΡΑΨΕΣ ΣΩΣΤΑ ΤΟ ΟΝΟΜΑ?";
            header("Location: ../login.php?msg=" . urlencode($funnyMessage)); // Redirect back to login page with message
            exit();
        }
    } else {
        // Error in prepared statement
        $funnyMessage = "An error occurred. Please try again.";
        header("Location: ../login.php?msg=" . urlencode($funnyMessage)); // Redirect back to login page with message
        exit();
    }
}
?>



