<!DOCTYPE html>
<!--
This software is a property of AlanDick & Co East Africa
Everyone is restricted from copying or distributing this software 
not unless with permission from AlanDick & Co East Africa.

Author <p> mwendapeter72@gmail.com </p> Peter Mwenda.

-->



<html lang="en-US">
<head>
<title> AlanDick & Co </title>
<meta name="author" content="Peter Mwenda">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="jquery/2.1.4/jquery.min.js"></script>

<style type="text/css">
    
    body{
    	background-color: #191e44;
    	font-size: 39px;
    	font-family: sans-serif;
    }

	p{ 
		width: auto; padding: 10px;
		font-size: 18px;
		border-radius: 5px;
		color: #FF7361;
		align-content: center;
	}
    span.label{ 
    	font-weight: bold; 
    	color: #000;
    }

    .content{
        height: 500px;
        width: 800px;
        background-color: #d4d4d4;
        align-content: center;
        margin: 30px auto;
    }

    .footer{
    	margin-top: 400px;
    	color: green;
    	height: 80px;
    	width: 800px;
    	position: relative;
    	font-size: 20px;
    	font-family: inherit;
    	align-content: center;
    	align:center;

    }

    .footer p{
      align-content: center;
      color: #191e44;
      height: 30px;
      width: 700px;
      margin-left: 260px;
    }
    	
</style>


<script>

/*When the page has fully loaded

*/
$(document).ready(function(){
	 /* Check if the browser support geolocation
	    and call the function 'showLocation'.
   
	 */
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showLocation);
    } else { 
    	/* Since the user browser doesn't support geolocation, give out this message */
        $('#location').html('Geolocation is not supported by this browser.');
    }
});

/*
function  showLocation
paremeter position 

This function finds the latitude and longitude  and make a ajax call to a php script 

*/
function showLocation(position) {
	var latitude = position.coords.latitude;
	var longitude = position.coords.longitude;
	$.ajax({
		type:'POST',
		url:'getLocation.php',
		data:'latitude='+latitude+'&longitude='+longitude,
		success:function(msg){
            if(msg){
               $("#location").html(msg);
            }else{
                $("#location").html('Not Available');
            }
		}
	});
}

/* End of the script */

</script>

</head>
<body>

<div class="content">
	<p><span class="label">Your Location:</span> <span id="location"></span></p>

	<div class="footer">
 	   <p> AlanDick & Co East Africa </p>
    </div>
</div>
 

</body>
</html>
