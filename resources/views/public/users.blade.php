@extends('layouts.public')

@section('content')
    <form action="{{ route('public.users') }}" method="get" id="index">
        <select name="perPage" class="form-control" onchange="document.getElementById('form').submit();">
            <option value="0" {{ !$perPage ? 'selected' : '' }}>&infin;</option>
            @for ($i = $step; $i <= $max; $i += $step)
                <option value="{{ $i }}" {{ $perPage === $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        <select>
    </form>

    @if ($perPage > 0)
        <div class="row justify-content-center">
            {{ $users->appends('perPage', $perPage)->links() }}
        </div>
    @endif

    <table class="table table-light table-striped table-hover users">
        <thead class="thead-dark text-nowrap">
            <tr>
                @th_users_sort(username)
                @th_users_sort(about)
                @th_users_sort(registered_at)
                @th_users_sort(post_count)
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td><a href="{{ route('public.profile.show', ['profile' => $user->username]) }}">{{ $user->username }}</a></td>
                    <td class="about">{{ limit_words($user->about, 10) ?? '-' }}</td>
                    <td>{{ extractDate($user->registered_at) }}</td>
                    <td>{{ $user->post_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($perPage > 0)
        <div class="row justify-content-center">
            {{ $users->appends('perPage', $perPage)->links() }}
        </div>
    @endif

    <form action="{{ route('public.users') }}" method="get" id="form">
        <select name="perPage" class="form-control" onchange="document.getElementById('form').submit();">
            <option value="0" {{ !$perPage ? 'selected' : '' }}>&infin;</option>
            @for ($i = $step; $i <= $max; $i += $step)
                <option value="{{ $i }}" {{ $perPage === $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        <select>
    </form>
@stop