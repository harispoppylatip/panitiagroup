<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use PhpMqtt\Client\MqttClient;

class MqttListen extends Command
{
    protected $signature = 'mqtt:listen {--seconds=55 : Berhenti sendiri setelah N detik (0 = jalan terus)}';

    protected $description = 'Dengarkan topik MQTT dan simpan data terakhir ke cache latest_bms';

    public function handle()
    {
        $broker = config('services.mqtt.Mqtt_broker');
        $topic = config('services.mqtt.Client_Subcribe');

        if (!$broker || !$topic) {
            $this->warn('MQTT_BROKER / MQTT_SUBSCRIBE belum diisi, dilewati.');
            return self::SUCCESS;
        }

        $mqtt = new MqttClient(
            $broker,
            1883,
            // client id unik per proses supaya tidak saling menendang di broker
            (config('services.mqtt.Client_ID') ?: 'paz') . '-' . getmypid()
        );

        try {
            $mqtt->connect();
        } catch (\Throwable $e) {
            $this->error('Gagal konek MQTT: ' . $e->getMessage());
            return self::FAILURE;
        }

        $mqtt->subscribe(
            $topic,
            function ($topic, $message) {

                Cache::put(
                    'latest_bms',
                    json_decode($message, true)
                );

                echo "Data diterima\n";
            },
            0
        );

        // Dijadwalkan tiap menit: proses lama selesai sendiri sebelum yang baru jalan
        $batas = (int) $this->option('seconds');
        if ($batas > 0) {
            $mqtt->registerLoopEventHandler(function (MqttClient $client, float $elapsed) use ($batas) {
                if ($elapsed >= $batas) {
                    $client->interrupt();
                }
            });
        }

        $mqtt->loop(true);
        $mqtt->disconnect();

        return self::SUCCESS;
    }
}
