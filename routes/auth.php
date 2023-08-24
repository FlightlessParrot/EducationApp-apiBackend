<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\CheckAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FindUserController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\GandalfController;
use App\Http\Controllers\GeneratedQuestionController;
use App\Http\Controllers\GeneratedTestController;
use App\Http\Controllers\NotyficationController;
use App\Http\Controllers\OpenAnswerController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TestController;
use App\Models\Flashcard;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');


Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/checkAuth', CheckAuthController::class)->middleware('auth');

Route::middleware(['auth'])->group(function () {
    
    Route::post('/tests/create', [TestController::class, 'store']);
    Route::delete('/tests/{test}/delete',[TestController::class, 'destroy']);
    Route::post('/tests/find',[TestController::class, 'find']);
    Route::post('/egzams/find',[GandalfController::class, 'findEgzam']);

    Route::delete('/tests/{test}/questions/remove',[TestController::class, 'removeAllQuestions']);

    Route::post('/tests/{test}/questions/{question}/attach',[QuestionController::class, 'attach']);
    Route::post('/questions/find',[QuestionController::class, 'find']);
    Route::post('/tests/{test}/questions/owned',[QuestionController::class, 'findOwned']);
    Route::post('/tests/{test}/questions/unowned',[QuestionController::class, 'findUnowned']);
    Route::delete('/tests/{test}/questions/{question}/detach',[QuestionController::class, 'destroy']);
    
    Route::post('/generate-test', [GeneratedTestController::class, 'store']);
    Route::delete('/generated-tests/{generatedTest}',[GeneratedTestController::class, 'destroy']);
    Route::get('/generated-tests/{generatedTest}/view',[GeneratedTestController::class, 'show']);
    Route::post('/generated-tests/{generatedTest}/submit', [GeneratedTestController::class, 'update']);
    Route::get('/generated-tests/{generatedTest}/answers',[GeneratedTestController::class,'getCorrectAnswerData']);
    Route::get('/generated-tests/{generatedTest}/summary',[GeneratedTestController::class, 'showStatistics']);
    Route::get('/generated-tests/{generatedTest}/summary',[GeneratedTestController::class, 'showStatistics']);
    
    Route::get('/statistics/global',[StatisticsController::class, 'showGeneralStatistic']);
    Route::get('/statistics/test/{test}',[StatisticsController::class, 'showTestStatistic']);
    Route::get('/statistics/question/{question}',[StatisticsController::class, 'showQuestionStatistic']);

    Route::get('/tests/{test}/categories',[CategoryController::class,'showCategoriesForTest']);
    Route::get('/tests/{test}/undercategories',[CategoryController::class,'showUnderCategoriesForTest']);
    Route::get('/teams/{team}/egzams/show',[GandalfController::class,'showEgzams']);

    Route::get('/notyfications',[NotyficationController::class,'index']);

    Route::post('/egzams/{test}/generate',[GandalfController::class,'generateEgzamInstanceForUser']);

    Route::post('/flashcards/view',[FlashcardController::class,'index']);
    Route::get('/flashcards/categories/undercategories',[CategoryController::class,'showFlashCardsCategoriesAndUndercategories']);

    Route::put('/generated-test/{generatedTest}/question/{question}/statistics/remove',[GeneratedQuestionController::class, 'removeFromStatistics']);
    Route::get('account/data',[AccountController::class, 'show']);
    Route::put('account/data/update',[AccountController::class,'update']);
});

Route::middleware(['auth','teamleader'])->group(function()
{
    Route::get('/users/find', FindUserController::class);

Route::post(
        '/team/create',[TeamController::class, 'create']
    );
    Route::get('/teams/show', [TeamController::class, 'index']);
    Route::get('/teams/{team}/view', [TeamController::class, 'show']);
    Route::get('/teams/{team}/tests/{test}/results', [StatisticsController::class,'showTeamResults']);

    Route::post('/teams/{team}/users/{user}/add',[TeamController::class,'addUser']);
    Route::delete('/teams/{team}/users/{user}/remove',[TeamController::class, 'removeUser']);
    
    Route::delete('/teams/{team}/tests/{test}/delete', [TeamController::class, 'removeTest']);
    Route::post('/teams/{team}/tests/{test}/add', [TeamController::class, 'addTest']);
    Route::get('/teams/{team}/tests/view',[TeamController::class, 'getTests']);

    Route::post('/teams/{team}/generated-tests/{generatedTest}/egzam/create',[GandalfController::class,'makeEgzam']);
    Route::delete('/teams/{team}/tests/{test}/egzam/delete',[GandalfController::class, 'deleteEgzam']);
    Route::put('/teams/{team}/egzams/{test}/start',[GandalfController::class,'startEgzam']);

    Route::post('/teams/{team}/egzams/{test}/question/create',[QuestionController::class,'createEgzamQuestion']);

    Route::put('open-answers/{openAnswer}/grade',[OpenAnswerController::class, 'giveGrade']);
    Route::get('egzams/{test}/open-question',[OpenAnswerController::class, 'index']);
    
});
