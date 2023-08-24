<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Team;
use App\Models\Test;
use App\TimeManagers\Timer;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


class StatisticsController extends Controller
{
    public function showTeamResults(Team $team,  Test $test)
    {
        $this->authorize('modify',$team);
        $results=[];
        foreach($team->users as $user)
        {
            $generatedEgzam=$test->generatedTests()->where('user_id',$user->id)->first();
            if($user->pivot->is_teacher || !$generatedEgzam )
            {
                continue;
            }
            else {
              
            $results[$user->id]=['id'=>null,'name'=>null,'resultsInPercent'=>0,'correctAnswers'=>0];

                if(!$generatedEgzam)
                {
                    continue;
                }else{
                    $allAnswers=0;
                    $correctAnswers=0;
                    $nextUser=false;
                   foreach($generatedEgzam->generatedQuestions as $generatedQuestion)
                   {
                    if($generatedQuestion->relevant)
                    {
                        $allAnswers++;
                        if($generatedQuestion->answer)
                        {
                            $correctAnswers++;
                        }
                    }else{
                          $nextUser=true;
                        }
                   }
                   if($nextUser){
                     unset($results[$user->id]);
                     continue;
                   }
                   $results[$user->id]['resultsInPercent']=(string)floor(($correctAnswers*100/$allAnswers)).'%';
                   $results[$user->id]['correctAnswers']=$correctAnswers;
                   $results[$user->id]['name']=$user->name;
                   $results[$user->id]['id']=$user->id;
                }
             } 
            
        }
        return response(['results'=>array_values($results),'headers'=>['id'=>'id','name'=>'Imię i nazwisko','resultsInPercent'=> 'Wynik w procentach',  'correctAnswers'=>'Liczba poprawnych odpowiedzi', ]]);
    }
    public function showGeneralStatistic()
    {
        $user=Auth::user();
        $tests=$user->tests()->get();

        //how many % of all answers has correct answer
        
        $allQuestions=new Collection();
        $allCorrectQuestions=new Collection();
        foreach($tests as $test)
        {
            $questions=$test->questions()->get();
            $allQuestions=$allQuestions->merge($questions);   
            $correctQuestions=$test->questions()->whereRelation('generatedQuestions', 'answer', true)->get();
            $allCorrectQuestions=$allCorrectQuestions->merge($correctQuestions);
        }   

  

        $allQuestionsNumber=count($allQuestions)!==0 ? count($allQuestions) : 1;
        $correctQuestionsNumber=count($allCorrectQuestions);
        $result=$correctQuestionsNumber*100/$allQuestionsNumber;

        //avarage time
       
        $timeArray=new Collection();
        $generatedTests=$user->generatedTests()->get();
        foreach($generatedTests as $generatedTest)
        {
            if($generatedTest->duration==null || !$generatedTest->egzam)
            {
                continue;
            }
            $timer=new Timer();
            $timer->setTimeFromHHMMSS($generatedTest->duration);
            $time=$timer->showTimeInMs();
            
            $howManyQuestions=$generatedTest->questions_number;
            $avgTime=$time/$howManyQuestions;
            $timeArray->push($avgTime);
            
        }
        $averageTime=$timeArray->avg();
 
        $averageTime=$averageTime===null ? 0: $averageTime;
        $result=ceil($result);
        return response(['average'=>$averageTime, 'result'=>$result]);
    }

    public function showTestStatistic(Request $request, Test $test)
    {
        $request->validate([
            'howOld'=>'required'
        ]);
        $this->authorize('view', $test);

        $date=new DateTime();
        
        $date->modify($request->input('howOld'));

        $generatedTests=$test->generatedTests()->where('updated_at','>',$date)->where('user_id',Auth::user()->id)->get();

            $avgTimeArray=new Collection();
            $resultArray=new Collection();
        foreach($generatedTests as $generatedTest)
        {
            $all=count($generatedTest->generatedQuestions()->where('relevant',true)->get());
            $correct=count($generatedTest->generatedQuestions()->where('relevant',true)->where('answer',true)->get());
            $timeObject=new DateTime($generatedTest->updated_at);
            $time=$timeObject->format('d-m-Y H:i:s');
            
            $avgResult=[$time, $correct/$all *100];
            $resultArray->push($avgResult);

            if($generatedTest->duration==null || !$generatedTest->egzam)
            {
                continue;
            }
            $timer=new Timer();
            $timer->setTimeFromHHMMSS($generatedTest->duration);
            $avg=[$time, $timer->showTimeInMs()/$generatedTest->questions_number];
            $avgTimeArray->push($avg);
        }

        return response(['result'=>$resultArray,'time'=>$avgTimeArray]);

    }

    public function showQuestionStatistic(Request $request, Question $question)
    {
        $request->validate([
            'howOld'=>'required'
        ]);
        $this->authorize('view', $question);

        $date=new DateTime();
        
        $date->modify($request->input('howOld'));

        $generatedQuestions=$question->generatedQuestions()->where(function (Builder $query) use ($date)
        {
            $tests=Auth::user()->generatedTests()->get();
            return $query->where('updated_at','>', $date)->whereBelongsTo($tests);
        })->get();

        $goodAnswers=0;
        $allAnswers=0; 
        $answersCollection= new Collection();
        foreach($generatedQuestions as $generatedQuestion)
        {
            $allAnswers++;
            $answerDate=new DateTime($generatedQuestion->updated_at);

            $answersCollection->push(['date' =>$answerDate->format('d-m-Y H:i:s'), 'answer'=>$generatedQuestion->answer ]);
            if($generatedQuestion->answer)
            {
                $goodAnswers++;
            }
        }
        return response(['summary'=>round(($goodAnswers/$allAnswers)*100), 'all'=>$answersCollection]);
    }
}
