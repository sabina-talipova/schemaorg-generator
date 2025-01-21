<?php

namespace OrganisationSchema\Models;

use SilverStripe\ORM\DataObject;

class OrganisationSchemaType extends DataObject
{
    private static array $db = [
        'Name'  => 'Varchar(255)',
        'Description' => 'Text',
        'Url'    => 'Varchar(255)',
        'Logo'   => 'Varchar(255)',
        'AlternateName'  => 'Varchar(255)',
        'SameAs' => 'Text',
    ];

    private static array $has_one = [
        'OrganisationSchema'  => OrganisationSchema::class,
    ];

    private static array $has_many = [
        "ContactPoints" => ContactPoint::class,
        "Addresses" => Address::class,
    ];

    private static $summary_fields = [
        'Name' => 'Name',
        'SchemaName' => 'Type',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName(['OrganisationSchemaID']);
        return $fields;
    }

    protected function getSchemaName()
    {
        return $this->ClassName;
    }

}