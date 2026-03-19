<?php

namespace App\Http\Controllers;

use App\Models\Tranzakcio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatController extends Controller
{
    public function index(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        }

        $userId = session('user')->id;

        // Összes költés
        $total = Tranzakcio::where('felhasznaloid', $userId)->sum('osszeg');

        // Havi bontás az aktuális évre (a dátum mező neve: 'rogzites')
        $year = Carbon::now()->year;

        // SQLite nem támogatja a MONTH() függvényt, ezért itt az adatbázis driver alapján választunk.
        $driver = DB::getDriverName();
        $monthExpr = $driver === 'sqlite' ? "strftime('%m', rogzites)" : 'MONTH(rogzites)';

        $monthly = Tranzakcio::selectRaw("{$monthExpr} as month, SUM(osszeg) as total")
            ->where('felhasznaloid', $userId)
            ->whereYear('rogzites', $year)
            ->groupBy(DB::raw($monthExpr))
            ->orderBy('month')
            ->get();

        // Kategória szerinti összegek (kategória névvel)
        $byCategory = Tranzakcio::select('kategoria.nev as name', DB::raw('SUM(tranzakcio.osszeg) as total'))
            ->join('kategoria', 'tranzakcio.kategoriaid', '=', 'kategoria.id')
            ->where('tranzakcio.felhasznaloid', $userId)
            ->groupBy('kategoria.nev')
            ->orderByDesc('total')
            ->get();

        // Százalékok számítása
        $byCategory = $byCategory->map(function ($item) use ($total) {
            $item->percent = $total ? round($item->total / $total * 100, 2) : 0;
            return $item;
        });

        // Összeg pénznemenként (ha szükséges)
        $byCurrency = Tranzakcio::select('penznem.nev as currency', DB::raw('SUM(tranzakcio.osszeg) as total'))
            ->join('penznem', 'tranzakcio.penznemid', '=', 'penznem.id')
            ->where('tranzakcio.felhasznaloid', $userId)
            ->groupBy('penznem.nev')
            ->get();

        return view('statisztika', compact('total', 'monthly', 'byCategory', 'byCurrency', 'year'));
    }
}
