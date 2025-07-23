<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class CurrencyExchange extends CModule
{
    public $MODULE_ID = 'currency.exchange';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/install/version.php';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('CURRENCY_EXCHANGE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('CURRENCY_EXCHANGE_MODULE_DESC');
        $this->PARTNER_NAME = Loc::getMessage('CURRENCY_EXCHANGE_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('CURRENCY_EXCHANGE_PARTNER_URI');
    }

    public function DoInstall()
    {
        global $APPLICATION;
        
        if (!IsModuleInstalled($this->MODULE_ID)) {
            RegisterModule($this->MODULE_ID);
            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('CURRENCY_EXCHANGE_INSTALL_TITLE'),
                __DIR__ . '/install/step.php'
            );
        }
    }

    public function DoUninstall()
    {
        global $APPLICATION;
        
        UnRegisterModule($this->MODULE_ID);
        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('CURRENCY_EXCHANGE_UNINSTALL_TITLE'),
            __DIR__ . '/install/unstep.php'
        );
    }

    public function InstallDB()
    {
        global $DB;
        
        $DB->RunSQLBatch(__DIR__ . '/install/db/mysql/install.sql');
        
        return true;
    }

    public function UnInstallDB()
    {
        global $DB;
        
        $DB->RunSQLBatch(__DIR__ . '/install/db/mysql/uninstall.sql');
        
        return true;
    }

    public function InstallEvents()
    {
        return true;
    }

    public function UnInstallEvents()
    {
        return true;
    }

    public function InstallFiles()
    {
        CopyDirFiles(
            __DIR__ . '/install/components',
            $_SERVER['DOCUMENT_ROOT'] . '/local/components',
            true,
            true
        );
        
        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFilesEx('/local/components/currency.list');
        DeleteDirFilesEx('/local/components/currency.filter');
        
        return true;
    }
}
