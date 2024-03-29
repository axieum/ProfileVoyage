@extends('layouts.app')

@section('title', "Profiles")
@section('subtitle', "Pick or create a new profile")

@section('content')
    <div class="container">
        <div class="columns is-multiline is-mobile m-b-15">
            @foreach ($profiles as $profile)
                <div class="column is-3-desktop is-4-tablet is-12-mobile">
                    <div class="card">
                        <div class="card-image">
                            <figure class="image is-1by1">
                                <img src="{{ url(file_exists(storage_path('app/public/avatars/' . $profile->avatar . '.png')) ? 'storage/avatars/' . $profile->avatar . '.png' : 'img/_profile.png') }}" alt="Profile avatar">
                            </figure>
                        </div>
                        <div class="card-content">
                            <div class="card-head">
                                <p class="title is-4 m-b-0">{{ $profile->name }}</p>
                                <a href="{{ route('profile.show', $profile->link) }}" class="is-size-6">
                                    <span class="icon is-small"><i class="fa fa-link"></i></span>
                                    {{ substr(route('profile.show', $profile->link), strpos(route('profile.show', $profile->link), '//') + 2) }}
                                </a>
                                <a onclick="copyLink(this);" data-value="{{ route('profile.show', $profile->link) }}"><span class="icon is-small"><i class="fa fa-share-square-o"></i></span></a>
                            </div>
                            <div class="level is-mobile">
                                <div class="level-left">
                                    <small class="level-item">
                                        <span class="icon is-small"><i class="fa fa-clock-o"></i></span>&nbsp;<time datetime="{{ $profile->updated_at }}">{{ date('jS \o\f F Y', strtotime($profile->updated_at)) }}</time>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <a href="{{ route('profile.show', $profile->link) }}" class="card-footer-item"><span class="icon"><i class="fa fa-eye"></i></span>&nbsp;View</a>
                            <a href="{{ route('profile.edit', $profile->link) }}" class="card-footer-item"><span class="icon"><i class="fa fa-pencil"></i></span>&nbsp;Edit</a>
                        </footer>
                    </div>
                </div>
            @endforeach
            <div class="column is-3-desktop is-4-tablet is-12-mobile">
                <a href="{{ route('profile.create') }}">
                    <div class="card has-content-centered" special>
                        <div class="wrapper has-text-centered p-a-15">
                            <span class="icon is-large white-text"><i class="fa fa-plus"></i></span>
                            <p class="white-text is-size-4 has-text-weight-semibold">Create New</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function copyLink(element) {
            var link = element.getAttribute("data-value");

            // Create a temporary element to hold the link.
            target = document.getElementById("_hiddenCopyText_");
            if (!target) {
                var target = document.createElement("textarea");
                target.style.position = "absolute";
                target.style.left = "-9999px";
                target.style.top = "0";
                target.id = "_hiddenCopyText_";
                document.body.appendChild(target);
            }
            target.textContent = link;

            // Select the text.
            var currentFocus = document.activeElement;
            target.focus();
            target.setSelectionRange(0, target.value.length);

            // Try to copy it.
            try {
                var successful = document.execCommand('copy');
                var msg = successful ? 'successfully' : 'unsuccessfully';
                var type = successful ? 'success' : 'danger';
                notifications.toast('The profile link "' + link + '" was copied ' + msg, {type: 'is-' + type});
            } catch(error) {
                notifications.toast('Something went wrong, unable to copy the url.', {type: 'is-danger'});
            }

            // Remove temporary element.
            target.remove();
        }
    </script>
@endsection
