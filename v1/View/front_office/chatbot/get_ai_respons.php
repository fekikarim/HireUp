<?php

$promt = "";

$data = json_decode(file_get_contents('php://input'), true);
$newContent = $data['newContent'];
$newContentImg = $data['user_img'];


$promt = $newContent;
$imgData = $newContentImg;

// Function to execute the Python script with parameters and return its output
function executePythonScript($scriptPath) {
    // Build the command to execute the Python script with parameters
    $command = 'python ' . $scriptPath;
    
    // Execute the command and capture the output
    $output = shell_exec($command);
    
    // Return the output
    return $output;
}

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

if ($promt != "") {

// Path to the Python script
$pythonScriptPath = './ai_mod.py';

// Parameters to pass to the Python script
$params = $promt; // Replace 'parameter_value' with the actual parameter value

overwriteDataFile(__DIR__ . '/data.hiry', $params);
if ($imgData != null) {
  overwriteDataFile(__DIR__ . '/image_data.hiry', $imgData);
}

// Execute the Python script with parameters and get its output
$scriptOutput = executePythonScript($pythonScriptPath);

// Send the output as the response
echo $scriptOutput;

} else {
    echo 'no data';
}

?>