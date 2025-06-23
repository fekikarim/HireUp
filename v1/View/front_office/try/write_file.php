<?php

function overwriteDataFile($filePath, $newContent) {

  // Open the file for writing with error handling
  if (!$handle = fopen($filePath, 'w')) {
    return false;  // Indicate error
  }

  // Write the new content to the file
  if (!fwrite($handle, $newContent)) {
    fclose($handle);
    return false;  // Indicate error
  }

  // Close the file
  fclose($handle);

  return true;  // Indicate success
}

// write_file.php

// Retrieve data from the request
$data = json_decode(file_get_contents('php://input'), true);
$filePath = __DIR__ . '/data.hiry';
$newContent = $data['newContent'];

// Implement your file manipulation logic here
$success = overwriteDataFile($filePath, $newContent);

if ($success) {
  echo "File overwritten successfully!";
} else {
  echo "Error overwriting file!";
}

// Your existing overwriteDataFile function can be included here
// ...

// Remember to handle any security considerations (e.g., validate input, sanitize data).
?>
