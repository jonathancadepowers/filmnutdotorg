<?php

require('global.php');

$conn = db_connect();

//Attempt to get URL parameters.
$id = htmlspecialchars($_GET["id"]);
$archive = htmlspecialchars($_GET["archive"]);

//Set the blog post query.
if ( empty($id) && empty($archive) ) {
    //User has requested the main blog page.
    $sql = "SELECT * FROM blog ORDER BY timestamp desc LIMIT 10";
} elseif ( $archive == 'true' )
	//User has requested the archive blog page.
	$sql = "SELECT * FROM blog ORDER BY timestamp desc";
else {
    //User has requested a specific blog post.
    $sql = "SELECT * FROM blog where id = " . $id;
}

//Execute the blog post query.
$result_blog_post_query = $conn->query($sql);

//Set the last five films watched query.
$sql = "SELECT * FROM film ORDER BY timestamp desc LIMIT 5";

//Execute the last five films watched query.
$result_last_five_films_watched_query = $conn->query($sql);

?>

<!DOCTYPE html>

<html>

<?php echo print_default_opening_head(); ?>

</head>

<body>
	
	<div id="toprail"></div>
	
	<!-- Begin master container. -->
	<div id="container">
		
		<!-- Begin left column. -->
		<div id="left">
			
			<!-- Get blog posts. -->
			<?php

				//User has requested the main blog page.
				if ( empty($archive) or $archive != 'true' ) {

					while($row = $result_blog_post_query->fetch_assoc()) {
					    
					    //Print the blog post's header.
					    echo "<p class=\"blog_post_header\">" . $row["title"] . "</p>";

						//Format the blog post's timestamp.
						$epoch = $row["timestamp"];
					    $dt = new DateTime("@$epoch");				    

					    //Print the blog post's timestamp/perm link.
					    echo "<p class=\"blog_post_timestamp\"><i class=\"fa fa-clock-o\" aria-hidden=\"true\" style=\"color: #F06768; font-size: 12px;\"></i> <a href=\"blog.php?id=" . $row["id"] . "\">Posted " . $dt->format('F j, Y') . "</a></p>";

					    //Print the blog post's body.
					    echo $row["body"];

					}

					//If this is not a perm link page, provide a link to the blog archive. Otherwise, provide a link back to the homepage.
					if ( empty($id) ) {

						echo "<p class=\"navigation_link\"><i class=\"fa fa-arrow-circle-o-right\" style=\"color: #F06768; font-size: 14px;\" aria-hidden=\"true\"></i><a href=\"blog.php?archive=true\"> More blog posts...</a></p>";

					} else {

						echo "<p class=\"navigation_link\"><a href=\"blog.php\"><i class=\"fa fa-arrow-circle-o-left\" aria-hidden=\"true\" style=\"font-size: 14px; \"></i> Back to homepage...</a></p>";

					}				

				}
				//User has requested the archive blog page.
				else {

					echo "<p class=\"archive_header\">The Blog Archive</p>";

					//Print a list of all blog posts.
					echo "<ul>";

					while($row = $result_blog_post_query->fetch_assoc()) {

						//Format the blog post timestamp.
						$epoch = $row["timestamp"];
					    $dt = new DateTime("@$epoch");	
					    
					    echo "<li class=\"archive_list\"><a href=\"blog.php?id=" . $row["id"] . "\">" . $row["title"] . "</a> <span class=\"archive_list_timestamp\">(" . $dt->format('n/j/y') . ")</span></li>";

					}					

					echo "</ul>";

					echo "<p class=\"navigation_link\"><a href=\"blog.php\"><i class=\"fa fa-arrow-circle-o-left\" aria-hidden=\"true\" style=\"font-size: 14px;\"></i> Back to homepage...</a></p>";

				}				

			?>
			
		</div>
		<!-- End left column. -->
		
		<!-- Begin right column. -->
		<div id="right">
			
			<div class="logo"><a href="blog.php">FILMNUT</a></div>
			
			<p class="site_welcome"><b>Hi. I'm <a href="about.php">Jonathan</a>.</b> I like to write about pop culture and technology, as well as track every <a href="film.php">film</a>, <a href="tv.php">TV show</a>, and <a href="lit.php">book</a> that I consume. I also like <a href="quotes.php">quotes</a>. Stay awhile.</p>

			<div class="external_links">
				<a target="_blank" href="https://twitter.com/filmnut"><i class="fa fa-twitter-square" aria-hidden="true"></i></a> 
				<a target="_blank" href="https://www.instagram.com/jonathanpowers/"><i class="fa fa-instagram" aria-hidden="true"></i></a>
				<a target="_blank" href="https://www.flickr.com/photos/filmnut"><i class="fa fa-flickr" aria-hidden="true"></i></a> 
			</div>

			<p style="margin-bottom: -10px;">
			
				<b>Last Five Movies I've Seen:</b>
			
				<ul>
					
					<?php

					while($row = $result_last_five_films_watched_query->fetch_assoc()) {

						echo "<a href=\"{$row["imdb_url"]}\" target=\"_blank\"><li>{$row["film"]}</a></li>";

					}

					?>

				</ul>

			</p>

			<p style="margin-bottom: -10px;">
			
				<b>Other Cool-ish Stuf:</b>
			
				<ul>
					<li><a href="blog.php?id=9">My Top 100 Films list. (2009)</a></li>
					<li><a href="https://www.laserfiche.com/solutionexchange/zeno-imaging-revamped-customer-order-process-laserfiche-forms/" target="_blank">An article written about an ECM system that I built.</a></li>
					<li><a href="https://www.flickr.com/photos/filmnut/sets/72157627793362503" target="_blank">Photos from my Iceland trip.</a></li>
					<li><a href="https://www.flickr.com/photos/filmnut/sets/72157633692262724" target="_blank">Photos from my honeymoon to Hawaii, Singapore, and Japan.</a></li>
				</ul>
				
			</p>

		</div>
		<!-- End right column. -->

	</div>
	<!-- End container. -->

</body>

</html>