<?php
/**
 * Simple import file
 */

$database_host          = "localhost";
$database_name          = "";
$database_table         = "";
$database_user          = "";
$database_pass          = "";
$field_separator        = ",";
$line_separator         = "\n";
$csv_file               = "posta_db.csv";

/**
 * Would you like to add an ampty field at the beginning of these records?
 * This is useful if you have a table with the first field being an auto_increment integer
 * and the csv file does not have such as empty field before the records.
 * Set 1 for yes and 0 for no. ATTENTION: don't set to 1 if you are not sure.
 * This can dump data in the wrong fields if this extra field does not exist in the table
 */
$add_auto = 0;


/**
 * Would you like to save the mysql queries in a file? If yes set $save to 1.
 * Permission on the file should be set to 777. Either upload a sample file through ftp and
 * change the permissions, or execute at the prompt: touch output.sql && chmod 777 output.sql
 */
$save = 1;
$output_file = "import.sql";

if (!file_exists($csvfile)) {
    echo "File not found. Make sure you specified the correct path.\n";
    exit;
}

$file = fopen($csvfile,"r");

if (!$file) {
    echo "Error opening data file.\n";
    exit;
}

$size = filesize($csvfile);

if (!$size) {
    echo "File is empty.\n";
    exit;
}

$csvcontent = fread($file,$size);

fclose($file);

/**
 * Create connection
 */
$con = new mysqli($database_host, $database_user, $database_pass);

/**
 * Check connection
 */
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$lines = 0;
$queries = "";
$line_array = array();

foreach(explode($line_separator, $csvcontent) as $line) {

    $lines++;

    $line = trim($line," \t");

    $line = str_replace("\r","",$line);

    /************************************
    This line escapes the special character. remove it if entries are already escaped in the csv file
     ************************************/
    $line = str_replace("'","\'",$line);
    /*************************************/

    $line_array = explode($field_separator,$line);
    $line_mysql = implode("','", $line_array);

    if($add_auto)
        $query = "insert into $database_table values('','$line_mysql');";
    else
        $query = "insert into $database_table values('$line_mysql');";

    $queries .= $query . "\n";
    mysqli_query($con, $query);

    echo "<pre>";
        var_dump($query);
    echo "<pre>";
}
mysqli_close($con);

if ($save) {

    if (!is_writable($output_file)) {
        echo "File is not writable, check permissions.\n";
    }

    else {
        $file2 = fopen($output_file,"w");

        if(!$file2) {
            echo "Error writing to the output file.\n";
        }
        else {
            fwrite($file2,$queries);
            fclose($file2);
        }
    }

}

echo "Found a total of $lines records in this csv file.\n";

