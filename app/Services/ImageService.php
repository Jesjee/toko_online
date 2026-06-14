<?php 
namespace App\Services; 
  
use Illuminate\Http\UploadedFile; 
use Illuminate\Support\Facades\Storage; 
  
class ImageService 
{ 
    /** 
     * Upload file gambar ke storage/app/public/{folder}/ 
     * Kembalikan path relatif untuk disimpan di database. 
     */ 
    public function upload( 
        UploadedFile $file, 
        string $folder = 'products' 
    ): string { 
        // Nama file unik: uniqid() + ekstensi asli 
        $filename = uniqid() . '.' . $file->getClientOriginalExtension(); 
  
        // Simpan di disk 'public' → storage/app/public/{folder}/{filename} 
        $file->storeAs($folder, $filename, 'public'); 
  
        // Return path relatif yang disimpan di kolom image 
        return $folder . '/' . $filename; 
    } 
  
    /** 
     * Hapus file lama dari storage. 
     * Dipanggil sebelum upload foto baru saat produk di-edit. 
     */ 
    public function delete(?string $path): void 
    { 
        if ($path && Storage::disk('public')->exists($path)) { 
            Storage::disk('public')->delete($path); 
        } 
    } 
} 
