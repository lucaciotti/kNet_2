<?php

namespace knet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use Session;
use knet\Jobs\ImportUsersExcel;
use knet\Exports\EnasarcoExport;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use RedisUser;

use knet\Http\Requests;
use Auth;
use knet\User;
use knet\Role;
use knet\ArcaModels\Client;
use knet\ArcaModels\Agent;
use knet\ArcaModels\RitAna;
use knet\ArcaModels\RitEnasarco;
use knet\ArcaModels\RitMov;
use knet\UserEvent;

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
                ->whereHas('roles', function($q){$q->whereIn('name',['agent', 'superAgent', 'quality']);})
                ->where('ditta', RedisUser::get('location'))
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
                ->where('ditta', RedisUser::get('location'))
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
      $agents = Agent::select('codice', 'descrizion', 'u_dataini')->get();
      // dd($user->roles->contains(33));
      return view('user.edit', [
        'user' => $user,
        'roles' => $roles,
        'clients' => $clients,
        'agents' => $agents,
      ]);
    }

    public function show(Request $req, $id){
      $user=User::with('client', 'agent')->findOrFail($id);
      $ritana=RitAna::first();
      $year = (string) Carbon::now()->year;
      $ritena=RitEnasarco::where('anno', $year)->first();
      // dd($ritena);
      $ritmov=RitMov::where('ftdatadoc', '>', new Carbon('first day of January '.$year))->get();
      return view('user.profile', [
        'user' => $user,
        'ritana' => $ritana,
        'ritena' => $ritena,
        'ritmov' => $ritmov,
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
      $user->ditta = $req->input('ditta');
      $user->isActive = $req->input('isActive');
      $user->save();
      RedisUser::store();

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
      RedisUser::store();
      return redirect()->action('HomeController@index');
    }

    public function changeSelfDitta(Request $req) {
      $user = Auth::user();
      $user->ditta = $req->input('ditta');
      $user->save();
      RedisUser::store();
      return redirect()->action('HomeController@index');
    }

    public function changeSelfLang(Request $req) {
      $user = Auth::user();
      $user->lang = $req->input('lang');
      $user->save();
      RedisUser::store();
      return redirect()->action('UserController@show', $user->id);
    }

    public function allUsers(Request $req){
      return User::with('roles')->get();
    }

    public function enasarcoXLS(Request $req, $id){
      $user = User::with('client', 'agent')->findOrFail($id);
      $ritana = RitAna::first();
      $year = (string) Carbon::now()->year;
      $ritena = RitEnasarco::where('anno', $year)->first();
      $ritmov = RitMov::where('ftdatadoc', '>', new Carbon('first day of January ' . $year))->get();
      return Excel::download(new EnasarcoExport($ritana, $ritena, $year, $ritmov, $user), 'Sit_Enasarco-'.$user->codag.'.xlsx');
    }

    public function enasarcoPDF(Request $req)
    { }

    public function events(Request $req) {
      if ($req->ajax()) {
        $user_id = $req->session()->get('event.user_id', 0);

        $data = UserEvent::whereDate('start', '>=', $req->start)
          ->whereDate('end',   '<=', $req->end)
          ->where('user_id', $user_id)
          ->get(['id', 'title', 'start', 'end']);

        return response()->json($data);
      }
      if(!in_array(RedisUser::get('role'), ['agent', 'superAgent'])) {
        $users = User::whereHas('roles', function ($q) {
          $q->whereIn('name', ['agent', 'superAgent']);
        })->where('isActive',1)->where('ditta', Auth::user()->ditta)->orderBy('name')->get();
      } else {
        $users = User::where('id', Auth::user()->id)->get();
      }

      $selectedUser = isset($req->selectedUser) ? $req->selectedUser : $users->first()->id;
      $req->session()->put('event.user_id', $selectedUser);

      return view('user.humanresource', [
        'users' => $users, 
        'selectedUser' => $selectedUser]);
    }

  public function eventsAjax(Request $request)
  {
    if (!in_array(RedisUser::get('role'), ['agent', 'superAgent'])) {
      $user_id = $request->session()->get('event.user_id', 0);
      switch ($request->type) {
        case 'add':
          $event = UserEvent::create([
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
            'user_id' => $user_id,
          ]);

          return response()->json($event);
          break;

        case 'update':
          $event = UserEvent::find($request->id)->update([
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
            'user_id' => $user_id,
          ]);

          return response()->json($event);
          break;

        case 'delete':
          $event = UserEvent::find($request->id)->delete();

          return response()->json($event);
          break;

        default:
          # code...
          break;
      }
    }
  }

}
