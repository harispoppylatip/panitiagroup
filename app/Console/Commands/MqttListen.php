<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use PhpMqtt\Client\MqttClient;

class MqttListen extends Command
{
    protected $signature = 'mqtt:listen';

    public function handle()
    {
        $mqtt = new MqttClient(
            config('services.mqtt.Mqtt_broker'),
            1883,
            config('services.mqtt.Client_ID')
        );

        $mqtt->connect();

        $mqtt->subscribe(
            config('services.mqtt.Client_Subcribe'),
            function ($topic, $message) {

                Cache::put(
                    'latest_bms',
                    json_decode($message, true)
                );

                echo "Data diterima\n";
            },
            0
        );

        $mqtt->loop(true);
    }
}