<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'label'];

<<<<<<< HEAD
=======
    
>>>>>>> 3cc225b18dddc1bbb66e97cc61c251fa3aafc24b
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
