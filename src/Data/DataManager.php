<?php
/**
 * User: Vitalii Sestrenskyi
 */

namespace VS\Orm\Data;

use VS\Orm\Data\TDataManager;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Data\DataManager as BxDataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\FloatField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\DateField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\SystemException;
use Bitrix\Main\Data\Cache;

/**
 * Class DataManager
 * @package VS\Orm\Data
 */
class DataManager extends BxDataManager
{
    /**
     * TDataManager
     */
    use TDataManager;

}
