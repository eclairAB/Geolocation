<?php

class GeoService {
    private $apiKey;

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function getCoordinates() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $geolocationApiUrl = "https://www.googleapis.com/geolocation/v1/geolocate?key={$this->apiKey}";

        $data = json_encode([
            'considerIp' => true,
            'wifiAccessPoints' => [],
            'cellTowers' => [],
            'homeMobileCountryCode' => 0,
            'homeMobileNetworkCode' => 0,
            'radioType' => 'unknown',
            'carrier' => 'unknown',
            'cellTowers' => [
                [
                    'cellId' => 0,
                    'locationAreaCode' => 0,
                    'mobileCountryCode' => 0,
                    'mobileNetworkCode' => 0,
                    'signalStrength' => 0
                ]
            ]
        ]);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $geolocationApiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);

        curl_close($ch);

        $geolocationData = json_decode($response, true);

        $latitude = $geolocationData['location']['lat'];
        $longitude = $geolocationData['location']['lng'];

        return $this->getCityAndStateCoord($latitude, $longitude);
    }

    public function getCityAndStateCoord($latitude, $longitude) {
        $geocodingApiUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$this->apiKey}";

        $response = file_get_contents($geocodingApiUrl);
        $data = json_decode($response, true);

        $city = '';
        $state = '';

        foreach ($data['results'][0]['address_components'] as $component) {
            if (in_array('locality', $component['types'])) {
                $city = $component['long_name'];
            } elseif (in_array('administrative_area_level_1', $component['types'])) {
                $state = $component['long_name'];
            }
        }

        return ['city' => $city, 'state' => $state];
    }

    public function getCityAndState($query) {
        // Constructing the API URL based on the query type
        $apiUrl = "https://maps.googleapis.com/maps/api/geocode/json?";
        
        if (isset($query['state'])) {
            $state = str_replace(' ', '+', $query['state']);
            $apiUrl .= "components=administrative_area:{$state}";
        } elseif (isset($query['city'])) {
            $city = str_replace(' ', '+', $query['city']);
            $apiUrl .= "address={$city}";
        } elseif (isset($query['postal_code'])) {
            $postal_code = str_replace(' ', '+', $query['postal_code']);
            $apiUrl .= "components=postal_code:{$postal_code}";
        } else {
            return ['error' => 'Invalid query parameters'];
        }

        $apiUrl .= "&key={$this->apiKey}";

        // Fetching data from the Geocoding API
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);

        // Extracting city and state information from the response
        $city = '';
        $state = '';

        if (!empty($data['results'])) {
            foreach ($data['results'][0]['address_components'] as $component) {
                if (in_array('locality', $component['types'])) {
                    $city = $component['long_name'];
                } elseif (in_array('administrative_area_level_1', $component['types'])) {
                    $state = $component['long_name'];
                }
            }
        } else {
            return ['error' => 'No results found'];
        }

        return ['city' => $city, 'state' => $state];
    }
}
