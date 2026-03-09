<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Student extends Model
{
    public function up() 
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('course');
            $table->integer('age');
            $table->timestamps();
        });
    }

    protected $fillable = [
        'name',
        'email',
        'course',
        'age'
    ];
}
