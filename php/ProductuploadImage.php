<?php
class ProductUploadImage
{
    private $apikey = '';
    private $secret = '';
    private $upload_image = 'http://tw.ews.mall.yahooapis.com/stauth/v1/Product/UploadImage';
    private $echo_api = 'http://tw.ews.mall.yahooapis.com/stauth/v1/echo?Format=json';

    public function getTimestamp()
    {
        $timestamp = file_get_contents($this->echo_api);
        $dec = json_decode($timestamp, true);
        return $dec['Response']['TimeStamp'];
    }

    public function upload_image() {
        $data = array();
        $pid = 'p1234566788';
        $data['ProductId'] = $pid;
        $image = array();
        $image['ImageFile1'] = '@/tmp/success.jpg';
        $image['ImageFile2'] = '@/tmp/success.jpg';

        $data['ApiKey'] = $this->apikey;
        //$data['TimeStamp'] = time();
        $data['TimeStamp'] = $this->getTimestamp();
        $data['Format'] = "xml";
        $data['MainImage'] = 'ImageFile1';
        $data['Purge'] = 'true';
        $data['Signature'] = $this->getSign($this->secret, $data);
        $new_data = array_merge($data, $image);

        $out = $this->fetch_data_files($this->upload_image, $new_data);

        return $out;
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

$obj = new ProductUploadImage();
$out = $obj->upload_image();
var_dump($out);

?>

