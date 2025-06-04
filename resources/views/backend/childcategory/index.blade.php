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
                td.subcategory-laptops {
                background-color: #E3F2FD; /* Soft Light Blue */
                }

                td.subcategory-mobiles {
                    background-color: #FFF3E0; /* Light Orange */
                }

                td.subcategory-household-items {
                    background-color: #F3E5F5; /* Lavender */
                }

                td.subcategory-man {
                    background-color: #FFCDD2; /* Soft Red */
                }

                td.subcategory-woman {
                    background-color: #E1BEE7; /* Soft Purple */
                }

                td.subcategory-kids {
                    background-color: #F8BBD0; /* Light Pink */
                }

                td.subcategory-cycle {
                    background-color: #C8E6C9; /* Soft Green */
                }

                td.subcategory-bike {
                    background-color: #B2EBF2; /* Pale Cyan */
                }

                td.subcategory-buy-pets {
                    background-color: #FFF9C4; /* Light Yellow */
                }

                td.subcategory-houses-appartments {
                    background-color: #F5F5F5; /* Soft Neutral Gray */
                }

                td.subcategory-shops-office {
                    background-color: #FFE0B2; /* Light Amber */
                }

                /* Add more styles for your subcategories as needed */
            </style>
@endsection