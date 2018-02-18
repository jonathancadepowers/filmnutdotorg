<?php

require('global.php');

$conn = db_connect();

//Execute the all books query.
$sql = "SELECT * FROM lit ORDER BY book ASC";
$result_all_books = $conn->query($sql);

//Count the number of books returned by the all books query.
$result_all_books_count = mysqli_num_rows($result_all_books);

//Count the number of books read this year.
$starting_timestamp_for_current_year = strtotime( "1 January" . date("Y") );
$sql = "SELECT * FROM lit WHERE timestamp > $starting_timestamp_for_current_year and backdated <> \"Yes\" ";
$result_books_this_year = $conn->query($sql);
$result_books_this_year_count = mysqli_num_rows($result_books_this_year);

//Calculate the average book's rating.
$sql = "SELECT AVG(rating) from lit";
$result_average_rating = $conn->query($sql);
$average_book_rating = 0;
while($row = $result_average_rating->fetch_assoc()) { $average_book_rating = $row["AVG(rating)"]; };
$average_book_rating = number_format($average_book_rating, 2);

?>

<!DOCTYPE html>

<html>

<head>
	
	<?php echo print_default_opening_head(); ?>	

	<script>

		$(document).ready(function(){
		    
		    //Activate the Nice select style.
		    $('select').niceSelect();

		    //Handle requests to filter the list of books.
		    $( ".pop_culture_toggler" ).change(function() {
		      
		    	//Get the filter value set by the user.
		    	$selected_value = '';
		    	$( ".pop_culture_toggler option:selected" ).each(function() {		   

		    		$selected_value = $(this).val();

			    });

    	        //Make an AJAX request using the selected book filter.
    	        $.post("global.php",
                {
                	
                	path_name: "pop_culture_toggler",
                	type: "lit",
                	order_by: "book",
                	filter: $selected_value

                },
                //Handle the AJAX response with the requested books.
                function(data){
                	
                	//Replace all of the books that are currently shown.
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
				
				I don't read near as much as I'd like. Here's a list of every book I've read in my life. Also check out my <a href="film.php">film</a> and <a href="tv.php">TV</a> pages.
					
			</div>
			
			<!-- Get books. -->
			<?php				

				while($row = $result_all_books->fetch_assoc()) {

					//Format the book's read timestamp.
					$epoch = $row["timestamp"];
				    $dt = new DateTime("@$epoch");
				    $dt_formatted = $dt->format('F j, Y');

				    //If the timestamp is the placeholder value, replace it with text.
				    if ($epoch == 1220508057) {
				    	$dt_formatted = "a long time ago.";
				    }

					//Print the book and its properties.
					echo "
										
					<div class=\"popcultureitemcontainer\">
					
						<div class=\"popcultureitemname\"><a href=\"{$row["link"]}\" target=\"_blank\">{$row["book"]}</a><br /><div class=\"popcultureitemproperties\">{$row["author"]} | Read {$dt_formatted}</div></div><div class=\"popcultureitemrating\"><img src=\"../images/{$row["rating"]}.gif\" /></div>	

					</div>
					
					";

				}				

			?>
			
		</div>
		<!-- End left column. -->
		
		<!-- Begin right column. -->
		<div id="right">
			
			<div class="logo"><a href="blog.php">FILMNUT</a></div>
			
			<!-- Show the total number of books read all-time. -->
			<div class="mediapagestatheader" style="font-size: 13px; margin-top: 20px;">Books I've Read in my Life</div>
			
				<div class="mediapagestatcontent"><?php echo number_format($result_all_books_count); ?></div>

			<!-- Show the total number of books read this year. -->
			<div class="mediapagestatheader">Books I've Read this Year</div>
				
				<div class="mediapagestatcontent" style="margin-bottom: 7px;"><?php echo $result_books_this_year_count; ?></div>

			<!-- Show the average book rating. -->
			<div class="mediapagestatheader" style="font-size: 13px; margin-top: 20px;">Average Book Rating</div>
			
				<div class="mediapagestatcontent"><?php echo $average_book_rating; ?></div>	

			<!-- Show the filter dropdown list. -->
			<div class="pop_culture_toggler">
				<select>
				  <option value="all">Show All books</option>
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