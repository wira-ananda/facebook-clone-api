<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Story extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'media_url',
    'media_type',
    'caption',
  ];

  /**
   * Relasi: Story milik satu user
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
