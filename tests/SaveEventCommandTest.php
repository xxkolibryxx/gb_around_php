<?php
use PHPUnit\Framework\TestCase;

//php ./vendor/bin/phpunit ./tests/ --filter SaveEventCommandTest

/**
    * @covers SaveEventCommand
*/

class SaveEventCommandTest extends TestCase {
    /**
     * @dataProvider isNeedHelpDataProvider
     */
    public function testIsNeedHelp(array $options, bool $isNeedHelp) {
        $saveEventCommand = new App\Commands\SaveEventCommand(new \App\Application(dirname(__DIR__)));

        $result = $saveEventCommand->isNeedHelp($options);

        self::assertEquals($result, $isNeedHelp);
    }

    public function IsNeedHelpDataProvider() {
        return [
            [
                [
                    "name" => "some-name",
                    "text" => "some-text",
                    "receiver" => "some-receiver",
                    "cron" => "some-cron",
                ], false
            ],
            [
                [
                    "name" => "some-name",
                    "text" => "some-text",
                    "receiver" => "some-receiver",
                    "cron" => "some-cron",
                    "help" => "some-help",
                    "h" => null,
                ], true
            ],
            [
                [
                    "name" => "some-name",
                    "text" => "some-text",
                    "receiver" => "some-receiver",
                    "cron" => "some-cron",
                    "help" => null,
                    "h" => 'some-h',
                ], true
            ],
            [
                [
                    "name" => "some-name",
                    "text" => "some-text",
                    "receiver" => "some-receiver",
                    "cron" => null,
                ], true
            ],
            [
                [
                    "name" => null,
                    "text" => "some-text",
                    "receiver" => "some-receiver",
                    "cron" => "some-cron",
                ], true
            ]
        ];
    }
}