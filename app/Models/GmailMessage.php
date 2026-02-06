<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GmailMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gmail_messages';
    protected $primaryKey = 'id_gmail_message';

    protected $fillable = [
        'gmail_message_id',
        'from_email',
        'to_email',
        'subject',
        'body',
        'message_type',
        'status',
        'related_type',
        'related_id',
        'label_ids',
        'sent_at',
        'received_at',
        'read_at',
        'raw_headers',
        'metadata',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'received_at' => 'datetime',
        'read_at' => 'datetime',
        'metadata' => 'array',
        'label_ids' => 'array',
    ];

    public function relatedPembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'related_id')
            ->where('related_type', 'pembayaran');
    }

    public function relatedKontrak()
    {
        return $this->belongsTo(KontrakSewa::class, 'related_id')
            ->where('related_type', 'kontrak');
    }

    public function scopeSent($query)
    {
        return $query->where('message_type', 'sent');
    }

    public function scopeReceived($query)
    {
        return $query->where('message_type', 'received');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeForPembayaran($query, $idPembayaran)
    {
        return $query->where('related_type', 'pembayaran')
                    ->where('related_id', $idPembayaran);
    }

    public function scopeForKontrak($query, $idKontrak)
    {
        return $query->where('related_type', 'kontrak')
                    ->where('related_id', $idKontrak);
    }
}
