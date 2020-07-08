<?php

namespace knet\AuditModels\_Dev;

use Illuminate\Database\Eloquent\Model;

class AuditModel_Dev extends Model
{
    protected $table = 'AuditModels';
    public $timestamps = true;
    protected $primaryKey = 'codice';
    public $incrementing = false;
    protected $connection = 'kNet_Audit_Dev';
    protected $fillable = ['codice', 'descrizione','versione', 'tipologia'];

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
