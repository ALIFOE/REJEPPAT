<?php

namespace App\Http\Controllers\Admin;

use App\Models\MessageContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $messages = MessageContact::query()
            ->when($request->query('q'), fn ($q, $recherche) => $q->where(fn ($q) => $q
                ->where('nom', 'like', "%{$recherche}%")
                ->orWhere('email', 'like', "%{$recherche}%")
                ->orWhere('message', 'like', "%{$recherche}%")))
            ->when($request->query('etat') === 'non-lus', fn ($q) => $q->nonLu())
            ->when($request->query('objet'), fn ($q, $objet) => $q->where('objet', $objet))
            ->latest()
            ->paginate(self::PAR_PAGE)->withQueryString();

        $mois = collect(range(5, 0))->map(fn ($i) => now()->startOfMonth()->subMonths($i));
        $recus = MessageContact::where('created_at', '>=', $mois->first())->pluck('created_at');

        return view('admin.messages.index', [
            'messages' => $messages,
            'nonLus' => MessageContact::nonLu()->count(),
            'total' => MessageContact::count(),
            'parObjet' => MessageContact::selectRaw('objet, COUNT(*) as total')->groupBy('objet')->pluck('total', 'objet'),
            'parMois' => [
                'labels' => $mois->map(fn ($m) => ucfirst($m->locale('fr')->translatedFormat('M Y'))),
                'totaux' => $mois->map(fn ($m) => $recus->filter(fn ($d) => $d->isSameMonth($m))->count()),
            ],
        ]);
    }

    public function show(MessageContact $message): View
    {
        if (! $message->lu_le) {
            $message->update(['lu_le' => now()]);
        }

        // « $message » est réservé par Blade (@error) : la vue reçoit « $contact »
        return view('admin.messages.show', ['contact' => $message]);
    }

    /** Marquer comme lu / non lu */
    public function update(MessageContact $message): RedirectResponse
    {
        $message->update(['lu_le' => $message->lu_le ? null : now()]);

        // Retour à la liste : rouvrir le message le marquerait à nouveau comme lu
        return redirect()->route('admin.messages.index')
            ->with('succes', $message->lu_le ? 'Message marqué comme lu.' : 'Message marqué comme non lu.');
    }

    public function destroy(MessageContact $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('succes', 'Message supprimé.');
    }
}
