@extends('layouts.main')

@section('content')

<div class="container py-5 text-center">
    <h1>TRASH CAN</h1>
    <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Project Name</th>
            <th scope="col">Deleted at</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
            @forelse ($projects as $project)
            <tr>
                <th class="align-middle" scope="row">{{ $project->id }}</th>
                <td class="align-middle">{{ $project->name }}</td>
                <td class="align-middle">{{ $project->deleted_at }}</td>
                <td class="align-middle"> 
                    <a class="btn btn-small btn-primary" href="{{ route('admin.projects.show', $project->id) }}"><i class="fa-solid fa-eye"></i></a> 
                    <a class="btn btn-small btn-warning" href="{{ route('admin.projects.edit', $project->id) }}"><i class="fa-regular fa-pen-to-square"></i></a>
                    <form class="d-inline delete-form" action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" data-project-name="{{ $project->name }}">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-small btn-success" disabled><i class="fa-solid fa-arrows-rotate"></i></button>
                    </form>
                </td>
            </tr> 
            @empty
            <tr>
                <th scope="row" colspan="4" class="text-center">Non ci sono progetti</th>
            </tr> 
            @endforelse
          
        </tbody>
    </table>

    <div class="buttons d-flex justify-content-end">
        <a href="{{ route('admin.projects.index') }}" class="btn btn-small btn-secondary me-2">BACK</a>
    </div>

    @include('includes.projects.delete-modal')

</div>

@endsection
