@include('api.header')

<form role="form" method="post" action="">
  <div class="form-group">
    <div class="col-md-6">
    <input type="text" class="form-control" id="city" placeholder="Write the name of the City" name="city">
	</div>
	<div class="col-md-6">
	<button type="submit" class="btn btn-primary">Search</button>
	</div>
  </div>
</form>

<div id="map"></div>

@include('api.footer')
	
	