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

    'accepted'             => 'The :attribute doit être accepté.',
    'active_url'           => 'The :attribute n\'est pas une URL valide.',
    'after'                => 'The :attribute doit être une date après :date.',
    'alpha'                => 'The :attribute peut contenir seulement des lettres.',
    'alpha_dash'           => 'The :attribute peut contenir seulement des lettres, des numéros et des tirets.',
    'alpha_num'            => 'The :attribute peut contenir seulement des lettres et des numéros.',
    'before'               => 'The :attribute doit être une date avant :date.',
    'between'              => [
        'numeric' => 'The :attribute doit  entre :min et :max.',
        'file'    => 'The :attribute doit être entre :min et :max kilo-octets.',
        'string'  => 'The :attribute doit être entre :min et :max charactères.',
        'array'   => 'The :attribute doit avoir entre :min et :max articles.',
    ],
    'boolean'              => 'The :attribute zone de texte doit être vrai ou faux.',
    'confirmed'            => 'The :attribute confirmation ne correspond pas.',
    'date'                 => 'The :attribute n\'est pas une date valide.',
    'date_format'          => 'The :attribute ne correspond pas au format :format.',
    'different'            => 'The :attribute et :other doit être différent.',
    'digits'               => 'The :attribute doit être :digits chiffres.',
    'digits_between'       => 'The :attribute doit être entre :min et :max chiffres.',
    'dimensions'           => 'The :attribute a des dimensions d\'images erronées.',
    'distinct'             => 'The :attribute zone de texte a une valeur double.',
    'email'                => 'The :attribute doit être une adresse email valide.',
    'exists'               => 'The :attribute sélectionné est erroné.',
    'filled'               => 'The :attribute zone de texte est requis.',
    'image'                => 'The :attribute doit être une image.',
    'in'                   => 'The :attribute sélectionné est erroné.',
    'in_array'             => 'The :attribute zone de texte n\'existe pas en :other.',
    'integer'              => 'The :attribute doit être un nombre entier.',
    'ip'                   => 'The :attribute doit être une adresse IP valide.',
    'json'                 => 'The :attribute doit être une chaîne JSON valide.',
    'max'                  => [
        'numeric' => 'The :attribute ne peut pas être supérieur à :max.',
        'file'    => 'The :attribute ne peut pas être supérieur à :max kilo-octets.',
        'string'  => 'The :attribute ne peut pas être supérieur à :max charactères.',
        'array'   => 'The :attribute ne peut pas avoir plus de :max articles.'
    ],
    'mimes'                => 'The :attribute doit être un fichier de type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute doit être au moins :min.',
        'file'    => 'The :attribute doit être au moins :min kilo-octets.',
        'string'  => 'The :attribute doit être au moins :min charactères.',
        'array'   => 'The :attribute doit avoir au moins :min articles.',
    ],
    'not_in'               => 'The :attribute sélectionné est erroné.',
    'numeric'              => 'The :attribute doit être un numéro.',
    'present'              => 'The :attribute zone de texte doit être présent.',
    'regex'                => 'The :attribute format est erroné.',
    'required'             => 'The :attribute zone de texte est requis.',
    'required_if'          => 'The :attribute zone de texte est requis quand :other est :value.',
    'required_unless'      => 'The :attribute zone de texte est requis au moins que :other ne soit en :values.',
    'required_with'        => 'The :attribute zone de texte est requis quand :values est présent.',
    'required_with_all'    => 'The :attribute zone de texte est requis quand :values est présent.',
    'required_without'     => 'The :attribute zone de texte est requis quand :values n\'est pas présent.',
    'required_without_all' => 'The :attribute zone de texte est requis quand aucun des :values est présent.',
    'same'                 => 'The :attribute et :other doivent correspondre.',
    'size'                 => [
        'numeric' => 'The :attribute doit être :size.',
        'file'    => 'The :attribute doit être :size kilo-octets.',
        'string'  => 'The :attribute doit être :size charactères.',
        'array'   => 'The :attribute doit contenir :size articles.',
    ],
    'string'               => 'The :attribute doit être une chaîne.',
    'timezone'             => 'The :attribute doit être une zone valide.',
    'unique'               => 'The :attribute a déjà été pris.',
    'url'                  => 'The :attribute format est erroné.',

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
