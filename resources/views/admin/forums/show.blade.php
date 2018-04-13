@extends('admin.base')

@section('more-content')
    <div class="card">

        <div class="card-header">
            <strong>{{ $forum->title }}</strong>
        </div>

        <div class="card-body">
            <p>{!! $forum->description ? BBCode::convertToHtml($forum->description) : 'Nema opisa.' !!}</p>

            <table class="table table-striped info">
                <tr>
                    <td>ID</td>
                    <td>{{ $forum->id }}</td>
                </tr>
                <tr>
                    <td>Slug</td>
                    <td>{{ $forum->slug }}</td>
                </tr>
                <tr>
                    <td>Pozicija</td>
                    <td>{{ $forum->position }}</td>
                </tr>
                <tr>
                    <td>Zaključan</td>
                    <td>{{ $forum->is_locked ? 'da' : 'ne' }}</td>
                </tr>
                <tr>
                    <td>Sekcija</td>
                    <td><a href="{{ route('sections.show', ['section' => $section->id]) }}">{{ $section->title }}</a></td>
                </tr>
                <tr>
                    <td>Natforum</td>
                    <td>
                        @if ($parentForum)
                            <a href="{{ route('forums.show', ['forum' => $parentForum->id]) }}">{{ $parentForum->title }}</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Obrisan</td>
                    <td>{{ $forum->deletedAt ?? '-' }}</td>
                </tr>
            </table>
        </div>

    </div>
@stop