<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function store(Request $request):Response
    {

        // authorize!!!
        $request->validate([
            'name'=>['required','max:250'],
            'test_id'=>['nullable','integer'],
        ]); 
        $user=Auth::user();
        $parentTest=Test::find($request->input('test_id'));
        $subscriptions=$parentTest->subscriptions()->get();
        
        $test=Test::create(['name'=>$request->name, 'role'=>'custom','user_id'=>$user->id]);
        foreach($subscriptions as $subscription)
        {
            $subscription->tests()->attach($test);
        }

        if($request->test_id)
        {
          foreach($parentTest->questions as $question)
          {
            $test->questions()->attach($question->id);
          } 
        }
        return response()->noContent();
    }
    public function adminStore(Request $request, Subscription $subscription):Response
    {

        $request->validate([
            'name'=>['required','max:250'],
            'test_id'=>['nullable','integer'],
        ]); 
   
        $test=$subscription->tests()->create(['name'=>$request->name, 'role'=>'general']);
      
        return response(['test'=>$test->id]);
    }
    
    public function removeAllQuestions(Test $test)
    {
       
        $this->authorize('delete', $test);
       
       $test->questions()->where('custom', true)->delete();
       $test->questions()->where('custom', false)->detach();
     
        return response()->noContent();

    }

    
    
    public function destroy(Test $test)
    {
   
        $this->authorize('delete', $test);
        $test->delete();
        return response()->noContent();
        
    }

    public function find(Request $request)
    {
        $request->validate(['search'=>'nullable|max:250']);
        $tests= Auth::user()->tests()->filter(function(Test $test) use($request){
            return str_contains(strtolower($test->name),strtolower($request->search)  );
        });
       
        $tests=$request->input('custom')==='false' ? $tests->whereNull('user_id'): $tests->whereNotNull('user_id');
        if($request->input('custom')==='false') 
        {
            foreach(Auth::user()->teams as $team)
            {
                $teamTests=$team->tests()->where('role','!=','egzam')->get()->filter(function(Test $test) use($request){
                    return str_contains(strtolower($test->name),strtolower($request->search));
                });
                $tests=$tests->merge($teamTests);
            }
            
        }
        
        return response($tests->unique()->values());
    }

    public function adminFind(Request $request)
    {
        $tests=Test::where('name','like','%'.$request->input('search').'%')->where('role','general')->get();
        return response(['tests'=>$tests]);
    }
   public function latestCustomTest()
   {
    $user=Auth::user();
    $latestTest=Test::where('user_id',$user->id)->latest()->first();
    return response(['test'=>$latestTest]);
   }
   public function show()
   {
    $tests=Test::where('role','general')->get();
    return response(['tests'=>$tests]);
   }
   public function getTest(Test $test)
   {
    $test['subscriptions']=$test->subscriptions()->get();
    return response(['test'=>$test]);
   }

   public function changeSubscription(Subscription $subscription, Test $test)
   {
    $responseText='Subskrypcja was ';
    if($subscription->tests->find($test->id))
    {
        $subscription->tests()->detach($test);
        $responseText=$responseText.'detached';
    }else{
        $subscription->tests()->attach($test);
        $responseText=$responseText.'attached';
    }
    
    return response(['subscription'=>$subscription,'test'=>$test, 'description'=>$responseText]);
   }
   public function addImageToTest(Request $request, Test $test )
   {
           $request->validate(['image'=>'image']);
           $path=$request->image->store('public/images/tests');
           If($test->path!==null)
           {
            Storage::delete(str_replace('/storage','public',$test->path));
           }
           $test->path=Storage::url($path);
           $test->save();
   
           return response(['image'=>$test->path]);
   }
   public function adminRemove(Test $test)
   {
    $test->delete();
    return response(['removedTest'=>$test]); 
   }
}
