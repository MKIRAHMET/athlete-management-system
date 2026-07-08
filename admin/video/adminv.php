<?php
include '../db.php'; // Include your database connection script
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Redirect back to the login page if not logged in
    header("Location: ../login.php");
    exit();
}

$funnyMessages = array(
    "Καλώς ήρθες! Εδώ ακόμα και τα sharks γελάνε με τους κωδικούς μας!",
    "Καλώς ήρθες! Εδώ τα passwords είναι σαν τα καλάμια, πρέπει να είναι πάντα στριμωγμένα και έτοιμα για ψάρεμα!",
    "Καλώς ήρθες! Η ασφάλεια είναι σαν τα καλά ακουστικά κατά τη διάρκεια μιας ψαριάς... ποτέ δεν είναι αρκετά!",
    "Καλώς ήρθες! Και τα passwords είναι σαν τα καλάμια του ψαρέματος... όσο πιο δυνατά, τόσο καλύτερα!",
    "Καλώς ήρθες! Εδώ και τα passwords είναι σαν τις βουτιές στο βυθό... πάντα γεμάτα εκπλήξεις!",
    "Καλώς ήρθες! Στον κόσμο των ψαρεμάτων, ακόμα και οι κωδικοί μας παίζουν ψάρεμα με τους hackers!",
    "Καλώς ήρθες! Εδώ τα passwords είναι σαν τα δίχτυα... πρέπει να είναι πάντα τεντωμένα!",
    "Καλώς ήρθες! Όπως τα καλάμια, έτσι και οι κωδικοί πρέπει να είναι πάντα έτοιμοι για δράση!",
    "Καλώς ήρθες! Στη θάλασσα των κωδικών, ένας καλός κωδικός είναι σαν ένας καλός ψαράς!",
    "Καλώς ήρθες! Εδώ ακόμα και τα dolphins θα τους ζηλέψουν τους κωδικούς μας!",
    "Καλώς ήρθες! Όπως οι τέχνες πολεμικών τεχνών, έτσι και οι κωδικοί πρέπει να είναι γρήγοροι και ασφαλείς!",
    "Καλώς ήρθες! Εδώ τα passwords είναι σαν τα καλάμια στο kayak... πάντα πρέπει να είναι σταθερά!",
    "Καλώς ήρθες! Στη θάλασσα της ασφάλειας, ένας καλός κωδικός είναι σαν μια καλή περιπέτεια!",
    "Καλώς ήρθες! Εδώ ακόμα και τα αλιεύματα γελάνε με τους κωδικούς μας!",
    "Καλώς ήρθες! Όπως οι τακτικές των πολεμικών τεχνών, έτσι και οι κωδικοί πρέπει να είναι οργανωμένοι!",
    "Καλώς ήρθες! Στον κόσμο του spearfishing, ακόμα και οι κωδικοί είναι σαν καλά spearheads!",
    "Καλώς ήρθες! Εδώ τα passwords είναι σαν τα καλά ψαροντούφεκα... πρέπει πάντα να είναι προσεκτικά στη στόχευση!",
    "Καλώς ήρθες! Όπως οι προετοιμασίες για ένα ματς, έτσι και οι κωδικοί πρέπει να είναι πάντα σωστά τακτοποιημένοι!",
    "Καλώς ήρθες! Στη θάλασσα των passwords, ένας καλός κωδικός είναι σαν μια καλή παρέα στο kayak!",
    "Τόσο κουρασμένος που αν ήμουν ένα τατουάζ, θα ήμουν ένας καναπές.",
    "Ο καφές είναι η μόνη θεολογία που αποδεικνύει ότι ο Θεός μας αγαπά.",
    "Πριν από τον καφέ, είμαι σαν ένα άλογο χωρίς καβαλάρη.",
    "Στην Ελλάδα, ακόμα και η ρύθμιση είναι μια περιπέτεια.",
    "Η ζωή είναι σαν το Brazilian Jiu-Jitsu, πρέπει να παλεύεις για να επιβιώσεις.",
    "Όταν είμαι κουρασμένος, είμαι πιο απρόσεκτος από ένα παιδί με νέο παιχνίδι.",
    "Η αναρχία στην Ελλάδα είναι σαν ένα χορό ζεϊμπέκικο με περισσότερες αγκαλιές.",
    "Η ζωή είναι σαν ένα οκτάγωνο κλουβί - συχνά αντιμετωπίζεις πολλούς δύσκολους αντιπάλους.",
    // Add more funny messages as needed
);

// Function to get a random message
function getRandomFunnyMessage($messages) {
    return $messages[array_rand($messages)];
}

// Function to handle video upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['video'])) {
    $targetDirectory = "uploads/videos/";
    $targetFile = $targetDirectory . basename($_FILES['video']['name']);
    $uploadOk = 1;
    $videoFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "ΑΥΤΟ ΤΟ ΒΙΝΤΕΟ ΥΠΑΡΧΕΙ ΗΔΗ";
        $uploadOk = 0;
    }

    // Check video file size (you might want to adjust the size)
    if ($_FILES['video']['size'] > 1000000000) { // For example, limiting to 100 MB
        echo "ΠΟΛΥ ΜΕΓΑΛΟ ΑΡΧΕΙΟ";
        $uploadOk = 0;
    }

    // Allow certain video file formats (you can add more if needed)
    if ($videoFileType != "mp4" && $videoFileType != "avi" && $videoFileType != "mov" && $videoFileType != "wmv") {
        echo "ΜΟΝΟ MP4, AVI, MOV, and WMV. ΔΗΛΑΔΗ ΒΙΝΤΕΟ";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "ΑΠΕΤΥΧΕ Η ΠΡΟΣΠΑΘΕΙΑ. ΠΡΟΣΠΑΘΗΣΕ ΞΑΝΑ ΔΑΣΚΑΛΕ ΑΝ ΔΕΝ ΚΑΤΑΦΕΡΕΙΣ ΠΑΡΕ ΜΕ ΤΗΛΕΦΩΝΟ";
    } else {
        if (move_uploaded_file($_FILES['video']['tmp_name'], $targetFile)) {
            echo "The file " . basename($_FILES['video']['name']) . " has been uploaded.";

            // Insert uploaded video details into the database
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';

            $sql = "INSERT INTO videos (video_path, title, description) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $targetFile, $title, $description);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "ΤΟ ΑΡΧΕΙΟ ΑΝΕΒΗΚΕ ΣΤΟ SERVER ΑΛΛΑ ΔΕΝ ΜΠΟΡΕΣΑ ΝΑ ΤΟ ΒΑΛΩ ΣΤΗΝ ΒΑΣΗ ΔΕΔΟΜΕΝΩΝ.ΕΝΗΜΕΡΩΣΕ ΤΟΝ ΜΟΧΑ.";
        }
    }
}
// Get a random message
$randomMessage = getRandomFunnyMessage($funnyMessages);

$limit = 10; // Number of videos to display at once
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0; // Get offset from URL

// Fetch video data from the database
$sql = "SELECT * FROM videos";

// Apply sorting criteria
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'latest';
switch ($sort_by) {
    case 'oldest':
        $sql .= " ORDER BY created_at ASC";
        break;
    case 'title_asc':
        $sql .= " ORDER BY title ASC";
        break;
    case 'title_desc':
        $sql .= " ORDER BY title DESC";
        break;
    // Add more sorting options as needed
    default:
        $sql .= " ORDER BY created_at DESC"; // Default to latest
        break;
}

// Add LIMIT and OFFSET for pagination
$sql .= " LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

$videosCount = $result->num_rows;
?>


<!DOCTYPE html>
<html>
<head>
    <title>ΔΙΑΧΕΙΡΗΣΗ ΒΙΝΤΕΟ</title>
    <link rel="stylesheet" href="../essentials/admin.css">
</head>
<body>
<?php include '../essentials/adminmenu.php'; ?>
<div style="margin-left: 250px; padding: 20px;">

    <p><?php echo $randomMessage; ?></p>
    <h2>ΑΝΕΒΑΣΕ Video</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="video" required>
        <br><br>
        ΤΙΤΛΟΣ: <input type="text" name="title">
        <br><br>
        ΠΕΡΙΓΡΑΦΗ: <textarea name="description"></textarea>
        <br><br>
        <input type="submit" value="Upload Video" name="submit">
    </form>
    <form action="?page=<?php echo $current_page; ?>" method="GET">
        <label for="sort">ΤΑΞΙΝΟΜΗΣΗ:</label>
        <select id="sort" name="sort">
            <option value="latest">ΝΕΩΤΕΡΑ</option>
            <option value="oldest">ΠΙΟ ΠΑΛΙΑ</option>
            <option value="title_asc">ΤΙΤΛΟΣ(A-Z)</option>
            <option value="title_desc">ΤΙΤΛΟΣ (Z-A)</option>
            <!-- Add more options as needed -->
        </select>
        <input type="submit" value="ΤΑΞΙΝΟΜΗΣΗ">
    </form>
    <!-- Displaying uploaded videos -->
    <div class="container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {  
            // Adjust the path according to your directory structure
            $videoPath = '../../' . $row['video_path']; // Prepending '../' to go one directory above       

            ?>
            <div class="video">
                <video controls>
                    <source src="<?php echo $videoPath; ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="video-actions">
                    <form action="delete_video.php" method="GET">
                        <input type="hidden" name="filename" value="<?php echo $row['video_path']; ?>">
                        <button type="submit">ΔΙΑΓΡΑΦΗ</button>
                    </form>
                    <button onclick="editVideo('<?php echo $row['video_path']; ?>')">ΕΠΕΞΕΡΓΑΣΙΑ</button>
                </div>
            </div>
            <?php
        }
    } else {
        echo "ΔΕΝ ΒΡΕΘΗΚΑΝ ΒΙΝΤΕΟ. ΕΦΤΑΣΕΣ ΣΤΟ ΤΕΛΟΣ ΔΑΣΚΑΛΕ"; // Placeholder message if no videos are available
    }
    ?>
</div>

    <!-- Pagination -->
    <?php if ($videosCount >= $limit) : ?>
            <div class="button-container">
                <a href="?offset=<?php echo $offset + $limit; ?>&sort=<?php echo $sort_by; ?>" class="redirect-button">ΕΠΟΜΕΝΗ ΣΕΛΙΔΑ</a>
            </div>
        <?php endif; ?>

        <?php if ($offset > 0) : ?>
            <div class="button-container">
                <a href="?offset=<?php echo $offset - $limit; ?>&sort=<?php echo $sort_by; ?>" class="redirect-button">ΠΡΟΗΓΟΥΜΕΝΗ ΣΕΛΙΔΑ</a>
            </div>
        <?php endif; ?>

        <!-- Script for edit functionality -->
        <script>
            function editVideo(filename) {
                window.location.href = `edit_video.php?filename=${filename}`;
            }
        </script>
    </div>
</body>
</html>