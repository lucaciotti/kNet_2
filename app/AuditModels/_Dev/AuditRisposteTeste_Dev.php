<?php

namespace knet\AuditModels\_Dev;

use Illuminate\Database\Eloquent\Model;

class AuditRisposteTeste_Dev extends Model
{
    protected $table = 'AuditRisposteTeste';
    public $timestamps = true;
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $connection = 'kNet_Audit_Dev';
    protected $fillable = ['id', 'codice_modello', 'azienda', 'data', 'auditor', 'persone_intervistate', 'tablet_id', 'conclusioni'];
    // protected $dates = ['data'];

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection('kNet_Audit_Dev');
    }

    protected static function boot()
    {
        parent::boot();
    }
}
