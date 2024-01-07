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

class AffiliateController extends Controller
{
    public function login(){
        //dd(Hash::make('.'));
        // if(!empty(Auth::check())){
        //     if(Auth::user()->user_type == 1){
        //         return redirect('admin/dashboard');
        //     }
        // }
        return view('affiliate.auth.login');
    }
    public function affiliateLogin(Request $request){
        //dd($request->all());
        
        $remember = !empty($request->remember) ? true : false;
        if(Auth::guard('affiliate')->attempt(['email' => $request->email, 'password' => $request->password], $remember)){
            //dd(Auth::guard('admin')->user());
            return redirect('affiliate/dashboard');
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
        return view('affiliate.dashboard');
    }
    public function list(){
        //echo "404";die;
        $getRecord['getRecord']=Affiliate::getAllSubAffiliate();
        return view('affiliate.sub-affiliate.list',$getRecord);
    }
    public function add(){ 
        $data['header_title']='Add New Affiliate';
        return view('affiliate.sub-affiliate.add', $data);
    }
    public function insert(Request $request){
        //dd($request->all());
        request()->validate([
            'email' => 'required|email|unique:users'
        ]);
        $lastAffiliateId = Affiliate::max('id');
        $user_id = Auth::guard('affiliate')->user()->id;
        //dd($user_id);

        $user = new Affiliate;
        $user->name             = trim($request->name);
        $user->email            = trim($request->email);
        $user->affiliate_type   = 2;
        $user->promo_code       = $lastAffiliateId+1;
        $user->password         = Hash::make($request->password);
        $user->created_by       = $user_id;
        $user->save();

        return redirect('affiliate/sub-affiliate/list')->with('success', "Affiliate added successfully.");
    }
    public function edit($id){ 
        $data['getRecord'] = Affiliate::getSingle($id);
        if( !empty($data['getRecord']) ){           
            $data['header_title']='Edit Sub Affiliate';
            return view('affiliate/sub-affiliate.edit', $data);
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

        return redirect('affiliate/sub-affiliate/list')->with('success', "Sub Affiliate updated successfully.");
    }
    public function logout(){
        //Auth::logout();
        Auth::guard('affiliate')->logout();
        return redirect('/affiliate');
    }
}
