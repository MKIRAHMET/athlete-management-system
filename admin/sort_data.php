<?php
session_start(); // Start the session

include('../db.php');

// Assuming you have a database connection here

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['section']) && isset($_GET['sortBy'])) {
    $section = $_GET['section'];
    $sortBy = $_GET['sortBy'];

    $data = []; // Array to store fetched data

    switch ($section) {
        case 'articles':
            // Fetch articles data based on sort criteria (date or title)
            $articles_query = "SELECT title, publication_date FROM articles ";
            if ($sortBy === 'date') {
                $articles_query .= "ORDER BY publication_date DESC";
            } elseif ($sortBy === 'title') {
                $articles_query .= "ORDER BY title ASC";
            }
            break;

        case 'videos':
          // Fetch videos data
            $videos_query = "SELECT video_path, created_at FROM videos";
            if ($sortBy === 'created_at') {
                    $articles_query .= "ORDER BY created_ate DESC";
             } elseif ($sortBy === 'title') {
           $videos_query .= "ORDER BY title ASC";
              }
              break;

        case 'images':
            // Fetch images data based on sort criteria (date or title)
            $videos_query = "SELECT image_path, created_at FROM images";
            if ($sortBy === 'created_at') {
                    $articles_query .= "ORDER BY created_ate DESC";
             } elseif ($sortBy === 'title') {
           $images_query .= "ORDER BY title ASC";
             }
            break;

        case 'announcements':
 // Fetch announcements data
    $announcements_query = "SELECT title, publication_date FROM announcements";
    if ($sortBy === 'date') {
        $articles_query .= "ORDER BY publication_date DESC";
    } elseif ($sortBy === 'title') {
        $announcements_query .= "ORDER BY title ASC";
    }

            break;

        default:
            $data = ['error' => 'Invalid section'];
            break;
    }

    // Convert fetched data to JSON and send it back as the response
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>

