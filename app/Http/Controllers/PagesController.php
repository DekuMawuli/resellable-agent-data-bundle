<?php

namespace App\Http\Controllers;



use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Customs\CustomHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class PagesController extends Controller
{

    public function index(){

        $categories = Category::where("is_active", true)->get();
        $setting = Setting::firstOrCreate(["id" => 1],
            [
                "code" => Str::uuid(),
                "whatsapp_link" => null,
                "whatsapp_number" => null,
                "contact_number" => null,
                "account_balance" => 0.00
            ]
        );
        $recentOrders = Order::with(["product", "product.category"])
            ->take(15)
            ->orderBy('created_at', 'desc')
            ->get();
        $allProducts = Product::query()
            ->with(["category"])
            ->orderBy("category_id")
            ->orderBy("created_at")
            ->get();

        return view("pages.home", [
            "categories" => $categories,
            "allProducts" => $allProducts,
            "recentOrders" => $recentOrders,
            "setting" => $setting
        ]);
    }

    public function allProducts(Request $request)
    {
        $setting = Setting::firstOrCreate(["id" => 1],
            [
                "code" => Str::uuid(),
                "whatsapp_link" => null,
                "whatsapp_number" => null,
                "contact_number" => null,
                "account_balance" => 0.00
            ]
        );

        return view("pages.products", [
            "setting" => $setting
        ]);
    }

    public function login(){
        return view("pages.login");
    }


    public function processLogin(Request $request){
        $rateLimitKey = $this->loginRateLimitKey($request);

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            CustomHelper::message("danger", "Too many login attempts. Try again in {$seconds} seconds.");
            return redirect(route("pages.login"))->withInput($request->only("phone"));
        }

        $validatedData = $request->validate([
            "phone" => "required",
            "password" => "required"
        ]);

        $user = User::firstWhere("phone", "=", $validatedData['phone']);

        if (is_null($user)){
            RateLimiter::hit($rateLimitKey, 300);
            CustomHelper::message("danger", "Invalid Credentials");
            return redirect(route("pages.login"))->withInput($request->only("phone"));
        }

        if ($user->role == "agent"){
            if (Auth::attempt($validatedData)){
                    $request->session()->regenerate();
                    RateLimiter::clear($rateLimitKey);
                    return redirect(route("agent.dashboard"));
                }
            RateLimiter::hit($rateLimitKey, 300);
            CustomHelper::message("danger", "Invalid Credentials");
            return redirect(route("pages.login"))->withInput($request->only("phone"));
        }
        elseif ($user->role == "admin"){
            if (Auth::attempt($validatedData)){
                $request->session()->regenerate();
                RateLimiter::clear($rateLimitKey);
                session(["view-balance" => "N"]);
                return redirect(route("root.dashboard"));
            }
            RateLimiter::hit($rateLimitKey, 300);
            CustomHelper::message("danger", "Invalid Credentials");
            return redirect(route("pages.login"))->withInput($request->only("phone"));
        }

        RateLimiter::hit($rateLimitKey, 300);
        CustomHelper::message("danger", "Unknown Role");
        return redirect(route("pages.login"))->withInput($request->only("phone"));
    }


     public function register(){
        return view("pages.register");
    }

    public function processRegistration(Request $request){
        $validatedData = $request->validate([
            "name" => "required|string",
            "phone" => "required|digits:10|unique:users,phone",
            "password" => "required",
            "confirm-password" => "required|same:password"
        ]);


        User::create([
            "name" => $validatedData['name'],
            "phone" => $validatedData['phone'],
            "password" => Hash::make($validatedData['password']),
            "role" => "agent",
            "agent_status" => "active",
            "balance" => 0.00,
            "code" => Str::uuid()
        ]);

        CustomHelper::message("primary", "Your account has been created successfully. Kindly log in, top up, and start buying.");
        // CustomHelper::sendSms($newUser);
        return redirect(route("pages.login"));
    }


    public function profile(){
        return view("pages.profile");
    }


    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route("pages.login"));
    }

    private function loginRateLimitKey(Request $request): string
    {
        return 'login|' . Str::lower(trim((string) $request->input('phone'))) . '|' . $request->ip();
    }
}
