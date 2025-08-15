<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientRequestAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_request_id',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
    ];

    /**
     * Get the client request that owns the attachment.
     */
    public function clientRequest()
    {
        return $this->belongsTo(ClientRequest::class);
    }

    /**
     * Get the file size in a human readable format.
     */
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get the full URL to the file.
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}