<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Excel;
use Illuminate\Support\Facades\Input;
use Session;
use knet\Jobs\ImportUsersExcel;
use Illuminate\Support\Facades\DB;
use Torann\Registry\Facades\Registry;

use knet\Http\Requests;
use Auth;
use knet\User;
use knet\Role;
use knet\ArcaModels\Client;
use knet\ArcaModels\Agent;

class UserController extends Controller
{

    public function __construct(){
      $this->middleware('auth');
    }

    public function index(Request $req){
      // $users = User::with(['roles', 'client', 'agent'])
      $users = User::with(['roles'])
                ->whereHas('roles', function($q){$q->whereNotIn('name', ['agent', 'superAgent', 'client']);})
                ->orderBy('id')->get();

      $agents = User::with(['roles', 'agent'])
                ->whereHas('roles', function($q){$q->whereIn('name',['agent', 'superAgent']);})
                ->where('ditta', Registry::get('location'))
                ->orderBy('id')->get();

      return view('user.index', [
        'users' => $users,
        'agents' => $agents,
      ]);
    }

    public function indexCli(Request $req){
      // $clients = User::with(['roles'])
      $clients = User::with(['roles', 'client'])
                ->whereHas('roles', function($q){$q->whereIn('name',['agent', 'client']);})
                ->where('ditta', Registry::get('location'))
                ->orderBy('id')->get();

      return view('user.indexCli', [
        'clients' => $clients,
      ]);
    }

    public function destroy(Request $req, $id){
      User::destroy($id);
      return Redirect::route('user::users.index');
    }

    public function edit(Request $req, $id){
      $user=User::with('roles')->findOrFail($id);
      $roles = Role::all();
      $clients = Client::select('codice', 'descrizion')
                  ->withoutGlobalScope('agent')
                  ->withoutGlobalScope('superAgent')
                  ->withoutGlobalScope('client')->get();
      $agents = Agent::select('codice', 'descrizion')->get();
      // dd($user->roles->contains(33));
      return view('user.edit', [
        'user' => $user,
        'roles' => $roles,
        'clients' => $clients,
        'agents' => $agents,
      ]);
    }

    public function show(Request $req, $id){
      $user=User::with('roles')->findOrFail($id);
      $roles = Role::all();
      // DB::disconnect();
      $clients = Client::select('codice', 'descrizion')
                  ->withoutGlobalScope('agent')
                  ->withoutGlobalScope('superAgent')
                  ->withoutGlobalScope('client')->get();
      // dd($clients);
      $agents = Agent::select('codice', 'descrizion')->get();
      // dd($user->roles->contains(33));
      return view('user.profile', [
        'user' => $user,
        'roles' => $roles,
        'clients' => $clients,
        'agents' => $agents,
      ]);
    }

    public function update(Request $req, $id){
      $user = User::findOrFail($id);

      $user->roles()->detach();
      $user->attachRole($req->input('role'));

      $user->name = $req->input('name');
      $user->email = $req->input('email');
      $user->codag = $req->input('codag');
      $user->codcli = $req->input('codcli');
      $user->save();

      return Redirect::route('user::users.index');
    }

    public function showImport(Request $req){
      return view('user.import');
    }

    public function doImport(Request $req){
      $destinationPath = 'usersFiles'; // upload path
      $extension = Input::file('file')->getClientOriginalExtension(); // getting image extension
      $fileName = rand(11111,99999).'.'.$extension; // renameing image
      Input::file('file')->move($destinationPath, $fileName); // uploading file to given path
      // sending back with message
      Session::flash('success', 'Upload successfully');
      $this->dispatch(new ImportUsersExcel($fileName));
      return Redirect::back();
    }

    public function actLike(Request $req, $id){
      Auth::loginUsingId($id);
      return redirect()->action('HomeController@index');
    }

    public function changeSelfDitta(Request $req) {
      $user = Auth::user();
      $user->ditta = $req->input('ditta');
      $user->save();
      return redirect()->action('HomeController@index');
    }

    public function changeSelfLang(Request $req) {
      $user = Auth::user();
      $user->lang = $req->input('lang');
      $user->save();
      return redirect()->action('UserController@show', $user->id);
    }
}
