<?php
/**
 * User: Vitalii Sestrenskyi
 */

namespace VS\Orm\Data;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\FloatField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\DateField;
use Bitrix\Main\ORM\Fields\DatetimeField;

/**
 * Trait Vitalii Sestrenskyi
 * @package VS\Orm\Data
 */
trait TDataManager
{
    /**
     * @param string $tableName
     * @return array
     * @throws SystemException
     * @throws \Bitrix\Main\Db\SqlQueryException
     * @throws \Bitrix\Main\LoaderException
     */
    public function getTableDescription( $tableName = '' )
    {
        $arResult = [];
        if(Loader::includeModule('main') && !empty($tableName))
        {
            $connection = Application::getInstance()->getConnection();
            $sql = 'SELECT * ';
            $sql .= 'FROM INFORMATION_SCHEMA.COLUMNS ';
            $sql .= 'WHERE TABLE_SCHEMA = "'.$connection->getDatabase().'" ';
            $sql .= 'AND TABLE_NAME = "'.$tableName.'";';
            $db = $connection->query($sql);
            while ($result = $db->fetch())
            {
                $arResult[] = $result;
            }
        }
        return $arResult;
    }

    /**
     * @param string $tableName
     * @return array
     * @throws \Bitrix\Main\LoaderException
     */
    public function getMysqlMap( $tableName = '' )
    {
        $arResult = [];
        $arFieldsDescription = self::getTableDescription($tableName);
        if(Loader::includeModule('main') && !empty($tableName) && count($arFieldsDescription) > 0)
        {
            $сache = Cache::createInstance();
            if ($сache->initCache(86400, md5("getMysqlMap"), 'vs/getMysqlMap/'.$tableName ))
            {
                $arResult = $сache->getVars();
            }
            elseif ($сache->startDataCache())
            {
                if (count($arFieldsDescription) > 0)
                {
                    foreach ($arFieldsDescription as $arItem)
                    {
                        $obField = null;
                        switch ($arItem['DATA_TYPE'])
                        {
                            case "int":
                                $obField = (new IntegerField($arItem['COLUMN_NAME']));
                                break;
                            case "tinyint":
                                $obField = (new BooleanField($arItem['COLUMN_NAME']));
                                break;
                            case "numeric":
                                $obField = (new IntegerField($arItem['COLUMN_NAME']));
                                break;
                            case "float":
                                $obField = (new FloatField($arItem['COLUMN_NAME']));
                                break;
                            case "decimal":
                                $obField = (new FloatField($arItem['COLUMN_NAME']));
                                break;
                            /////////////////
                            case "char":
                                $obField = (new StringField($arItem['COLUMN_NAME']));
                                break;
                            case "varchar":
                                $obField = (new StringField($arItem['COLUMN_NAME']));
                                break;
                            case "text":
                                $obField = (new TextField($arItem['COLUMN_NAME']));
                                break;
                            case "longtext":
                                $obField = (new TextField($arItem['COLUMN_NAME']));
                                break;
                            case "mediumtext":
                                $obField = (new TextField($arItem['COLUMN_NAME']));
                                break;
                            /////////////////
                            case "date":
                                $obField = (new DateField($arItem['COLUMN_NAME']));
                                break;
                            case "datetime":
                                $obField = (new DatetimeField($arItem['COLUMN_NAME']));
                                break;
                            case "time":
                                $obField = (new DatetimeField($arItem['COLUMN_NAME']));
                                break;
                            case "timestamp":
                                $obField = (new DatetimeField($arItem['COLUMN_NAME']));
                                break;
                            case "year":
                                $obField = (new DateField($arItem['COLUMN_NAME']));
                                break;
                            default:
                                break;
                        }
                        if(is_object($obField))
                        {
                            if($arItem['EXTRA'] == 'auto_increment')
                            {
                                $obField->configureAutocomplete(true);
                            }
                            if($arItem['COLUMN_KEY'] == 'PRI')
                            {
                                $obField->configurePrimary(true);
                            }
                            if($arItem['COLUMN_DEFAULT'] != '' && !empty($arItem['COLUMN_DEFAULT']))
                            {
                                $obField->configureDefaultValue($arItem['COLUMN_DEFAULT']);
                            }
                        }
                        if(!is_null($obField))
                        {
                            $arResult[$arItem['COLUMN_NAME']] = $obField;
                        }
                    }
                }
                $сache->endDataCache($arResult);
            }
        }
        return $arResult;
    }
}
