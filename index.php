<!-- 
	basic flow:
	- grab file (test.txt)
		- save in temp folder (/tmp)
	- open file in temp folder in text field html
	- edit
	- overwrite to initial file
 -->

<?php
include ("config.php");

$conn_id = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");

// login with username and password
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// try to download $server_file and save to $local_file
if (ftp_get($conn_id, $local_file, $server_file, FTP_ASCII)) {
    echo "Successfully written to $local_file\n";
}
else {
    echo "There was a problem\n";
}

// check if form has been submitted
if (isset($_POST['text'])) {
    $conn_id = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");

    // login with username and password
    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

    // save content to local
    file_put_contents($local_file, $_POST['text']);
    // upload to ftp
    if (ftp_put($conn_id, $server_file, $local_file, FTP_ASCII)) {
        echo "Successfully uploaded $local_file.";        
    } else { 
        echo "Error uploading $file.";
    }

    // redirect to form again
    header(sprintf('Location: %s', $url));
    printf('<a href="%s">Moved</a>.', htmlspecialchars($url));

    exit();
}

// close the connection
ftp_close($conn_id);

// read the textfile
$text = file_get_contents($local_file);
?>

<form action="" method="post">
<textarea rows="30" cols="30" name="text"><?php echo htmlspecialchars($text) ?></textarea>
<input type="submit"/>
<input type="reset"/>
</form>