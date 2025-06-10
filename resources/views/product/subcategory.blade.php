@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')
@section('content')

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
            </div>
        </div>
    </div>

@endsection
