<?php
// app/Models/Post.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  protected $fillable = ['user_id', 'content', 'image_url'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
