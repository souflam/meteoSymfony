<?php

namespace App\Service;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Meteo;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\Psr6CacheStorage;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use App\Service\ContainerParametersHelper;


class MeteoService {

    
    private $pathHelpers;

    public function __construct(ContainerParametersHelper $pathHelpers)
    {
        $this->pathHelpers = $pathHelpers;
    }

    public function getMeteo($ville = "RABAT")
    {

        $url = 'https://weather-ydn-yql.media.yahoo.com/forecastrss';
        $app_id = 'X7f3bM32';
        $consumer_key = 'dj0yJmk9bkpWSnZwRUJ4WkZYJmQ9WVdrOVdEZG1NMkpOTXpJbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmc3Y9MCZ4PTU4';
        $consumer_secret = '9da097409a08c82c8e4ed93958acb95896881c91';
        $uniq_id = uniqid(mt_rand(1, 1000));
        $time = time();

        $query = $this->getQuery($ville);
        $oauth = array(
            'oauth_consumer_key' => $consumer_key,
            'oauth_nonce' => $uniq_id,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => $time,
            'oauth_version' => '1.0'
        );

        $base_info = $this->buildBaseString($url, 'GET', array_merge($query, $oauth));
        $composite_key = rawurlencode($consumer_secret) . '&';
        $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));



        $client = $this->getGuzzleFileCachedClient();
        $url = 'https://weather-ydn-yql.media.yahoo.com/forecastrss?location='. $ville .'&u=c&format=json';
        $headers = [
            'Authorization' => 'OAuth oauth_consumer_key="'. $consumer_key .'", oauth_nonce="'. $uniq_id .'", oauth_signature_method="HMAC-SHA1", oauth_timestamp="'. $time .'", oauth_version="1.0", oauth_signature="'. $oauth_signature .'"',
            'X-Yahoo-App-Id' => $app_id
        ];

        $response = $client->request('GET', $url, [
            'headers' => $headers
        ]);
        
        $response = $client->request('GET', $url);

        $dataMeteo = $response->getBody()->getContents();
        $dataMeteo_decode = \json_decode($dataMeteo)->forecasts;

        $meteoObject = new Meteo();

        foreach($dataMeteo_decode as $meteo) {
            if(date('Ymd') == date('Ymd', $meteo->date)) {
                $meteoObject->setUpdatedAt(new \DateTime());
                $meteoObject->setMaxTemperature($meteo->high);
                $meteoObject->setMinTemperature($meteo->low);
                $meteoObject->setTextMeteo($meteo->text);
                $meteoObject->setVille($ville);
            }
            
        }
       return $meteoObject;
    }

    public function getQuery($ville)
    {
        return [
            'location' => $ville,
            'u' => 'c',
            'format' => 'json',
        ];
    }

    /**
    * Returns a GuzzleClient that uses a file based cache manager
    *
    * @return Guzzle Http Client
    */
    private function getGuzzleFileCachedClient(){
        // Create a HandlerStack
        $stack = HandlerStack::create();

        // 10 minutes to keep the cache
        // This value will obviously change as you need
        $TTL = 600;

        // Create Folder GuzzleFileCache inside the providen cache folder path
        $requestCacheFolderName = 'GuzzleFileCache';

        // Retrieve the cache folder path of your Symfony Project
        $cacheFolderPath = $this->pathHelpers->getApplicationRootDir() . '/var/cache'; 
        
        // Instantiate the cache storage: a PSR-6 file system cache with 
        // a default lifetime of 10 minutes (60 seconds).
        $cache_storage = new Psr6CacheStorage(
            new FilesystemAdapter(
                $requestCacheFolderName,
                $TTL, 
                $cacheFolderPath
            )
        );
        
        // Add Cache Method
        $stack->push(
            new CacheMiddleware(
                new GreedyCacheStrategy(
                    $cache_storage,
                    $TTL // the TTL in seconds
                )
            ), 
            'greedy-cache'
        );
        
        // Initialize the client with the handler option and return it
        return new Client(['handler' => $stack]);
    }


    function buildBaseString($baseURI, $method, $params) {
        $r = array();
        ksort($params);
        foreach($params as $key => $value) {
            $r[] = "$key=" . rawurlencode($value);
        }
        return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
    }
    
}