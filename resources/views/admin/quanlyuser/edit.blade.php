@extends('admin.quanlyuser.layouts.master')
@section('content2')
      <div class="container">
        <h1>Edit Users</h1>
      @isset($user)
        <form action="{{ route('admin.postUserEdit',$user[0]->id) }}" method="post" enctype="multipart/form-data">
          @csrf
          @method('put')
          <label for="">Full name</label>
          <input type="text" name="full_name" id="" value="{{isset($user[0]->full_name)?$user[0]->full_name:''}}" class="form-control"disabled aria-describedby="helpId">
          <label for="">Email</label>
          <input type="text" name="email" id="" value="{{isset($user[0]->email)?$user[0]->email:''}}" class="form-control"disabled aria-describedby="helpId">
          <label for="">Password</label>
          <input type="text" name="password" id="" value="{{isset($user[0]->password)?$user[0]->password:''}}" class="form-control"disabled aria-describedby="helpId">      
          <label for="">Phone</label>
          <input type="text" name="phone" id="" value="{{isset($user[0]->phone)?$user[0]->phone:''}}" class="form-control"disabled aria-describedby="helpId">
          <label for="">Address</label>
          <input type="text" name="address" id="" value="{{isset($user[0]->address)?$user[0]->address:''}}" class="form-control"disabled aria-describedby="helpId">
          <div class="form-group">
            <label for="">Level</label>
            <select class="form-control" name="level" id="">
              <option>{{$user[0]->level}}</option>
              @if($user[0]->level=='1')
                <option value='2'>2</option>
                <option value='3'>3</option>
              @elseif($user[0]->level=='2')
                <option value='1'>1</option>
                <option value='3'>3</option>
              @else
                <option value='1'>1</option>
                <option value='2'>2</option>
              @endif
            </select>
          </div>
          </br></br><button type="submit" class="btn btn-primary">Edit</button>
        </form>
      @endisset
      </div>
@endsection