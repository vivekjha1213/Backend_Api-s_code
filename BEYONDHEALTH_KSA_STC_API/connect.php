<?php
$link = mysql_connect('63.142.255.67', 'root', '4cafe!@#321');
if (!$link) {
    die('Not connected : ' . mysql_error());
}
$db_selected = mysql_select_db('mobileca_cafe4u', $link);
if (!$db_selected) {
    die ('Can\'t use Database : ' . mysql_error());
}

?>
