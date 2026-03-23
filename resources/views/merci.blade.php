@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="text-center">
        <h2 class="mb-4" style="color:#fff;">Merci !</h2>
        @if(isset($alreadyAnswered) && $alreadyAnswered)
            <p style="color:#e9f5e9;">Vous avez déjà répondu à ce questionnaire.<br>Il n'est pas possible de soumettre plusieurs fois.</p>
        @else
            <p style="color:#e9f5e9;">Votre participation a bien été enregistrée.</p>
        @endif
    </div>
</div>
@endsection
