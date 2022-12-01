<?php
$mysqli = new mysqli("63.142.255.67","parveen","digifish","mobileca_cafe4u");
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
// else
// {
// 	echo "database connected Successfully";
// }
?>
