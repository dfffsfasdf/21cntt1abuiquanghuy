<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //dd("phải cần có quyền truy cập admin hoặc kỹ thuật viên");
        if(Auth::check()){
            $user=Auth::user();
            if($user->level==1 || $user->level==2){
                return $next($request);
            }
            else {
                return redirect('trangchu');
            }
        }
        else {
            return redirect('/dangnhap');
        }
    }
}
