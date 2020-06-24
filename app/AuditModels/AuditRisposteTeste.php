<?php

namespace knet\AuditModels;

use Illuminate\Database\Eloquent\Model;

class AuditRisposteTeste extends Model
{
    protected $table = 'AuditRisposteTeste';
    public $timestamps = true;
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $connection = 'kNet_Audit';
    protected $fillable = ['id', 'codice_modello', 'azienda', 'data', 'auditor', 'persone_intervistate', 'tablet_id', 'conclusioni'];
    // protected $dates = ['data'];

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection('kNet_Audit');
    }

    protected static function boot()
    {
        parent::boot();
    }
}
