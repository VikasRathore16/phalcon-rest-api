<?php

namespace App\Listeners;

use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * NotificationListerners class
 */
class NotificationListeners extends Injectable
{
    /**
     * beforeSend function
     * handle main.log file 
     * @param Event $event
     * @param [array] $addarr
     * @param [array] $settings
     * @return void
     */
    public function createhook(Event $event, $array)
    {

        $webhook =  new \Users($this->mongo, 'store', 'webhook');
        $new =  $webhook->find([
            'events' => 'product.create',
        ]);
        foreach ($new as $value) {
            $base = $value['webhook_name'];
        }
        // die($base);
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
            'POST',
            $base,
            [
                'headers' => [
                    'Authorization' => "Bearer ",
                ],
                'body' => json_encode(
                    $array
                ),

            ],
        );
        // $webhook->insertOne(
        //     $array
        // );
    }
    public function updatehook(Event $event, $array)
    {
        $webhook =  new \Users($this->mongo, 'store', 'webhook');
        $new =  $webhook->find([
            'events' => 'product.update',
        ]);
        foreach ($new as $value) {
            $base = $value['webhook_name'];
        }
        // die($base);
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
            'POST',
            $base,
            [
                'headers' => [
                    'Authorization' => "Bearer ",
                ],
                'body' => json_encode(
                    $array
                ),

            ],
        );
        // $webhook->insertOne(
        //     $array
        // );
    }
}
