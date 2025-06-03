@extends('backend.layouts.master')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <h4>Manage Category</h4>
            <div class="row justify-content-center">


                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">


                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($categories as $category)
                                        <tr>
                                            <td><img src="{{ asset($category->image) }}" alt="{{ $category->name }}" width="50"></td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <a href="{{ route('category.edit', $category->id) }}" class="btn btn-info">
                                                    <i class="mdi mdi-table-edit"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <form action="{{ route('category.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger"><i class="mdi mdi-delete"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4">No categories found.</td></tr>
                                        @endforelse
                                        </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endsection
