@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
<<<<<<< HEAD
<div class="container">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header text-white text-center" style="background-color: red;">Filter ::</div>
                <div class="card-body">
                    @foreach($filterByChildCategories as $filterchildcategory)
                        <p>


                            <a href="{{url()->current()}}/{{($filterchildcategory->childcategory->slug)??''}}">
                                {{ $filterchildcategory->childcategory?->name ?? 'No child category' }}
                            </a>
                        </p>
                    @endforeach
                </div>
            </div>

            <br>
            <form action=""></form>
        </div>

        <!-- Main content -->
        <div class="col-md-9">
            <div class="row">
                @forelse($advertisements as $advertisement)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <img src="{{ route('ad.image', basename($advertisement->feature_image)) }}" class="card-img-top img-thumbnail" alt="Ad Image">
                            <div class="card-body p-2 text-center">
                                <p class="mb-1">{{ $advertisement->name }}</p>
                                <strong>USD {{ $advertisement->price }}</strong>
                            </div>
                        </div>
                    </div>
                @empty 
                    <div class="col-12 text-center">
                        Sorry, we are unable to find products based on this category.
                    </div>
                @endforelse
=======

    <div class="container ">
        <div class="row ">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header text-white text-center" style="background-color: red;">Filter ::</div>
                    <div class="card-body">
                    @foreach($filterByChildCategories as $filterchildcategory)
                            <p>{{ $filterchildcategory->childcategory?->name ?? 'No child category' }}</p>

                    @endforeach
                    </div>


                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                   @forelse($advertisements as $advertisement)
                        <div class="col-3">
                          <img src="{{ route('ad.image', basename($advertisement->feature_image)) }}" class="img-thumbnail">

                            {{$advertisement->name}} /USD{{$advertisement->price}}
                        </div>
                       
                       
                    @empty 
                        Sorry,we are unable to find product based on this cantegory
                     @endforelse
                </div>
>>>>>>> 64abdc50cc53eb8880f3aa634ab3fe2ea4407f88
            </div>
        </div>
    </div>
</div>
@endsection
