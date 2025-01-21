<?php

namespace OrganisationSchema\Models;

use SilverStripe\ORM\DataObject;

class Address extends DataObject
{
    private static array $db = [
        'StreetAddress' => 'Varchar',
        'AddressLocality' =>'Varchar',
        'AddressRegion' => 'Varchar',
        'postalCode' => 'Int',
        'addressCountry' => 'Varchar',
    ];

    private static array $has_one = [
        "OrganisationSchemaType" => OrganisationSchemaType::class,
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName(['OrganisationSchemaTypeID']);
        return $fields;
    }
}