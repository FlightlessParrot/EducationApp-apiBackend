<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Test;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user=Auth::user();
        $teams=$user->teams()->wherePivot('is_teacher',true)->get();
        return response($teams);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate(['name'=>'required|max:250']);
        $team=new Team(['name'=>$request->input('name')]);
        $team->save();
        $team->users()->attach(Auth::user(),['is_teacher'=>true]);
        return response('Team was created',200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /** 
     * Display the specified resource.
     */
    public function show(Team $team)
    {
      $this->authorize('view', $team);
      $users=$team->users()->wherePivot('is_teacher', false)->get();
      $hiddenProperties=$users->setVisible(['name','email','id']);

      return response(['team'=>$team, 'members'=>$hiddenProperties]);
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        //
    }

    public function addTest(Team $team, Test $test)
    {
        $this->authorize('modify', $team);
        $this->authorize('update',$test);

        
        $team->tests()->attach($test);
        return response(['title'=>'Udostępniono materiał','description'=>'Udało się udostępnić materiał zespołowi.']);
        

    }

    public function removeTest(Team $team, Test $test)
    {
        
        $this->authorize('modify', $team);
        $this->authorize('update',$test);

        $team->tests()->detach($test);
        return response(['title'=>'Usunięto materiał','description'=>'Udało się usunąć materiał.']);
    }
    public function addUser(Team $team, User $user)
    {
        $this->authorize('modify', $team);
        $team->users()->attach($user);
        return response(['title'=>'Dodano użytkownika','description'=>'Udało się dodać użytkownika do zespołu.']);
    }

    public function removeUser(Team $team, User $user)
    {
        $this->authorize('modify', $team);
        $team->users()->detach($user);
        return response(['title'=>'Usunięto użytkownika','description'=>'Udało się usunąć użytkownika z zespołu.']);
    }

    public function getTests(Team $team)
    {

        $this->authorize('view', $team);
        return $team->tests()->where('role','!=','egzam')->get();
    }
    public function destroy(Team $team)
    {
        
    }
}
