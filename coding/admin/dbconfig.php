<?php
//connection to link to the server with database and php
$host = "localhost"; /* Host name */
$user = "root"; /* User */
$password = ""; /* Password */
$dbname = "fyp1web"; /* Database name */

$conn = mysqli_connect($host, $user, $password,$dbname);
// Check connection
if (!$conn) {
 die("Connection failed: " . mysqli_connect_error());
}

?>
