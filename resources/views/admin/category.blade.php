<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin</title>
    @include('admin.css')

<style>
  .div_center{
    text-align: center;
    padding-top:40px; 
  }
  .text{
    font-weight: bold; 
    font-size: 40px;
  }
  .btn-primary{
    height: 42px;
  }
  .table{
    margin: auto;
    width: 50%;
    margin-top:20px;
    border-collapse: collapse;
    text-align: center;
    color: white;
    border: 2px solid white

  }
</style>
  </head>
  <body>
    <div class="container-scroller">

      @include('admin.sidebar')
      <div class="container-fluid page-body-wrapper">
      @include('admin.header')
      
      <div class="main-panel">
          <div class="content-wrapper">
            @if(session()->has('message'))
                <div class="alert alert-success ">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="div_center">
              <h1 class="text">Add Category </h1>
              <form class="pt-2" action="{{ url('add_category') }}" method="POST">
                @csrf
              <input class="text-black" name="category_name" type="text" placeholder="Write Category Name" >
              <input class="btn btn-primary" type="submit" name="submit" value="Add Category">
             </form>
            </div>
            <table class="table">
              <tr class="table-primary">
                <th>Category Name</th>
                <th>Action</th>
              </tr>
              @foreach ($data as $item )
              <tr>
                <td>{{ $item->category_name }}</td>
                <th>
                  <a onclick=" return confirm('Are You Sure Delete Category')"
                  class="btn btn-danger"
                  href="{{ url('delete_category',$item->id) }}"
                  >
                    Delete
                  </a>
                </th>
              </tr>
              @endforeach
            </table>
            
            
          </div>
          </div>
      </div>

    </div>
    @include('admin.js')
  </body>
</html>