<?php

require('global.php');

$conn = db_connect();

//Determine the total number of quotes in the datbase.
$sql = "SELECT COUNT(*) FROM quotes";
$result_total_rows_count = $conn->query($sql);
$quote_count = 0;
while($row = $result_total_rows_count->fetch_assoc()) { $quote_count = $row["COUNT(*)"]; };

//Get a random quote.
$random_number = rand(1,$quote_count);
$sql = "SELECT * FROM quotes WHERE id = {$random_number}";
$result_random_quote = $conn->query($sql);
$random_quote_source = "";
$random_quote_body = "";
while($row = $result_random_quote->fetch_assoc()) { 
	$random_quote_source = $row["source"];
	$random_quote_body = $row["quote"];
};

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
		
		<div class="logo"><a href="blog.php">FILMNUT</a></div>

		<p style="font-size: 80px; line-height: 90px; margin-top: 18px; margin-bottom: 0px;">

			<?php echo $random_quote_body ?>

		</p>

		<p style="font-size: 20px; background-color: #EEEEEE; padding: 8px; display: inline-block; color: #36393D;">
			
			from <b><?php echo $random_quote_source ?></b></span>

		</p>

		<p style="color: grey; font-size: 14px;">I kinda love quotes. So much so that I built a webpage<br>to show a random favorite each time it loads. <a href="quotes.php">Refresh!</a></p>

		<p class="navigation_link"><a href="blog.php"><i class="fa fa-arrow-circle-o-left" aria-hidden="true" style="font-size: 14px; "></i> Back to homepage...</a></p>

	</div>
	<!-- End container. -->

</body>

</html>