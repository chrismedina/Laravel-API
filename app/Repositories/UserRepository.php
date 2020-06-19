<?php

namespace App\Repositories;

use App\Image;
use App\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return Image::all();
    }

    public function getByImage(Image $image)
    {
        return Image::where('image_id'. $image->id)->get();
    }
}