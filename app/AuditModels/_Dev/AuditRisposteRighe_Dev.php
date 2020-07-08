<?php

namespace knet\AuditModels\_Dev;

use Illuminate\Database\Eloquent\Model;

class AuditRisposteRighe_Dev extends Model
{
    protected $table = 'AuditRisposteRighe';
    public $timestamps = true;
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $connection = 'kNet_Audit_Dev';
    protected $fillable = ['id_testa', 'id_domanda', 'risposta', 'osservazioni','note', 'tablet_id', 'tablet_idtesta', 'voto'];

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
