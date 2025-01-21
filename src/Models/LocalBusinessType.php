<?php

namespace OrganisationSchema\Models;

class LocalBusinessType extends OrganisationSchemaType
{
    private static array $db = [
        'Image' => 'Varchar(255)',
        'Address'  => 'Varchar(255)',
        'Telephone' => 'Varchar(255)',
        'Geo' => 'Varchar(255)',
        'OpeningHours' => 'Varchar(255)',
    ];
}