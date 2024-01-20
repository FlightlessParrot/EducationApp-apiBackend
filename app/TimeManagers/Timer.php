<?php
namespace App\TimeManagers;


class Timer
{
    
    private $timeInMs;

    public function __construct(int|float $timeInMs=0)
    {
    $this->timeInMs=floor($timeInMs);
    }

    public function setTimeFromHHMMSS(string $time):void
    {
        $explosion= explode(':',$time);
        $hours= (integer)$explosion[0];
        $minutes=(integer)$explosion[1];
        $seconds=(integer)$explosion[2];

        $ms=($hours*60*60+$minutes*60+$seconds)*1000;
        
        $this->timeInMs=$ms;
        
    }
    public function setTimeFromMs($ms): void
    {
        $this->timeInMs=$ms;
    }
    public function showTimeInMs():int
    {
        return $this->timeInMs;
    }

    public function showTimeInHHMMSS():string
    {
        $ms=$this->timeInMs;
        $strHours=$this->makeTwoNumberDigitString(floor($ms/(60*60*1000 )));
        $minutes=floor($ms/(60*1000 ))%60;
        $strMinutes=$this->makeTwoNumberDigitString($minutes);
        $seconds=floor($ms/(1000 ))%60;
        $strSeconds=$this->makeTwoNumberDigitString($seconds);
        $timeHHMMSS=$strHours.':'.$strMinutes.':'.$strSeconds;
        return $timeHHMMSS;
    }

    private function makeTwoNumberDigitString(int $number):string
    {
        $value=(string) $number;
        if(strlen($value)<2)
        {
            $value='0'.$value;
        }
        return $value;
    }
}



?>