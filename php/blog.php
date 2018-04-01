<?php

require('global.php');

$conn = db_connect();

//Attempt to get URL parameters.
if ( isset($_GET["id"]) ) { $id = htmlspecialchars($_GET["id"]); }
if ( isset($_GET["archive"]) ) { $archive = htmlspecialchars($_GET["archive"]); }
if ( isset($_GET["tag"]) ) { $tags = htmlspecialchars($_GET["tag"]); }

switch (true) {

	case ( isset($archive) && $archive == 'true' ): //User has requested the archive blog page.
		$sql = "SELECT * FROM blog ORDER BY timestamp desc";
		break;

	case ( isset($id) ): //User has requested a specific blog post.
		$sql = "SELECT * FROM blog where id = " . $id;
		break;

	case ( isset($tags) ): //User has requested a specific tag page.
		$sql = "SELECT * FROM blog where tags LIKE '%" . $tags . ";%'";
		break;

	default: //User has requested the main blog page.
    $sql = "SELECT * FROM blog ORDER BY timestamp desc LIMIT 10";
    break;

}

 $result_blog_post_query = $conn->query($sql);

//Set the last five films watched query.
$sql = "SELECT * FROM film WHERE backdated <> \"Yes\" ORDER BY timestamp desc LIMIT 5";

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
		<div id="left" style="top: 20px;">
			
			<!-- Get blog posts. -->
			<?php

				switch (true) {

					case ( isset($archive) && $archive == 'true' ): //User has requested the archive blog page.
						
						echo "<p class=\"archive_header\">The Blog Archive</p>";

						//Print a list of all full blog posts.
						echo "<ul>";

						while($row = $result_blog_post_query->fetch_assoc()) {

							//Format the blog post timestamp.
							$epoch = $row["timestamp"];
						  $dt = new DateTime("@$epoch");	
						    					  
						  if ( $row["is_mini"] == "false" ) {

						  	echo "<li class=\"archive_list\"><a href=\"blog.php?id=" . $row["id"] . "\">" . $row["title"] . "</a> <span class=\"archive_list_timestamp\">(" . $dt->format('n/j/y') . ")</span></li>";

							}

						}							

						echo "</ul>";

						echo "<p class=\"navigation_link\"><a href=\"blog.php\"><i class=\"fa fa-arrow-circle-o-left\" aria-hidden=\"true\" style=\"font-size: 14px;\"></i> Back to homepage...</a></p>";

						break;

					default: //User has NOT requested the archive blog page.
				  
						while($row = $result_blog_post_query->fetch_assoc()) {
						    
						  echo "<div class=\"blog_post_container\">";

						  //Format the blog post's timestamp.
							$epoch = $row["timestamp"];
						  $dt = new DateTime("@$epoch");

						  //Handle a full blog post.
						  if ( $row["is_mini"] == "false" ) {

						  	//Print the blog post's header.
						  	echo "<p class=\"blog_post_header\">" . $row["title"] . "</p>";

						  	//Extract the blog post's tags.
						  	$tags_as_links = "";
						  	if ( !empty($row["tags"]) ) {

				  		  	$tags_array = explode( ";", $row["tags"] );				  		  	
				  		  	foreach ($tags_array as &$tag) {

				  					$new_tag = "<a href=\"blog.php?tag=" . $tag . "\"><i class=\"fas fa-tag\"></i> " . $tag . "</a>&nbsp;&nbsp;";

				  					$tags_as_links = $tags_as_links . $new_tag;

				  				}
				  				
				  				//Clean up the tags array, by ensuring that a blank tag doesn't get added to the end and also adding various formatting to it.
				  				$tags_as_links = str_replace("<a href=\"blog.php?tag=\"><i class=\"fas fa-tag\"></i> </a>&nbsp;&nbsp;","",$tags_as_links);
				  				$tags_as_links = "&nbsp;&nbsp;<span class=\"tags\">" . $tags_as_links . "</span>";

						  	}						  	

						  	//Print the blog post's timestamp/perm link.
						  	echo "<p class=\"blog_post_timestamp\"><i class=\"fa fa-clock-o\" aria-hidden=\"true\" style=\"color: #F06768; font-size: 12px;\"></i> <a href=\"blog.php?id=" . $row["id"] . "\">Blog Post from " . $dt->format('F j, Y') . "</a>$tags_as_links</p>";

						  	//Print the blog post's body.
						  	echo htmlspecialchars_decode($row["body"]);

						  }					  										    					  					  

						  //Handle a mini blog post.
						  if ( $row["is_mini"] == "true" ) {					  	

						  	//Print the blog post's body.
						  	echo "<p>" . htmlspecialchars_decode($row["body"]) . "</p>";

						  	//Print the blog post's timestamp/perm link.
						  	echo "<p class=\"blog_post_timestamp\"><i class=\"fa fa-clock-o\" aria-hidden=\"true\" style=\"color: #F06768; font-size: 12px;\"></i> <a href=\"blog.php?id=" . $row["id"] . "\">Mini Post from " . $dt->format('F j, Y') . "</a></p>";

						  }

						  echo "</div>";

						}

						//If this is not a perm link page, provide a link to the blog archive. Otherwise, provide a link back to the homepage.
						if ( empty($id) ) {

							echo "<p class=\"navigation_link\"><i class=\"fa fa-arrow-circle-o-right\" style=\"color: #F06768; font-size: 14px;\" aria-hidden=\"true\"></i><a href=\"blog.php?archive=true\"> More blog posts...</a></p>";

						} else {

							echo "<p class=\"navigation_link\"><a href=\"blog.php\"><i class=\"fa fa-arrow-circle-o-left\" aria-hidden=\"true\" style=\"font-size: 14px; \"></i> Back to homepage...</a></p>";

						}

				    break;

				}	

			?>
			
		</div>
		<!-- End left column. -->
		
		<!-- Begin right column. -->
		<div id="right">
			
			<div class="logo"><a href="blog.php">FILMNUT</a></div>
			
			<p class="site_welcome"><b>Hi. I'm <a href="about.php">Jonathan</a>.</b> I <a href="blog.php?archive=true">write</a> about pop culture and technology. I track every <a href="film.php">film</a>, <a href="tv.php">TV show</a>, and <a href="lit.php">book</a> that I consume. I like <a href="quotes.php">quotes</a>. Stay awhile.</p>

			<div class="external_links">
				<a target="_blank" href="https://twitter.com/filmnut"><i class="fa fa-twitter-square" aria-hidden="true"></i></a> 
				<a target="_blank" href="https://www.instagram.com/jonathanpowers/"><i class="fa fa-instagram" aria-hidden="true"></i></a>
				<a target="_blank" href="https://github.com/jonathancadepowers"><i class="fa fa-github" aria-hidden="true"></i></a> 
				<a target="_blank" href="https://www.youtube.com/playlist?list=LLW0do5SbMoOvxBqnZzMFzeQ"><i class="fa fa-youtube" aria-hidden="true"></i></a> 
				<a target="_blank" href="https://www.last.fm/user/afilmnut"><i class="fa fa-lastfm-square" aria-hidden="true"></i></a> 
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
					<li><a href="blog.php?archive=true">Every blog post I've ever made.</a></li>
					<li><a href="https://www.youtube.com/playlist?list=PLCVz03lD2vD9TCBqyktmBUoBgLV33n25p" target="_blank">A list of movies I want to see.</a></li>
					<li><a href="blog.php?id=22">My Top 100 Films list (2017 Edition).</a></li>
					<li><a href="https://www.laserfiche.com/solutionexchange/zeno-imaging-revamped-customer-order-process-laserfiche-forms/" target="_blank">An article written about an ECM system that I built.</a></li>
					<li><a href="https://vimeo.com/filmnut" target="_blank">My Vimeo videos.</a></li>
					<li><a href="https://www.flickr.com/photos/filmnut/sets/72157627793362503" target="_blank">Photos from my Iceland trip.</a></li>
					<li><a href="https://www.flickr.com/photos/filmnut/sets/72157633692262724" target="_blank">Photos from my honeymoon to Hawaii, Singapore, and Japan.</a></li>
					<li><a href="https://www.flickr.com/photos/filmnut/albums/72157671915141692" target="_blank">Way too many pictures of my daughter Brighton.</a></li>
					<li>The time that <a href="https://www.metafilter.com/51987/David-Pogue-is-the-rudest-man-alive" target="_blank">I called out</a> New York Times columnist David Pogue on MetaFilter (and got him to <a href="https://www.metafilter.com/51987/David-Pogue-is-the-rudest-man-alive#1326948" target="_blank">interact</a> with the thread).</li>
				</ul>
				
			</p>

		</div>
		<!-- End right column. -->

	</div>
	<!-- End container. -->

</body>

</html>