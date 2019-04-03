<?php
if(PHP_SAPI !== 'cli' || isset($_SERVER['HTTP_USER_AGENT'])):
    die('Please run only from cli');
endif;

/**
 * Include Dotenv library to pull config options from .env file.
 */
if(file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::create(__DIR__, '/env/.env');
    $dotenv->load();

} else {
    die('Please install via composer and setup a new .env file!');
}

try {
    $conn = new mysqli(getenv('DB_HOST'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));
    mysqli_set_charset($conn,"utf8");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $db = getenv('DB_DATABASE');
    echo "Connected DB: $db\n\n";

    $csv = 'zip.csv';

    function csvToArray($filename='', $delimiter=',')
    {
        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;
        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                if(!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }

    $data = csvToArray($csv);
    foreach($data as $d) {
        $zip = $d['zip'];
        $city = $d['city'];
        $district = $d['district'];

        $sql = "INSERT INTO zip(zip, city, district)
                VALUES ('$zip', '$city', '$district')";

        if($conn->query($sql) === TRUE) {
            echo "Zip inserted: $zip --------–>\n";
        } else {
            echo "Unable to save, try again --------–>\n";
        }
    }

    $conn->close();

} catch (Exception $e) {
    echo 'Script error: ' . $e->getMessage();
    exit(1);
}