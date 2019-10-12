Bitrix ORM Extention
=================

Extention for Bitrix ORM DataManager
-----------------

##### Package caches the data so if you add new columns in the table do not remember clean the cache


### How to use this package?

##### Example if you do not have to set relationship

```php
<?php 
namespace VS\ProjectName\Models;

use VS\Orm\Data\DataManager;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\{
    Fields\IntegerField,
    Fields\FloatField,
    Fields\BooleanField,
    Fields\Relations\ManyToMany,
    Fields\StringField,
    Fields\DatetimeField,
    Fields\TextField,
    Fields\Relations\Reference,
    Query\Join
};

if(!Loader::includeModule('main'))
{
    return;
}

class BitrixProductRowTable extends DataManager
{
    public static function getTableName()
    {
        return 'b_crm_product_row';
    }

    /** 
    * @return mixed
    */
    public static function getMap()
    {
        return self::getMysqlMap(self::getTableName());
    }
}
```

##### Example if you want to set relationship

```php
<?php 
namespace VS\ProjectName\Models;

use VS\Orm\Data\DataManager;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\{
    Fields\IntegerField,
    Fields\FloatField,
    Fields\BooleanField,
    Fields\Relations\ManyToMany,
    Fields\StringField,
    Fields\DatetimeField,
    Fields\TextField,
    Fields\Relations\Reference,
    Query\Join
};

if(!Loader::includeModule('main'))
{
    return;
}

class BitrixProductRowTable extends DataManager
{
    public static function getTableName()
    {
        return 'vs_crm_product_row';
    }

    /** 
    * @return mixed
    */
    public static function getMap()
    {
        global $USER;
        $arMap = self::getMysqlMap(self::getTableName());
        $arMap['OWNER_TYPE']->configureDefaultValue('D');
        $arMap['CREATED_BY']->configureDefaultValue($USER->GetID());
        $arMap['CREATED_AT']->configureDefaultValue(new DateTime());
        $arMap['UPDATED_AT']->configureDefaultValue(new DateTime());
        $arRelations = [
            'CREATED'=>new Reference(
                'CREATED',
                \Bitrix\Main\UserTable::class,
                array('=this.CREATED_BY' => 'ref.ID')
            ),
            'CATALOG_GROUP'=>new Reference(
                'CATALOG_GROUP',
                \Bitrix\Catalog\GroupTable::class,
                array('=this.CATALOG_GROUP_ID' => 'ref.ID')
            )
        ];
        return array_merge($arMap, $arRelations);
    }
}
```



-----------------

