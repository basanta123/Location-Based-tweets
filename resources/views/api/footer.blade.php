<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAtUnF1kdBGQ_OoFap1XnZWx10x6wgxsnw&callback=initMap"
    async defer></script>
  
  <script>
  var map;
  
  
 
   
  
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: {{$latitude}}, lng: {{$longitude}}},
          zoom: 12

        });
        var myTitle = document.createElement('h1');
        myTitle.style.color = 'red';
        myTitle.innerHTML = 'Tweets about {{$city}}';
        var myTextDiv = document.createElement('div');
        myTextDiv.appendChild(myTitle);

map.controls[google.maps.ControlPosition.TOP_CENTER].push(myTextDiv);
        
       @for($i=0;$i<count($tweets); $i++)
      
        <?php  $tweet = $tweets[$i];?>


       
      
       
        

        var contentString = '<div id="content">'+
      '<div id="siteNotice">'+
      '</div>'+
      '<h1 id="firstHeading" class="firstHeading">{{$tweet[4]}}</h1>'+
      '<div id="bodyContent">'+
      '<p>{{json_encode($tweet[3])}}</p>'+
      
      '</div>'+
      '</div>';

   window['varinfowindow{{$i}}']  = new google.maps.InfoWindow({
    content: contentString
  });

  var marker = new google.maps.Marker({
    position: {lat: {{$tweet[0]}}, lng: {{$tweet[1]}}},
    map: map,
    icon: '{{$tweet[2]}}'
  });

  marker.addListener('click', function() {
    window['varinfowindow{{$i}}'] .open(map, marker);
  });

@endfor

}
  
     
    
  </script>

</body>
</html>