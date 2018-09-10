<?php
namespace App;

use Exception;
use Torann\GeoIP\Support\HttpClient;
use Torann\GeoIP\Services\AbstractService;

class geo2IPua extends AbstractService
{
    /**
     * Http client instance.
     *
     * @var HttpClient
     */
    protected $client;

    /**
     * The "booting" method of the service.
     *
     * @return void
     */
    public function boot()
    {
        $this->client = new HttpClient([
            'base_uri' => 'https://api.2ip.ua/',
            /*
            'query' => [
                'some_option' => $this->config('some_option'),
            ],*/
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function locate($ip)
    {
        // Get data from client
        $data = $this->client->get('geo.json?ip=' . $ip);


        // Verify server response
        if ($this->client->getErrors() !== null) {
            throw new Exception('Request failed (' . $this->client->getErrors() . ')');
        }

        // Parse body content
        $json = json_decode($data[0]);

        return $this->hydrate([
            'ip' => $ip,
            'iso_code' => $json->country_code,
            'country' => $json->country_rus,
            'city' => $json->city_rus,
            'state' => $json->region_rus,
            'state_name' => $json->region_rus,
            'postal_code' => $json->zip_code,
            'lat' => $json->latitude,
            'lon' => $json->longitude,
            'timezone' => $json->time_zone,
            'continent' => $json->country,
        ]);
    }

    /**
     * Update function for service.
     *
     * @return string
     */
    public function update()
    {
        return "Continent file updated.";
    }
}