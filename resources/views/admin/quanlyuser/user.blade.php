@extends('admin.quanlyuser.layouts.master')
@section('content2')
  @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
  @endif
      <table class="table">
        <tbody>
            <tr> 
            <th>STT</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Mật khẩu</th>
            <th>Số điện thoại</th>
            <th>Địa chỉ</th>
            <th>Level</th>
            <th colspan="2">Setting</th>
            </tr>
            @isset($users)
            @php
            $i=1;
            @endphp
            @foreach ($users as $user)
            <tr>
            <td scope="row">{{$i;}}</td>
            <td>{{$user->full_name}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->password}}</td>
            <td>{{$user->phone}}</td>
            <td>{{$user->address}}</td>
            <td>{{$user->level}}</td>
            <td>
              <a href="{{route('admin.getUserEdit',$user->id)}}" class="btn btn-outline-secondary" role="button"><i class="fas fa-cog  fa-lg"></i></a>
            </td>
            <form action="{{route('admin.getUserDelete',$user->id)}}" method="post">
              @csrf
              @method('delete')
              <td>
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-trash-alt"></i></button>
              </td>
            </form>
            </tr>
            @php
            $i+=1;
            @endphp
            @endforeach
            @endisset
        </tbody>
      </table>
@endsection
   