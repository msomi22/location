
<?php

include("db.php");

function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}



function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    }
    elseif(preg_match('/OPR/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 

$browser=getBrowser();


/*

This software is a property of AlanDick & Co East Africa
Everyone is restricted from copying or distributing this software 
not unless with permission from AlanDick & Co East Africa.

Author <p> mwendapeter72@gmail.com </p> Peter Mwenda


PHP script that receive an ajax call will latitude and longitude
The two parameters are then sent to google location API and the location is displayed
*/


//make sure the latitude and longitude are not empty before invoking the API
if(!empty($_POST['latitude']) && !empty($_POST['longitude'])){
    //Send request with latitude and longitude to Google API and receive response with json data 
    $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($_POST['latitude']).','.trim($_POST['longitude']).'&sensor=false';
    $json = @file_get_contents($url);
    $data = json_decode($json);
    //Get the response status 
    $status = $data->status;
    //if status is Ok
    if($status=="OK"){
        //Get the location name and/or description 
        $location = $data->results[0]->formatted_address;
        //Save the details to database

        $uuid = gen_uuid();
        $personid = "";
        $agent = $browser['name'] . " " . $browser['version'];
        $latitude = pg_escape_string($_POST['latitude']); 
        $longitude = pg_escape_string($_POST['longitude']); 

        $query = "INSERT INTO Geodata(uuid,personid,agent,latitude,longitude) 
                  VALUES('" . $uuid . "', '" . $personid . "', '" . $agent . "','" . $latitude . "','" . $longitude . "')";
        $result = pg_query($query);

        if (!$result) { 
            echo "Error";  
            exit(); 
        }
        pg_close(); 


    }else{
        $location =  '';
    }

    // output the location to the user 
    $lat = trim($_POST['latitude']);
    $long = trim($_POST['longitude']); 

    //echo "lat: " . $lat . " long: " .$long ;

    echo $location; 
    echo "<br> 
     <a href='http://maps.google.com/maps?q='.$lat.','.l$ong' target='_blank'>  view on map </a>";  
}
?>