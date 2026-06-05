<?php
require "/var/www/html/wp-load.php";

// Delete duplicate old posts that match new imports
$delete_ids = [1, 98, 100]; // Hello world, old 香港愛情故事, old 鼎家喜筷

foreach ($delete_ids as $id) {
    $result = wp_delete_post($id, true); // true = force delete, bypass trash
    if ($result) {
        echo "Deleted post ID $id\n";
    } else {
        echo "Failed to delete ID $id\n";
    }
}

echo "Done\n";
