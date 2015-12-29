<?php
class BaseClient
{
    protected $apikey = '';
    protected $secret = '';
    private $base_url= 'http://tw.ews.mall.yahooapis.com/stauth/';
    private $echo_api = 'http://tw.ews.mall.yahooapis.com/stauth/v1/echo?Format=json';

	protected function getUrl($apipath) 
	{
		$ret = $this->base_url . $apipath;
		return $ret;
	}

    protected function getTimestamp()
    {
	    $echo_api = 
        $timestamp = file_get_contents($this->echo_api);
        $dec = json_decode($timestamp, true);
        return $dec['Response']['TimeStamp'];
    }

    protected function fetch_data_files($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $out = curl_exec($ch);
        curl_close($ch);
        return $out;
    }

    protected function getSign($secret, $data) {
        $query_str = $this->get_url_string($data, false);
        return hash_hmac("sha1", $query_str, $secret);
    }

    protected function get_url_string($data, $is_urlencode){
        if($is_urlencode == true){
            return http_build_query($data);
        } else {
            $data2 = array();
            foreach($data as $k => $v)
                $data2[] = "{$k}={$v}";
            return implode("&", $data2);
        }
    }
}
?>

