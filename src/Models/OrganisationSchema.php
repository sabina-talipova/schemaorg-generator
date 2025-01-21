<?php

namespace OrganisationSchema\Models;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\DataObject;
use Symbiote\GridFieldExtensions\GridFieldAddNewMultiClass;

class OrganisationSchema extends DataObject
{
    private static string $table_name = 'OrganisationSchema';

    private static $db = [
        'Name' => 'Varchar',
        'Description' => 'Text',
        'SchemaMarkup' => 'Text',
    ];

    private static $summary_fields = [
        'Name' => 'Name',
        'Description' => 'Description',
    ];

    private static array $has_many = [
        'OrganisationSchemaTypes'  => OrganisationSchemaType::class,
    ];

    private static $cascade_deletes = [
        'OrganisationSchemaTypes',
    ];

    /**
     * @param FieldList $fields
     * @return void
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName(['OrganisationSchemaTypes']);

        $gridField = GridField::create(
            'OrganisationSchemaTypes',
            'Organisation Schema Type',
            OrganisationSchemaType::get(),
            $config = GridFieldConfig_RecordEditor::create()
        );

        $config->removeComponentsByType(GridFieldAddNewButton::class)->addComponent(new GridFieldAddNewMultiClass());
        $fields->addFieldToTab('Root.Main', $gridField);
        $fields->addFieldToTab('Root.SchemaMarkup', $fields->dataFieldByName('SchemaMarkup')->setReadonly(true));

        return $fields;
    }

    public function onBeforeWrite()
    {
        $this->generateOrganisationSchema();
        parent::onBeforeWrite();
    }

    private function generateOrganisationSchema()
    {
        $orgSchema = [];
        $orgSchema['@context'] = "http://schema.org";
        $orgSchema['@graph'] = [];

        OrganisationSchemaType::get()->each(function ($item) use (&$orgSchema) {
            $schema = [];
            foreach($item->toMap() as $key => $value) {
                if($key == 'ClassName') {
                    switch($value){
                        case GovernmentOrganisationType::class:
                            $schema['@type'] = 'GovernmentService';
                            break;
                        case LocalBusinessType::class:
                            $schema['@type'] = 'LocalBusiness';
                            break;
                        default:
                            $schema['@type'] = 'Organization';
                            break;
                    }

                    continue;
                }

                if($this->hiddenFields($key)) continue;
                
                $schema[lcfirst($key)] = $value;
            }

            $item->ContactPoints()->each(function($contact) use (&$schema) {
                $contactPoints = [];
                $contactPoints['@type'] = 'ContactPoint';
                foreach($contact->toMap() as $key => $value) {
                    if($this->hiddenFields($key)) continue;

                    $contactPoints[lcfirst($key)] = $value;
                }
                $schema['contactPoint'][] = $contactPoints;
            });

            $item->Addresses()->each(function($contact) use (&$schema) {
                $addresses = [];
                $addresses['@type'] = 'PostalAddress';
                foreach($contact->toMap() as $key => $value) {
                    if($this->hiddenFields($key)) continue;

                    $addresses[lcfirst($key)] = $value;
                }
                $schema['address'][] = $addresses;
            });

            $orgSchema['@graph'][] = $schema;
        });
        $this->SchemaMarkup = json_encode($orgSchema);
    }

    private function hiddenFields($field)
    {
        $list = [
            'ID', 
            'ClassName',
            'LastEdited',
            'Created',
            'OrganisationSchemaID',
            'OrganisationSchemaTypeID',
            'RecordClassName'
        ];

        return in_array($field, $list);
    }
}