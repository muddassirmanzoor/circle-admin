<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Helpers\CommonHelper;
use GuzzleHttp\Psr7;

class ApiService {

    public static function guzzleRequest($type = 'POST', $end_Point, $data = []) {
        try {
            $URL = config('paths.SERVICES_APP_BASE_URL') . $end_Point;
            $client = new Client(['base_uri' => config('paths.SERVICES_APP_BASE_URL')]);
            $request_data = [
                'headers' => [
                    'apikey' => config('paths.SERVICES_APP_KEY'),
                ],
                'json' => $data
            ];
            if (!empty($data)) {
                $res = $client->request($type, $URL, $request_data);
            } else {
                $res = $client->request($type, $URL, $request_data);
            }
            $status = $res->getStatusCode();
            if ($status == 200) {
                $resBodyContents = $res->getBody()->getContents();
                $resBodyContents = json_decode($resBodyContents, true);
                return $resBodyContents;
            } else {
                return CommonHelper::errorResponse('Server responded with a status code of ' . $status);
            }
        } catch (RequestException $e) {
            $req = Psr7\str($e->getRequest());
            return CommonHelper::errorResponse($req);
            // echo Psr7\str($e->getRequest());
            // if ($e->hasResponse()) {
            //       echo Psr7\str($e->getResponse());
            // }
        }
    }

//    public function launchRequest($method, $endPoint, $data) {
//        try {
//            $client = new Client();
//            $api_base_url = stristr(request()->getUri(), 'localhost') ? env('API_BASE_URL_LOCAL') . $endPoint : env('API_BASE_URL_LIVE') . $endPoint;
//            $response = $client->request($method, $api_base_url, [
//                'headers' => [
//                    'apikey' => 'base64:t0qrkPVhFVYmHO3p5WaebnsvcbmRYDIXZ8IY/ZB6QyQ=',
//                ],
//                'form_params' => $data
//            ]);
//            $status = $response->getStatusCode();
//            if ($status == 200) {
//                $resBodyContents = $response->getBody()->getContents();
//                $resBodyContents = json_decode($resBodyContents, true);
//                return $resBodyContents;
//            } else {
//                return self::error_response('Server responded with a status code of ' . $status);
//            }
//            $val = $response->getBody()->getContents();
//            return json_decode($val);
//        } catch (RequestException $e) {
//            $req = Psr7\str($e->getRequest());
//            return self::error_response($req);
//            // echo Psr7\str($e->getRequest());
//            // if ($e->hasResponse()) {
//            //       echo Psr7\str($e->getResponse());
//            // }
//        }
//    }
}
