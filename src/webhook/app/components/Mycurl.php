<?php

namespace App\Components;

use Phalcon\Di\Injectable;


/**
 * Mycurl class
 */
class Mycurl extends Injectable
{
    /**
     * find function
     * All API requests goes from this function
     * @param [string] $method
     * @param string $query
     * @param string $body
     * @return object
     */
    public function find(string $method, string $query = '', string $body = '', array $form = array()): ?object
    {
        //creating a new client
        $client = new \GuzzleHttp\Client();

        //checking method
        if ($method == 'GET') {
            $response = $client->request('GET', $this->config->get('url')['base_url'] . $query);
            // die($response);
            $response = (object)(json_decode($response->getBody(), true));
        }

        //checking method
        if ($method == "POST") {
            $response = $client->request(
                'POST',
                $this->config->get('url')['base_url'] . $query,
                [
                    'headers' => [
                        'Authorization' => "Bearer ",
                    ],
                    'form_params' => $form,
                    'body' => json_encode(
                        $body
                    ),

                ],
            );
        }

        //checking method
        if ($method == "DELETE") {
            $response = $client->request(
                'DELETE',
                $this->config->get('url')['base_url'] . $query,
                [
                    'headers' => [
                        'Authorization' => "Bearer " . $this->session->get('bearer'),
                    ],
                    'body' => json_encode(
                        $body
                    )
                ],
            );
        }
        return $response;
    }
}
