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

    'accepted'             => ':attribute soll akzeptiert werden.',
    'active_url'           => ':attribute ist keine gültige URL.',
    'after'                => ':attribute soll ein Datum nach :date sein.',
    'alpha'                => ':attribute darf nur Buchstaben enthalten.',
    'alpha_dash'           => ':attribute darf nur Buchstaben, Zahlen und Striche enthalten.',
    'alpha_num'            => ':attribute darf nur Buchstaben und Zahlen enthalten.',
    'before'               => ':attribute soll ein Datum bevor :date sein.',
    'between'              => [
        'numeric' => ':attribute soll zwischen :min und :max sein.',
        'file'    => ':attribute soll zwischen :min und :max Kilobytes sein.',
        'string'  => ':attribute soll zwischen :min und :max Schriftzeichen sein.',
        'array'   => ':attribute soll zwischen :min und :max Artikeln sein.',
    ],
    'boolean'              => ':attribute Datenfeld soll true oder false sein.',
    'confirmed'            => ':attribute Bestätigung fällt nicht zusammen.',
    'date'                 => ':attribute ist keine gültig Datum.',
    'date_format'          => ':attribute fällt nicht mit dem Format :format zusammen.',
    'different'            => ':attribute und :other soll anders sein.',
    'digits'               => ':attribute soll :digits Zahlen sein.',
    'digits_between'       => ':attribute soll zwischen :min und :max Zahlen sein.',
    'dimensions'           => ':attribute hat Bildgröße nicht gültig.',
    'distinct'             => ':attribute Datenfeld hat einen doppelten Wert.',
    'email'                => ':attribute soll eine gültige E-Mail-Adresse.',
    'exists'               => ':attribute ausgewählt ist nicht gültig.',
    'filled'               => ':attribute Datenfeld ist erforderlich.',
    'image'                => ':attribute soll ein Bild sein.',
    'in'                   => ':attribute ausgewählt ist nicht gültig.',
    'in_array'             => ':attribute Datenfeld besteht nicht in :other.',
    'integer'              => ':attribute soll eine ganze Zahl sein.',
    'ip'                   => ':attribute soll eine gültige IP adresse sein.',
    'json'                 => ':attribute soll eine gültige JSON Kette sein.',
    'max'                  => [
        'numeric' => ':attribute darf nicht größer als :max sein.',
        'file'    => ':attribute darf nicht größer als :max Kilobyte sein.',
        'string'  => ':attribute darf nicht größer als :max Schriftzeichen sein.',
        'array'   => ':attribute darf nicht mehr als :max Artikeln haben.',
    ],
    'mimes'                => ':attribute soll ein Dateityp: :values.',
    'min'                  => [
        'numeric' => ':attribute soll mindestens :min sein.',
        'file'    => ':attribute soll mindestens :min Kilobytes sein.',
        'string'  => ':attribute soll mindestens :min Schriftzeichen sein.',
        'array'   => ':attribute soll mindestens :min Artikeln haben.',
    ],
    'not_in'               => ':attribute ausgewählt ist nicht gültig.',
    'numeric'              => ':attribute soll eine Yahl sein.',
    'present'              => ':attribute Datenfeld soll anwesend sein.',
    'regex'                => ':attribute Format ist nicht gültig.',
    'required'             => ':attribute Datenfeld ist erforderlich.',
    'required_if'          => ':attribute Datenfeld ist erforderlich wenn :other ist :value.',
    'required_unless'      => ':attribute Datenfeld ist erforderlich es sei denn, dass :other sei nicht in :values.',
    'required_with'        => ':attribute Datenfeld ist erforderlich wenn :values ist anwesend.',
    'required_with_all'    => ':attribute Datenfeld ist erforderlich wenn :values ist anwesend.',
    'required_without'     => ':attribute Datenfeld ist erforderlich wenn :values ist nicht anwesend.',
    'required_without_all' => ':attribute Datenfeld ist erforderlich wenn kein :values ist anwesend.',
    'same'                 => ':attribute und :other soll zusammenfallen.',
    'size'                 => [
        'numeric' => ':attribute soll :size sein.',
        'file'    => ':attribute soll :size Kilobyte sein.',
        'string'  => ':attribute soll :size Schriftzeichen sein.',
        'array'   => ':attribute soll :size Artikeln enthalten.',
    ],
    'string'               => ':attribute soll eine Kette sein.',
    'timezone'             => ':attribute soll eine gültige Zone.',
    'unique'               => ':attribute war schon genommen.',
    'url'                  => ':attribute Format ist nicht gültig.',

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
