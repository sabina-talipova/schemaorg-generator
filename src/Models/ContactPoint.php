<?php

namespace OrganisationSchema\Models;

use SilverStripe\ORM\DataObject;

class ContactPoint extends DataObject
{
    private static array $db = [
        'Telephone' => 'Varchar',
        'ContactType' => 'Varchar',
        'Email' => 'Varchar',
        'AreaServed' => 'Varchar',
        'AvailableLanguage' => 'Varchar',
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