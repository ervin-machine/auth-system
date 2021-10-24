<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;
class InvitedUsersController extends Controller
{
    use RegistersUsers;
    use VerifiesUsers;

    public function __construct()
    {
        $this->middleware('guest',['except' => ['getVerification', 'getVerificationError']]);
    }

    public function invite_process(Request $request)
    {
        $request->validate([
            'email'=>'required'
        ]);
        do{
            $linktoken = Str::random(5);
        } while(Invite::where('linktoken', $linktoken)->first());
        $invite = new Invite;
        $invite->linktoken = $request->linktoken;
        $invite->email = $request->email;
        $invite.save();
        #return response()->json(['message'=>'Invite sent'], 200);
        return redirect('/invite/registration');
    }
    public function registration(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'user_name'=>'required',
            'avatar'=>'required',
            'email'=>'required',
            'password'=>'required',
            'user_role'=>'required',
        ]);

        $registration = new Registration;
        $registration->name = $request->name;
        $registration->user_name = $request->user_name;
        $registration->avatar = $request->avatar;
        $registration->email = $request->email;
        $registration->password = $request->password;
        $registration->user_role = $request->user_role;
        $registration->save();
        UserVerification::generate($registration);
        UserVerification::send($registration, 'Verify email');
        return redirect('/login');
        #return response()->json(['message'=>'Registration']);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'=>'required|email',
            'password'=>'required|alphaNum|min:3'
        ]);
        $user_data = array(
            'email'=>$request->get('email'),
            'password'=>$request->get('password')
        );
        if(Auth::attempt($user_data))
        {
            #return response()->json(['message'=>'Login successful']);
            return redirect('/user/{id}');
        }
        else{
            return response()->json(['message'=>'Wrong email or password']);
        }
    }
    public function update(Request $request)
    {
        $request->validate([
            'user_name' => 'optional',
            'avatar' => 'optional',
            'new_password' => 'optional',
            'new_confirm_password' => 'same:new_password',
        ]);
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        User::find(auth()->user()->id)->update(['user_name'=> Hash::make($request->user_name)]);
        User::find(auth()->user()->id)->update(['avatar'=> Hash::make($request->avatar)]);
        #return response()->json(['message'=>'Profile updated']);
        return redirect('/user/{id}');
    }
}
