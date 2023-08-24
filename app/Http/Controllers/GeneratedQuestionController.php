<?php

namespace App\Http\Controllers;

use App\Models\GeneratedQuestion;
use App\Models\GeneratedTest;
use App\Models\Question;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class GeneratedQuestionController extends Controller
{
    public function removeFromStatistics(GeneratedTest $generatedTest, Question $question)
    {
        $generatedQuestion=$generatedTest->generatedQuestions()->where('question_id',$question->id)->first();
        $this->authorize('update', $generatedQuestion);
        $generatedQuestion->relevant=false;
        $generatedQuestion->save();
        return Response()->noContent();
    }
}
