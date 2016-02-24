<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Abraham\TwitterOAuth\TwitterOAuth;



class ApiController extends Controller
{
    /**
     *limit for tweets to show and the radius of the location
     *passed as a query parameter to the twitter search api  
     */
    private $limit=24;
    private $radius='50km';

    /**
	 * Process a data to show on the map.
	 *
	 * @return the  map view api.index
	 */
    public function index(Request $request){
        $city=$request->input('city','Kathmandu');
        if($city==""){
            $city='Kathmandu';

        }
        $city_google=str_replace(' ','+',$city);
    	
    	$coordinate=$this->getLatitudeLongitude($city_google);
        if($coordinate){
        $latitude=$coordinate['latitude'];
        $longitude=$coordinate['longitude'];
        $tweets=$this->getTweets($city,$latitude,$longitude);
        //print_r($tweets);
    	return view('api.index')->with([
            'latitude'=>$latitude,
            'longitude'=>$longitude,
            'tweets'=>$tweets,
            'city'=>$city
        ]);
        }
    }

    /**
     *get latitude and longitude by inputting the city name  using google geocoding api
     *
     * @param  $city: name of the city .type:string
     * @return latitude and longitude in an array
     */
    private function getLatitudeLongitude($city){
    	$url='http://maps.googleapis.com/maps/api/geocode/json?address='.$city.'&sensor=false';
        $apiData=json_decode(file_get_contents($url));
        $result=array();
        if($apiData->status=="OK"){
            $result=array(
                           "latitude"=>$apiData->results[0]->geometry->location->lat,
                           "longitude"=>$apiData->results[0]->geometry->location->lng
                         );
        }
        
        return $result;
    }

    /**
     *get tweets by inputting the city name,latitude and longitude  using twitter search api
     *
     * @param  $city: name of the city .type:string $latitude .latitude of the city $longitude.longitude of the city
     * @return tweets data in an array
     */
    private function getTweets($city,$latitude,$longitude){
        
        $twitter = new TwitterOAuth(env('CONSUMER_KEY'), env('CONSUMER_SECRET'), env('ACCESS_TOKEN'), env('ACCESS_TOKEN_SECRET'));
        $twitter->setTimeouts(20, 30);

        $twitterArgs = array(
            'q'           => $city,
            'count'       => $this->limit, 
            'geocode'     => $latitude.','.$longitude.','.$this->radius, 
            'result_type' => 'recent'
        );

        $tweets=array();
        $apiData = $twitter->get('search/tweets', $twitterArgs);
        foreach($apiData->statuses as $result){
            if($result->coordinates){
                $latitude= $result->coordinates->coordinates[1];
                $longitude=$result->coordinates->coordinates[0];
                $icon=$result->user->profile_image_url;
                $tweet=$result->text;
                $date=$result->created_at;
                $tweets[]=array($latitude,$longitude,$icon,$tweet,$date);

            }
        }
        return $tweets;
    }

}
