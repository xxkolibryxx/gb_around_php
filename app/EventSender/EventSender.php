<?php
namespace App\EventSender;

use App\Telegram\TelegramApi;
use App\Queue\Queue;
use App\Queue\Queueable;

// class EventSender
// {
//     private TelegramApi $telegram;

//     public function __construct(TelegramApi $telegram) {
//         $this->telegram = $telegram;
//     }

//     public function sendMessage(string $receiver, string $message)
//     {
//         $this->telegram->sendMessage($receiver, $message);
//         echo date('d.m.y H:i') . " Я отправил сообщение $message получателю с id $receiver\n";
//     }
// }

class EventSender implements Queueable
{
    private string $receiver;
    private string $message;

    public function __construct(private TelegramApi $telegram, private Queue $queue)
    {
    }

    public function sendMessage(string $receiver, string $message)
    {
        $this->toQueue($receiver, $message);
    }

    public function handle(): void
    {
        var_dump('asdasd');
        $this->telegram->sendMessage($this->receiver, $this->message);
    }

    public function toQueue(...$args):void
    {
        $this->receiver = $args[0];
        $this->message = $args[1];

        $this->queue->sendMessage(serialize($this));
    }
}

