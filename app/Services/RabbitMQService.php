<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    protected $connection;
    protected $channel;
    protected $config;

    public function __construct()
    {
        $this->config = config('rabbitmq');
        $this->connect();
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function connect()
    {
        try {
            $this->connection = new AMQPStreamConnection(
                $this->config['host'],
                $this->config['port'],
                $this->config['user'],
                $this->config['password'],
                $this->config['vhost']
            );

            $this->channel = $this->connection->channel();
        } catch (\Throwable $exception) {
            Log::error("RabbitMQ connection failed: " . $exception->getMessage());
            throw new \Exception("RabbitMQ connection failed: " . $exception->getMessage());
        }
    }

    /**
     * @param string $exchange
     * @param string $routingKey
     * @param string $queue
     * @param array $data
     * @return void
     * @throws \Throwable
     */
    public function publish(string $exchange, string $routingKey, string $queue, array $data)
    {
        try {
            $this->channel->exchange_declare($exchange, 'direct', false, true, false);

            $this->channel->queue_declare($queue, false, true, false, false, false, [
                'x-dead-letter-exchange' => ['S', 'dead_letter_exchange'],
                'x-dead-letter-routing-key' => ['S', 'dead_letter'],
            ]);

            $this->channel->queue_bind($queue, $exchange, $routingKey);

            $messageBody = json_encode($data);
            $message = new AMQPMessage($messageBody, [
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            ]);

            $this->channel->basic_publish($message, $exchange, $routingKey);

        } catch (\Throwable $exception) {
            Log::error("RabbitMQ publish failed: " . $exception->getMessage());
            throw $exception;
        }
    }

    public function close()
    {
        if ($this->channel) {
            $this->channel->close();
        }
        if ($this->connection) {
            $this->connection->close();
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}
