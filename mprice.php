<!DOCTYPE html>
<html> 
<head>
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script  defer src="https://maps.googleapis.com/maps/api/js?libraries=places&language=en&key=AIzaSyBUtnGzrMS7PmKN6SH9unAyoBr9SpxhHnw"  type="text/javascript"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<style> 
.alert {
  padding: 20px;
  background-color: #2196F3;
  color: white;
}

.closebtn {
  margin-left: 15px;
  color: white;
  font-weight: bold;
  float: right;
  font-size: 22px;
  line-height: 20px;
  cursor: pointer;
  transition: 0.3s;
}

.closebtn:hover {
  color: black;
}
</style>
	<title></title>
</head>
<body>
	<div class="container">
		<form id="distance_form "action="###" method="post">
        	<center><label><i class="icon-lock"></i> <b>Choose Destination</b></label></center>
        <div class="form-group">
			Transport Costs :<input id="textbox1" readonly class="form-control"/>
			<input type="text" id="in_kilo" name="in_kilo" class="form-control" value="Distance In kilo" disabled>
			<input type="hidden" id="dprice" name="dprice" class="form-control">
        	<input type="text" id="to_places" name="location" style="margin-bottom: 10px;" class="form-control col-md-7 col-xs-12"
        	required />
        	<div id="dvMap" style="width: 100%; height: 300px;"></div>
        </div>
        	<button type="button" id="calpr" name="calpr" class="btn btn-primary" >Submit</button>
		</form>
	
		<div id="result"></div>
		
	<div class="alert">
		<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
  <strong>Formula</strong> Formula **First 20 Kilometres = 200 Thai Baht after that 60 Baht per 10 Kilometres.
		
	</div>
	</div>
<script>
    $(function() {
        // add input listeners
        google.maps.event.addDomListener(window, 'load', function () {
            var to_places = document.getElementById('to_places').value;
        });
		
        // calculate distance
        function calculateDistance() {
			
			var input = document.getElementById('to_places').value;
			var latlngStr = input.split(',', 2);
			var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
			var origin = new google.maps.LatLng(18.806648,98.971026); //origin Address
			var destination = new google.maps.LatLng(latlng);
            var service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix(
                {
                    origins: [origin],
                    destinations: [destination],
                    travelMode: google.maps.TravelMode.DRIVING,
                    unitSystem: google.maps.UnitSystem.IMPERIAL, // miles and feet.
                    // unitSystem: google.maps.UnitSystem.metric, // kilometers and meters.
                    avoidHighways: false,
                    avoidTolls: false
                }, callback);
        }
        // get distance results
        function callback(response, status) {
            if (status != google.maps.DistanceMatrixStatus.OK) {
                $('#result').html(err);
            } else {
                var origin = response.originAddresses[0];
                var destination = response.destinationAddresses[0];
                if (response.rows[0].elements[0].status === "ZERO_RESULTS") {
                	$
                    $('#result').html("There are no roads between "  + origin + " and " + destination);
                } else {
                    var distance = response.rows[0].elements[0].distance;
                    var duration = response.rows[0].elements[0].duration;
                    console.log(response.rows[0].elements[0].distance);
                    var distance_in_kilo = distance.value / 1000; // the kilometre
                    var distance_in_mile = distance.value / 1609.34; // the mile
                    var duration_text = duration.text;
                    var duration_value = duration.value;
					if(distance_in_kilo < 20){
						var a = 200.00;
						var textbox1 = document.getElementById('textbox1');
						textbox1.value = a.toFixed(2) +" " + "Baht";
						dprice.value = a.toFixed(2);
						in_kilo.value = distance_in_kilo +" " + "Km";
					}
					else{
						/*
						Formula

						If distance between Origin and Destination < 20 Kilo They will charged 200 Thai Baht
						First 20 Kilometres Is 200 Baht,After that in this case  10 Kilo Per 60 Baht and plus 200 (first 20 kilo) = costs

						Distance_in_kilo - 20(first 20 kilometres) / (10 kilo) * (60 Baht) + 200 (costs for first 20 kilo) = 

						*/
						var a = (distance_in_kilo - 20) / (10) * (60) + 200;
						var textbox1 = document.getElementById('textbox1');
						textbox1.value = a.toFixed(2) +" " + "Baht";
						dprice.value = a.toFixed(2);
						in_kilo.value = distance_in_kilo +" " + "Km";
						
					}

                }
            }
        }
        // print results on submit the form
		$("#calpr").click(function(e) {
            e.preventDefault();
            calculateDistance();
        });

    });

</script>

<script type="text/javascript">

    window.onload = function () {
		
        var mapOptions = {
            center: new google.maps.LatLng(18.806648,98.971026), // Destination Address
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var marker;
        var infoWindow = new google.maps.InfoWindow();
        var latlngbounds = new google.maps.LatLngBounds();
        var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
        google.maps.event.addListener(map, 'click', function (e) {
            $("input[name='location']").val(e.latLng.lat() + "," + e.latLng.lng());
            var myLatLng = {lat: e.latLng.lat(), lng: e.latLng.lng()};

            if ( marker ) {
                marker.setPosition(myLatLng);
            } else {
                marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    title: 'Marked Point' //name of the marker
                });
            }
        });
    }


</script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?keyPUTYOUAPIKEYHERE&callback=initMap">
</script>
</body>
</html>
