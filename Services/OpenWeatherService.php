<?php

class OpenWeatherService 
{
    private $baseURI = "https://api.openweathermap.org/data/2.5";
    private $client;
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = "a4a508a510e839342a4907fd149cf2f0";
        $this->client = curl_init();
        // curl_setopt(CurlHandle $handle, int $option, mixed $value): bool
        curl_setopt($this->client, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:85.0) Gecko/20100101 Firefox/85.0');
        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, true);
    }

    public function getWeatherByCity (string $name): array
    {
        $payload = http_build_query([
            'q' => $name,
            'appId' => $this->apikey
        ]);

        $uri = $this->baseURI. "/weather?" . $payload;

        curl_setopt($this->client, CURLOPT_URL, $uri);
        $result = json_decode(curl_exec($this->client), true);

        $celsius = $result['main']['temp'] - 272.15;

        $raining = false;
        $weathers = [];

        foreach ($result['weather'] as $weather) {
            $weathers[] = $weather['main'];
            if ($weather['main'] == "Rain") {
                $raining = true;
                break;
            }
        }

        return [
            'temp' => $celsius,
            'weather' => $weathers,
            'raining' => $raining
        ];

    }
}