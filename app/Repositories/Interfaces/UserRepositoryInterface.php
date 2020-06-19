<?php
namespace App\Repositories\Interfaces;
use App\Image;

interface UserRepositoryInterface
{
    public function all();

    public function getByImage(Image $image);
}