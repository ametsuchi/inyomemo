<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    // amazon
    protected function amazonItemLookup($isbn)
    {
    	$params = array();
 
    	// 必須
    	$access_key_id = env('AMAZON_ACCESS_ID','');
    	$secret_access_key = env('AMAZON_SECRET_ACCESS_KEY','');
    	$params['AssociateTag'] = env('ASSOCIATE_TAG','');
    	$baseurl = 'http://ecs.amazonaws.jp/onca/xml';
 
    	// パラメータ
    	$params['Service'] = 'AWSECommerceService';
    	$params['AWSAccessKeyId'] = $access_key_id;
    	$params['Version'] = '2011-08-01';
    	$params['Operation'] = 'ItemLookup';
    	$params['ItemId'] = $isbn;
    	$params['ResponseGroup'] = 'ItemAttributes,Images';
    	$params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
    	ksort($params);
 
    	// 送信用URL・シグネチャ作成
    	$canonical_string = '';
    	foreach ($params as $k => $v) {
        	$canonical_string .= '&' . $this->urlencode_RFC3986($k) . '=' . $this->urlencode_RFC3986($v);
    	}
    	$canonical_string = substr($canonical_string, 1);
    	$parsed_url = parse_url($baseurl);
    	$string_to_sign = "GET\n{$parsed_url['host']}\n{$parsed_url['path']}\n{$canonical_string}";
    	$signature = base64_encode(hash_hmac('sha256', $string_to_sign, $secret_access_key, true));
    	$url = $baseurl . '?' . $canonical_string . '&Signature=' . $this->urlencode_RFC3986($signature);
 
    	$amazon_xml = $this->requestbook($url);
    	$xmlObj = simplexml_load_string($amazon_xml);
    	$data = get_object_vars($xmlObj);
    	
    	if (!(array_key_exists("Items", $data))){
    		info("not data.");
    		return array();
    	}

    	$item = $data["Items"]->Item;

    	$results = array();
    	$results['title'] = $item->ItemAttributes->Title;
    	$results['author'] = $item->ItemAttributes->Author;
    	$results['image'] = $item->LargeImage->URL;
    	$results['mimage'] = $item->MediumImage->URL;
    	$results['simage'] = $item->SmallImage->URL;
        $results['url'] = $item->DetailPageURL;
    	return $results;
    }

    protected function amazonItemSearch($str,$page)
    {
    	$params = array();
 
    	// 必須
    	$access_key_id = env('AMAZON_ACCESS_ID','');
    	$secret_access_key = env('AMAZON_SECRET_ACCESS_KEY','');
    	$params['AssociateTag'] = env('ASSOCIATE_TAG','');
    	$baseurl = 'http://ecs.amazonaws.jp/onca/xml';
 
    	// パラメータ
    	$params['Service'] = 'AWSECommerceService';
    	$params['AWSAccessKeyId'] = $access_key_id;
    	$params['Version'] = '2011-08-01';
    	$params['Operation'] = 'ItemSearch';
    	$params['SearchIndex'] = 'Books';
    	$params['Keywords'] = $str;
    	$params['ItemPage'] = $page;
    	$params['ResponseGroup'] = 'ItemAttributes,Images';
    	$params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
    	// とりあえずKindle除外
    	$params['Power'] = "binding:not kindle";
    	ksort($params);
 
    	// 送信用URL・シグネチャ作成
    	$canonical_string = '';
    	foreach ($params as $k => $v) {
        	$canonical_string .= '&' . $this->urlencode_RFC3986($k) . '=' . $this->urlencode_RFC3986($v);
    	}
    	$canonical_string = substr($canonical_string, 1);
    	$parsed_url = parse_url($baseurl);
    	$string_to_sign = "GET\n{$parsed_url['host']}\n{$parsed_url['path']}\n{$canonical_string}";
    	$signature = base64_encode(hash_hmac('sha256', $string_to_sign, $secret_access_key, true));
    	$url = $baseurl . '?' . $canonical_string . '&Signature=' . $this->urlencode_RFC3986($signature);
 
    	$amazon_xml = $this->requestbook($url);
    	$xmlObj = simplexml_load_string($amazon_xml);
    	$data = get_object_vars($xmlObj);
    	
    	if (!(array_key_exists("Items", $data))){
    		info("not data.");
    		return array();
    	}

    	$items = array();
    	foreach ($data["Items"]->Item as $item) {
    		$result = array();
    		$result["title"] = $item->ItemAttributes->Title;
    		$result["author"] = $item->ItemAttributes->Author;
    		$result["image"] = $item->MediumImage->URL;
    		$result["isbn"] = $item->ItemAttributes->ISBN;
            $result["publicationDate"] = $item->ItemAttributes->PublicationDate;
    		// MediumImageになかった場合はImageSetsに入ってるはず
    		/*
    		if($result["image"] == null){
    			if(count($item->ImageSets->ImageSet) > 0){
	    			foreach ($item->ImageSets->ImageSet as $imageset) {
    					if($imageset["Category"] == "primary"){
    						$result["image"] = $imageset->MediumImage->URL;
    					}
    				}
    			}
    		}
    		// Category=variantしかないぞ…？
    		if($result["image"] == null){
    			if(count($item->ImageSets->ImageSet) > 0){
    				$result["image"] = $item->ImageSets->ImageSet->MediumImage->URL;
    			}
    		}*/
    		array_push($items,$result);
    	}
        $results = [];
        $results["items"] = $items;
    	$results["totalPages"] = intval($data["Items"]->TotalPages);
    	return $results;
    }


    function urlencode_RFC3986($str)
	{
    	return str_replace('%7E', '~', rawurlencode($str));
	}
 
	function requestbook($url){
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    	$response = curl_exec($ch);
    	curl_close($ch);
     	return $response;
	}


}
