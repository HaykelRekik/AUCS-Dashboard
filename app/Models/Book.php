<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    const COLUMN_ID = 'id';
    const COLUMN_TITLE = 'title';
    const COLUMN_YEAR = 'year';
    const COLUMN_AUTHOR = 'author';
    const COLUMN_STOCK = 'stock';

    protected $guarded = [self::COLUMN_ID];
}
