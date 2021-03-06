<?php
class Client
{
	private $instance_url;
	private $access_token;
	private $app;

	public function __construct($instance_url, $access_token = '') {
		$this->instance_url = $instance_url;
		$this->access_token = $access_token;
	}

	public function register_app($redirect_uri) {

		$result = $this->_post('/api/v1/apps', array(
			'client_name' => 'Mastodon Share for WordPress',
			'redirect_uris' => $redirect_uri,
			'scopes' => 'read write',
			'website' => $this->instance_url
		));

		$response = json_decode($result);

		$this->app = $response;

		$params = http_build_query(array(
			'response_type' => 'code',
			'scope' => 'read write',
			'redirect_uri' => $redirect_uri,
			'client_id' =>$this->app->client_id
		));

		return $this->instance_url.'/oauth/authorize?'.$params;
	}

	public function verify_credentials($access_token){

		$headers = array(
			'Authorization: Bearer '.$access_token
		);

		$response = $this->_get('/api/v1/accounts/verify_credentials', null, $headers);

		return $response;
	}

	public function get_bearer_token($client_id, $client_secret, $code, $redirect_uri) {

		$response = $this->_post('/oauth/token',array(
			'grant_type' => 'authorization_code',
			'redirect_uri' => $redirect_uri,
			'client_id' => $client_id,
			'client_secret' => $client_secret,
			'code' => $code
		));

		return json_decode($response);
	}

	public function get_client_id() {
		return $this->app->client_id;
	}

	public function get_client_secret() {
		return $this->app->client_secret;
	}

	public function postStatus($status, $mode, $media = '') {

		$headers = array(
			'Authorization: Bearer '.$this->access_token
		);

		$response = $this->_post('/api/v1/statuses', array(
			'status' => $status,
			'visibility' => $mode,
			'media_ids[]' => $media
		), $headers);

		return json_decode($response);
	}

	public function create_attachment($media_path) {
		$headers[] = 'Authorization: Bearer '.$this->access_token;

		$file = curl_file_create($media_path);
		$data = array('file' => $file);
		$response = $this->_post('/api/v1/media', $data, $headers);

		return json_decode($response);
	}

	private function _post($url, $data = array(), $headers = array()) {
		return $this->post($this->instance_url.$url, $data, $headers);
	}

	public function _get($url, $data = array(), $headers = array()) {
		return $this->get($this->instance_url.$url, $data, $headers);
	}

	private function post($url, $data = array(), $headers = array()) {

		$ch = curl_init($url);

		$options = array(
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_RETURNTRANSFER => true,
			//CURLOPT_HEADER => true
		);

		curl_setopt_array($ch, $options);

		$response = curl_exec($ch);

		return $response;
	}

	public function get($url, $data = array(), $headers = array()) {
		$ch = curl_init($url);
		$options = array(
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_RETURNTRANSFER => true,
			//CURLOPT_HEADER => true
		);

		curl_setopt_array($ch, $options);

		$response = curl_exec($ch);

		return json_decode($response);

	}

	public function dump($value){
		echo '<pre>';
		print_r($value);
		echo '</pre>';
	}
}