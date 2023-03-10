@extends('layouts.main')

@section('title', 'PROJECTS')
    
@section('content')

<div class="container py-5 text-center">
    <h1>Types</h1>
    <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Type Name</th>
            <th scope="col">Color</th>
            <th scope="col">Created at</th>
            <th scope="col">Updated at</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
            @forelse ($types as $type)
            <tr>
                <th class="align-middle" scope="row">{{ $type->id }}</th>
                <td class="align-middle">{{ $type->label }}</td>
                <td class="align-middle"><input type="color" value="{{ $type->color }}" disabled></td>
                <td class="align-middle">{{ $type->created_at }}</td>
                <td class="align-middle">{{ $type->updated_at }}</td>
                <td class="align-middle"> 
                    <a class="btn btn-small btn-warning" href="{{ route('admin.types.edit', $type->id) }}"><i class="fa-regular fa-pen-to-square"></i></a>
                    <form class="d-inline delete-form" action="{{ route('admin.types.destroy', $type->id) }}" method="POST" data-project-name="{{ $type->label }}">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-small btn-danger"><i class="fa-regular fa-trash-can"></i></button>
                    </form>
                </td>
            </tr> 
            @empty
            <tr>
                <th scope="row" colspan="5" class="text-center">Non ci sono tipi</th>
            </tr> 
            @endforelse
          
        </tbody>
    </table>

    <div class="buttons d-flex justify-content-end mb-5">
        <a href="{{ route('admin.types.create') }}" class="btn btn-small btn-success"><i class="fa-solid fa-plus"></i></a>
    </div>

    <div class="offset-9 col-3" >{{ $types->links() }}</div>

    @include('includes.projects.delete-modal')

</div>
    
@endsection