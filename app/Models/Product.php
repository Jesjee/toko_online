<?php 
namespace App\Models; 
  
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Support\Str; 
  
class Product extends Model 
{ 
    use SoftDeletes; 
  
    protected $fillable = [ 
        'user_id','name','slug','description', 
        'price','stock','image','status','views', 
    ]; 
  
    protected $casts = ['price' => 'decimal:2']; 
  
    protected static function booted(): void 
    { 
        static::creating(fn($p) => $p->slug = Str::slug($p->name)); 
        static::updating(fn($p) => $p->slug = Str::slug($p->name)); 
    } 
  
    public function seller() 
    { 
        return $this->belongsTo(User::class, 'user_id'); 
    } 
  
    public function categories() 
    { 
        return $this->belongsToMany(Category::class); 
    } 

    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
  
    public function scopeActive($query) 
    { 
        return $query->where('status', 'active'); 
    } 
}