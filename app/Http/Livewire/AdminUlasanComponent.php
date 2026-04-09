<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ulasan;
use Illuminate\Support\Facades\Auth;

class AdminUlasanComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $ratingFilter = '';
    public $sortBy = 'latest';

    protected $queryString = [
        'search' => ['except' => ''],
        'ratingFilter' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRatingFilter()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function deleteUlasan($id)
    {
        if (Auth::user()->role !== 'admin') {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus ulasan.');
            return;
        }

        $ulasan = Ulasan::findOrFail($id);
        $ulasan->delete();

        session()->flash('success', 'Ulasan berhasil dihapus.');
    }

    public function render()
    {
        $query = Ulasan::with(['user', 'buku.kategori']);

        // Filter pencarian judul buku
        if ($this->search) {
            $query->whereHas('buku', function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%');
            });
        }

        // Filter rating
        if ($this->ratingFilter) {
            $query->where('rating', $this->ratingFilter);
        }

        //urutan
        switch ($this->sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'rating_high':
                $query->orderBy('rating', 'desc');
                break;
            case 'rating_low':
                $query->orderBy('rating', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $ulasans = $query->paginate(10);

        return view('livewire.admin-ulasan-component', [
            'ulasans' => $ulasans,
        ]);
    }
}