<?php

require('global.php');

//If URL parameters were posted, react to them.
$type = htmlspecialchars($_GET["event"]);
if ( !empty($type) ) {

//Connect to database.
$conn = db_connect();

$now = time();

//Get more URL parameters.
$film = htmlspecialchars($_GET["film"]);
$url = htmlspecialchars($_GET["url"]);
$rating = htmlspecialchars($_GET["rating"]);
$backdated = htmlspecialchars($_GET["backdated"]);
$directors = htmlspecialchars($_GET["directors"]);
$release_year = htmlspecialchars($_GET["release_year"]);
$running_time = htmlspecialchars($_GET["running_time"]);
$db_result_message = "";

	switch ($type) {
	    case "new_film_submission":

	    	$sql = "INSERT INTO film (film, imdb_url, rating, timestamp, backdated, directors, release_year, running_time)
	    	VALUES (\"{$film}\",\"{$url}\",{$rating},{$now},\"{$backdated}\",\"{$directors}\",{$release_year},{$running_time})";

	    	if ($conn->query($sql) === TRUE) {

	    	    $db_result_message = "<br><div id=\"db_update_message\">Nice! <b>{$film}</b> was succesfully submitted/updated.</div>";

	    	} else {

	    	    $db_result_message = "<br><div id=\"db_update_message\">Uh oh! An error was encountered while trying to submit/update <b>{$film}.</b></div>";

	    	}

	        break;
	}

}

?>

<!DOCTYPE html>

<html>

<head>
	
	<?php echo print_default_opening_head(); ?>

</head>

<body>
	
	<div id="toprail"></div>
	
	<!-- Begin master container. -->
	<div id="container">
		
		<div class="logo"><a href="admin.php">F-ADMIN</a></div>

		<?php if( !empty($db_result_message) ) { echo $db_result_message; } ?>

		<p><b>Submit New:</b> <a href="edit_db_item.php?type=film&action=create">Film</a>

			<!-- TODO: Implement adding shows, books, and quotes.

			| <a href="edit_db_item.php?type=show&action=create">Show</a> | <a href="edit_db_item.php?type=book&action=create">Book</a> | <a href="edit_db_item.php?type=quote&action=create">Quote</a></p>

			-->
		
		<!-- TODO: Implement an "edit" feature.

		<p><b>Edit Existing:</b> <a href="edit_db_item.php?type=film&action=edit">Film</a> | <a href="edit_db_item.php?type=show&action=edit">Show</a> | <a href="edit_db_item.php?type=book&action=edit">Book</a> | <a href="edit_db_item.php?type=quote&action=edit">Quote</a></p></p>

		-->

	</div>
	<!-- End container. -->

</body>

</html>	