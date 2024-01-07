<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Auth;
use App\Models\User;
use App\Models\Affiliate;
use App\Mail\ForgotPasswordMail;
use Mail;
use Str;

class AdminController extends Controller
{
    public function login(){
        //dd(Hash::make('.'));
        // if(!empty(Auth::check())){
        //     if(Auth::user()->user_type == 1){
        //         return redirect('admin/dashboard');
        //     }
        // }
        return view('admin.auth.login');
    }
    public function adminLogin(Request $request){
        //dd($request->all());
        
        $remember = !empty($request->remember) ? true : false;
        if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $remember)){
            //dd(Auth::guard('admin')->user());
            return redirect('admin/dashboard');
        }else{
            return redirect()->back()->with('error', 'Please enter currect email and password');
        }
    }
    public function dashboard(){
        //dd(Hash::make('.'));
        // if(!empty(Auth::check())){
        //     if(Auth::user()->user_type == 1){
        //         return redirect('admin/dashboard');
        //     }
        // }
        return view('admin.dashboard');
    }
    public function list(){
        $getRecord['getRecord']=Affiliate::getAll();
        return view('admin.affiliate.list',$getRecord);
    }
    public function add(){ 
        $data['header_title']='Add New Affiliate';
        return view('admin.affiliate.add', $data);
    }
    public function insert(Request $request){
        //dd($request->all());
        request()->validate([
            'email' => 'required|email|unique:users'
        ]);
        $lastAffiliateId = Affiliate::max('id');

        $user = new Affiliate;
        $user->name             = trim($request->name);
        $user->email            = trim($request->email);
        $user->affiliate_type   = 1;
        $user->promo_code       = $lastAffiliateId+1;
        $user->password         = Hash::make($request->password);
        $user->save();

        return redirect('admin/affiliate/list')->with('success', "Affiliate added successfully.");
    }
    public function edit($id){ 
        $data['getRecord'] = Affiliate::getSingle($id);
        if( !empty($data['getRecord']) ){           
            $data['header_title']='Edit Affiliate';
            return view('admin.affiliate.edit', $data);
        }
        else{
            abort(404);
        }
        
    }
    public function update($id, Request $request){
        //dd($request->all());
        request()->validate([
            'email' => 'required|email|unique:users,email,'.$id
        ]);
        $user = Affiliate::getSingle($id);
        $user->name     = trim($request->name);
        $user->email    = trim($request->email);
        if( !empty($user->password) ){
            $user->password = Hash::make($request->password);
        }       
        $user->save();

        return redirect('admin/affiliate/list')->with('success', "User updated successfully.");
    }
    public function logout(){
        //Auth::logout();
        Auth::guard('admin')->logout();
        return redirect('/admin');
    }
}
