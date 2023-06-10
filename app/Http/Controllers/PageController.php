<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;
use App\Models\Customer;
use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Auth;
use Slide;


class PageController extends Controller
{
    public function index(){
        $new_products=Product::where('new',1)->get();
        $products=Product::all();
        return view('index', compact('new_products','products'));
    }
    public function show(string $id){
        //$pr = Product::find($id);
        $product=DB::table('products')->where('id',$id)->get();
        return view('product', array('product' => $product));
    }
    //thêm 1 sản phẩm có id cụ thể vào model cart rồi lưu dữ liệu của model cart vào 1 session có tên cart (session được truy cập bằng thực thể Request)
    public function addToCart(Request $request,$id){
        $product=Product::find($id);
        $oldCart=Session('cart')?Session::get('cart'):null;
        $cart=new Cart($oldCart);
        $cart->add($product,$id);
        $request->session()->put('cart',$cart);
        return redirect()->back();
    }

    //thêm 1 sản phẩm có số lượng >1 có id cụ thể vào model cart rồi lưu dữ liệu của model cart vào 1 session có tên cart (session được truy cập bằng thực thể Request)
    public function addManyToCart(Request $request,$id){
        $product=Product::find($id);
        $oldCart=Session('cart')?Session::get('cart'):null;
        $cart=new Cart($oldCart);
        $cart->addMany($product,$id,$request->qty);
        $request->session()->put('cart',$cart);
       
        return redirect()->back();
    }


    public function delCartItem($id){
        $oldCart=Session::has('cart')?Session::get('cart'):null;
        $cart=new Cart($oldCart);
        $cart->removeItem($id);
        if(count($cart->items)>0){
            Session::put('cart',$cart);
        }else Session::forget('cart');
        return redirect()->back();
    }

    public function getCheckout(){
        return view('checkout');
    }

    public function postCheckout(Request $request){
    
        $cart=Session::get('cart');
        $customer=new Customer();
        $customer->name=$request->input('name');
        $customer->gender=$request->input('gender');
        $customer->email=$request->input('email');
        $customer->address=$request->input('address');
        $customer->phone_number=$request->input('phone_number');
        $customer->note=$request->input('note');
        $customer->save();

        $bill=new Bill();
        $bill->id_customer=$customer->id;
        $bill->date_order=date('Y-m-d');
        $bill->total=$cart->totalPrice;
        $bill->payment=$request->input('payment_method');
        $bill->note=$request->input('note');
        $bill->save();

        foreach($cart->items as $key=>$value)
        {
            $bill_detail=new BillDetail();
            $bill_detail->id_bill=$bill->id;
            $bill_detail->id_product=$key;
            $bill_detail->quantity=$value['qty'];
            $bill_detail->unit_price=$value['price']/$value['qty'];
            $bill_detail->save();
        }
        Session::forget('cart');
        return redirect()->back()->with('success','Đặt hàng thành công');
    }

    public function getSignin(){
        return view('signup');
    }
    public function getLogin(){
        return view('admin.login');
    }

    public function postSignin(Request $req){
        $this->validate($req,
        ['email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|max:20',
            'fullname'=>'required',
            'repassword'=>'required|same:password'
        ],
        ['email.required'=>'Vui lòng nhập email',
        'email.email'=>'Không đúng định dạng email',
        'email.unique'=>'Email đã có người sử  dụng',
        'password.required'=>'Vui lòng nhập mật khẩu',
        'repassword.same'=>'Mật khẩu không giống nhau',
        'password.min'=>'Mật khẩu ít nhất 6 ký tự'
        ]);

        $user=new User();
        $user->full_name=$req->fullname;
        $user->email=$req->email;
        $user->password=Hash::make($req->password);
        $user->phone=$req->phone;
        $user->address=$req->address;
        $user->level=3;  //level=1: admin; level=2:kỹ thuật; level=3: khách hàng
        $user->save();
        return redirect()->back()->with('success','Tạo tài khoản thành công');
    }

    public function postLogin(Request $req) {
        $this->validate($req,
        ['email'=>'required|email',
            'password'=>'required|min:6|max:20',
        ],
        ['email.required'=>'Vui lòng nhập email',
        'email.email'=>'Không đúng định dạng email',
        'password.required'=>'Vui lòng nhập mật khẩu',
        'repassword.max'=>'Mật khẩu không quá 20 ký tự',
        'password.min'=>'Mật khẩu ít nhất 6 ký tự'
        ]);
        $credentials = array('email'=>$req->email,'password'=>$req->password);
        if (Auth::attempt($credentials)) {
            return redirect() -> back()->with(['flag'=>'success','message'=> 'Đăng nhập thành công!']);
        }else {
            return redirect() -> back()->with(['flag'=>'success','message'=> 'Đăng nhập không thành công!']);
        }
    }

    public function postlogout() {
        Auth::logout();
        return redirect()->route();
    }

}   

