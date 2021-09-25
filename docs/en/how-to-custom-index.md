# How To Make A Custom View Of Index Method

A way to custom the index method is override the existing index method. This is best way if CMSHelper can't handle the layout that you want

```php
public function getIndex() {
  //First, Add an auth
   if(!CMS::isView()) CMS::redirect(CMS::adminPath(),trans('cms.denied_access'));
   
   //Create your own query 
   $data = [];
   $data['page_title'] = 'Products Data';
   $data['result'] = DB::table('products')->orderby('id','desc')->paginate(10);
    
   //Create a view. Please use `view` method instead of view method from laravel.
   return $this->view('your_custom_view_index',$data);
}
```

Create your own view in `/resources/views/your_custom_view_index.blade.php'
```php
<!-- First you need to extend the CMSHelper layout -->
@extends('cms::admin_template')
@section('content')
<!-- Your custom  HTML goes here -->
<table class='table table-striped table-bordered'>
  <thead>
      <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Action</th>
       </tr>
  </thead>
  <tbody>
    @foreach($result as $row)
      <tr>
        <td>{{$row->name}}</td>
        <td>{{$row->description}}</td>
        <td>{{$row->price}}</td>
        <td>
          <!-- To make sure we have read access, wee need to validate the privilege -->
          @if(CMS::isUpdate() && $button_edit)
          <a class='btn btn-success btn-sm' href='{{CMS::mainpath("edit/$row->id")}}'>Edit</a>
          @endif
          
          @if(CMS::isDelete() && $button_edit)
          <a class='btn btn-success btn-sm' href='{{CMS::mainpath("delete/$row->id")}}'>Delete</a>
          @endif
        </td>
       </tr>
    @endforeach
  </tbody>
</table>

<!-- ADD A PAGINATION -->
<p>{!! urldecode(str_replace("/?","?",$result->appends(Request::all())->render())) !!}</p>
@endsection
```

## What's Next
- [How To Make A Custom View Of Add Method](./how-custom-view-add.md)

## Table Of Contents
- [Back To Index](./index.md)
