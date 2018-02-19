<?php

require('global.php');

$conn = db_connect();

//Execute the all films query.
$sql = "SELECT * FROM film ORDER BY film ASC";
$result_all_films = $conn->query($sql);

//Count the number of films returned by the all films query.
$result_all_films_count = mysqli_num_rows($result_all_films);

//Count the number of films watched this year.
$starting_timestamp_for_current_year = strtotime( "1 January" . date("Y") );
$sql = "SELECT * FROM film WHERE timestamp > $starting_timestamp_for_current_year and backdated <> \"Yes\" ";
$result_films_this_year = $conn->query($sql);
$result_films_this_year_count = mysqli_num_rows($result_films_this_year);

//Calculate the average film's rating.
$sql = "SELECT AVG(rating) from film";
$result_average_rating = $conn->query($sql);
$average_film_rating = 0;
while($row = $result_average_rating->fetch_assoc()) { $average_film_rating = $row["AVG(rating)"]; };
$average_film_rating = number_format($average_film_rating, 2);

//Calculate total minutes watching film.
$sql = "SELECT SUM(`running_time`) FROM `film`";
$totalminutesoffilmwatched = 0;
$result_minutes_watching = $conn->query($sql);
while($row = $result_minutes_watching->fetch_assoc()) { $totalminutesoffilmwatched = $row["SUM(`running_time`)"]; };

//Identify the top directors.
$sql = "select `Directors`, count(*) from `film` group by `Directors` order by count(*) desc LIMIT 15";
$result_top_directors = $conn->query($sql);

//Identify the top release years.
$sql = "select `release_year`, count(*) from `film` group by `release_year` order by count(*) desc limit 15";
$result_top_years = $conn->query($sql);

?>

<!DOCTYPE html>

<html>

<head>
	
	<?php echo print_default_opening_head(); ?>	

	<script>

		$(document).ready(function(){
		    
		    //Activate the Nice select style.
		    $('select').niceSelect();

		    //Handle requests to filter the list of films.
		    $( ".pop_culture_toggler" ).change(function() {
		      
		    	//Get the filter value set by the user.
		    	$selected_value = '';
		    	$( ".pop_culture_toggler option:selected" ).each(function() {		   

		    		$selected_value = $(this).val();

			    });

    	        //Make an AJAX request using the selected film filter.
    	        $.post("global.php",
                {
                	
                	path_name: "pop_culture_toggler",
                	type: "film",
                	order_by: "film",
                	filter: $selected_value

                },
                //Handle the AJAX response with the requested films.
                function(data){
                	
                	//Replace all of the films that are currently shown.
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
				
				Film is kinda my thing. Here's a list of every movie I've ever seen. Also check out my <a href="tv.php">TV</a> and <a href="lit.php">book</a> pages, plus see a <a href="https://www.youtube.com/playlist?list=PLCVz03lD2vD9TCBqyktmBUoBgLV33n25p" target="_blank">list of movies</a> I want to see.
					
			</div>
			
			<!-- Get films. -->
			<?php				

				while($row = $result_all_films->fetch_assoc()) {

					//Format the film's watched timestamp.
					$epoch = $row["timestamp"];
				    $dt = new DateTime("@$epoch");
				    $dt_formatted = $dt->format('F j, Y');

				    //If the timestamp is the placeholder value, replace it with text.
				    if ($epoch == 1220508057) {
				    	$dt_formatted = "a long time ago.";
				    }

					//Print the film and its properties.
					echo "
										
					<div class=\"popcultureitemcontainer\">
					
						<div class=\"popcultureitemname\"><a href=\"{$row["imdb_url"]}\" target=\"_blank\">{$row["film"]}</a><br /><div class=\"popcultureitemproperties\">{$row["directors"]} | Released {$row["release_year"]} | Watched {$dt_formatted}</div></div><div class=\"popcultureitemrating\"><img src=\"../images/{$row["rating"]}.gif\" /></div>	
											
					</div>
					
					";

				}				

			?>
			
		</div>
		<!-- End left column. -->
		
		<!-- Begin right column. -->
		<div id="right">
			
			<div class="logo"><a href="blog.php">FILMNUT</a></div>
			
			<!-- Show the total number of films watched all-time. -->
			<div class="mediapagestatheader">Films I've Seen in my Life</div>
			
				<div class="mediapagestatcontent"><?php echo number_format($result_all_films_count); ?></div>

			<!-- Show the total number of films watched this year. -->
			<div class="mediapagestatheader">Films I've Seen this Year</div>
				
				<div class="mediapagestatcontent" style="margin-bottom: 7px;"><?php echo $result_films_this_year_count; ?></div>

			<!-- Show the average film rating. -->
			<div class="mediapagestatheader">Average Film Rating</div>
			
				<div class="mediapagestatcontent"><?php echo $average_film_rating; ?></div>

			<!-- Show the total minutes watching film. -->
			<div class="mediapagestatheader">Lifetime Minutes Watching Film</div>
			
				<div class="mediapagestatcontent"><?php echo number_format($totalminutesoffilmwatched); ?></div>	

			<!-- Show the filter dropdown list. -->
			<div class="pop_culture_toggler">
				<select>
				  <option value="all">Show All Films</option>
				  <option value="5">Show Only 5 Stars</option>
				  <option value="4">Show Only 4 Stars</option>
				  <option value="3">Show Only 3 Stars</option>
				  <option value="2">Show Only 2 Stars</option>
				  <option value="1">Show Only 1 Stars</option>				  
				</select>
			</div>

			</br></br>

			<!-- Show the top directors. -->
			<div class="mediapagestatheader" style="font-weight: bold; margin-top: 26px;">Most Watched Directors</div>
			
				<div class="mediapagestatcontentsmall"><ol><?php while($row = $result_top_directors->fetch_assoc()) { echo "<li>".$row["Directors"]." (".$row["count(*)"].") </li>"; }; ?></ol></div>

				<!-- Show the top release years. -->
			<div class="mediapagestatheader" style="font-weight: bold;">Most Watched Release Years</div>
			
				<div class="mediapagestatcontentsmall"><ol><?php while($row = $result_top_years->fetch_assoc()) { echo "<li>".$row["release_year"]." (".$row["count(*)"].") </li>"; }; ?></ol></div>			

		</div>
		<!-- End right column. -->

	</div>
	<!-- End container. -->

</body>

</html>