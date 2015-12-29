<?php
require_once __DIR__ . '/BaseClient.php';

class MallCategoryGet extends BaseClient {
  protected $apikey = '';
  protected $secret = '';

  public function getMallCategory() {
	$url = $this->getUrl('v1/MallCategory/Get');
	$data = [
		'ApiKey'     => $this->apikey,
		'TimeStamp'  => $this->getTimestamp(),
		'Format'     => 'json',
		'CategoryId' => 0,
	];
	$data['Signature'] = $this->getSign($this->secret, $data);
	print_r($data);

	$args = $this->get_url_string($data, false);
	echo $url . "?$args" . PHP_EOL;

    $out = $this->fetch_data_files($url, $data);
	return $out;
  }
}

$obj = new MallCategoryGet();
$ret = $obj->getMallCategory();

print_r(json_decode($ret, 1));
echo PHP_EOL;
