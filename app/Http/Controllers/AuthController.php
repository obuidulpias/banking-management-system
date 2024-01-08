<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Auth;
use App\Models\User;
use App\Models\Affiliate;
use App\Models\Transaction;
use App\Mail\ForgotPasswordMail;
use Mail;
use Str;

class AuthController extends Controller
{
    public function login(){
        //dd(Hash::make('.'));
        // if(!empty(Auth::check())){
        //     if(Auth::user()->user_type == 1){
        //         return redirect('admin/dashboard');
        //     }
        // }
        return view('user.auth.login');
    }
    public function adminLogin(Request $request){
        //dd($request->all());
        $remember = !empty($request->remember) ? true : false;
        if(Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $remember)){
            //dd(Auth::guard('admin')->user());
            return redirect('user/dashboard');
        }else{
            return redirect()->back()->with('error', 'Please enter currect email and password');
        }
    }
    public function signUp(){
        return view('user.auth.register');
    }
    public function register(Request $request){
        //dd($request->all());
        request()->validate([
            'email' => 'required|email|unique:users'
        ]);
        if($request->promo_code !=""){
            $affiliate_arr = Affiliate::getSingle($request->promo_code);
            if($affiliate_arr!=""){
                $promo_code = $request->promo_code;
            }else{
                return redirect()->back()->with('error', "Promo Code doesn't match.");
            }
        }else{
            $promo_code = "";
        }

        $user = new User;
        $user->name             = trim($request->name);
        $user->email            = trim($request->email);
        $user->bod              = $request->bod;
        $user->affiliates_id    = $promo_code;
        if($request->password == $request->confim_password){
            $user->password = Hash::make($request->password);
            $user->save();
        }        
        else{
            return redirect()->back()->with('error', "Password and confirm password doesn't match.");
        }

        return redirect('/')->with('success', "Sign Up Successfully. Please log in....");
    }
    public function dashboard(){
        // $user_id = Auth::id();
        // dd($user_id);
        return view('user.dashboard');
    }

    public function list(){
        $user_id = Auth::guard('web')->user()->id;
        //dd($user_id);
        $getRecord['getRecord']=Transaction::getAll($user_id);
        return view('user.transaction.list',$getRecord);
    }
    public function add(){ 
        //echo "404";die;
        $data['header_title']='Add Money';
        return view('user.transaction.add', $data);
    }
    public function insert(Request $request){
        //dd($request->all());
        $user_id = Auth::guard('web')->user()->id;
        $user_amount = Auth::guard('web')->user()->amount;
        $affiliates_id = Auth::guard('web')->user()->affiliates_id;
        

        $user = new Transaction;
        $user->amount           = $request->amount;
        $user->transaction_type = 1;
        $user->user_id          = $user_id;
        $user->save();
        
        User::where('id', $user_id)
       ->update([
           'amount' => $request->amount+$user_amount
        ]);

        //commissions part here
        if($affiliates_id != ""){
            $affiliate_arr = Affiliate::getSingle($affiliates_id);
            if($affiliate_arr->affiliate_type == 1){
                $commition_affiliate1=round($request->amount*30/100,2);
                //dd($commition);
                Affiliate::where('id', $affiliates_id)
                    ->update([
                        'amount' => $affiliate_arr->amount+$commition_affiliate1
                        ]);
            }
            if($affiliate_arr->affiliate_type == 2){
                $commition_affiliate1=round($request->amount*20/100,2);
                //dd($commition);
                Affiliate::where('id', $affiliates_id)
                    ->update([
                        'amount' => $affiliate_arr->amount+$commition_affiliate1
                        ]);
                $commition_affiliate2=round($request->amount*10/100,2);
                        //dd($commition);
                $parent_affiliateID=$affiliate_arr->created_by;
                $affiliate_arr = Affiliate::getSingle($parent_affiliateID);
                Affiliate::where('id', $parent_affiliateID)
                        ->update([
                            'amount' => $affiliate_arr->amount+$commition_affiliate2
                        ]);
            }
        }
        return redirect('user/transaction/list')->with('success', "Amount added successfully.");
    }
    
    public function logout(){
        //Auth::logout();
        Auth::guard('web')->logout();
        return redirect('/');
    }
}
