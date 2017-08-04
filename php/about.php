<?php

require('global.php');

$conn = db_connect();

//Build age stats.
$age_in_years = dateDifference("11/13/1981", date("m/d/y"), "%y");
$age_in_days = dateDifference("11/13/1981", date("m/d/y"), "%a");

//Calculate the total number of films watched.
$sql = "SELECT * FROM film ORDER BY film ASC";
$result_all_films = $conn->query($sql);
$result_all_films_count = mysqli_num_rows($result_all_films);

//Calculate the total number of TV shows watched.
$sql = "SELECT * FROM tv ORDER BY `show` ASC";
$result_all_shows = $conn->query($sql);
$result_all_shows_count = mysqli_num_rows($result_all_shows);

//Calculate the total number of books read.
$sql = "SELECT * FROM lit ORDER BY book ASC";
$result_all_books = $conn->query($sql);
$result_all_books_count = mysqli_num_rows($result_all_books);

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
		
		<!-- Begin left column. -->
		<div id="left">

			<p class="bioheader"><i class="fa fa-info-circle" aria-hidden="true"></i> <b>Stuffy Third Person Bio</b></p>
			
			<p>Jonathan is a dude that plays with software for a living and currently lives in Houston, Texas. He prefers Mac to PC, no car payments to fancy BMWs, Chipotle to Freebirds, Google to Yahoo, Pinkberry to Yogurtland, post-modern to modern, slow to fast, Sofia to Francis Ford, and grey to black or white.</p>
			
			<p>In his spare time, Jonathan plays with technology and consumes pop culture.</p>
			
			<p>Filmnut is the not-so-popular or influential weblog that Jonathan has maintained <a href="http://web.archive.org/web/*/http://www.filmnut.org" target="_blank">since December 2001</a>. He he has used the website as both a personal diary and a mind-dump on technology, film, and culture.</p>
			
			<p><p class="bioheader"><i class="fa fa-calendar" aria-hidden="true"></i> <b>Reverse Chronological Timeline of My Life</b></p>
			
			<p><b>2017:</b> In progress...

			<p><b>2016:</b> I become a dad! Me! To the cutest most wonderful little girl in the world named <a href="https://www.instagram.com/p/BWYrlObFoNZ/?taken-by=jonathanpowers" target="_blank">Brighton</a>! Ahhhhhh!

			<p><b>2015:</b> I'm devestated to leave Midland behind and return to Houston. Not. Oh, and we buy a house in the awesome neighborhood of <a href="https://en.wikipedia.org/wiki/Montrose,_Houston" target="_blank">Montrose</a>.

			<p><b>2014:</b> I move to Midland, Texas. The things we do for love.

			<p><b>2013:</b> I get married (to the girl of my dreams) in Hawaii! We honeymoon in Singapore and Japan.

			<p><b>2012:</b> I live in an <a href="http://www.thecorkfactory.com/" target="_blank">old cork factory</a> for a year. I also learn that cold weather is not great.
			
			<p><b>2011:</b> I vacation in Iceland, move from a technical writing position to a solutions engineer (at the same company), and spend about four months living in Los Angeles for work. On the last day of the year, I move to Pittsburgh (for a very pretty girl).
			
			<p><b>2010:</b> I sell my house and move to downtown Houston, into an <a target="_blank" href="http://en.wikipedia.org/wiki/Post_Rice_Lofts">awesome old hotel</a>.
			
			<p><b>2009:</b> I learn VB.NET.
			
			<p><b>2008:</b> I love working from home and I love my new technical writing gig. I get to play with technology all day, recruit at all the nearby universities, and teach software. This year I also learn CSS and PHP, get another dog (Scout), and buy my first house.
			
			<p><b>2007:</b> I receive my Masters from Baylor and move to Hollywood. I quickly find a job as an assistant to a literary manager who represented writers and directors. Be careful what you wish for: I hate Hollywood with a passion and it hates me. I quit within four months and stumble across an awesome job as a technical writer at a software company called <a href="http://www.laserfiche.com" target="_blank">Laserfiche</a>). Best of all they let me move back to Houston and work remotely.
			
			<p><b>2006:</b> Being a grad student is fun: I help teach an undergraduate class on video/film production methods, I write papers on awesome topics (like Radiohead and Frederick Wiseman), and I get to talk film all day long.
			
			<p><b>2005:</b> I graduate from Baylor with a Bachelors in Business Administration (Business-Broadcasting major). I decide to put off Hollywood for two years in order to get my Master of Arts degree in Communication Studies (also from Baylor).
			
			<p><b>2004:</b> I decide my undergrad focus will be in business broadcasting, and start mixing in a lot of film classes with my business curriculum.
			
			<p><b>2003:</b> I try my hand at screenwriting and before long am awarded the Grand Screenwritting Prize at the 2003 Black Glasses Film Festival.
			
			<p><b>2002:</b> I intern with an Emmy Nominated producer in Los Angeles for a summer.
			
			<p><b>2001:</b> Right before the beginning of my sophomore year, I decide, out of the blue almost, that sports is not for me. Instead, I vow to work in film. I also return from the mall one day carrying a brand new addition to the family: Mattie the dog.
			
			<p><b>2000:</b> I graduate from high school, spend a summer mowing lawns and start my undergraduate degree at Baylor University in Waco, Texas. My sports obsession grows even stronger and decide I want to one day become a General Manager for an NBA franchise. I begin working in the Sports Information Department at Baylor.
			
			<p><b>1999:</b> I become transfixed with sports (watching, never playing). I get a gig on local sports talk radio show, start calling games at my high school and watching nothing but SportsCenter.
			
			<p><b>1998:</b> I go through an odd obsession with becoming a country-western singer.
			
			<p><b>1997:</b> Desperately wanting to stay within the private sector, despite my sub-par grades, I beg and plead with Northwest Academy (soon to be Houston Christian High School) to accept me. They do and high-school begins.
			
			<p><b>1996:</b> While back in Atlanta on summer break, my dad finds out that, if he wants, the company will transfer him to Houston, Texas. Upon receiving this information, he turns the decision over to my brother and I. After careful consideration, we opt to return to the USA and, before we know, are official Texans. American schooling however, is not how I remembered it. I attend three days of public school up the road from my house before begging my parents to save me from being shot and enrolling me in a private school. Thankfully they do. I attend Grace Presbyterian Academy and have the single best school year of my life (during which I get my first kiss).
			
			<p><b>1995:</b> Singapore and seventh grade, as it turns out, is a blast.
			
			<p><b>1994:</b> I'm heartbroken when I am forced to leave behind my new American friends and life and move back to Singapore. We also spend the summer in Yemen.
			
			<p><b>1993:</b> I move from the apartment into a house, but still remain in Peachtree City where I attend fifth grade. This turns out to be my second favorite school year of my life. We spend Christmas with my dad in Dubai.
			
			<p><b>1992:</b> We bid farewell to Darwin and return to Atlanta once again. The plan was to then move to Saudi Arabia, where my dad had been transferred, but, due to some problems with the schooling situation, we stayed in Georgia, moving into a typical little apartment in Peachtree City. I finally get to attend an American school, play baseball and be a Yank!
			
			<p><b>1991:</b> My mom wakes me up in the middle of school night to watch "West Side Story," jump starting my adoration for the cinema.
			
			<p><b>1990:</b> Having been completely immersed in Australian culture for the past 12 months, I develop a completely authentic Auzzie accent which I turn on and off depending on who I am talking to.
			
			<p><b>1989:</b> A few months in Singapore and Atlanta again before we settle down for five whole years in Darwin, Australia. In Darwin I start formal schooling.
			
			<p><b>1987:</b> Same country, new city as we go from Guangzhou to Shekou. As there was no English-speaking schools, my mom starts one which, to this day, still exists.
			
			<p><b>1986:</b> Most of the year is split between Singapore, Atlanta and our new more permanent home: Guangzhou, China.
			
			<p><b>1985:</b> My fabulous brother Carson is born. We take residence in a five-star hotel in Seoul Korea. As odd as it may seem now, I'm driven to pre-school each day by a driver, my lunches (which often includes escargot) are prepared by the hotel's chef and my mom dresses me in tiny designer suits which have my initials sown into the collar.
			
			<p><b>1984:</b> I move back to Atlanta shortly before making my first journey across the Atlantic and into my new home in Singapore, though it's pretty short-lived.
			
			<p><b>1983:</b> As my dad moves up the ranks in the oil industry, we begin the crazy relocating shuffle that will soon define my adolescence. During the span of 12-months, I move from Mesquite, to Dallas, to New Orleans and then back to Dallas.
			
			<p><b>1982:</b> I stumble my first few steps.
			
			<p><b>1981:</b> I am born to the world's best parents: John (the oil executive) and Joyce (the Delta flight attendant) in Atlanta, Georgia. Within less than a few weeks, I move to Mesquite, Texas.
			
		</div>
		<!-- End left column. -->
		
		<!-- Begin right column. -->
		<div id="right">

			<div class="logo"><a href="blog.php">FILMNUT</a></div>
			
			<div class="aboutpagestatheader" style="font-size: 13px; margin-top: 20px;">My Current Age by Years</div>
			
				<div class="aboutpagestatcontent"><?php echo $age_in_years; ?></div>

			<div class="aboutpagestatheader" style="font-size: 13px; margin-top: 20px;">Total Days Lived</div>
			
				<div class="aboutpagestatcontent"><?php echo number_format($age_in_days); ?></div>

			<div class="aboutpagestatheader" style="font-size: 13px; margin-top: 20px;">Current Hometown</div>
			
				<div class="aboutpagestatcontent">Houston, TX</div>

			<div class="aboutpagestatheader" style="font-size: 13px; margin-top: 20px;">Countries I've Lived In</div>
			
				<div class="aboutpagestatcontent">8</div>

			<div class="aboutpagestatheader" style="font-size: 13px; margin-top: 20px;">Total Films Watched</div>
			
				<div class="aboutpagestatcontent"><?php echo number_format($result_all_films_count); ?></div>

			<div class="aboutpagestatheader" style="font-size: 13px; margin-top: 20px;">Total TV Shows Watched</div>
			
				<div class="aboutpagestatcontent"><?php echo number_format($result_all_shows_count); ?></div>

			<div class="aboutpagestatheader" style="font-size: 13px; margin-top: 20px;">Total Books Read</div>
			
				<div class="aboutpagestatcontent"><?php echo number_format($result_all_books_count); ?></div>			

		</div>
		<!-- End right column. -->

	</div>
	<!-- End container. -->

</body>

</html>