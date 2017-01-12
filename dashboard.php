<!DOCTYPE html>
<?php
  include("db.php");
 ?>

<html>
<head>
	<title></title>
</head>
<body>


           <table border="2px;">
			<thead>
			 <tr>				
			    <th>*</th>
			    <th>Uuid</th>
			    <th>userId</th>
			    <th>agent</th>
			    <th>accessTime</th>
			    <th>Latitude</th>
			    <th>Longatude</th>
			    <th>Location</th>
			    <th>View on map</th>
			 </tr>
			</thead>
			<tbody>

			<?php 
                 
            $result = pg_query($dbcon, "select * from Geodata");    
            if($result) {
            	$count = 1;
            	while($myrow = pg_fetch_assoc($result)) {                                
				         $lat = htmlspecialchars($myrow['latitude']);
                         $long = htmlspecialchars($myrow['longitude']); 
			          ?>									
				   <td>	<?php echo $count; ?> </td>
				   <td> <?php echo htmlspecialchars($myrow['uuid']); ?> </td>
				   <td> <?php echo htmlspecialchars($myrow['personid']); ?> </td>
				   <td> <?php echo htmlspecialchars($myrow['agent']); ?> </td> 
				   <td> <?php echo htmlspecialchars($myrow['accessdate']); ?> </td>
				   <td> <?php echo htmlspecialchars($myrow['latitude']); ?> </td>
				   <td> <?php echo htmlspecialchars($myrow['longitude']); ?> </td> 

				   <td> 
				      <?php
				      $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$long.'&sensor=false';
						    $json = @file_get_contents($url);
						    $data = json_decode($json);
						    //Get the response status 
						    $status = $data->status;
						    //if status is Ok
						    if($status=="OK"){
						        //Get the location name and/or description 
						        $location = $data->results[0]->formatted_address;
						        //Save the details to database
						    }else{
						        $location =  '';
						    }
						    echo $location; 
				      ?>
				   </td>
				 
				   <td> 
				      <button class="btn btn-minier btn-info">
						<a href="http://maps.google.com/maps?q=<?php echo $lat.','.$long ?>"  target="_blank"> view </a>
					  </button>
				   </td>
				</tr>
				<?php 
				      $count++;
                       }
                     }    
                   pg_close($dbcon);                                       
				 ?>

                 
			</tbody>
		</table>


</body>
</html>