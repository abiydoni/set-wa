<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data['title'] = 'Manage Users | WA Gateway';
        $data['users'] = $this->userModel->findAll();
        
        return view('users/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Add User | WA Gateway';
        return view('users/create', $data);
    }

    public function save()
    {
        $post = $this->request->getPost();
        
        if(empty($post['username']) || empty($post['password'])) {
            return redirect()->back()->with('error', 'Username and password are required.');
        }

        $existing = $this->userModel->where('username', $post['username'])->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Username already exists.');
        }

        $data = [
            'username' => $post['username'],
            'password' => password_hash($post['password'], PASSWORD_DEFAULT),
        ];

        $this->userModel->insert($data);

        return redirect()->to(base_url('users'))->with('success', 'User added successfully!');
    }

    public function edit(int|string $id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $data = [
            'title' => 'Edit User | WA Gateway',
            'user' => $user
        ];

        return view('users/edit', $data);
    }

    public function update(int|string $id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $post = $this->request->getPost();
        
        if(empty($post['username'])) {
            return redirect()->back()->with('error', 'Username is required.');
        }

        $existing = $this->userModel->where('username', $post['username'])->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Username already exists.');
        }

        $data = [
            'username' => $post['username'],
        ];

        if (!empty($post['password'])) {
            $data['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);

        return redirect()->to(base_url('users'))->with('success', 'User updated successfully!');
    }

    public function delete(int|string $id)
    {
        $user = $this->userModel->find($id);
        if ($user) {
            if (session()->get('id') == $id) {
                return redirect()->to(base_url('users'))->with('error', 'You cannot delete your currently active account.');
            }

            $this->userModel->delete($id);
            return redirect()->to(base_url('users'))->with('success', 'User deleted successfully!');
        }
        return redirect()->to(base_url('users'))->with('error', 'User not found!');
    }
}
