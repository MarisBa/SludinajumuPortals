@extends('backend.layouts.master')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            @include('backend.inc.message')
            <h4>Manage ChildCategory</h4>
            <div class="row justify-content-center">

                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Subcategory</th>
                                            <th>Name</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($childcategories as $childcategory)
                                            <tr>
                                                <td class="subcategory-{{ Str::slug($childcategory->subcategory ? $childcategory->subcategory->name : 'none') }}">
                                                    {{ $childcategory->subcategory ? $childcategory->subcategory->name : 'N/A' }}
                                                </td>
                                                <td>{{ $childcategory->name }}</td>
                                                <td>
                                                    <a href="{{ route('childcategory.edit', [$childcategory->id]) }}">
                                                        <button class="btn btn-sm btn-info">
                                                            <i class="mdi mdi-table-edit"></i>
                                                        </button>
                                                    </a>
                                                </td>
                                                <td>
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                                        data-target="#exampleModal{{ $childcategory->id }}">
                                                         <i class="mdi mdi-delete"></i>
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="exampleModal{{ $childcategory->id }}" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form action="{{ route('childcategory.destroy', $childcategory->id) }}"
                                                                method="post">@csrf
                                                                @method('DELETE')
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Delete
                                                                            confirmation</h5>
                                                                        <button type="button" class="close" data-dismiss="modal"
                                                                            aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                       <p> Are you sure you want to delete this item ?</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Cancel</button>
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Yes Delete it</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">No childcategory to display</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                /* Example styles - adjust colors as needed */
                td.subcategory-electronics {
                    background-color: aliceblue;
                }
                td.subcategory-clothing {
                    background-color: bisque;
                }
                td.subcategory-home-appliances {
                    background-color: thistle;
                }
                td.subcategory-books {
                    background-color: tomato;
                }
                td.subcategory-sports {
                    background-color: gray;
                }
                td.subcategory-none {
                    background-color: #f8f9fa;
                }
                /* Add more styles for your subcategories as needed */
            </style>
@endsection