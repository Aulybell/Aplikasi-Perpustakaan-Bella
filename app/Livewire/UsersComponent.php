<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\User;

class UsersComponent extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public function getUsersProperty()
    {
        return User::whereIn('role', ['peminjam', 'user'])->paginate(10);
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'User berhasil dihapus.');
        $this->reset();
    }

    public function render()
    {
        $users = User::whereIn('role', ['peminjam', 'user'])->paginate(10);
        return view('livewire.users-component', compact('users'));
    }
}
