<?php
// Disable caching to ensure a new file is generated each time
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Generate a random string
function generateRandomString($length = 1000) { // Aim for approx 1KB of content
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$randomContent = generateRandomString();
$filename_txt = "random_string_" . uniqid() . ".txt"; // Unique temporary file name for the text
$filename_zip = "sandbox_test_" . uniqid() . ".zip"; // Unique temporary file name for the zip

// Get the current working directory for full paths
$current_dir = __DIR__;
$full_path_txt = $current_dir . '/' . $filename_txt;
$full_path_zip = $current_dir . '/' . $filename_zip;

// 1. Save the random string to a temporary text file
if (file_put_contents($full_path_txt, $randomContent) === FALSE) {
    // In a production environment, you would log this error instead of dying
    error_log("Failed to write temporary text file: " . $full_path_txt);
    http_response_code(500); // Send a 500 Internal Server Error status
    exit("Server error: Could not generate file.");
}

// 2. Create a new Zip Archive
$zip = new ZipArchive();
$zip_open_result = $zip->open($full_path_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

if ($zip_open_result === TRUE) {
    // Add the text file to the zip archive.
    if (!$zip->addFile($full_path_txt, 'random_data.txt')) {
        $zip->close();
        unlink($full_path_txt);
        // In a production environment, you would log this error instead of dying
        error_log("Failed to add file to zip archive. Path: " . $full_path_txt . " Zip Status: " . $zip->getStatusString());
        http_response_code(500);
        exit("Server error: Could not complete zip operation.");
    }

    $zip->close(); // IMPORTANT: Close the zip archive to finalize it.

    // Check if the zip file was actually created and has content after closing
    if (!file_exists($full_path_zip) || filesize($full_path_zip) === 0) {
        unlink($full_path_txt);
        // In a production environment, you would log this error instead of dying
        error_log("Zip file was not created or is empty after closing: " . $full_path_zip);
        http_response_code(500);
        exit("Server error: Generated zip file is empty.");
    }

    // 3. Set headers for download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . basename($full_path_zip) . '"');
    header('Content-Length: ' . filesize($full_path_zip));

    // 4. Output the zip file
    readfile($full_path_zip);

    // 5. Delete the temporary files
    unlink($full_path_txt);
    unlink($full_path_zip);
    exit; // Ensure no other output is sent
} else {
    // In a production environment, you would log this error instead of dying
    $error_message = "Failed to open zip file for creation. Error Code: " . $zip_open_result . ". System error: " . $zip->getStatusString();
    error_log($error_message);
    http_response_code(500);
    exit("Server error: Unable to create zip file.");

    // Clean up text file even if zip creation fails
    if (file_exists($full_path_txt)) {
        unlink($full_path_txt);
    }
}
?>
