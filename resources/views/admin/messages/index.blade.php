@extends('admin.layout')

@section('title', 'Messages de contact')
@section('sous_titre', 'Messages envoyés depuis la page Contact')

@section('content')

    <div class="a-grille a-grille--2 a-section">
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Messages reçus</h2><p>Sur les 6 derniers mois</p></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'bar',
                    'labels' => $parMois['labels'],
                    'series' => [['label' => 'Messages', 'data' => $parMois['totaux'], 'couleur' => 'terre']],
                    'hauteur' => 220,
                ])
            </div>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Objets des messages</h2></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'doughnut',
                    'labels' => $parObjet->keys(),
                    'series' => [['label' => 'Messages', 'data' => $parObjet->values()]],
                    'hauteur' => 220,
                ])
            </div>
        </div>
    </div>

    <div class="a-carte">
        <div class="a-filtres">
            <div class="a-onglets">
                <a href="{{ route('admin.messages.index', request()->except('etat', 'page')) }}" class="{{ request('etat') ? '' : 'actif' }}">Tous <span>{{ $total }}</span></a>
                <a href="{{ route('admin.messages.index', ['etat' => 'non-lus'] + request()->except('etat', 'page')) }}" class="{{ request('etat') === 'non-lus' ? 'actif' : '' }}">Non lus <span>{{ $nonLus }}</span></a>
            </div>
        </div>
        <form class="a-filtres" method="get">
            @if (request('etat'))<input type="hidden" name="etat" value="{{ request('etat') }}">@endif
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Nom, e-mail ou contenu…">
            <select name="objet" onchange="this.form.submit()">
                <option value="">Tous les objets</option>
                @foreach (config('rejeppat.objets_contact') as $objet)
                    <option value="{{ $objet }}" @selected(request('objet') === $objet)>{{ $objet }}</option>
                @endforeach
            </select>
            <button type="submit" class="a-btn a-btn--vert"><i class="fas fa-search"></i> Rechercher</button>
        </form>

        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Expéditeur</th><th>Objet</th><th>Message</th><th>Reçu</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($messages as $contact)
                        <tr class="{{ $contact->lu_le ? '' : 'non-lu' }}">
                            <td><a href="{{ route('admin.messages.show', $contact) }}">{{ $contact->nom }}</a><br><small>{{ $contact->email }}</small></td>
                            <td>{{ $contact->objet }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($contact->message, 70) }}</td>
                            <td><small>{{ $contact->created_at->format('d/m/Y H:i') }}</small></td>
                            <td>
                                @include('admin.partials.actions', [
                                    'modifier' => route('admin.messages.show', $contact),
                                    'supprimer' => route('admin.messages.destroy', $contact),
                                    'confirmation' => 'Supprimer ce message ?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="a-vide">Aucun message.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $messages->links() }}</div>
    </div>

@endsection
