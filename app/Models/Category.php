<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title', 'slug', 'summary', 'photo', 'status', 'is_parent', 'parent_id', 'added_by'];

    // Quan hệ với danh mục cha (1 - 1)
    public function parent_info()
    {
        return $this->hasOne('App\Models\Category', 'id', 'parent_id');
    }

    // Quan hệ với các danh mục con (1 - N)
    public function child_cat()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->where('status', 'active');
    }


    // Quan hệ với các sản phẩm (1 - N)
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'cat_id', 'id')->where('status', 'active');
    }

    // Quan hệ với các sản phẩm phụ (1 - N)
    public function sub_products()
    {
        return $this->hasMany('App\Models\Product', 'child_cat_id', 'id')->where('status', 'active');
    }

    // Lấy tất cả danh mục với sản phẩm và danh mục con
    public static function getAllCategory()
    {
        return Category::orderBy('id', 'DESC')->with('parent_info', 'child_cat', 'products')->paginate(10);
    }

    // Thay đổi các danh mục con thành danh mục cha
    public static function shiftChild($cat_id)
    {
        return Category::whereIn('id', $cat_id)->update(['is_parent' => 1]);
    }

    // Lấy danh mục con theo parent_id
    public static function getChildByParentID($id)
    {
        return Category::where('parent_id', $id)->orderBy('id', 'ASC')->pluck('title', 'id');
    }

    // Lấy tất cả danh mục cha với danh mục con
    public static function getAllParentWithChild()
    {
        return Category::with('child_cat')->where('is_parent', 1)->where('status', 'active')->orderBy('title', 'ASC')->get();
    }

    // Lấy sản phẩm theo danh mục
    public static function getProductByCat($slug)
    {
        return Category::with('products')->where('slug', $slug)->first();
    }

    // Lấy sản phẩm theo danh mục con
    public static function getProductBySubCat($slug)
    {
        return Category::with('sub_products')->where('slug', $slug)->first();
    }

    // Đếm số lượng danh mục đang hoạt động
    public static function countActiveCategory()
    {
        $data = Category::where('status', 'active')->count();
        return $data ? $data : 0;
    }
}
