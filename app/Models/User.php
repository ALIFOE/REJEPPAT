<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'telephone', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const SUPER_ADMIN = 'super_admin';

    public const ADMIN = 'admin';

    public const CLIENT = 'client';

    /** Rôle => [libellé, couleur du badge dans l'administration] */
    public const ROLES = [
        self::SUPER_ADMIN => ['Super admin', 'violet'],
        self::ADMIN => ['Administrateur', 'bleu'],
        self::CLIENT => ['Client', 'vert'],
    ];

    /** Ce que chaque rôle permet, affiché sur les profils. */
    public const DROITS = [
        self::SUPER_ADMIN => [
            'Accès complet à l’administration du site et de la boutique',
            'Création des comptes et attribution des rôles',
            'Suppression des comptes administrateurs et clients',
        ],
        self::ADMIN => [
            'Gestion des commandes et des produits de la boutique',
            'Traitement des demandes de services et des messages',
            'Publication des articles, offres, fermes écoles et projets',
        ],
        self::CLIENT => [
            'Commandes sur la boutique en ligne',
            'Suivi de toutes ses commandes dans « Mon compte »',
            'Gestion de ses informations et de son mot de passe',
        ],
    ];

    protected $attributes = [
        'role' => self::CLIENT,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** Commandes passées en étant connecté à ce compte. */
    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    public function estSuperAdmin(): bool
    {
        return $this->role === self::SUPER_ADMIN;
    }

    /** Super admin ou administrateur : accès à l'administration. */
    public function estAdministrateur(): bool
    {
        return in_array($this->role, [self::SUPER_ADMIN, self::ADMIN], true);
    }

    /** Adresse du tableau de bord du rôle : l'administration ou l'espace client. */
    public function tableauDeBord(): string
    {
        return $this->estAdministrateur() ? route('admin.tableau') : route('compte.index');
    }

    public function roleLibelle(): string
    {
        return self::ROLES[$this->role][0] ?? $this->role;
    }

    /** Les comptes de l'équipe sont créés par un super admin : leur adresse n'a pas à être vérifiée. */
    public function hasVerifiedEmail(): bool
    {
        return $this->estAdministrateur() || parent::hasVerifiedEmail();
    }
}
