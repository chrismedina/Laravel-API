<?php

namespace App\Repositories;

use App\Image;
use App\User;
use App\Repositories\Interfaces\ImageRepositoryInterface;

class ImageRepository implements ImageRepositoryInterface
{
    public function all()
    {
        return Image::all();
    }

    public function getByUser(User $user)
    {
        return Image::where('user_id'. $user->id)->get();
    }
}