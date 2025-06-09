@extends('layouts.app')
@section('content')

<style>
    .table td img {
        width: 100px;
        height: auto;
        border-radius: 4px;
    }

    .badge-success {
        background-color: #28a745;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    .table-responsive-bordered {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 15px;
        background: #fff;
    }
</style>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar Column -->
        <div class="col-md-3 col-lg-2">
            <div class="sidebar-sticky">
                @include('ads.sidebar')
            </div>
        </div>

        <!-- Main Content Column -->
        <div class="col-md-9 col-lg-10">
            <div class="table-responsive-bordered">
                <table class="table align-middle table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>View</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ads as $key => $ad)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>
                                    <img src="{{ Storage::url($ad->feature_image) }}" alt="Ad Image">

                                </td>
                                <td>{{ $ad->name }}</td>
                                <td>USD {{ $ad->price }}</td>
                                <td>
                                    @if ($ad->published == 1)
                                        <span class="badge badge-success">Published</span>
                                    @else
                                        <span class="badge badge-danger">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('ads.edit', $ad->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                </td>
                                <td>
                                    <a href="{{ route('ads.show', $ad->id) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                                <td>
                                    <!-- Example delete form/button -->
                                    <form action="{{ route('ads.destroy', $ad->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">You have no ads</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
