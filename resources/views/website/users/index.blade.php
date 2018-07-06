@extends('layouts.website')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('users.index.public') }}" method="get" id="form1">
                <select name="perPage" class="form-control" onchange="document.getElementById('form1').submit();">
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
                        <th>#</th>
                        @th_users_sort(username)
                        @th_users_sort(about)
                        @th_users_sort(registered_at)
                        @th_users_sort(post_count)
                    </tr>
                </thead>
                <tbody>
                    @php ($i = 0)
                    @foreach ($users as $_user)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td><a href="{{ route_user_show($_user) }}">{{ $_user->username }}</a></td>
                            <td class="about">{{ limit_words($_user->about ?: '-', 10) }}</td>
                            <td>{{ extract_date($_user->registered_at) }}</td>
                            <td>{{ $_user->post_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($perPage > 0)
                <div class="row justify-content-center">
                    {{ $users->appends('perPage', $perPage)->links() }}
                </div>
            @endif

            <form action="{{ route('users.index.public') }}" method="get" id="form2">
                <select name="perPage" class="form-control" onchange="document.getElementById('form2').submit();">
                    <option value="0" {{ !$perPage ? 'selected' : '' }}>&infin;</option>
                    @for ($i = $step; $i <= $max; $i += $step)
                        <option value="{{ $i }}" {{ $perPage === $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                <select>
            </form>
        </div>
    </div>
@stop
