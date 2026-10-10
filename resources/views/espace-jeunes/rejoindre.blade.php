@extends('layouts.app')

@section('title', __('Rejoindre l’annuaire'))

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Rejoindre l’annuaire'),
            'texte' => __('Faites connaître votre exploitation ou votre coopérative.'),
            'image' => 'demande.jpg',
            'liens' => [__('Espace Jeunes') => route('espace-jeunes.index'), __('Annuaire') => route('espace-jeunes.annuaire')],
        ])

        <section class="rj-section rj-section--clair">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-9 col-lg-10">
                        <div class="rj-formulaire">
                            <h2 class="rj-titre-bloc">{{ __('Proposer mon profil') }}</h2>
                            <p>{{ __('Votre profil est publié dans l’annuaire après vérification par l’équipe du REJEPPAT. Les coordonnées que vous indiquez seront visibles par les visiteurs du site.') }}</p>

                            @if (session('succes'))
                                <div class="alert alert-success">{{ session('succes') }}</div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">{{ __('Veuillez corriger les champs signalés.') }}</div>
                            @endif

                            <form action="{{ route('espace-jeunes.inscrire') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 input-box">
                                        <label class="rj-label" for="m-nom">{{ __('Nom complet') }} *</label>
                                        <input type="text" id="m-nom" name="nom" value="{{ old('nom') }}" required>
                                        @error('nom') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="col-md-6 input-box">
                                        <label class="rj-label" for="m-organisation">{{ __('Coopérative / organisation') }}</label>
                                        <input type="text" id="m-organisation" name="organisation" value="{{ old('organisation') }}">
                                    </div>
                                    <div class="col-md-6 input-box">
                                        <label class="rj-label" for="m-filiere">{{ __('Filière') }} *</label>
                                        <select id="m-filiere" name="filiere" class="ignore" required>
                                            @foreach (\App\Models\Membre::FILIERES as $cle => $libelle)
                                                <option value="{{ $cle }}" @selected(old('filiere') === $cle)>{{ __($libelle) }}</option>
                                            @endforeach
                                        </select>
                                        @error('filiere') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="col-md-6 input-box">
                                        <label class="rj-label" for="m-region">{{ __('Région') }}</label>
                                        <select id="m-region" name="region_id" class="ignore">
                                            <option value="">{{ __('– Choisir –') }}</option>
                                            @foreach ($regions as $region)
                                                <option value="{{ $region->id }}" @selected((string) old('region_id') === (string) $region->id)>{{ __($region->nom) }}</option>
                                            @endforeach
                                        </select>
                                        @error('region_id') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="col-md-6 input-box">
                                        <label class="rj-label" for="m-localite">{{ __('Localité') }}</label>
                                        <input type="text" id="m-localite" name="localite" value="{{ old('localite') }}">
                                    </div>
                                    <div class="col-md-6 input-box">
                                        <label class="rj-label" for="m-photo">{{ __('Photo (facultatif)') }}</label>
                                        <input type="file" id="m-photo" name="photo" accept="image/jpeg,image/png,image/webp">
                                        @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="col-12 input-box">
                                        <label class="rj-label" for="m-presentation">{{ __('Présentation') }} *</label>
                                        <textarea id="m-presentation" name="presentation" placeholder="{{ __('Votre activité, vos productions, votre parcours, vos projets…') }}" required>{{ old('presentation') }}</textarea>
                                        @error('presentation') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="col-md-6 input-box">
                                        <label class="rj-label" for="m-telephone">{{ __('Téléphone / WhatsApp') }}</label>
                                        <input type="tel" id="m-telephone" name="telephone" value="{{ old('telephone') }}">
                                    </div>
                                    <div class="col-md-6 input-box">
                                        <label class="rj-label" for="m-email">{{ __('Adresse e-mail') }}</label>
                                        <input type="email" id="m-email" name="email" value="{{ old('email') }}">
                                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>
                                <label class="rj-case">
                                    <input type="checkbox" name="accord" value="1" @checked(old('accord')) required>
                                    <span>{{ __('J’accepte que ces informations soient publiées dans l’annuaire du site du REJEPPAT.') }}</span>
                                </label>
                                @error('accord') <small class="text-danger" style="display: block; margin: -14px 0 18px">{{ $message }}</small> @enderror
                                <button type="submit" class="btn-one"><i class="icon-arrow"></i><span class="txt">{{ __('Envoyer mon profil') }}</span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

@endsection
