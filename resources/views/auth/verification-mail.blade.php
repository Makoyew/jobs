<p>Welcome {{ $user->name }}</p>


<p>You received this email as a result of your registration to our website</p>
<p>Please click on the verification link to verify you account</p>

<p>
    <a href="{{ url('/verification/' . $user->id. '/' .$user->remember_token) }}">Click me! hehe</a>
    {{-- <a href="{{ url('/verification/' . $user->id . '/' . $user->remember_token . '?return_to=' . urlencode('/original_page')) }}">Click Me!</a> --}}

</p>
