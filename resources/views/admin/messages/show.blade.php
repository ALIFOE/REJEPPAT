@extends('admin.layout')

@section('title', 'Message de ' . $contact->nom)
@section('sous_titre', 'Reçu le ' . $contact->created_at->locale('fr')->translatedFormat('j F Y à H\hi'))

@section('actions')
    <a href="{{ route('admin.messages.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Messages</a>
@endsection

@section('content')

    <div class="a-grille a-grille--large">
        <div class="a-carte">
            <div class="a-carte__entete">
                <div><h2>{{ $contact->objet }}</h2></div>
            </div>
            <div class="a-carte__corps">
                <p class="a-texte-long" style="margin: 0">{{ $contact->message }}</p>
            </div>
            <div class="a-form__pied" style="justify-content: flex-start">
                <a href="mailto:{{ $contact->email }}?subject={{ rawurlencode('Re : ' . $contact->objet) }}" class="a-btn"><i class="fas fa-reply"></i> Répondre par e-mail</a>
                <form action="{{ route('admin.messages.update', $contact) }}" method="post">
                    @csrf
                    @method('put')
                    <button type="submit" class="a-btn a-btn--clair"><i class="fas fa-envelope"></i> Marquer comme non lu</button>
                </form>
            </div>
        </div>

        <div>
            <div class="a-carte a-section">
                <div class="a-carte__entete"><h2>Expéditeur</h2></div>
                <div class="a-carte__corps">
                    <dl class="a-details">
                        <dt>Nom</dt><dd>{{ $contact->nom }}</dd>
                        <dt>E-mail</dt><dd><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></dd>
                        <dt>Téléphone</dt><dd>@if ($contact->telephone)<a href="tel:{{ $contact->telephone }}">{{ $contact->telephone }}</a>@else – @endif</dd>
                    </dl>
                </div>
            </div>
            <form action="{{ route('admin.messages.destroy', $contact) }}" method="post" data-confirmer="Supprimer ce message ?">
                @csrf
                @method('delete')
                <button type="submit" class="a-btn a-btn--danger a-btn--petit"><i class="fas fa-trash-alt"></i> Supprimer le message</button>
            </form>
        </div>
    </div>

@endsection
