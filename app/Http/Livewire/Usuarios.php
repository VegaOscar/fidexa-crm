<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Usuarios extends Component
{
    public $usuarios;

    public $name, $email, $password, $user_id;
    public $showModal = false;
    public $isEditMode = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user_id)],
            'password' => $this->isEditMode ? 'nullable|min:6' : 'required|min:6',
        ];
    }

    public function mount()
    {
        $this->usuarios = User::all();
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->isEditMode = false;
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->user_id = null;
    }

    public function store()
    {
        $this->validate();

        if ($this->user_id) {
            $user = User::find($this->user_id);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password ? Hash::make($this->password) : $user->password,
            ]);

            $this->dispatch('toast', 'Usuario actualizado correctamente.');

        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $this->dispatch('toast', 'Usuario creado correctamente.');

        }

        $this->usuarios = User::all();
        $this->closeModal();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function confirmDelete($id)
    {
    $this->dispatch('confirmDelete', $id);

    }

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        $this->usuarios = User::all();
        session()->flash('message', 'Usuario eliminado.');

        // 🔔 Dispara evento para mostrar SweetAlert
        $this->dispatch('usuarioEliminado');
    }



    public function render()
    {
        return view('livewire.usuarios')->layout('layouts.app');
    }
}
