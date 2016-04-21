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

        // 著者が複数ある場合あり
        $author = "";
        foreach ($item->ItemAttributes->Author as $value) {
            $author = $author.",".$value;
        }
        $author = mb_substr($author, 1);

    	$results = array();
    	$results['title'] = $item->ItemAttributes->Title;
    	$results['author'] = $author;
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
        // 検索結果
    	$items = array();
        // 検索結果のＩＳＢＮのセット
        $isbns = array();
    	foreach ($data["Items"]->Item as $item) {
    		$result = array();
    		$result["title"] = $item->ItemAttributes->Title;
    		$result["author"] = $item->ItemAttributes->Author;
    		$result["image"] = $item->MediumImage->URL;
    		$result["isbn"] = $item->ItemAttributes->ISBN;
            // ISBNがない商品はASINをセット
            if($result["isbn"] == ""){
                $result["isbn"] = $item->ASIN;
            }
            $result["publicationDate"] = $item->ItemAttributes->PublicationDate;
            $result["url"] = $item->DetailPageURL;
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
            array_push($isbns,$result["isbn"]);
    	}
        $results = [];
        $results["items"] = $items;
        $results["isbns"] = $isbns;
    	$results["totalPages"] = intval($data["Items"]->TotalPages);
    	return $results;
    }

    protected function getCallbackUrl()
    {
        $thisUrl = (empty($_SERVER['HTTPS'])) ? "http://" : "https://";
        $thisUrl .= $_SERVER['SERVER_NAME'];
        $thisUrl .= ($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) ? "" : (":".$_SERVER['SERVER_PORT']);
        $thisUrl .= "/evernote/callback";

        return $thisUrl;
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
