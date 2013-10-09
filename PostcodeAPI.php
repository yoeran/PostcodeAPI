<?php

class PostcodeAPI
{
  /*
  * Get your API key at: http://www.postcodeapi.nu/
  * API Docs: http://api.postcodeapi.nu/docs/
  */
  private $API_URL = 'http://api.postcodeapi.nu/';
  private $API_KEY = 'GET_YOUR_OWN';

  /**
   * Setup API with key
   * @param string $api_key PostcodeAPI.nu api-key
   */
  public function __construct($api_key)
  {
    $this->API_KEY = $api_key;
  }


  /**
   * Get information about zipcode
   * @param  string $pc zipcode
   * @return object     object with
   */
  public function getInfo( $pc )
  {
    return $this->request( $pc );
  }


  /**
   * Get distance between two zipcodes.
   * @param  string $pc1 Start zipcode
   * @param  string $pc2 End zipcode
   * @return float       Distance in meters
   */
  public function getDistance( $pc1, $pc2 )
  {
    $start  = $this->request( $pc1 );
    $end  = $this->request( $pc2 );

    if( false === $start ) return false;
    if( false === $end ) return false;

    return $this->getCoordDistance( $start->latitude, $end->latitude, $start->longitude, $end->longitude );   
  }


  /**
   * Get distance between two coordinates using the haversine formula.
   * @param  float $lat1 Start latitude
   * @param  float $lat2 End latitude
   * @param  float $lon1 Start longitude
   * @param  float $lon2 End longitude
   * @return float       Distance in meters
   */
  public function getCoordDistance( $lat1, $lat2, $lon1, $lon2 )
  {
    $R    = 6371; // mean earth readius (km)
    $dLat   = deg2rad( $lat2-$lat1 );
    $dLon   = deg2rad( $lon2-$lon1 );
    $lat1   = deg2rad( $lat1 );
    $lat2   = deg2rad( $lat2 );

    $a      = sin($dLat/2) * sin($dLat/2) + sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2); 
    $c      = 2 * atan2( sqrt($a) , sqrt(1-$a) ); 
    $distance   = $R * $c;

    return round($distance * 1000);
  }


  /**
   * API request
   * @param  string $url URL to load
   * @return object      JSON response converted to object
   */
  private function request( $pc )
  {
    $params = array();
    $pc_len = strlen( $pc );
    switch( $pc_len ){
      case 5:
        $params[] = 'type=p5';
        break;

      case 4:
        $params[] = 'type=p4';
        break;

      default:
        $params[] = 'type=p6';
        break;
    }

    $url    = $this->API_URL . $pc . '?' . implode('&', $params);
    $ch     = curl_init();

    curl_setopt($ch, CURLOPT_URL,       $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Api-Key: ' . $this->API_KEY
      ));

    $result = curl_exec( $ch );

    curl_close( $ch );

    $json = json_decode( $result );

    if( $json->success === true ){
      return $json->resource;
    } else {
      return false;
    }
  }
}
