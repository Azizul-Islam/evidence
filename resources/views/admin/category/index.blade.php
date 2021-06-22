@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info">{{ __('Create category') }}</div>
               <div class="card-body">
                <form action="@if(!blank($update_category)) {{ route('categories.update',$update_category->id) }} @else {{ route('categories.store') }} @endif"  method="POST">
                    @csrf
                    @if(!blank($update_category)) @method('PUT') @endif
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" @if(!blank($update_category)) value="{{ old('name',$update_category->name) }}" @else value="{{ old('name') }}" @endif placeholder="Category name" id="">
                        @error('name')
                            <strong class="invalid-feedback">{{ $message }}</strong>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Parent</label>
                        <select name="parent_id" class="form-control @error('parent_id') is-invalid @enderror" id="">
                            <option value="">-- Parent Category --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category['id'] }}" @if(!blank($update_category) && $update_category->parent_id == $category['id']) selected @endif>{!! $category['name'] !!}</option>
                            @endforeach
                        </select>
                        @error('name')
                            <strong class="invalid-feedback">{{ $message }}</strong>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary">SUBMIT</button>
                        @if(!blank($update_category)) <a href="{{ route('categories.index') }}" class="btn btn-outline-danger" >Cancel</a>@endif
                    </div>
                    
                    
                    </form>
               </div>
            </div>
        </div>
        <div class="col-md-6">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              @elseif(session('error'))
              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif
            <div class="card">
                <div class="card-header bg-info">{{ __('All Category') }}</div>
                <table class="table table-hover">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $i => $category)
                      <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{!! $category["name"] !!}</td>
                        <td>{{ $category["slug"] }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('categories.index',['category_id' => $category['id']]) }}" class="btn btn-sm btn-outline-info rounded">Edit</a>
                                <form onsubmit="return confirm('Are you sure?')" action="{{ route('categories.destroy',$category['id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger ml-2 rounded">Delete</button>
                                   
                                </form>
                            </div>
                        </td>
                      </tr>
                      @empty
                      <tr><td colspan="4" class="text-center text-danger">No data found!</td></tr>
                          
                      @endforelse
                    </tbody>
                  </table>
                
            </div>
        </div>
    </div>
</div>
@endsection
