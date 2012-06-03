<?php
/**
 * File that stores api calls for user api calls
 * @package apicalls
 */
/**
 * Allows access to the api requests relating to users
 * @package apicalls
 */
class lastfmApiChart extends lastfmApi {
	/**
	 * Stores the config values set in the call
	 * @access public
	 * @var array
	 */
	public $config;
	/**
	 * Stores the auth variables used in all api calls
	 * @access private
	 * @var array
	 */
	private $auth;
	/**
	 * States if the user has full authentication to use api requests that modify data
	 * @access private
	 * @var boolean
	 */
	private $fullAuth;
	
	/**
	 * @param array $auth Passes the authentication variables
	 * @param array $fullAuth A boolean value stating if the user has full authentication or not
	 * @param array $config An array of config variables related to caching and other features
	 */
	function __construct($auth, $fullAuth, $config) {
		$this->auth = $auth;
		$this->fullAuth = $fullAuth;
		$this->config = $config;
	}
	
	
	/**
	 * Get the top artists listened to by a user. You can stipulate a time period. Sends the overall chart by default
	 * @param array $methodVars An array with the following required values: <i>user</i> and optional value: <i>period</i>
	 * @return array
	 */
	public function getTopArtists($methodVars) {
		// Check for required variables
		if ( !empty($methodVars['apiKey']) ) {
			$vars = array(
				'method' => 'chart.getTopArtists',
				'api_key' => $this->auth->apiKey
			);
			$vars = array_merge($vars, $methodVars);
			
			if ( $call = $this->apiGetCall($vars) ) {
				if ( count($call->topartists->artist) > 0 ) {
					$i = 0;
					foreach ( $call->topartists->artist as $artist ) {
						$topartists[$i]['name'] = (string) $artist->name;
						$topartists[$i]['rank'] = (string) $artist['rank'];
						$topartists[$i]['playcount'] = (string) $artist->playcount;
                                                $topartists[$i]['listeners'] = (string) $artist->listeners;
						$topartists[$i]['mbid'] = (string) $artist->mbid;
						$topartists[$i]['url'] = (string) $artist->url;
						$topartists[$i]['streamable'] = (string) $artist->streamable;
						$topartists[$i]['images']['small'] = (string) $artist->image[0];
						$topartists[$i]['images']['medium'] = (string) $artist->image[1];
						$topartists[$i]['images']['large'] = (string) $artist->image[2];
						$i++;
					}
					
					return $topartists;
				}
				else {
					$this->handleError(90, 'No hay Top de Artistas Globales');
					return FALSE;
				}
			}
			else {
				return FALSE;
			}
		}
		else {
			// Give a 91 error if incorrect variables are used
			$this->handleError(91, 'You must include artist variable in the call for this method');
			return FALSE;
		}
	}

}

?>