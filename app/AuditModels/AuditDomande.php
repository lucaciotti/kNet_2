<?php

namespace knet\AuditModels;

use Illuminate\Database\Eloquent\Model;

class AuditDomande extends Model
{
    protected $table = 'auditDomande';
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $connection = 'kNet_Audit';
    protected $fillable = ['codice_modello', 'super_capitolo', 'capitolo', 'sub_capitolo', 'domanda', 'descrizione'];

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
