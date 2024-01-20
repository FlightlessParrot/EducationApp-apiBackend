<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\TimeManagers\Timer;
class TimerClassTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_object_shows_time_in_ms(): void
    {
        $timer=new Timer(3000);
        $time=$timer->showTimeInMs();
        $this->assertEquals(3000,$time);
    }
    public function test_object_shows_time_in_using_time_format():void
    {
        $entryTime=(3*60*60+8*60+10)*1000;
        $timer=new Timer($entryTime);
        $time=$timer->showTimeInHHMMSS();
        $this->assertEquals('03:08:10',$time);  
    }
    public function test_can_change_time()
    {
        $timer=new Timer();
        $timer->setTimeFromMs(3000);
        $time=$timer->showTimeInMs();
        $this->assertEquals(3000,$time);
    }
    public function test_can_change_time_using_time_format()
    {
        $timer=new Timer();
        $timer->setTimeFromHHMMSS('03:08:10');
        $changeTime=(3*60*60+8*60+10)*1000;
        $time=$timer->showTimeInMs();
        $this->assertEquals($changeTime,$time);
    }
}
