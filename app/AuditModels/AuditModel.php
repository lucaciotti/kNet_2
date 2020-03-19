<?php

namespace knet\AuditModels;

use Illuminate\Database\Eloquent\Model;

class AuditModel extends Model
{
    protected $table = 'auditModels';
    public $timestamps = false;
    protected $primaryKey = 'codice';
    public $incrementing = false;
    protected $connection = 'kNet_Audit';
    protected $fillable = ['codice', 'descrizione', 'versione'];

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
