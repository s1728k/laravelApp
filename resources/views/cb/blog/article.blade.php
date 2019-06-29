{{-- @extends("cb.layouts.blog")

@if($category == 'github' && $article =='basic-git-usage')

@section("category")
<h4><a href="/blog">Category</a> >> <a href="/blog/{{$category}}">Github</a> >> <a href="/blog/{{$category}}/{{$article}}">Basic Git Usage</a></h4>
@endsection

@section("blog")
@include('cb.blog.basic-git-usage')
@endsection

@endif
 --}}

@extends("cb.layouts.app")

@section("content")
@if($category == 'github' && $article =='basic-git-usage')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-9">
      <h4><a href="/blog">Category</a> >> <a href="/blog/{{$category}}">Github</a> >> <a href="/blog/{{$category}}/{{$article}}">Basic Git Usage</a></h4>
      {{-- <ul class="breadcrumb">
        <li><a href="/blog">Category</a></li>
        <li><a href="/blog/{{$category}}">Github</a></li>
        <li class="active">Basic Git Usage</li>
      </ul> --}}
    </div>
  </div>
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-9">
      @include('cb.blog.basic-git-usage')
    </div>
  </div>
</div>
@endif
@endsection