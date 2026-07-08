<?php

include '../db.php'; // Include your database connection script
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Redirect back to the login page if not logged in
    header("Location: login.php");
    exit();
}


$funnyMessages = array(
    "UMC",
   
     );


// Function to get a random message
function getRandomFunnyMessage($messages) {
    return $messages[array_rand($messages)];
}

// Get a random message
$randomMessage = getRandomFunnyMessage($funnyMessages);



// Fuction to handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image'])) {
    $targetDirectory = "../../uploads/";
    $targetFile = $targetDirectory . basename($_FILES['image']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check !== false) {
        echo "ΑΥΤΗ ΕΙΝΑΙ ΜΙΑ ΦΩΤΟΓΡΑΦΙΑ- " . $check['mime'] . ".";
        $uploadOk = 1;
    } else {
        echo "ΑΥΤΟ ΔΕΝ ΕΙΝΑΙ ΦΩΤΟΓΡΑΦΙΑ";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "ΕΧΕΙΣ ΞΑΝΑΑΝΕΒΑΣΕΙ ΑΥΤΗ ΤΗΝ ΦΩΤΟΓΡΑΦΙΑ";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES['image']['size'] > 500000) {
        echo "ΠΟΛΥ ΜΕΓΑΛΗ ΦΩΤΟΓΡΑΦΕΙΑ";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "ΜΟΝΟ JPG, JPEG, PNG & GIF ΔΗΛΑΔΗ ΑΡΧΕΙΑ ΦΩΤΟΓΡΑΦΙΑΣ";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "ΑΠΕΤΥΧΕ Η ΠΡΟΣΠΑΘΕΙΑ. ΠΡΟΣΠΑΘΗΣΕ ΞΑΝΑ ΑΝ ΔΕΝ ΚΑΤΑΦΕΡΝΕΙΣ ΕΝΗΜΕΡΩΣΕ ΤΟΝ ΜΟΧΑ";
    } else {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            echo "The file " . basename($_FILES['image']['name']) . " has been uploaded.";

            // Insert uploaded image details into the database
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';

            $sql = "INSERT INTO images (image_path, title, description) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $targetFile, $title, $description);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "ΤΟ ΑΡΧΕΙΟ ΑΝΕΒΗΚΕ ΣΤΟ SERVER ΑΛΛΑ ΔΕΝ ΜΠΟΡΕΣΑ ΝΑ ΤΟ ΒΑΛΩ ΣΤΗΝ ΒΑΣΗ ΔΕΔΟΜΕΝΩΝ.ΕΝΗΜΕΡΩΣΕ ΤΟΝ ΜΟΧΑ.";
        }
    }
}

// Set the number of images to display per page
$images_per_page = 10;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the offset for pagination
$offset = ($current_page - 1) * $images_per_page;

// Fetch image data from the database
$sql = "SELECT * FROM images";

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
$sql .= " LIMIT $offset, $images_per_page";

$result = $conn->query($sql);

// Fetch total count of images for pagination
$total_count_sql = "SELECT COUNT(*) AS total FROM images";
$total_result = $conn->query($total_count_sql);
$total_row = $total_result->fetch_assoc();
$total_images = $total_row['total'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>ΔΙΑΧΕΙΡΗΣΗ ΦΩΤΟΓΡΑΦΕΙΩΝ</title>

    <link rel="stylesheet" href="../essentials/admin.css">

</head>
<body>
<body>

<?php include '../essentials/adminmenu.php'; ?>
<div style="margin-left: 250px; padding: 20px;">
   <p><?php echo $randomMessage; ?></p>
    <h2>ΑΝΕΒΑΣΕ ΦΩΤΟΓΡΑΦΙΑ</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <br><br>
        ΤΙΤΛΟΣ: <input type="text" name="title">
        <br><br>
        ΠΕΡΙΓΡΑΦΗ: <textarea name="description"></textarea>
        <br><br>
        <input type="submit" value="Upload Image" name="submit">
    </form>
</body>
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
<div class="container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) { 
            // Adjust the path according to your directory structure
            $imagePath = '../../' . $row['image_path']; // Prepending '../' to go one directory above
            ?>
            <div class="image">
                <img src="<?php echo $imagePath; ?>" class="image" alt="Image">
                <div class="image-actions">
                    <form action="delete_image.php" method="GET">
                        <input type="hidden" name="filename" value="<?php echo $row['image_path']; ?>">
                        <button type="submit">ΔΙΑΓΡΑΦΗ</button>
                    </form>
                    <button onclick="editImage('<?php echo $row['image_path']; ?>')">ΕΠΕΞΕΡΓΑΣΙΑ</button>
                </div>
            </div>
        <?php
        }
    } else {
        echo "ΔΕΝ ΒΡΕΘΗΚΑΝ ΦΩΤΟΓΡΑΦΙΕΣ. ΕΦΤΑΣΕΣ ΣΤΟ ΤΕΛΟΣ ΔΑΣΚΑΛΕ"; // Placeholder message if no images are available
    }
    ?>
</div>

<!-- Pagination -->
<?php if ($total_images > $images_per_page) : ?>
                <div class="pagination">
                    <?php if ($current_page > 1) : ?>
                        <a href="?page=<?php echo ($current_page - 1); ?>&sort=<?php echo $sort_by; ?>">Προηγούμενη</a>
                    <?php endif; ?>

                    <?php if ($total_images > ($offset + $images_per_page)) : ?>
                        <a href="?page=<?php echo ($current_page + 1); ?>&sort=<?php echo $sort_by; ?>">Επόμενη</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>


<!-- Script for edit functionality -->
<script>
    function editImage(filename) {
        window.location.href = `edit_image.php?filename=${filename}`;
    }
</script>
</body>
</html>


