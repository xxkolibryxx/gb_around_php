<?php

namespace App\Queue;

use PhpAmqpLib\Channel\AbstractChanel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ implements Queue
{
    private AMQPMessage|null $lastMessage;
    private AbstractChanel|AMQPChannel $channel;
    private AMQPStreamConnection $connection;

    public function __construct(private string $queueName)
    {
        $this->lastMessage = null;
    }

    public function sendMessage($message): void
    {
        $this->open();
        $msg = new AMQPMessage($message, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $this->channel->basic_publish($msg, '', $this->queueName);
        var_dump($msg);
        $this->close();
    }

    public function getMessage():?string
    {
        $this->open();

        $msg = $this->channel->basic_get($this->queueName);

        if($msg){
            $this->lastMessage = $msg;
            return $msg->body;
        }

        $this->close();
        return null;
    }

    public function ackLastMessage() :void
    {
        $this->lastMessage?->ack();
        $this->close();
    }

    public function open():void
    {
        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($this->queueName, false, false, false, true);
    }

    public function close():void
    {
        $this->channel->close();
        $this->connection->close();
    }

}