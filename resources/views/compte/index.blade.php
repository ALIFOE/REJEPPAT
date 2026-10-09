@extends('layouts.app')

@section('title', 'Mon compte')

@php use App\Support\Contenu; @endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => 'Mon compte',
            'texte' => 'Bonjour ' . auth()->user()->name . ', retrouvez ici vos commandes et vos informations.',
            'image' => 'fermes.jpg',
        ])

        <section class="compte-section">
            <div class="container">
                <div class="compte-espace">
                    @include('compte._menu')

                    <div class="compte-espace__contenu">
                        @if (session('succes'))
                            <div class="alert alert-success" role="status">{{ session('succes') }}</div>
                        @endif

                        <div class="compte-bloc">
                            <h3>Mes commandes</h3>

                            @if ($commandes->isEmpty())
                                <div class="compte-vide">
                                    <span><i class="fas fa-shopping-basket"></i></span>
                                    <p>Vous n’avez pas encore passé de commande avec ce compte.</p>
                                    <a class="btn-one" href="{{ route('boutique.index') }}">
                                        <i class="icon-arrow"></i>
                                        <span class="txt">Découvrir la boutique</span>
                                    </a>
                                </div>
                            @else
                                <div class="compte-tableau">
                                    <table>
                                        <thead>
                                            <tr><th>Numéro</th><th>Date</th><th>Total</th><th>Statut</th><th><span class="sr-only">Détail</span></th></tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($commandes as $commande)
                                                <tr>
                                                    <td><strong>{{ $commande->numero }}</strong></td>
                                                    <td>{{ $commande->created_at->locale('fr')->translatedFormat('j F Y') }}</td>
                                                    <td>{{ Contenu::prix($commande->total) }}</td>
                                                    <td><span class="statut-commande statut-commande--{{ $commande->statut }}">{{ $commande->statutLibelle() }}</span></td>
                                                    <td><a href="{{ route('compte.commande', $commande->numero) }}">Voir <i class="fas fa-chevron-right"></i></a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $commandes->links('pagination::simple-bootstrap-5') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

@endsection
