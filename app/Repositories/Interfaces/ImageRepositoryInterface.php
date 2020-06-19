<?php
namespace App\Repositories\Interfaces;
use App\User;

interface ImageRepositoryInterface
{
    public function all();

    public function getByUser(User $user);
}