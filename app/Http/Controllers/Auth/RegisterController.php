<?php

namespace knet\Http\Controllers\Auth;

use knet\User;
use knet\Role;
use Validator;
use knet\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use knet\Mail\NewRegistration;
use Illuminate\Support\Facades\Mail;

/**
 * Class RegisterController
 * @package %%NAMESPACE%%\Http\Controllers\Auth
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'nickname' => 'required|email|max:255|unique:users',
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed|min:6',
            'terms' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'nickname' => $data['nickname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        $user->roles()->detach();
        $user->attachRole(Role::where('name', 'user')->first()->id);
        $user->ditta = 'it';
        $user->isActive = true;
        $user->save();
        
        Mail::to('ced-it@k-group.com')
            ->cc('ced@k-group.com')
            ->send(new NewRegistration($user));

        return $user;

        // $fields = [
        //     'name'     => $data['name'],
        //     'email'    => $data['email'],
        //     'password' => bcrypt($data['password']),
        // ];
        // if (config('auth.providers.users.field', 'email') === 'username' && isset($data['username'])) {
        //     $fields['username'] = $data['username'];
        // }
        // return User::create($fields);
    }
}
