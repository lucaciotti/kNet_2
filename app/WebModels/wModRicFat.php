<?php

namespace knet\WebModels;

use Illuminate\Database\Eloquent\Model;

class wModRicFat extends Model
{
    protected $table = 'w_mod_carp_01';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'rubri_id',
        'prod_mobili',
        'prod_porte',
        'prod_portefinestre',
        'prod_cucine',
        'prod_other',
        'prod_isMulti',
        'prod_note',
        'know_kk',
        'isKkBuyer',
        'yes_supplierType',
        'yes_supplierName',
        'yes_isInformato',
        'not_why_prezzo',
        'not_why_qualita',
        'not_why_servizio',
        'not_why_catalogo',
        'not_why_noinfo',
        'not_supplierType',
        'not_supplierName',
        'wants_tryKK',
        'notryKK_note',
        'wants_info',
        'final_note',
        'vote',
        'user_id'
    ];
}
