# How To Make A Custom View Of Add Method

A way to make a custom view of add method is override it. This is a best way if CMSHelper can't handle the layout that you want.

```php
public function getAdd() {
  //Create an Auth
  if(!CMS::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {    
    CMS::redirect(CMS::adminPath(),trans("cms.denied_access"));
  }
  
  $data = [];
  $data['page_title'] = 'Add Data';
  
  //Please use view method instead view method from laravel
  return $this->view('custom_add_view',$data);
}
```

Then, create your own `add view`

```php
<!-- First, extends to the CMS Layout -->
@extends('cms::admin_template')
@section('content')
  <!-- Your html goes here -->
  <div class='panel panel-default'>
    <div class='panel-heading'>Add Form</div>
    <div class='panel-body'>
      <form method='post' action='{{CMS::mainpath('add-save')}}'>
        <div class='form-group'>
          <label>Label 1</label>
          <input type='text' name='label1' required class='form-control'/>
        </div>
         
        <!-- etc .... -->
        
      </form>
    </div>
    <div class='panel-footer'>
      <input type='submit' class='btn btn-primary' value='Save changes'/>
    </div>
  </div>
@endsection
```
## What's Next
- [How To Make A Custom View Of Edit Method](./how-to-custom-edit-view.md)

## Table Of Contents
- [Back To Index](./index.md)
