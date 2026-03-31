<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ulasan;

class UlasanComponent extends Component
{
    use WithPagination;

    public $bukuId;
    public $rating = 0;
    public $ulasan = '';
    public $userReview = null;
    public $showForm = false;

    public function mount($bukuId = null)
    {
        $this->bukuId = $bukuId;
        if ($this->bukuId && auth()->check()) {
            $this->userReview = Ulasan::where('user_id', auth()->id())
                ->where('buku_id', $this->bukuId)
                ->first();
            if ($this->userReview) {
                $this->rating = $this->userReview->rating;
                $this->ulasan = $this->userReview->ulasan;
            }
        }
    }

    public function submitReview()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000',
        ]);

        if ($this->userReview) {
            $this->userReview->update([
                'rating' => $this->rating,
                'ulasan' => $this->ulasan,
            ]);
            session()->flash('message', 'Ulasan berhasil diperbarui!');
        } else {
            Ulasan::create([
                'user_id' => auth()->id(),
                'buku_id' => $this->bukuId,
                'rating' => $this->rating,
                'ulasan' => $this->ulasan,
            ]);
            session()->flash('message', 'Ulasan berhasil ditambahkan!');
        }

        $this->userReview = Ulasan::where('user_id', auth()->id())
            ->where('buku_id', $this->bukuId)
            ->first();
        $this->showForm = false;
    }

    public function destroy($id)
{
    $ulasan = Ulasan::findOrFail($id);

    if (Auth::id() !== $ulasan->user_id && Auth::user()->role !== 'admin') {
        abort(403);
    }

    $bukuId = $ulasan->buku_id;

    $ulasan->delete();

    return redirect()
        ->route('ulasan.show', $bukuId)
        ->with('success', 'Ulasan berhasil dihapus.');
}

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
    }

    public function render()
    {
        $reviews = Ulasan::where('buku_id', $this->bukuId)
            ->with('user')
            ->latest()
            ->paginate(5);

        $averageRating = Ulasan::where('buku_id', $this->bukuId)->avg('rating') ?? 0;
        $totalReviews = Ulasan::where('buku_id', $this->bukuId)->count();

        return view('livewire.ulasan-component', [
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'totalReviews' => $totalReviews,
        ]);
    }
}
