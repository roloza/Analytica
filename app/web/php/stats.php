<?php

require '../../../vendor/autoload.php';

use Jenssegers\Agent\Agent;
use GeoIp2\Database\Reader;
use Medoo\Medoo;

$agent = new Agent();
$reader = new Reader('../../GeoIP/GeoLite2-City.mmdb');
$database = new medoo([
	'database_type' => 'mysql',
	'database_name' => '',
	'server' => '',
	'username' => '',
	'password' => '',
	'charset' => 'utf8',
]);

$ignoredIp = [];

$params = [];
if (isset($_REQUEST['status']) && $_REQUEST['status'] == 'ready') {
  $params['id'] = ($_REQUEST['id']) ? $_REQUEST['id'] : 0;
  $params['timeHtmlDomReady'] = ($_REQUEST['timeHtmlDomReady']) ? $_REQUEST['timeHtmlDomReady'] : '';
  $params['url'] = ($_REQUEST['url']) ? $_REQUEST['url'] : '';
  $params['referer'] = ($_REQUEST['referer']) ? $_REQUEST['referer'] : '';
  $params['refererDomain'] = ($_REQUEST['referer']) ? getDomain($params['referer']) : '';
  $params['refererType'] = getRefererType($_REQUEST['referer'], $_REQUEST['url']);
  $params['userAgent'] = ($_REQUEST['userAgent']) ? $_REQUEST['userAgent'] : '';
  $params['resolution'] = ($_REQUEST['resolution']) ? $_REQUEST['resolution'] : '';
  $params['resolutionWindow'] = ($_REQUEST['resolutionWindow']) ? $_REQUEST['resolutionWindow'] : '';
  $params['platform'] = $agent->platform();
  $params['platformType'] = ($agent->isDesktop()) ? 'desktop' : 'mobile';
  $params['browser'] = $agent->browser();
  $params['languages'] = $agent->languages();
  $params['isRobot'] = ($agent->isRobot()) ? true : false;
  $params['user_ip'] = IP::getIpInfos($reader);

	if (in_array($params['user_ip']['ip'], $ignoredIp)) {
		var_dump($params['referer']);
		var_dump($params['refererDomain']);
		echo $params['user_ip']['ip'].' ignorée'; die;
  }
  
  $database->insert('stats', [
    'ident' => $params['id'],
    'ip' => $params['user_ip']['ip'],
    'url' => $params['url'],
    'referer' => $params['referer'],
	  'refererDomain' => $params['refererDomain'],
    'refererType' => $params['refererType'],
    'loadDom' => $params['timeHtmlDomReady'],
    'resolution' => $params['resolution'],
    'resolutionWindow' => $params['resolutionWindow'],
    'platform' => $params['platform'],
    'platformType' => $params['platformType'],
    'browser' => $params['browser'],
    'isRobot' => $params['isRobot'],
    'countryName' => $params['user_ip']['countryNameFR'],
    'departmentName' => $params['user_ip']['departmentName'],
    'cityName' => $params['user_ip']['cityName'],
    'latitude' => $params['user_ip']['latitude'],
    'longitude' => $params['user_ip']['longitude'],
    'updated_at' => date('Y-m-d H:i:s'),
    'created_at' => date('Y-m-d H:i:s')
  ]);

} elseif (isset($_REQUEST['status']) && $_REQUEST['status'] == 'load') {
  sleep(1);
  $params['id'] = ($_REQUEST['id']) ? $_REQUEST['id'] : '';
  $params['timeHtmlLoad'] = ($_REQUEST['timeHtmlLoad']) ? $_REQUEST['timeHtmlLoad'] : '';

  $database->update('stats', [
    'loadHtml' => $params['timeHtmlLoad']
  ],
  [
    'ident' => $params['id'],
  ]);
} elseif (isset($_REQUEST['status']) && $_REQUEST['status'] == 'exit') {
  $params['id'] = ($_REQUEST['id']) ? $_REQUEST['id'] : '';
  $params['timeVisiteUrl'] = ($_REQUEST['timeVisiteUrl']) ? $_REQUEST['timeVisiteUrl'] : '';
  $database->update('stats', [
    'timeVisiteUrl' => $params['timeVisiteUrl']
  ],
  [
    'ident' => $params['id'],
  ]);
}
function getRefererType($referer, $url) {

  if (isSearchEngineRefererType($referer)) {
    return 'searchEngine';
  } elseif (isInternalRefererType($referer, $url)) {
    return 'internal';
  }
  return 'external';
}

function isSearchEngineRefererType($referer) {
  $searchEngines = ['google', 'bing', 'yahoo'];
  foreach ($searchEngines as $key => $searchEngine) {
    if(strpos($referer, $searchEngine) !== false) {
      return true;
    }
  }
  return false;
}

function isInternalRefererType($referer, $url) {
  $itemsUrl = parse_url($url);
  $host = $itemsUrl['host'];
  if(strpos($referer, $host) !== false) {
    return true;
  }
  return false;
}

function getDomain($url) {
	$parsed_url = parse_url($url);
	$host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
	return $host;
}

class Ip {
  public static function getUserIP() {
      $client  = @$_SERVER['HTTP_CLIENT_IP'];
      $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
      $remote  = $_SERVER['REMOTE_ADDR'];

      if (filter_var($client, FILTER_VALIDATE_IP)) {
          $ip = $client;
      }
      elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
          $ip = $forward;
      }
      else {
          $ip = $remote;
      }
      return $ip;
  }

  public static function getIpInfos($reader) {
    $ip = Ip::getUserIP();
    $record = $reader->city($ip);
    $response = [
      'ip'              => $ip,
      'countryIsoCode'  => ($record->country->isoCode) ? $record->country->isoCode : '',
      'countryName'     => ($record->country->name) ? $record->country->name : '',
      'countryNameFR'   => ($record->country->names['fr']) ? $record->country->names['fr'] : '',
      'departmentName'  => ($record->mostSpecificSubdivision->name) ? $record->mostSpecificSubdivision->name : '',
      'departmentCode'  => ($record->mostSpecificSubdivision->isoCode) ? $record->mostSpecificSubdivision->isoCode : '',
      'cityName'        => ($record->city->name) ? $record->city->name : '',
      'cityPostalCode'  => ($record->postal->code) ? $record->postal->code : '',
      'latitude'        => ($record->location->latitude) ? $record->location->latitude : '',
      'longitude'       => ($record->location->longitude) ? $record->location->longitude : ''
    ];
    return $response;
  }
}

?>
