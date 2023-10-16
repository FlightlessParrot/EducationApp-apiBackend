<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminQuestionCreator;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\CheckAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\FindStudentController;
use App\Http\Controllers\FindTeacherController;
use App\Http\Controllers\FindUserController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\GandalfController;
use App\Http\Controllers\GeneratedQuestionController;
use App\Http\Controllers\GeneratedTestController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NotyficationController;
use App\Http\Controllers\OpenAnswerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserRoleController;
use App\Models\Category;
use App\Models\DiscountCode;
use App\Models\Flashcard;
use App\Models\Question;
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
Route::get('guest/subscriptions',[SubscriptionController::class,'showAllActiveSubscriptions']);
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
    
    Route::put('user/password/update',ChangePasswordController::class);
    Route::post('/tests/create', [TestController::class, 'store']);
    Route::delete('/tests/{test}/delete',[TestController::class, 'destroy']);
    Route::post('/tests/find',[TestController::class, 'find']);
    Route::get('test/custom/latest/get',[TestController::class,'latestCustomTest' ]);
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
    Route::get('/generated-tests/{generatedTest}/summary',[StatisticsController::class, 'showStatistics']);
  
    
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
    Route::put('account/newsletter/toggle',[AccountController::class, 'toogleNewsletter']);
    Route::get('/subscriptions',[SubscriptionController::class, 'index']);

    Route::get('categories/all/undercategories/all',[CategoryController::class, 'showAllCategoriesAndUndercategories']); 
    Route::get('subscription/{subscription}',[SubscriptionController::class,'show']);
    Route::get('/code/{code}',[DiscountCodeController::class,'verify']);
    Route::post('/payments/subscription/{subscription}',[PaymentController::class,'makePayment']); 
});

Route::middleware(['auth','teamleader'])->group(function()
{
    Route::get('/users/find', FindUserController::class);

    Route::post(
        '/team/create',[TeamController::class, 'create']
    );
    Route::delete('/teams/{team}/remove',[TeamController::class,'destroy']);
    Route::get('/teams/show', [TeamController::class, 'index']);
    Route::get('/teams/{team}/view', [TeamController::class, 'show']);
    Route::get('/teams/{team}/tests/{test}/results', [StatisticsController::class,'showTeamResults']);

    Route::post('/teams/{team}/users/{user}/add',[TeamController::class,'addUser']);
    Route::delete('/teams/{team}/users/{user}/remove',[TeamController::class, 'removeUser']);
    
    Route::delete('/teams/{team}/tests/{test}/delete', [TeamController::class, 'detachTest']);
    Route::post('/teams/{team}/tests/{test}/add', [TeamController::class, 'attachTest']);
    Route::get('/teams/{team}/tests/view',[TeamController::class, 'getTests']);

    Route::post('/teams/{team}/generated-tests/{generatedTest}/egzam/create',[GandalfController::class,'makeEgzam']);
    Route::delete('/teams/{team}/tests/{test}/egzam/delete',[GandalfController::class, 'deleteEgzam']);
    Route::put('/egzams/{test}/start',[GandalfController::class,'startEgzam']);

    Route::post('/teams/{team}/egzams/{test}/question/create',[QuestionController::class,'createEgzamQuestion']);

    Route::put('open-answers/{openAnswer}/grade',[OpenAnswerController::class, 'giveGrade']);
    Route::get('egzams/{test}/open-question',[OpenAnswerController::class, 'index']);


   
    
});

Route::middleware(['auth','admin'])->group(
    function(){
        Route::post('subscription/create',[SubscriptionController::class,'store']);
        Route::put('subscription/{subscription}/update',[SubscriptionController::class,'update']);
       
        Route::get('flashcard/{flashcard}',[FlashcardController::class,'show']);
        Route::put('/flashcard/{flashcard}/update',[FlashcardController::class,'update'] );

        Route::delete('subscription/{subscription}/delete',[SubscriptionController::class,'destroy']);
        Route::get('subscriptions/inactive',[SubscriptionController::class,'showInactiveSubscriptions']);
        Route::get('subscriptions/active',[SubscriptionController::class,'showAllActiveSubscriptions']);
        Route::put('subscription/{subscription}/activate',[SubscriptionController::class, 'activateSubscription']);
        Route::put('subscription/{subscription}/disactivate',[SubscriptionController::class, 'disactivateSubscription']);
        Route::get('tests/all', [TestController::class, 'show']);
        Route::get('subscriptions/all',[SubscriptionController::class,'showAllSubscriptions']);
        Route::get('test/{test}',[TestController::class,'getTest']);

        Route::get('tests/general/show',[TestController::class, 'adminFind']);
        Route::put('test/{test}/image/add',[TestController::class, 'addImageToTest']);
        Route::delete('/test/{test}/remove',[TestController::class, 'adminRemove']);

        Route::post('subscription/{subscription}/test/create',[TestController::class, 'adminStore']);
        Route::put('subscription/{subscription}/test/{test}/update',[TestController::class, 'changeSubscription']);
        Route::post('test/{test}/question/create',[AdminQuestionCreator::class, 'createQuestion']);
        Route::post('question/{question}/one-answer/create',[AdminQuestionCreator::class,'addAnswers']);
        Route::post('question/{question}/many-answers/create',[AdminQuestionCreator::class,'addAnswers']);
        Route::post('question/{question}/order/create',[AdminQuestionCreator::class,'addOrder']);
        Route::post('question/{question}/pairs/create',[AdminQuestionCreator::class,'addPairs']);
        Route::post('question/{question}/short-answer/create',[AdminQuestionCreator::class,'addShortAnswer']);
        Route::put('question/{question}/image/add',[AdminQuestionCreator::class,'addImageToQuestion']);

        Route::delete('questions/{question}/remove',[QuestionController::class, 'remove']);

        Route::post('category/new',[CategoryController::class,'storeCategory']);
        Route::post('undercategory/new',[CategoryController::class,'storeUndercategory']);
        Route::delete('categories/{category}/delete',[CategoryController::class, 'deleteCategory']);
        Route::delete('undercategories/{undercategory}/delete',[CategoryController::class, 'deleteUndercategory']);

        Route::post('/flashcard/new',[FlashcardController::class,'store']);
        Route::put('flashcards/{flashcard}/image/update',[FlashcardController::class,'addImage']);
        Route::get('subscriptions/{subscription}/flashcards/find',[FlashcardController::class,'find']);

        Route::delete('flashcards/{flashcard}/delete',[FlashcardController::class,'destroy']);
        Route::get('/users/teacher/find',FindTeacherController::class);
        Route::get('/users/student/find',FindStudentController::class);

        Route::put('/users/{user}/upgrade',[UserRoleController::class,'upgrade']);
        Route::put('/users/{user}/downgrade',[UserRoleController::class,'downgrade']);

        Route::post('news/send',[MailController::class, 'sentMailToAll']);

        Route::get('/discount-codes',[DiscountCodeController::class, 'index']);
        Route::post('/discount-codes/create',[DiscountCodeController::class, 'store']);
        Route::delete('/discount-code/{discountCode}/delete',[DiscountCodeController::class,'destroy']);
    }
);