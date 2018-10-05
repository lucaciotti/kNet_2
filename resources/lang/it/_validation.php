<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute deve essere accettato.',
    'active_url'           => 'The :attribute non è un URL valido.',
    'after'                => 'The :attribute deve essere una data dopo :date.',
    'alpha'                => 'The :attribute può contenere solo lettere.',
    'alpha_dash'           => 'The :attribute può contenere solo lettere, numeri, e trattini.',
    'alpha_num'            => 'The :attribute può contenere solo lettere e numeri.',
    'before'               => 'The :attribute deve essere una data prima di :date.',
    'between'              => [
        'numeric' => 'The :attribute deve essere tra :min e :max.',
        'file'    => 'The :attribute deve essere tra :min e :max kilobytes.',
        'string'  => 'The :attribute deve essere tra :min e :max caratteri.',
        'array'   => 'The :attribute deve essere tra :min e :max articoli.',
    ],
    'boolean'              => 'The :attribute campo deve essere vero o falso.',
    'confirmed'            => 'The :attribute conferma non coincide.',
    'date'                 => 'The :attribute non è una data valida.',
    'date_format'          => 'The :attribute non coincide con il formato :format.',
    'different'            => 'The :attribute e :other deve essere differente.',
    'digits'               => 'The :attribute deve essere :digits cifre.',
    'digits_between'       => 'The :attribute deve essere tra :min e :max cifre.',
    'dimensions'           => 'The :attribute ha dimensioni dell\'immagine non valide.',
    'distinct'             => 'The :attribute campo ha un valore duplicato.',
    'email'                => 'The :attribute deve essere un indirizzo email valido.',
    'exists'               => 'The :attribute selezionato non è valido.',
    'filled'               => 'The :attribute campo è richiesto.',
    'image'                => 'The :attribute deve essere una immagine.',
    'in'                   => 'The :attribute selezionato non è valido.',
    'in_array'             => 'The :attribute campo non esiste in :other.',
    'integer'              => 'The :attribute deve essere un numero intero.',
    'ip'                   => 'The :attribute deve essere un indirizzo IP valido.',
    'json'                 => 'The :attribute deve essere una stringa JSON valida.',
    'max'                  => [
        'numeric' => 'The :attribute non può essere superiore a :max.',
        'file'    => 'The :attribute non può essere superiore a :max kilobytes.',
        'string'  => 'The :attribute non può essere superiore a :max caratteri.',
        'array'   => 'The :attribute non può avere più di :max articoli.',
    ],
    'mimes'                => 'The :attribute deve essere un file di tipo: :values.',
    'min'                  => [
        'numeric' => 'The :attribute deve essere almeno :min.',
        'file'    => 'The :attribute deve essere almeno :min kilobytes.',
        'string'  => 'The :attribute deve essere almeno :min caratteri.',
        'array'   => 'The :attribute deve avere almeno :min articoli.',
    ],
    'not_in'               => 'The :attribute selezionato non è valido.',
    'numeric'              => 'The :attribute deve essere un numero.',
    'present'              => 'The :attribute campo deve essere presente.',
    'regex'                => 'The :attribute formato non è valido.',
    'required'             => 'The :attribute campo è richiesto.',
    'required_if'          => 'The :attribute campo è richiesto quando :other è :value.',
    'required_unless'      => 'The :attribute campo è richiesto a meno che :other non sia in :values.',
    'required_with'        => 'The :attribute field è richiesto quando :values é presente.',
    'required_with_all'    => 'The :attribute field è richiesto quando :values é presente.',
    'required_without'     => 'The :attribute field è richiesto quando :values non è presente.',
    'required_without_all' => 'The :attribute field è richiesto quando nessuno dei :values è presente.',
    'same'                 => 'The :attribute e :other deve coincidere.',
    'size'                 => [
        'numeric' => 'The :attribute deve essere :size.',
        'file'    => 'The :attribute deve essere :size kilobytes.',
        'string'  => 'The :attribute deve essere :size caratteri.',
        'array'   => 'The :attribute deve contenere :size articoli.',
    ],
    'string'               => 'The :attribute deve essere una stringa.',
    'timezone'             => 'The :attribute deve essere una zona valida.',
    'unique'               => 'The :attribute è già stato preso.',
    'url'                  => 'The :attribute formato non è valido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
