<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug','parent_id'];


    public function parent()
    {
        return $this->belongsTo(static::class,'parent_id')->with('parent');
    }

    public function child()
    {
        return $this->hasMany(static::class,'parent_id');
    }

    public function allChild()
    {
        return $this->child()->with('allChild');
    }


    public static function getAllCategories($cat = null, $sapce = null)
    {
        $categories = $cat ? $cat : static::whereNull('parent_id')->with('child')->get();
        $sp = $sapce ? $sapce."→&nbsp;" : "";
        $results = [];
        foreach($categories as $category){
            $results[] = [
                'name' => $sp.$category->name,
                'slug' => $category->slug,
                'parent_id' => $category->parent_id,
                'id' => $category->id
            ];

            if(!blank($category->child)){
                $child = static::getAllCategories($category->child,$sp."&nbsp;&nbsp;");
                $results = array_merge($results,$child);
            }
        }
        return $results;
    }
}
