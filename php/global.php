<?php

function db_connect() {

    if ( getenv("CLEARDB_DATABASE_URL") ) {

        $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
        $server = $url["host"];
        $username = $url["user"];
        $password = $url["pass"];
        $database = substr($url["path"], 1);        

    } else {

        $server = "localhost";
        $username = "root";
        $password = "root";
        $database = "filmnut";
        
    }

    $conn = new mysqli($server, $username, $password, $database);
	return $conn;

}

function print_default_opening_head() {

    $head = "

        <head>
    
            <meta content=\"text/html; charset=iso-8859-1\" http-equiv=\"Content-Type\">

            <title>Filmnut</title>

            <link rel=\"icon\" type=\"image/png\" href=\"../images/fav.png\" sizes=\"32x32\" />

            <link href=\"../css/styles.css\" rel=\"stylesheet\" type=\"text/css\">

            <script src=\"../js/fc1321e1c1.js\"></script>

            <script src=\"../js/jquery.min.js\"></script>

            <script src=\"../js/jquery.nice-select.min.js\"></script>

            <link rel=\"stylesheet\" href=\"../css/nice-select.css\">

    ";

    return $head;

}

//Handle AJAX calls.
$path_name = filter_input(INPUT_POST, 'path_name', FILTER_SANITIZE_STRING);

//If an AJAX call was made, connect to the database.
if ($path_name) {

	$conn = db_connect();	

}

switch ($path_name) {
    
    //Handle requests to filter the film page.
    case 'pop_culture_toggler':
        
    	//Get the URL parameters.
        $filter = filter_input(INPUT_POST, 'filter', FILTER_SANITIZE_STRING);
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
        $order_by = filter_input(INPUT_POST, 'order_by', FILTER_SANITIZE_STRING);
        
    	//Build the filter query.
        if ($filter == "all") {
            $sql = "SELECT * FROM {$type} ORDER BY `{$order_by}` ASC";
        } else {
            $sql = "SELECT * FROM {$type} WHERE rating = {$filter} ORDER BY `{$order_by}` ASC";
        }
        
    	//Execute the filter query.
        $result = $conn->query($sql);

    	//Build the new HTML that will be passed back to the requester.
        $new_html = "";
    	while($row = $result->fetch_assoc()) {

    		//Format the film's watched timestamp.
    		$epoch = $row["timestamp"];
    	    $dt = new DateTime("@$epoch");
    	    $dt_formatted = $dt->format('F j, Y');

    	    //If the timestamp is the placeholder value, replace it with text.
    	    if ($epoch == 1220508057) {
    	    	$dt_formatted = "a long time ago.";
    	    }

    		//Set the return HTML based on the pop culture type.
            if ($type == "film") {

                $new_html = $new_html . "
                
                <div class=\"popcultureitemcontainer\">
                
                    <div class=\"popcultureitemname\"><a href=\"{$row["imdb_url"]}\" target=\"_blank\">{$row["film"]}</a><br /><div class=\"popcultureitemproperties\">{$row["directors"]} | Released {$row["release_year"]} | Watched {$dt_formatted}</div></div><div class=\"popcultureitemrating\"><img src=\"../images/{$row["rating"]}.gif\" /></div>          
                
                </div>
                
                ";

            } else if ($type == "tv") {

                $new_html = $new_html . "

                <div class=\"popcultureitemcontainer\">
                
                    <div class=\"popcultureitemname\"><a href=\"{$row["link"]}\" target=\"_blank\">{$row["show"]}</a><br /><div class=\"popcultureitemproperties\">Watched {$dt_formatted}</div></div><div class=\"popcultureitemrating\"><img src=\"../images/{$row["rating"]}.gif\" /></div>                      
                </div>

                ";

            } else if ($type == "lit") {

                $new_html = $new_html . "

                <div class=\"popcultureitemcontainer\">
                                    
                    <div class=\"popcultureitemname\"><a href=\"{$row["link"]}\" target=\"_blank\">{$row["book"]}</a><br /><div class=\"popcultureitemproperties\">{$row["author"]} | Read {$dt_formatted}</div></div><div class=\"popcultureitemrating\"><img src=\"../images/{$row["rating"]}.gif\" /></div>    
                                      
                </div>

                ";

            }        

    	}

        //Return the final HTML to the requester.
        echo $new_html;
        break;
}

//Return the difference between two dates.
function dateDifference($date_1, $date_2, $differenceFormat)
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
    
    $interval = date_diff($datetime1, $datetime2);
    
    return $interval->format($differenceFormat);
    
}