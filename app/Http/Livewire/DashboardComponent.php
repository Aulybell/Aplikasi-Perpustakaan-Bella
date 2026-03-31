<?php

namespace App\Http\Livewire;

use App\Models\Buku;
use App\Models\Pinjam;
use Livewire\Component;

class DashboardComponent extends Component
{
 public function index()
    {
        $data['buku'] = Buku::count();
        $data['user'] = user::count();
        $data['pinjam'] = Pinjam::where('satus', 'dipinjam')->sum('total');
        $data['kembali'] = Pinjam::where('status', 'dikembalikan')->sum('total');
        return view('dashboard', $data);
    }
}