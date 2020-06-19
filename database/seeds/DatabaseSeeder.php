<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Image;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(Faker $faker)
    {

        // Create Roles / Permissions
        try {
            $role = Role::findByName('admin');
        } catch (\Exception $e ) {
            $role = Role::create(['name' => 'admin']);
        }

        try {
            $permission = Permission::findByName('god mode');
        } catch (\Exception $e) {
            $permission = Permission::create(['name' => 'god mode']);
            $permission->assignRole($role);
        }

        // Create Users
        $admin = new User();
        $admin->name = 'josh';
        $admin->email_verified_at = $faker->datetime;
        $admin->password = Hash::make('password');
        $admin->email = 'josh@gmail.com';
        $admin->save();

        $role->users()->sync(App\User::where('id', $admin->id)->pluck('id'));

        $user = new User();
        $user->name = 'john doe';
        $user->email_verified_at = $faker->datetime;
        $user->password = Hash::make('password');
        $user->email = 'basic@gmail.com';
        $user->save();

        //$faker->image('storage/app/images',640,480, null, false )]));

        $user->images()->save(new Image (['image' => $faker->word() . $faker->randomNumber() . '.jpg' ]));


        //admin users
        factory(App\User::class, 3)->create()->each(function ($user) {
            $user->assignRole('admin');
            $user->givePermissionTo('god mode');
        });

        //Seed Many Regular Users/Images
        factory(App\User::class, 3)->create()->each(function ($user) {
            $user->images()->save(factory(App\Image::class)->make());
        });

    }
}
