<?php

namespace OrganisationSchema\Extensions;

use OrganisationSchema\Models\OrganisationSchema;
use SilverShop\HasOneField\HasOneButtonField;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;

class OrganisationSchemaSiteConfigExtension extends Extension
{
    private static array $has_one = [
        'OrganisationSchema' => OrganisationSchema::class,
    ];

    private static $owns = [
        'OrganisationSchema',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $this->owner->updateCMSFields($fields);

        $fields->addFieldToTab('Root.OrganisationSchema',
            HasOneButtonField::create($this->getOwner(), "OrganisationSchema"),
        );

        return $fields;
    }

    public function getSchemaMarkup()
    {
        return $this->owner->OrganisationSchema()->SchemaMarkup;
    }
}