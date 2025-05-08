<?php
namespace App\Telegram;

use App\Telegram\TelegramApi;

class TelegramApiImpl implements TelegramApi {

    const ENDPOINT = 'https://api.telegram.org/bot';
    private int $offset;
    private string $token;

    public function __construct(string $token) {
        $this -> token = $token;
    }

    public function getMessages(int $offset): array{
        $url = self::ENDPOINT . $this->token . '/getUpdates?timeout=1';
        $result = [];

        while(true) {
            // $ch = curl_init("{$url}&offset= {$offset}");
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
            $response = json_decode(curl_exec($ch), true);

            if (!$response['ok'] || empty($response['result'])) break;

            foreach ($response['result'] as $data) {
                $result[$data['message']['chat']['id']] = [...$result[$data['message']['chat']['id']] ?? [], $data['message']['text']];
                $offset = $data['update_id'] + 1;
            }
            curl_close($ch);

            // var_dump(gettype(['offset' => $offset,'result' => $result]));
            // die();

            if (count($response['result']) < 100) break;
        }
        return [
            'offset' => $offset,
            'result' => $result
        ];
    }

    public function sendMessage(string $chatId, string $text): void {
        $url = self::ENDPOINT . $this->token . '/sendMessage';

        $data = [
            'chat_id' => $chatId,
            'text' => $text
        ];
        $ch = curl_init($url);
        $jsonData = json_encode($data);

        curl_setopt($ch, CURLOPT_POST, true);                                            // CURLOPT_POST - следует ли отправлять запрос методом POST. По умолчанию cURL отправляет GET-запросы.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);                                 // параметр cURL, используемый для установки тела POST-запроса.
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));    //параметр cURL, который задает HTTP-заголовки, отправляемые вместе с запросом.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                  // константа, устанавливающая значение дескриптора cURL так, чтобы ответ от сервера возвращался в виде строкового значения

        curl_exec($ch);            //Выполняет запрос cURL.
        curl_close($ch);           //Завершение сеанса.
    }
}