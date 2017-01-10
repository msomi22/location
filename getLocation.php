
<?php
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
    }else{
        $location =  '';
    }

    // output the location to the user 
    echo $location;
}
?>