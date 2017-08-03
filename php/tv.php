<?php

require('global.php');

$conn = db_connect();

//Execute the all TV shows query.
$sql = "SELECT * FROM tv ORDER BY `show` ASC";
$result_all_shows = $conn->query($sql);

//Count the number of TV shows returned by the all TV shows query.
$result_all_shows_count = mysqli_num_rows($result_all_shows);

//Count the number of TV shows watched this year.
$starting_timestamp_for_current_year = strtotime( "1 January" . date("Y") );
$sql = "SELECT * FROM tv WHERE timestamp > $starting_timestamp_for_current_year";
$result_shows_this_year = $conn->query($sql);
$result_shows_this_year_count = mysqli_num_rows($result_shows_this_year);

//Calculate the average TV show's rating.
$sql = "SELECT AVG(rating) from tv";
$result_average_rating = $conn->query($sql);
$average_show_rating = 0;
while($row = $result_average_rating->fetch_assoc()) { $average_show_rating = $row["AVG(rating)"]; };
$average_show_rating = number_format($average_show_rating, 2);

?>

<!DOCTYPE html>

<html>

<head>
	
	<?php echo print_default_opening_head(); ?>	

	<script>

		$(document).ready(function(){
		    
		    //Activate the Nice select style.
		    $('select').niceSelect();

		    //Handle requests to filter the list of shows.
		    $( ".pop_culture_toggler" ).change(function() {
		      
		    	//Get the filter value set by the user.
		    	$selected_value = '';
		    	$( ".pop_culture_toggler option:selected" ).each(function() {		   

		    		$selected_value = $(this).val();

			    });

    	        //Make an AJAX request using the selected show filter.
    	        $.post("global.php",
                {
                	
                	path_name: "pop_culture_toggler",
                	type: "tv",
                	order_by: "show",
                	filter: $selected_value

                },
                //Handle the AJAX response with the requested shows.
                function(data){
                	
                	//Replace all of the shows that are currently shown.
                	$( "#left" ).html(data);

                });

		    });

		});

	</script>

</head>

<body>
	
	<div id="toprail"></div>
	
	<!-- Begin master container. -->
	<div id="container">
		
		<!-- Begin left column. -->
		<div id="left">

			<div id="mediateaser">
				
				Here's a list of every TV show I ever seen at least one full season of. Also check out my <a href="film.php">film</a> and <a href="lit.php">book</a> pages.
					
			</div>
			
			<!-- Get shows. -->
			<?php				

				while($row = $result_all_shows->fetch_assoc()) {

					//Format the show's watched timestamp.
					$epoch = $row["timestamp"];
				    $dt = new DateTime("@$epoch");
				    $dt_formatted = $dt->format('F j, Y');

				    //If the timestamp is the placeholder value, replace it with text.
				    if ($epoch == 1220508057) {
				    	$dt_formatted = "a long time ago.";
				    }

					//Print the show and its properties.
					echo "
										
					<div class=\"popcultureitemcontainer\">
					
						<div class=\"popcultureitemname\"><a href=\"{$row["link"]}\" target=\"_blank\">{$row["show"]}</a><br /><div class=\"popcultureitemproperties\">Watched {$dt_formatted}</div></div><div class=\"popcultureitemrating\"><img src=\"../images/{$row["rating"]}.gif\" /></div>						
						
					</div>
					
					";

				}				

			?>
			
		</div>
		<!-- End left column. -->
		
		<!-- Begin right column. -->
		<div id="right">
			
			<div class="logo"><a href="blog.php">FILMNUT</a></div>
			
			<!-- Show the total number of shows watched all-time. -->
			<div class="mediapagestatheader" style="font-size: 13px; margin-top: 20px;">Shows I've Seen in my Life</div>
			
				<div class="mediapagestatcontent"><?php echo $result_all_shows_count; ?></div>

			<!-- Show the total number of shows watched this year. -->
			<div class="mediapagestatheader">Shows I've Seen this Year</div>
				
				<div class="mediapagestatcontent" style="margin-bottom: 7px;"><?php echo $result_shows_this_year_count; ?></div>

			<!-- Show the average show's rating. -->
			<div class="mediapagestatheader" style="font-size: 13px; margin-top: 20px;">Average Show's Rating</div>
			
				<div class="mediapagestatcontent"><?php echo $average_show_rating; ?></div>	

			<!-- Show the filter dropdown list. -->
			<div class="pop_culture_toggler">
				<select>
				  <option value="all">Show All TV Shows</option>
				  <option value="5">Show Only 5 Stars</option>
				  <option value="4">Show Only 4 Stars</option>
				  <option value="3">Show Only 3 Stars</option>
				  <option value="2">Show Only 2 Stars</option>
				  <option value="1">Show Only 1 Stars</option>				  
				</select>
			</div>			

		</div>
		<!-- End right column. -->

	</div>
	<!-- End container. -->

</body>

</html>