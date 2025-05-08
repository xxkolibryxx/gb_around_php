<?php
use App\Actions\EventSaver;
use App\Models\Event;
use PHPUnit\Framework\TestCase;

/**
    * @covers EventSaver
*/

class EventSaverTest extends TestCase
{
    /**
     * @dataProvider eventDtoDataProvider
    */

    public function testHandleCallCorrectInsertInModel(array $eventDto, array $expectedArray):void
    {
        $mock = $this->getMockBuilder(Event::class)
        ->setMethods(['insert'])
        ->disableOriginalConstructor()
        ->getMock();

        $mock->expects($this->once())
        ->method('insert')
        ->with("name, text, receiver_id, minute, hour, day, month, day_of_week", $expectedArray);

        //$mock = Mockery::mock(Event::class);
        // $mock->shouldReceive('insert')->once();

        $eventSaver = new EventSaver($mock);
        $eventSaver->handle($eventDto);

        // $mock->shouldHaveReceived('insert',
        //     [
        //         "name,receiver_id, test, minute, hour, day, month, day_of_week",
        //         $expectedArray
        //     ]
        // );

        // $this->assertTrue(true);
    }

    public static function eventDtoDataProvider(): array
    {
        return 
        [
            [
                [

                    'name' => 'some name',
        
                    'text' => 'some text',
        
                    'receiver_id' => 'some id',
        
                    'minute' => 'some min',
        
                    'hour' => 'some hour',
        
                    'day' => 'some day',
        
                    'month' => 'some mnth',
        
                    'day_of_week' => 'some day week'
        
                ],
                [

                    'some name',
        
                    'some text',
        
                    'some id',
        
                    'some min',
        
                    'some hour',
        
                    'some day',
        
                    'some mnth',
        
                    'some day week'
        
                ]
            ],
            [
                [

                    'name' => 'some name',
        
                    'text' => 'some text',
        
                    'receiver_id' => 'some id',
        
                    'minute' => null,
        
                    'hour' => null,
        
                    'day' => null,
        
                    'month' => null,
        
                    'day_of_week' => null
        
                ],
                [

                    'some name',
        
                    'some text',
        
                    'some id',
        
                    null,
        
                    null,
        
                    null,
        
                    null,
        
                    null
        
                ]
            ]
        ];
    }
}