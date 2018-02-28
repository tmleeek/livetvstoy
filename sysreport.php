<?php
error_reporting(E_ALL);

/**
 * Magento System Report Shell Tool for Magento 1.x
 *
 * Sysreport tool is running in Magento Framework environment, developer mode is ON
 */
class Mage_Shell_SystemReport
{
    /**
     * Report tool version
     */
    const REPORT_CLASS_VERSION = '1.9.0';

    /**
     * Remote server URL
     * This is API URL which is used to send report, get reference data files etc.
     */
    const REPORT_REMOTE_SERVER_URL = 'http://sysreport.sparta.corp.magento.com/api/';

    /**
     * Remote server HTTP Authentication username and password
     */
    const REPORT_REMOTE_SERVER_HTTP_AUTH_CREDENTIALS =
        'V2tWak5XUnRTa2hTYm1Sb1ZraENSVlY2Um1GaVYxSkZWVzEwYkdFeWVHaFplazV6Vld4c1ZWVnJUVDA9';

    /**
     * Sysreport tool error messages/debug log file
     */
    const REPORT_LOG_FILE = 'sysreport.log';

    /**
     * Core files check sum local data file mask
     */
    const REPORT_FILES_CHECK_SUM_LOCAL_DATA_FILE_MASK = 'core_files_checksum_%s_v%s.local';

    /**
     * Core files check sum reference data file mask
     */
    const REPORT_FILES_CHECK_SUM_REF_DATA_FILE_MASK = 'core_files_checksum_%s_v%s.ref';

    /**
     * DB Structure snapshot local file mask
     */
    const REPORT_DB_STRUCTURE_SNAPSHOT_LOCAL_FILE_MASK = 'db_structure_snapshot_%s_v%s.local';

    /**
     * DB Structure snapshot reference file mask
     */
    const REPORT_DB_STRUCTURE_SNAPSHOT_REF_FILE_MASK = 'db_structure_snapshot_%s_v%s.ref';

    /**
     * Payments Functionality wiki data file mask
     */
    const REPORT_PAYMENTS_FUNCTIONALITY_WIKI_FILE_MASK = 'payments_functionality_matrix_%s_v%s.wiki';

    /**
     * Merged XML config file name
     */
    const REPORT_MERGED_CONFIG_FILE_NAME = 'merged_config.xml';

    /**
     * Maintenance mode flag file name
     */
    const REPORT_MAINTENANCE_MODE_FLAG_FILE_NAME = 'maintenance.flag';

    /**
     * Applied patches list file name
     */
    const REPORT_APPLIED_PATCHES_LIST_FILE_NAME = 'applied.patches.list';

    /**
     * Maximum size to read for applied patches list file
     */
    const REPORT_APPLIED_PATCHES_LIST_FILE_MAX_SIZE = 1048576; // 1MB

    /**
     * Default priority for supported commands
     */
    const REPORT_COMMAND_DEFAULT_PRIORITY = 45;

    /**
     * Contain maximum tries number when getting environment data and entering wrong HTTP Auth credentials
     */
    const REPORT_ENVIRONMENT_DATA_HTTP_AUTH_MAX_TRIES_NUMBER = 3;

    /**
     * Contain maximum web local IP addresses number to check when sysreport tool installed at web node
     * which can be under load balancer
     */
    const REPORT_ENVIRONMENT_DATA_MAX_WEB_LOCAL_IP_NUMBER_TO_CHECK = 15;

    /**
     * Maximum data count when table in HTML format still expanded
     */
    const REPORT_HTML_NONE_COLLAPSIBLE_TABLE_DATA_COUNT = 64;

    /**
     * Maximum HTML table cell text length to display before cut with expand ability
     */
    const REPORT_HTML_NONE_COLLAPSIBLE_TABLE_CELL_STRING_LENGTH = 1000;

    /**
     * CLI Table Data Limitations
     */
    const TABLE_DATA_ROW_MAXIMUM_COUNT_FOR_OUTPUT = 1300;
    const TABLE_DATA_COLUMN_MAXIMUM_COUNT_FOR_OUTPUT = 64;
    const TABLE_DATA_COLUMN_MINIMUM_WIDTH = 3;
    const TABLE_DATA_COLUMN_MAXIMUM_WIDTH = 120;

    /**
     * Limitation for File Permissions report to avoid huge list of files to be displayed
     * (for ex. from var/log/ directory)
     */
    const TABLE_DATA_PERMISSIONS_REPORT_MAX_FILES_PER_DIRECTORY = 100;

    /**
     * Maximum file size which will be considered to parse log files for entries calculation
     */
    const MAX_FILE_SIZE_TO_OPEN_FOR_LOG_ENTRIES_CALC = 367001600; // 350MB

    /**
     * Number of log messages to report
     */
    const TOP_SYSTEM_LOG_MESSAGES_NUMBER_TO_REPORT = 5;
    const TOP_EXCEPTION_LOG_MESSAGES_NUMBER_TO_REPORT = 5;

    /**
     * Disable custom modules settings
     */
    const MODULE_CONFIG_FILE_MAX_SIZE = 1048576; // 1MB
    const DCM_DISABLED_MODULE_DIRECTORY_PREFIX = '__disabled__';

    /**
     * $this->_getFilesList() files list modes
     */
    const REPORT_FILE_LIST_ALL   = 0;
    const REPORT_FILE_LIST_FILES = 1;
    const REPORT_FILE_LIST_DIRS  = 2;

    /**
     * Magento Root path
     *
     * @var string
     */
    protected $_rootPath;

    /**
     * Initialize application with code (store, website code)
     *
     * @var string
     */
    protected $_appCode     = 'admin';

    /**
     * Initialize application code type (store, website, store_group)
     *
     * @var string
     */
    protected $_appType     = 'store';

    /**
     * Input arguments
     *
     * @var array
     */
    protected $_args        = array();

    /**
     * Known core namespaces and modules
     *
     * @var array
     */
    protected $_allowedCodePools = array('local', 'community');
    protected $_coreNamespaces = array('Mage', 'Zend', 'Enterprise');
    protected $_additionalCoreModules = array(
        'community' => array('Cm_RedisSession', 'Phoenix_Moneybookers', 'Find_Feed', 'Social_Facebook'),
        'local'     => array(),
        'core'      => array(),
    );

    /**
     * Contain sysreport behavior properties and other additional data
     *
     * @var array
     */
    protected $_properties = array(
        // Flag which points if current instance is EE
        'is_enterprise_mode' => false,
        // Flag which points if current instance is PE
        'is_professional_mode' => false,
        // If true then error messages will not be logged into log file
        'do_not_log' => false,
        // If true then no messages will be output to STDOUT
        'is_silent_mode' => false,
        // If true then no progress info for any commands will be generated
        'do_not_output_progress' => false,
    );

    /**
     * List of all Magento supported versions by sysreport tool
     *
     * @var array
     */
    protected $_supportedMagentoVersions = array(
        'ee' => array(
            '1.9.0.0',  '1.9.1.0',  '1.9.1.1',
            '1.10.0.1', '1.10.0.2', '1.10.1.0', '1.10.1.1',
            '1.11.0.0', '1.11.0.1', '1.11.0.2', '1.11.1.0', '1.11.2.0',
            '1.12.0.0', '1.12.0.1', '1.12.0.2',
            '1.13.0.0', '1.13.0.1', '1.13.0.2', '1.13.1.0',
            '1.14.0.0', '1.14.0.1', '1.14.1.0'
        ),
        'pe' => array(
            '1.9.0.0',  '1.9.1.0',
            '1.10.0.1', '1.10.0.2', '1.10.1.0',
            '1.11.0.0', '1.11.1.0',
            '1.12.0.0'
        ),
        'ce' => array(
            '1.4.0.0', '1.4.0.1', '1.4.1.0', '1.4.1.1', '1.4.2.0',
            '1.5.0.0', '1.5.0.1', '1.5.1.0',
            '1.6.0.0', '1.6.1.0', '1.6.2.0',
            '1.7.0.0', '1.7.0.1', '1.7.0.2',
            '1.8.0.0', '1.8.1.0',
            '1.9.0.0', '1.9.0.1',
        )
    );

    /**
     * List of command categories/groups
     *
     * @var array
     */
    protected $_commandGroups = array(
        'general'       => array('priority' => 10,  'title' => 'General'),
        'data'          => array('priority' => 20,  'title' => 'Data'),
        'environment'   => array('priority' => 30,  'title' => 'Environment'),
        'modules'       => array('priority' => 40,  'title' => 'Modules'),
        'configuration' => array('priority' => 50,  'title' => 'Configuration'),
        'rewrites'      => array('priority' => 60,  'title' => 'Rewrites'),
        'files'         => array('priority' => 70,  'title' => 'Files'),
        'dbtables'      => array('priority' => 80,  'title' => 'DB Tables'),
        'logs'          => array('priority' => 90,  'title' => 'Logs'),
        'attributes'    => array('priority' => 100, 'title' => 'Attributes'),
        'events'        => array('priority' => 110, 'title' => 'Events'),
        'cron'          => array('priority' => 120, 'title' => 'Cron'),
        'design'        => array('priority' => 130, 'title' => 'Design'),
        'stores'        => array('priority' => 140, 'title' => 'Stores'),
        'other'         => array('priority' => 150, 'title' => 'Other'),
    );

    /**
     * List of supported commands and aliases
     * Do not change anything except description typo corrections
     * Command data structure:
     * Array (
     *     cmd         => command_name,
     *     alias       => command_alias,
     *    // It is not required parameter, but if execution priority needed, this parameter can be used
     *     priority    => 4,
     *     // Important: only specified arguments here will be considered and passed to appropriate command class method
     *     // If specified here value of argument not null, then it will be used as final value passed to
     *     // appropriate command class method
     *     arguments   => array(id => null, code => null, status => true),
     *     description => command description,
     *     // Mage_Shell_SystemReport class method, which will be called to generate specific report data
     *     method      => _runCommand
     * )
     *
     * @var array
     */
    protected $_supportedCommands = array(
        // Main data generation commands
        'version' => array(
            'cmd'         => 'version',
            'alias'       => 'v',
            'description' => 'Show Magento Version',
            'method'      => '_generateMagentoVersionData',
            'group'       => 'general',
        ),
        'classrewrites' => array(
            'cmd'         => 'classrewrites',
            'alias'       => 'r',
            'description' => 'Show all classes rewrites',
            'method'      => '_generateClassRewritesData',
            'group'       => 'rewrites',
        ),
        'classrewriteconflicts' => array(
            'cmd'         => 'classrewriteconflicts',
            'alias'       => 'rc',
            'description' => 'Show all classes rewrite conflicts',
            'method'      => '_generateClassRewriteConflictsData',
            'group'       => 'rewrites',
        ),
        'hardclassrewrites' => array(
            'cmd'         => 'hardclassrewrites',
            'alias'       => 'R',
            'description' => 'Show all core php files rewrites that are exist in local and community code pools.',
            'method'      => '_generateFileRewritesData',
            'group'       => 'rewrites',
        ),
        'controllerrewrites' => array(
            'cmd'         => 'controllerrewrites',
            'alias'       => 'crw',
            'description' => 'Show all controllers rewrites',
            'method'      => '_generateControllerRewritesData',
            'group'       => 'rewrites',
        ),
        'routerrewrites' => array(
            'cmd'         => 'routerrewrites',
            'alias'       => 'rrw',
            'description' => 'Show all routers rewrites',
            'method'      => '_generateRouterRewritesData',
            'group'       => 'rewrites',
        ),
        'configuration' => array(
            'cmd'         => 'configuration',
            'alias'       => 'c',
            'description' => "Important Configuration values (e.g. is system log, solr search, flat functionality, FPT,
\t\tcompilation, Page Cache crawler, Merge JavaScript/CSS Files enabled etc.)",
            'method'      => '_generateConfigurationData',
            'group'       => 'configuration',
        ),
        'localxml' => array(
            'cmd'         => 'localxml',
            'alias'       => 'lx',
            'description' => "Show app/etc/local.xml values",
            'method'      => '_generateEtcLocalXmlData',
            'group'       => 'configuration',
        ),
        'etcenterprisexml' => array(
            'cmd'         => 'etcenterprisexml',
            'alias'       => 'ex',
            'description' => "Show app/etc/enterprise.xml values",
            'method'      => '_generateEtcEnterpriseXmlData',
            'group'       => 'configuration',
        ),
        'etcconfigxml' => array(
            'cmd'         => 'etcconfigxml',
            'alias'       => 'cx',
            'description' => "Show app/etc/config.xml values",
            'method'      => '_generateEtcConfigXmlData',
            'group'       => 'configuration',
        ),
        'datacount' => array(
            'cmd'         => 'datacount',
            'alias'       => 'd',
            'description' => "Show amount of stores, tax rules, customers, customer attributes, customer segments,
\t\torders, categories, products, catalog attributes, URL rewrites, promo rules, cms pages, banners, visitors log",
            'method'      => '_generateCountData',
            'group'       => 'general',
        ),
        'environment' => array(
            'cmd'         => 'environment',
            'alias'       => 'i',
            'description' => "Show environment information
\t\t(e.g. apache, php, mysql version, configuration and loaded modules)",
            'method'      => '_generateEnvironmentData',
            'group'       => 'environment',
        ),
        'mysqlstatus' => array(
            'cmd'         => 'mysqlstatus',
            'alias'       => 'ms',
            'description' => 'Show MySQL status. Additionally output MySQL status after 10 seconds delay',
            'method'      => '_generateMysqlStatusData',
            'group'       => 'environment',
        ),
        'logfiles' => array(
            'cmd'         => 'logfiles',
            'alias'       => 'g',
            'description' => 'Show log files list, top 5 system and top 5 exception messages',
            'method'      => '_generateLogFilesData',
            'group'       => 'logs',
        ),
        'corruptedfiles' => array(
            'cmd'         => 'corruptedfiles',
            'alias'       => 'cf',
            'arguments'   => array('f' => true),
            'description' => "Show all corrupted core files.
\t\tTo regenerate local core files check sum snapshot use additionally flag \"-f\" to force generation
\t\totherwise if there is already exist local snapshot then it will be used.",
            'method'      => '_generateCorruptedCoreFilesData',
            'group'       => 'files',
        ),
        'missingfiles' => array(
            'cmd'         => 'missingfiles',
            'alias'       => 'mf',
            'arguments'   => array('f' => true),
            'description' => "Show missing core files.
\t\tTo regenerate local core files check sum snapshot use additionally flag \"-f\" to force generation
\t\totherwise if there is already exist local snapshot then it will be used.",
            'method'      => '_generateMissingCoreFilesData',
            'group'       => 'files',
        ),
        'newfiles' => array(
            'cmd'         => 'newfiles',
            'alias'       => 'nf',
            'arguments'   => array('f' => true),
            'description' => "Show new files.
\t\tTo regenerate local core files check sum snapshot use additionally flag \"-f\" to force generation
\t\totherwise if there is already exist local snapshot then it will be used.",
            'method'      => '_generateNewLocalFilesData',
            'group'       => 'files',
        ),
        'patches' => array(
            'cmd'         => 'patches',
            'alias'       => 'pp',
            'description' => "Show list of all provided patches.
\t\tNotice: provided patches data is based on only existing *.patch files in root directory or first level
\t\tsub-directories.",
            'method'      => '_generateProvidedPatchesData',
            'group'       => 'files',
        ),
        'filepermissions' => array(
            'cmd'         => 'filepermissions',
            'alias'       => 'fp',
            'description' => "Show files tree with permissions information (directory depth is 2)",
            'method'      => '_generateFilePermissionsData',
            'group'       => 'files',
        ),
        'corruptedtables' => array(
            'cmd'         => 'corruptedtables',
            'alias'       => 'ct',
            'arguments'   => array('f' => true),
            'description' => "Show all corrupted db tables.
\t\tTo regenerate local db tables snapshot use additionally flag \"-f\" to force regeneration
\t\totherwise if there is already exist local snapshot then it will be used
\t\tor it will be generated automatically if not exists.",
            'method'      => '_generateCorruptedCoreDbTablesData',
            'group'       => 'dbtables',
        ),
        'missingtables' => array(
            'cmd'         => 'missingtables',
            'alias'       => 'mt',
            'arguments'   => array('f' => true),
            'description' => "Show missing db tables.
\t\tTo regenerate local db tables snapshot use additionally flag \"-f\" to force regeneration
\t\totherwise if there is already exist local snapshot then it will be used
\t\tor it will be generated automatically if not exists.",
            'method'      => '_generateMissingCoreDbTablesData',
            'group'       => 'dbtables',
        ),
        'newtables' => array(
            'cmd'         => 'newtables',
            'alias'       => 'nt',
            'arguments'   => array('f' => true),
            'description' => "Show new db tables.
\t\tTo regenerate local db tables snapshot use additionally flag \"-f\" to force regeneration
\t\totherwise if there is already exist local snapshot then it will be used
\t\tor it will be generated automatically if not exists.",
            'method'      => '_generateNewDbTablesData',
            'group'       => 'dbtables',
        ),
        'tablesstatus' => array(
            'cmd'         => 'tablesstatus',
            'alias'       => 'ts',
            'description' => "Show tables and views information. For tables additionally retrieving Engine, Rows count
\t\tand table Size (data length + index length).",
            'method'      => '_generateDbTablesStatusData',
            'group'       => 'dbtables',
        ),
        'dbroutines' => array(
            'cmd'         => 'dbroutines',
            'alias'       => 'dr',
            'arguments'   => array('f' => true),
            'description' => 'Show database stored functions and procedures list.',
            'method'      => '_generateDbRoutinesListData',
            'group'       => 'dbtables',
        ),
        'missingdbroutines' => array(
            'cmd'         => 'missingdbroutines',
            'alias'       => 'mr',
            'arguments'   => array('f' => true),
            'description' => 'Show mising database stored functions and procedures list.',
            'method'      => '_generateMissingDbRoutinesData',
            'group'       => 'dbtables',
        ),
        'newdbroutines' => array(
            'cmd'         => 'newdbroutines',
            'alias'       => 'nr',
            'arguments'   => array('f' => true),
            'description' => 'Show new database stored functions and procedures list.',
            'method'      => '_generateNewDbRoutinesData',
            'group'       => 'dbtables',
        ),
        'dbtriggers' => array(
            'cmd'         => 'dbtriggers',
            'alias'       => 'dt',
            'arguments'   => array('f' => true),
            'description' => 'Show database triggers list.',
            'method'      => '_generateDbTriggersListData',
            'group'       => 'dbtables',
        ),
        'missingdbtriggers' => array(
            'cmd'         => 'missingdbtriggers',
            'alias'       => 'mdt',
            'arguments'   => array('f' => true),
            'description' => 'Show missing database triggers list.',
            'method'      => '_generateMissingDbTriggersData',
            'group'       => 'dbtables',
        ),
        'newdbtriggers' => array(
            'cmd'         => 'newdbtriggers',
            'alias'       => 'ndt',
            'arguments'   => array('f' => true),
            'description' => 'Show new database triggers list.',
            'method'      => '_generateNewDbTriggersData',
            'group'       => 'dbtables',
        ),

        // Duplicates: categories, products, orders, users
        'categoryduplicates' => array(
            'cmd'         => 'categoryduplicates',
            'alias'       => 'cd',
            'description' => 'Show category duplicates by URL key',
            'method'      => '_generateCategoryDuplicates',
            'group'       => 'data',
        ),
        'productduplicates' => array(
            'cmd'         => 'productduplicates',
            'alias'       => 'pd',
            'description' => 'Show product duplicates by SKU and URL key',
            'method'      => '_generateProductDuplicates',
            'group'       => 'data',
        ),
        'orderduplicates' => array(
            'cmd'         => 'orderduplicates',
            'alias'       => 'od',
            'description' => 'Show order duplicates by increment ID',
            'method'      => '_generateOrderDuplicates',
            'group'       => 'data',
        ),
        'userduplicates' => array(
            'cmd'         => 'userduplicates',
            'alias'       => 'ud',
            'description' => 'Show user duplicates by email',
            'method'      => '_generateUserDuplicates',
            'group'       => 'data',
        ),

        // Corrupted data
        'corruptedcategoriesdata' => array(
            'cmd'         => 'corruptedcategoriesdata',
            'alias'       => 'ccd',
            'description' => 'Show categories data inconsistency',
            'method'      => '_generateCorruptedCategoriesData',
            'group'       => 'data',
        ),

        // Websites, Stores, Store Views
        'websitestree' => array(
            'cmd'         => 'websitestree',
            'alias'       => 'W',
            'description' => "Show detailed information of websites, stores and store views as tree",
            'method'      => '_generateWebsitesTreeData',
            'group'       => 'stores',
        ),
        'websiteslist' => array(
            'cmd'         => 'websiteslist',
            'alias'       => 'w',
            'description' => "Show websites list",
            'method'      => '_generateWebsitesData',
            'group'       => 'stores',
        ),
        'storeslist' => array(
            'cmd'         => 'storeslist',
            'alias'       => 'sl',
            'description' => "Show stores list",
            'method'      => '_generateStoresData',
            'group'       => 'stores',
        ),
        'storeviewslist' => array(
            'cmd'         => 'storeviewslist',
            'alias'       => 'svl',
            'description' => "Show store views list",
            'method'      => '_generateStoreViewsData',
            'group'       => 'stores',
        ),

        // Shipping and Payment methods information
        'shippingmethods' => array(
            'cmd'         => 'shippingmethods',
            'alias'       => 's',
            'description' => 'Show shipping methods status',
            'method'      => '_generateShippingMethodsData',
            'group'       => 'configuration',
        ),
        'paymentmethods' => array(
            'cmd'         => 'paymentmethods',
            'alias'       => 'p',
            'description' => 'Show payment methods status',
            'method'      => '_generatePaymentMethodsData',
            'group'       => 'configuration',
        ),
        'paymentsmatrix' => array(
            'cmd'         => 'paymentsmatrix',
            'alias'       => 'P',
            'arguments'   => array('wiki' => true),
            'description' => "Show payments functionality matrix.
\t\tTo save Matrix under payments_functionality_matrix_ee|ce|pe_vx.x.x.x.wiki Wiki file as well - use flag \"-wiki\"",
            'method'      => '_generatePaymentsFunctionalityMatrixData',
            'group'       => 'configuration',
        ),

        // Cache, Index, Compiler, Cron statuses
        'cachestatus' => array(
            'cmd'         => 'cachestatus',
            'alias'       => 'cs',
            'description' => 'Show cache status',
            'method'      => '_generateCacheStatusData',
            'group'       => 'general',
        ),
        'indexstatus' => array(
            'cmd'         => 'indexstatus',
            'alias'       => 'is',
            'description' => 'Show index status',
            'method'      => '_generateIndexStatusData',
            'group'       => 'general',
        ),
        'compilerstatus' => array(
            'cmd'         => 'compilerstatus',
            'alias'       => 'C',
            'description' => 'Show compiler status',
            'method'      => '_generateCompilerStatusData',
            'group'       => 'general',
        ),
        'cronstatus' => array(
            'cmd'         => 'cronstatus',
            'alias'       => 'cr',
            'description' => "Show cron status data such as amount of schedules per status (e.g. running, success etc)
\t\tand per job code (e.g. rule_apply_all, newsletter_send_all etc)",
            'method'      => '_generateCronStatusData',
            'group'       => 'cron',
        ),
        'cronerrors' => array(
            'cmd'         => 'cronerrors',
            'alias'       => 'cre',
            'description' => "Show the most frequent errors per each cron job code",
            'method'      => '_generateCronErrorsData',
            'group'       => 'cron',
        ),
        'cronschedules' => array(
            'cmd'         => 'cronschedules',
            'alias'       => 'crs',
            'arguments'   => array('id' => null, 'code' => null, 'status' => null),
            'description' => "Show cron schedules.
\t\tYou can filter output by code using \"-code\" option and by status using \"-status\" option.
\t\tTo get full information about specific schedule use \"-id\" option.",
            'method'      => '_generateCronSchedulesData',
            'group'       => 'cron',
        ),

        // Attributes
        'entitytypes' => array(
            'cmd'         => 'entitytypes',
            'alias'       => 'et',
            'description' => 'Show all Magento entity types list',
            'method'      => '_generateAllEntityTypesData',
            'group'       => 'attributes',
        ),
        'allattributes' => array(
            'cmd'         => 'allattributes',
            'alias'       => 'aa',
            'description' => 'Show all EAV attributes',
            'method'      => '_generateAllEavAttributesData',
            'group'       => 'attributes',
        ),
        'newattributes' => array(
            'cmd'         => 'newattributes',
            'alias'       => 'AA',
            'description' => 'Show new EAV attributes list',
            'method'      => '_generateNewEavAttributesData',
            'group'       => 'attributes',
        ),
        'userattributes' => array(
            'cmd'         => 'userattributes',
            'alias'       => 'ua',
            'description' => 'Show all user defined EAV attributes list',
            'method'      => '_generateUserDefinedEavAttributesData',
            'group'       => 'attributes',
        ),
        'categoryattributes' => array(
            'cmd'         => 'categoryattributes',
            'alias'       => 'cta',
            'description' => 'Show category EAV attributes list',
            'method'      => '_generateCategoryEavAttributesData',
            'group'       => 'attributes',
        ),
        'productattributes' => array(
            'cmd'         => 'productattributes',
            'alias'       => 'pra',
            'description' => 'Show product EAV attributes list',
            'method'      => '_generateProductEavAttributesData',
            'group'       => 'attributes',
        ),
        'customerattributes' => array(
            'cmd'         => 'customerattributes',
            'alias'       => 'ca',
            'description' => 'Show customer EAV attributes list',
            'method'      => '_generateCustomerEavAttributesData',
            'group'       => 'attributes',
        ),
        'customeraddressattributes' => array(
            'cmd'         => 'customeraddressattributes',
            'alias'       => 'caa',
            'description' => 'Show customer address EAV attributes list',
            'method'      => '_generateCustomerAddressEavAttributesData',
            'group'       => 'attributes',
        ),
        'rmaitemattributes' => array(
            'cmd'         => 'rmaitemattributes',
            'alias'       => 'ria',
            'description' => 'Show RMA item EAV attributes list',
            'method'      => '_generateRMAItemEavAttributesData',
            'group'       => 'attributes',
        ),

        // Events
        'allevents' => array(
            'cmd'         => 'allevents',
            'alias'       => 'e',
            'description' => 'Show all events declarations',
            'method'      => '_generateAllEventsData',
            'group'       => 'events',
        ),
        'coreevents' => array(
            'cmd'         => 'coreevents',
            'alias'       => 'E',
            'description' => 'Show core events declarations',
            'method'      => '_generateCoreEventsData',
            'group'       => 'events',
        ),
        'eeevents' => array(
            'cmd'         => 'eeevents',
            'alias'       => 'ee',
            'description' => 'Show enterprise events declarations',
            'method'      => '_generateEnterpriseEventsData',
            'group'       => 'events',
        ),
        'customevents'  => array(
            'cmd'         => 'customevents',
            'alias'       => 'ce',
            'description' => 'Show custom events declarations',
            'method'      => '_generateCustomEventsData',
            'group'       => 'events',
        ),

        // Modules
        'allmodules' => array(
            'cmd'         => 'allmodules',
            'alias'       => 'm',
            'description' => 'Show all modules (with output disabled/enabled information)',
            'method'      => '_generateAllModulesData',
            'group'       => 'modules',
        ),
        'coremodules' => array(
            'cmd'         => 'coremodules',
            'alias'       => 'M',
            'description' => 'Show core modules',
            'method'      => '_generateCoreModulesData',
            'group'       => 'modules',
        ),
        'eemodules' => array(
            'cmd'         => 'eemodules',
            'alias'       => 'em',
            'description' => 'Show enterprise modules',
            'method'      => '_generateEnterpriseModulesData',
            'group'       => 'modules',
        ),
        'custommodules' => array(
            'cmd'         => 'custommodules',
            'alias'       => 'cm',
            'description' => 'Show custom modules',
            'method'      => '_generateCustomModulesData',
            'group'       => 'modules',
        ),
        'disabledmodules' => array(
            'cmd'         => 'disabledmodules',
            'alias'       => 'dm',
            'description' => "Show disabled modules.
\t\tIf \"-scope\" specified, then modules from that scope will be shown only.
\t\tScope can be: all, core, enterprise, custom",
            'arguments'   => array('scope' => null),
            'method'      => '_generateDisabledModulesData',
            'group'       => 'modules',
        ),

        // Cron jobs
        'allcronjobs' => array(
            'cmd'         => 'allcronjobs',
            'alias'       => 'j',
            'description' => 'Show all cron jobs list sorted by job code',
            'method'      => '_generateAllCronJobsData',
            'group'       => 'cron',
        ),
        'corecronjobs' => array(
            'cmd'         => 'corecronjobs',
            'alias'       => 'J',
            'description' => 'Show core cron jobs list sorted by job code',
            'method'      => '_generateCoreCronJobsData',
            'group'       => 'cron',
        ),
        'eecronjobs' => array(
            'cmd'         => 'eecronjobs',
            'alias'       => 'ej',
            'description' => 'Show enterprise cron jobs list sorted by job code',
            'method'      => '_generateEnterpriseCronJobsData',
            'group'       => 'cron',
        ),
        'customcronjobs' => array(
            'cmd'         => 'customcronjobs',
            'alias'       => 'cj',
            'description' => 'Show custom cron jobs list sorted by job code',
            'method'      => '_generateCustomCronJobsData',
            'group'       => 'cron',
        ),

        // Design Themes, Skins
        'themes' => array(
            'cmd'         => 'themes',
            'alias'       => 't',
            'description' => 'Show design themes list',
            'method'      => '_generateDesignThemeListData',
            'group'       => 'design',
        ),
        'themesconfig' => array(
            'cmd'         => 'themesconfig',
            'alias'       => 'tc',
            'description' => 'Show design themes settings per store',
            'method'      => '_generateDesignThemeConfigData',
            'group'       => 'design',
        ),
        'skins' => array(
            'cmd'         => 'skins',
            'alias'       => 'ds',
            'description' => 'Show design skins list',
            'method'      => '_generateDesignSkinsListData',
            'group'       => 'design',
        ),

        // Additional data generators (not report data)
        'getfileschecksum' => array(
            'cmd'         => 'getfileschecksum',
            'alias'       => 'fc',
            'description'=>'Generate core files check sum snapshot (file "core_files_checksum_ee|ce|pe_vx.x.x.x.local")',
            'method'      => '_generateFilesCheckSumData'
        ),
        'getdbsnapshot' => array(
            'cmd'         => 'getdbsnapshot',
            'alias'       => 'db',
            'description' => 'Generate db structure snapshot (file "db_structure_snapshot_ee|ce|pe_vx.x.x.x.local")',
            'method'      => '_generateDbDataAndStructureSnapshot'
        ),
        'getconfigfile' => array(
            'cmd'         => 'getconfigfile',
            'alias'       => 'F',
            'description' => 'Generate merged xml config (file "merged_config.xml")',
            'method'      => '_generateMergedConfigFile'
        ),
        'getfpcdata' => array(
            'cmd'         => 'getfpcdata',
            'alias'       => 'fpc',
            'arguments'   => array(
                'fpc' => null,
                'uncompress' => true,
                'unserialize' => true,
                'unjson' => true,
                'into' => null
            ),
            'description' => "Collect FPC data by specified cache ID.
\t\tCommon usage: \"-fpc cache_id\".
\t\tTo gzuncompress use flag \"-uncompress\"
\t\tTo unserialize use flag \"-unserialize\"
\t\tTo decode JSON use flag \"-unjson\"
\t\tTo save data into file use: \"-into file_name.dat\", otherwise data will be output",
            'method'      => '_generateFpcData'
        ),
        'mysqlshellcmd' => array(
            'cmd'         => 'mysqlshellcmd',
            'alias'       => 'msc',
            'description' => 'Generate shell command to connect to current Magento DB using mysql shell',
            'method'      => '_generateMysqlShellCommandData'
        ),

        //Data setters (not report data)
        'disablecustommodules' => array(
            'cmd'         => 'disablecustommodules',
            'alias'       => 'dcm',
            'description' => "Disable/Enable specified custom modules located in local and/or community code pools.
\t\tCommon usage: -dcm \"Namespace1_*, Namespace2_*, Ns_Module\"|* [-enable] [-communityonly|-localonly] [-cleancache]
\t\tTo flush *Magento* cache after enabling/disabling modules use flag \"-cleancache\"
\t\tTo enable/disable modules only at *local* code pool use flag \"-localonly\"
\t\tTo enable/disable modules only at *community* code pool use flag \"-communityonly\"",
            'arguments'   => array(
                'dcm' => null, 'cleancache' => true, 'localonly' => true, 'communityonly' => true, 'enable' => true
            ),
            'method'      => '_disableCustomModules'
        ),
        'cache' => array(
            'cmd'         => 'cache',
            'alias'       => 'cc',
            'description' => "Cache control comamnd: refresh, flush, disable, enable cache.
\t\tCommon usage: \"-cache -enable|-disable|-refresh|-flush <cache_type1,cache_type2, ... cache_typeN|*|all>\"
\t\tTo flush Magento cache storage use flag \"-flush\"
\t\tTo enable/disable cache use option \"-enable\"/\"-disable\" w/ specifying cache type(s)
\t\tTo refresh cache use option \"-refresh\" w/ specifying cache type(s)
\t\tAvailable cache type names can be obtained by running command \"$ php sysreport.php -cs\"
\t\tInstead of cache type(s)  you can use word \"all\" to enable/disable/refresh all available cache",
            'arguments'   => array('disable' => null, 'enable' => null,  'refresh' => null, 'flush' => true),
            'method'      => '_controlCache',
        ),
        'setdevmode' => array(
            'cmd'         => 'setdevmode',
            'alias'       => 'dev',
            'description' => "Switch on/off developer mode.
\t\tIf \"-default\" argument specified then dev mode control code will be reverted to default (will be removed).
\t\tCommon usage: -dev 1|0|true|false|on|off [-default]",
            'arguments'   => array('dev' => null, 'default' => true),
            'method'      => '_setDeveloperMode'
        ),
        'setmaintenancemode' => array(
            'cmd'         => 'setmaintenancemode',
            'alias'       => 'mm',
            'description' => "Enable/Disable maintenance mode.
\t\tCommon usage: -mm 1|0|true|false|on|off",
            'arguments'   => array('mm' => null),
            'method'      => '_setMaintenanceMode'
        ),

        // Tool data control commands
        'savereport' => array(
            'cmd'         => 'savereport',
            'alias'       => 'save',
            'description' => 'Save specific/full report into HTML file "sysreport-yyyy-mm-dd_hh-mm-ss-domain.com.html"',
            'method'      => '_saveReportIntoHtml'
        ),
        'sendreport' => array(
            'cmd'         => 'sendreport',
            'alias'       => 'send',
            'description' => 'Send specific/full report to Support reports remote database',
            'method'      => '_sendReport'
        ),
        'cleanreport' => array(
            'cmd'         => 'cleanreport',
            'alias'       => 'clean',
            'arguments'   => array('f' => true),
            'description' => "Clean all generated files by sysreport tool.
\t\tIf \"-f\" flag passed, then tool itself will be removed as well.",
            'method'      => '_cleanReportFiles'
        ),

        //Sysreport behaviour flags
        'all' => array(
            'cmd'         => 'all',
            'alias'       => 'a',
            'description' => 'Report all information',
        ),
        'mage' => array(
            'cmd'         => 'mage',
            'alias'       => 'mage',
            'description' => 'Report all Magento information (doesn\'t contain environment related reports)',
        ),
        'nolog' => array(
            'cmd'         => 'nolog',
            'alias'       => 'nl',
            'description' => 'Do not log any error/debug/info messages into file',
        ),
        'silent' => array(
            'cmd'         => 'silent',
            'alias'       => 'si',
            'description' => 'Do not output any error/debug/info messages to STDOUT',
        ),
        'noprogress' => array(
            'cmd'         => 'noprogress',
            'alias'       => 'np',
            'description' => "Do not output progress for any commands where data generation takes long time.
\t\tIf \"-silent\" flag is set then this option will be taken into account and no progress will be output.",
        ),
        'toolversion' => array(
            'cmd'         => 'toolversion',
            'alias'       => 'V',
            'description' => 'Show Sysreport Tool Version',
            'method'      => '_generateToolVersionData'
        ),
        'supportedversions' => array(
            'cmd'         => 'supportedversions',
            'alias'       => 'sv',
            'description' => 'Show supported Magento versions by current Sysreport Tool',
            'method'      => '_generateToolSupportedMagentoVersionsData'
        ),
    );

    /**
     * Command priorities list
     *
     * @var array
     */
    protected $_commandPriorities = array(
        // Initial information
        'version'           => 40,
        'datacount'         => 50,
        'websitestree'      => 60,
        'cachestatus'       => 70,
        'indexstatus'       => 80,
        'compilerstatus'    => 90,
        'cronstatus'        => 100,
        'cronerrors'        => 110,
        'logfiles'          => 120,

        // Corruption
        'corruptedfiles'            => 130,
        'corruptedtables'           => 140,
        'corruptedcategoriesdata'   => 150,
        'categoryduplicates'        => 160,
        'productduplicates'         => 170,
        'orderduplicates'           => 180,
        'userduplicates'            => 190,

        // Customization
        'classrewrites'         => 200,
        'classrewriteconflicts' => 210,
        'hardclassrewrites'     => 230,
        'controllerrewrites'    => 240,
        'routerrewrites'        => 250,
        'custommodules'         => 260,
        'disabledmodules'       => 270,
        'patches'               => 280,
        'missingfiles'          => 290,
        'newfiles'              => 300,
        'missingtables'         => 310,
        'newtables'             => 320,
        'missingdbroutines'     => 330,
        'newdbroutines'         => 340,
        'missingdbtriggers'     => 350,
        'newdbtriggers'         => 360,
        'customevents'          => 370,
        'userattributes'        => 380,
        'newattributes'         => 390,
        'customcronjobs'        => 400,


        // System
        'environment'       => 410,
        'mysqlstatus'       => 420,
        'filepermissions'   => 430,
        'tablesstatus'      => 440,
        'dbroutines'        => 450,
        'dbtriggers'        => 460,

        // Configuration
        'configuration'     => 470,
        'themesconfig'      => 480,
        'etcenterprisexml'  => 490,
        'localxml'          => 500,
        'etcconfigxml'      => 510,
        'shippingmethods'   => 520,
        'paymentmethods'    => 530,

        // Data
        'websiteslist'              => 540,
        'storeslist'                => 550,
        'storeviewslist'            => 560,
        'entitytypes'               => 570,
        'themes'                    => 580,
        'skins'                     => 590,
        'allattributes'             => 600,
        'categoryattributes'        => 610,
        'productattributes'         => 620,
        'customerattributes'        => 630,
        'customeraddressattributes' => 640,
        'rmaitemattributes'         => 650,
        'paymentsmatrix'            => 660,
        'cronschedules'             => 670,
        'allevents'                 => 680,
        'coreevents'                => 690,
        'eeevents'                  => 700,
        'allmodules'                => 710,
        'coremodules'               => 720,
        'eemodules'                 => 730,
        'allcronjobs'               => 740,
        'corecronjobs'              => 750,
        'eecronjobs'                => 760,


        // Control
        'getfileschecksum'      => 10,
        'getdbsnapshot'         => 20,
        'getconfigfile'         => 30,
        'mysqlshellcmd'         => 770,
        'getfpcdata'            => 780,
        'disablecustommodules'  => 790,
        'cache'                 => 800,
        'setmaintenancemode'    => 810,
        'setdevmode'            => 820,
        'cleanreport'           => 830,
        'all'                   => 840,
        'mage'                  => 850,
        'nolog'                 => 860,
        'silent'                => 870,
        'noprogress'            => 880,
        'toolversion'           => 890,
        'supportedversions'     => 900,
        'savereport'            => 1000,
        'sendreport'            => 1010,
    );

    /**
     * List of Environment related report commands
     *
     * @var array
     */
    protected $_environmentRelatedCommands = array(
        'environment',
        'mysqlstatus',
    );

    /**
     * List of keys from $this->_supportedCommands of commands
     * that are not used to generate system report data but considered as "action" commands/flags
     *
     * @var array
     */
    protected $_actionCommandsKeys = array(
        'all',
        'mage',
        'nolog',
        'silent',
        'noprogress',
        'savereport',
        'sendreport',
        'getfileschecksum',
        'getdbsnapshot',
        'getconfigfile',
        'getfpcdata',
        'cleanreport',
        'disablecustommodules',
        'cache',
        'setdevmode',
        'mysqlshellcmd',
        'setmaintenancemode',
        'toolversion',
        'supportedversions',
    );

    /**
     * Sysreport tool messages management settings "command key" => "sysreport tool property" map
     *
     * @var array
     */
    protected $_toolMessagesManagementCommandKeyToPropertyMap = array(
        'silent'     => 'is_silent_mode',
        'nolog'      => 'do_not_log',
        'noprogress' => 'do_not_output_progress'
    );

    /**
     * List of all supported command names and aliases
     * Generated from $this->_supportedCommands
     *
     * @var array
     */
    protected $_commandsNamesAndAliases = array();

    /**
     * List of successfully executed commands
     *
     * @var array
     */
    protected $_succeededCommands = array();

    /**
     * Contain all sysreport tool generated data
     *
     * @var array
     */
    protected $_systemReport = array();

    /**
     * Contain all sysreport tool generated data by report groups
     *
     * @var array
     */
    protected $_systemReportByGroups = null;

    /**
     * Store current magento version
     *
     * @var null|string
     */
    protected $_magentoVersion = null;

    /**
     * Contain current Magento instance edition title (CE/EE/PE)
     *
     * @var null|string
     */
    protected $_magentoEdition = null;

    /**
     * Store core resource model instance
     *
     * @var null|Mage_Core_Model_Resource|Mage_Core_Model_Resource_Resource
     */
    protected $_coreResourceModel = null;

    /**
     * Store read connection object
     *
     * @var null|Varien_Db_Adapter_Pdo_Mysql
     */
    protected $_readConnection = null;

    /**
     * Fields in local.xml file that must be hidden due to privacy
     *
     * @var array
     */
    protected $_xmlConfigRestrictedFields = array('username', 'password', 'key');

    /**
     * Store flag which determines if Sysreport Tool was initialized
     *
     * @var bool
     */
    protected $_isToolInitialised = false;

    /**
     * Initialize application and parse input parameters
     *
     */
    public function __construct()
    {
        try {
            $this->_validate();
            $this->_parseArgs();
            $this->_showHelp();
        } catch (Exception $e) {
            die('System Report tool could not be initialized because of an error: ' . "\n" . $e->getMessage() . "\n");
        }

        try {
            require_once $this->_getRootPath() . 'app' . DIRECTORY_SEPARATOR . 'Mage.php';
            Mage::setIsDeveloperMode(true);
            Mage::app($this->_appCode, $this->_appType);
        } catch (Exception $e) {
            // After Magento internal error handler was set and if during Magento initialization an error was raised,
            // stop sysreport tool processing in all cases except Notice, User Notice, Strict Notice,
            // Deprecated functionality messages
            if (!preg_match('~(?:User |Strict |)(?:Notice|Deprecated functionality).+~', $e->getMessage())) {
                die('Magento instance could not be initialized because of an error: ' . "\n" . $e->getMessage() . "\n");
            }
        }

        try {
            $this->_applyPhpVariables();
            $this->_prepare();
        } catch (Exception $e) {
            die('System Report tool could not be initialized because of an error: ' . "\n" . $e->getMessage() . "\n");
        }

        $this->_isToolInitialised = true;
    }

    /**
     * Generate supported commands and its class methods
     * Sort supported commands by their priority to order them for execution
     *
     * Instantiate core resource model and get read connection
     * Determine Magento version and is current run in EE mode
     */
    protected function _prepare()
    {
        try {
            // Set sysreport tool messages management (output or not, log into file or not etc.) flags
            foreach ($this->_toolMessagesManagementCommandKeyToPropertyMap as $key => $property) {
                if ($this->getArg($this->_supportedCommands[$key]['cmd'])
                    || $this->getArg($this->_supportedCommands[$key]['alias'])
                ) {
                    $this->_properties[$property] = true;
                }
            }

            foreach ($this->_supportedCommands as $key => $info) {
                $this->_commandsNamesAndAliases[] = $info['cmd'];
                $this->_commandsNamesAndAliases[] = $info['alias'];

                // Set default priority for command
                if (!array_key_exists($key, $this->_commandPriorities)) {
                    $this->_supportedCommands[$key]['priority'] = self::REPORT_COMMAND_DEFAULT_PRIORITY;
                } else {
                    $this->_supportedCommands[$key]['priority'] = $this->_commandPriorities[$key];
                }
            }

            uasort($this->_supportedCommands, array(__CLASS__, 'commandPriorityCompare'));

            $this->_magentoVersion = Mage::getVersion();
            $this->_magentoEdition = 'CE';
            $eeModuleConfig = Mage::getBaseDir('code') . DIRECTORY_SEPARATOR  .
                'core' . DIRECTORY_SEPARATOR . 'Enterprise' . DIRECTORY_SEPARATOR . 'Enterprise' . DIRECTORY_SEPARATOR .
                'etc' . DIRECTORY_SEPARATOR . 'config.xml';
            if (file_exists($eeModuleConfig)) {
                $appDir = Mage::getBaseDir('app') . DIRECTORY_SEPARATOR;
                if (file_exists($appDir . 'Mage.php') && is_readable($appDir . 'Mage.php')) {
                    $mageAppFileContents = file_get_contents($appDir . 'Mage.php');
                    if (preg_match('~\*\sMagento Commercial Edition~i', $mageAppFileContents)) {
                        $this->_properties['is_professional_mode'] = true;
                        $this->_magentoEdition = 'PE';
                    } else {
                        $this->_properties['is_enterprise_mode'] = true;
                        $this->_magentoEdition = 'EE';
                    }
                }

            }

            $edition = strtolower($this->_magentoEdition);
            if (!in_array($this->_magentoVersion, $this->_supportedMagentoVersions[$edition])) {
                die(
                    'You are running the sysreport tool on Magento ' . $this->_magentoEdition .' ' . $this->_magentoVersion.
                    '. This version of Magento is currently not compatible with the tool.' . "\n"
                );
            }

            if ((version_compare($this->_magentoVersion, '1.11.0.0', '<') && $this->_magentoEdition == 'EE')
                || (version_compare($this->_magentoVersion, '1.11.0.0', '<') && $this->_magentoEdition == 'PE')
                || (version_compare($this->_magentoVersion, '1.6.0.0', '<') && $this->_magentoEdition == 'CE')
            ) {
                $this->_coreResourceModel = Mage::getSingleton('core/resource');
                $this->_readConnection = $this->_coreResourceModel->getConnection('core_read');
            } else {
                $this->_coreResourceModel = Mage::getResourceSingleton('core/resource');
                $this->_readConnection = $this->_coreResourceModel->getReadConnection();
            }
        } catch (Exception $e) {
            $this->_log($e);
        }
    }

    /**
     * Retrieve table name depending on Magento version
     *
     * @param string $name
     *
     * @return string
     */
    protected function _getTableName($name)
    {
        if ((version_compare($this->_magentoVersion, '1.11.0.0', '<') && $this->_magentoEdition == 'EE')
            || (version_compare($this->_magentoVersion, '1.11.0.0', '<') && $this->_magentoEdition == 'PE')
            || (version_compare($this->_magentoVersion, '1.6.0.0', '<') && $this->_magentoEdition == 'CE')
        ) {
            return $this->_coreResourceModel->getTableName($name);
        } else {
            return $this->_coreResourceModel->getTable($name);
        }
    }

    /**
     * Compare method used to sort commands before execution
     *
     * @param mixed $a
     * @param mixed $b
     *
     * @return bool
     */
    public function commandPriorityCompare($a, $b)
    {
        return $a['priority'] > $b['priority'];
    }

    /**
     * Run command(s) with or w/o arguments
     *
     * @return null
     */
    public function run()
    {
        if (!$this->_isToolInitialised) {
            die('System Report tool could not be run because of an error or Magento misconfiguration!' . "\n");
        }

        // If it was requested to get all system information then emulate args for it
        if ($this->getArg($this->_supportedCommands['all']['cmd'])
            || $this->getArg($this->_supportedCommands['all']['alias'])
        ) {
            foreach ($this->_supportedCommands as $key => $info) {
                if (in_array($key, $this->_actionCommandsKeys)) {
                    continue;
                }
                $this->_args[$info['cmd']] = true;
            }
        }
        // If it was requested to get all Magento information except environment data, then emulate args for it
        else if ($this->getArg($this->_supportedCommands['mage']['cmd'])
            || $this->getArg($this->_supportedCommands['mage']['alias'])
        ) {
            foreach ($this->_supportedCommands as $key => $info) {
                if (in_array($key, $this->_actionCommandsKeys) || in_array($key, $this->_environmentRelatedCommands)) {
                    continue;
                }
                $this->_args[$info['cmd']] = true;
            }
        }

        $_inputCommands = array();
        foreach ($this->_args as $key => $val) {
            // If it was requested to collect specific group(s) of data: -all:stores or -all:data etc.
            $_parts = explode(':', $key);
            if (sizeof($_parts) == 2 && $_parts[0] == 'all' && array_key_exists($_parts[1], $this->_commandGroups)) {
                $commands = $this->_getCommandsListByGroup($_parts[1]);
                $_inputCommands = array_merge($_inputCommands, $commands);
            }

            $_inputCommands[] = $key;
        }

        /**
         * If there is no one supported command then show help
         * Perform filtration of input commands to create final list of commands to be run
         */
        $_inputCommands = array_intersect($_inputCommands, (array) $this->_commandsNamesAndAliases);
        $_inputCommands = array_unique($_inputCommands);
        if (sizeof($_inputCommands) == 0) {
            echo $this->usageHelp();
            return null;
        }

        if ($this->getArg($this->_supportedCommands['sendreport']['cmd'])
            || $this->getArg($this->_supportedCommands['sendreport']['alias'])
        ) {
            $jiraTicket = null;
            echo "\n";
            /**
             * It is recommended that you simply use the constants STDIN, STDOUT and STDERR instead
             * of manually opening streams using these wrappers.
             */
            while (!preg_match('~^(?:SUP|SUPEE|MPERF|SF)-[1-9][0-9]*$~', $jiraTicket)) {
                if ($jiraTicket === null){
                    echo 'Enter Related Jira Ticket (e.g. SF-123456789 or SUP-1234 or SUPEE-1234 or MPERF-1234 etc.): ';
                } else {
                    echo 'Ticket number is wrong, re-type it: ';
                }
                $jiraTicket = trim(fgets(STDIN));
            }
            $this->_properties['jira_ticket'] = substr($jiraTicket, 0, 13);
        }

        /**
         * Run requested commands
         */
        $_executedMethods = array();
        foreach ($this->_supportedCommands as $info) {
            if ((in_array($info['cmd'], $_inputCommands) || in_array($info['alias'], $_inputCommands))
                && !empty($info['method']) && !in_array($info['method'], $_executedMethods)
            ) {
                $_commandArguments = array();
                if (!empty($info['arguments']) && is_array($info['arguments'])) {
                    foreach ($info['arguments'] as $argument => $value) {
                        $inputArgument = isset($this->_args[$argument]) ? $this->_args[$argument] : '';
                        if (strlen($inputArgument) > 0) {
                            $_commandArguments[$argument] = !is_null($value) ? $value : $inputArgument;
                        }
                    }
                }

                $result = false;
                $methodTitle = '';
                try {
                    if (!$this->getArg($this->_supportedCommands['cleanreport']['cmd'])
                        && !$this->getArg($this->_supportedCommands['cleanreport']['alias'])
                    ) {
                        $methodTitle = strtolower(preg_replace('/(.)([A-Z])/', "$1 $2", $info['method']));
                        $methodTitle = trim($methodTitle, '_');
                        $this->_log(null, 'Started ' . $methodTitle . '.');
                    }

                    $result = call_user_func(array(__CLASS__, $info['method']), $_commandArguments);
                    if ($result && is_array($result)) {
                        $this->_systemReport[$info['cmd']] = $result;
                    }

                    if (!$this->getArg($this->_supportedCommands['cleanreport']['cmd'])
                        && !$this->getArg($this->_supportedCommands['cleanreport']['alias'])
                    ) {
                        $this->_log(null, 'Finished ' . $methodTitle . '.' . "\n");
                    }
                } catch (Exception $e) {
                    $this->_log($e);
                }

                $_executedMethods[] = $info['method'];
                if ($result) {
                    $this->_succeededCommands[] = $info['cmd'];
                }
            }
        }

        /**
         * Output report data
         */
        if (!$this->getArg($this->_supportedCommands['sendreport']['cmd'])
            && !$this->getArg($this->_supportedCommands['sendreport']['alias'])
            && !$this->getArg($this->_supportedCommands['savereport']['cmd'])
            && !$this->getArg($this->_supportedCommands['savereport']['alias'])
        ) {
            foreach ($this->_systemReport as $reports) {
                foreach ($reports as $title => $info) {
                    try {
                        $table = '';
                        if (isset($info['data']) && isset($info['header'])) {
                            $table = $this->_generateCLITable(
                                $info['data'],
                                $info['header'],
                                self::TABLE_DATA_COLUMN_MAXIMUM_WIDTH
                            );
                        }
                        $count = $this->_getSysReportDataCount($info);
                        $title .=  ': ' . $count;
                        $title = $this->_formatCLIStyle($title, 'white', 'blue', array('bold'));

                        echo "\n" . $title . "\n" . ($table ? $table : 'No data') . "\n";
                    } catch (Exception $e) {
                        $this->_log($e);
                    }
                }
            }
        }
    }

    /**
     * Retrieve successfully executed commands list
     *
     * @return array
     */
    public function getSucceededCommands()
    {
        return $this->_succeededCommands;
    }

    /**
     * Execute cUrl request with specified parameters
     *
     * @param $url
     * @param array $params possible keys:
     * 1. "httpauth" - for HTTP Authentication. Here you must specify data in next format: "user:password"
     * 2. "host_ip" - to request data using IP but with header "Host" to tell web server with multiple websites
     *                where to send request. Here you must specify IP Address in IPv4 format
     * 3. "post" - to send POST request. Here you can specify either values in string format or in array
     * 4. "header" - to send headers
     *
     * @return mixed|null
     * @throws Exception
     */
    public function curlRequest($url, $params = array())
    {
        if (!extension_loaded('curl')) {
            throw new Exception('cUrl must be enabled', __LINE__);
        }

        $curlOptions = array(
            CURLOPT_URL                 => $url,
            CURLOPT_FAILONERROR         => false,
            CURLOPT_AUTOREFERER         => true,
            CURLOPT_FOLLOWLOCATION      => true,
            CURLOPT_HEADER              => false,
            CURLOPT_NOPROGRESS          => true,
            CURLOPT_RETURNTRANSFER      => true,
            CURLOPT_VERBOSE             => false,
            CURLOPT_FAILONERROR         => false,
            CURLOPT_FRESH_CONNECT       => true,
            CURLOPT_CONNECTTIMEOUT      => 10,
            CURLOPT_TIMEOUT             => 120,
            CURLOPT_MAXREDIRS           => 15,
            CURLOPT_SSL_VERIFYPEER      => false,
            CURLOPT_SSL_VERIFYHOST      => false,
            CURLOPT_ENCODING            => '',
            CURLOPT_REFERER             => 'http://google.com/',
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.1.8) Gecko/20100202 Firefox/3.5.8'
        );

        if (!empty($params['post'])) {
            $curlOptions[CURLOPT_POST] = true;
            $curlOptions[CURLOPT_POSTFIELDS] = $this->_preparePostData($params['post']);
        }

        if (!empty($params['httpauth'])) {
            $curlOptions[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
            $curlOptions[CURLOPT_USERPWD] = $params['httpauth'];
        }

        if (!empty($params['header'])) {
            $curlOptions[CURLOPT_HTTPHEADER] = $params['header'];
        }

        /**
         * For services with multiple IP addresses and also to omit using DNS
         *
         * @link http://www.php.net/manual/en/function.curl-setopt.php#102793
         */
        if (!empty($params['host_ip'])) {
            $urlData = $this->_parseUrl($url);
            $url = $urlData['scheme'] . '://' . $params['host_ip'] . '/' . $urlData['path']
                 . (!empty($urlData['query']) ? '?' . $urlData['query'] : '');
            $curlOptions[CURLOPT_HTTPHEADER] = array('Host: '. $urlData['host']);
            $curlOptions[CURLOPT_URL] = $url;
        }

        $results = null;
        $handle = curl_init($url);
        if (is_resource($handle)) {
            curl_setopt_array($handle, $curlOptions);
            $results = curl_exec($handle);
            if ($results === false) {
                $errorCode = curl_errno($handle);
                $error     = curl_error($handle);
                curl_close($handle);
                throw new Exception('cUrl error: [' . $errorCode . '] ' . $error, __LINE__);
            }

            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            $captureCodes = array(
                307 => 'Temporary Redirect',
                400 => 'Bad Request',
                401 => 'Authorization Required',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                406 => 'Not Acceptable',
                407 => 'Proxy Authentication Required',
                408 => 'Request Time-out',
                409 => 'Conflict',
                410 => 'Gone',
                411 => 'Length Required',
                412 => 'Precondition Failed',
                413 => 'Request Entity Too Large',
                414 => 'Request-URI Too Large',
                415 => 'Unsupported Media Type',
                416 => 'Requested range not satisfiable',
                417 => 'Expectation Failed',
                500 => 'Internal Server Error',
                501 => 'Not Implemented',
                502 => 'Bad Gateway',
                503 => 'Service Unavailable',
                504 => 'Gateway Time-out'
            );
            if (isset($captureCodes[$httpCode])) {
                curl_close($handle);
                throw new Exception(sprintf('HTTP Error: %s - %s', $httpCode, $captureCodes[$httpCode]), $httpCode);
            }
            curl_close($handle);
        }
        return $results;
    }

    /**
     * Call API method at remote server with or w/o specified arguments
     *
     * @param $method
     * @param array $arguments
     *
     * @return mixed $result
     * @throws Exception
     */
    public function callApi($method, $arguments = array())
    {
        $result = $this->curlRequest(
            self::REPORT_REMOTE_SERVER_URL,
            array(
                'httpauth'  => $this->_getHttpAuthData(),
                'post'      => array('req' => array($method => $arguments)
            ))
        );

        $result = @unserialize($result);

        if ($result && is_array($result)) {
            $result = $result['result'];
            if (is_array($result) && array_key_exists('error', $result)) {
                throw new Exception('API Error: ' . $result['error']);
            } else {
                return $result;
            }
        } else {
            throw new Exception('API result is invalid.');
        }
    }

    /**
     * Retrieve http auth data
     *
     * @return string
     */
    protected function _getHttpAuthData()
    {
        $a = '';
        eval(base64_decode(
            'JHogPSB0YW4oYXRhbihzcXJ0KDI1KSkpICogc2luKGRlZzJyYWQoMzApKSArIHRhbihkZWcycmFkKDQ1KSkgKyBjb3MoZGVnMnJhZCg2' .
            'MCkpICsgcG93KHRhbihhdGFuKDcpKSwgMik7CiRiID0gKCR6ICAtIHBvdygxNiwgc2luKGRlZzJyYWQoMzApKSkgKiBsb2cxMCgxMDAp' .
            'ICogNCkgLyAzICogMiAtIDE7CiRjID0gMDsgJGUgPSBhcnJheSgpOyBkbyB7JGVbXSA9IHBvdygoJGMgKyAyKSAqIDMsIDUpOyAkYysr' .
            'OyB9IHdoaWxlICgkYyA8ICRiIC0gMSk7CiRuID0gJzk4Nzc3Njk3NTkwNDkxMTUyNDg4MzIxMDE3NTkzNzU1NDE4ODk1Njg1MjQwODQx' .
            'MDE5NTc5NjI2MjQxMDAxNDM0ODkwNzEwMTI0MzAwMDAwOTkzOTEzNTM5MzExMTYwNDY2MTc2MTAwJzsKJG4gLj0gJzkwMjI0MTk5MTAx' .
            'MTMwNjkxMjMyJzsgJG4gPSBleHBsb2RlKCcgJywgdHJpbShzdHJfcmVwbGFjZSgkZSwgJyAnLCAkbikpKTsgJGsgPSAnJzsKZm9yZWFj' .
            'aCAoJG4gYXMgJGcpIHskayAuPSBjaHIoJGcpO30gJGEgPSBzZWxmOjpSRVBPUlRfUkVNT1RFX1NFUlZFUl9IVFRQX0FVVEhfQ1JFREVO' .
            'VElBTFM7CiRiID0gcG93KCh0YW4oZGVnMnJhZCg0NSkpICsgY29zKGRlZzJyYWQoNjApKSkgKiA4ICsgcG93KHRhbihhdGFuKDIpKSwg' .
            'MiksIHNpbihkZWcycmFkKDMwKSkpOyAkYyA9IDA7CmRvIHskYysrOyAkYSA9ICRrKCRhKTt9IHdoaWxlICgkYyA8ICRiKTs='
        ));
        return $a;
    }

    /**
     * Retrieve argument value by name or false
     *
     * @param string $name the argument name
     * @return mixed
     */
    public function getArg($name)
    {
        if (isset($this->_args[$name])) {
            return $this->_args[$name];
        }
        return false;
    }

    /**
     * Generate sysreport tool usage help
     *
     * @return string
     */
    public function usageHelp()
    {
        $_usageInfo = '';
        $maxCmdLength = 0;
        foreach ($this->_supportedCommands as $info) {
            // Calculating maximum "alias plus command" string length for usage help output formatting
            $_cmdLength = strlen('-' . $info['alias'] . ', -' . $info['cmd']);
            if ($_cmdLength > $maxCmdLength) {
                $maxCmdLength = $_cmdLength;
            }
        }
        uasort($this->_commandGroups, array(__CLASS__, 'commandPriorityCompare'));
        ksort($this->_supportedCommands);
        foreach ($this->_supportedCommands as $info) {
            if (empty($info['group'])) {
                $info['group'] = 'other';
            }
            if (!isset($this->_commandGroups[$info['group']]['usage_text'])) {
                $title = ' ' . $this->_commandGroups[$info['group']]['title'];
                $title = $info['group'] != 'other' ? $title . ' [-all:' . $info['group'] . ']' : $title;
                $title = $this->_formatCLIStyle($title, 'white', 'blue', array('bold'));
                $this->_commandGroups[$info['group']]['usage_text'] = $title . "\n";
            }
            $_option = '-' . $info['alias'] . ', -' . $info['cmd'];
            $_cmdLength = strlen($_option);
            $_option = $this->_formatCLIStyle($_option, 'white', null, array('bold'));
            $_option .= str_repeat(' ', $maxCmdLength + 4 - $_cmdLength);
            $_option .= str_replace("\t\t", str_repeat(' ', $maxCmdLength + 6), $info['description']) . "\n";
            $this->_commandGroups[$info['group']]['usage_text'] .= '  ' . $_option;
        }
        foreach ($this->_commandGroups as $groupData) {
            $_usageInfo .= $groupData['usage_text'] . "\n";
        }

        return <<<USAGE
=====================================================================
Usage: php sysreport.php [MAGENTO_ROOT_PATH] [OPTIONS]
If the sysreport tool is outside of Magento root directory you have to specify the location of the tool
=====================================================================

$_usageInfo
USAGE;
    }

    /**
     * Getter for sysreport tool version
     *
     * @return string
     */
    public function getVersion()
    {
        return self::REPORT_CLASS_VERSION;
    }

    /**
     * Send generated system report to remote server
     *
     * @param array $arguments
     */
    protected function _sendReport(array $arguments = array())
    {
        $url = 'unknown';
        if ($this->_readConnection) {
            $url = Mage::getStoreConfig('web/secure/base_url', Mage_Core_Model_App::ADMIN_STORE_ID);
        }
        $urlInfo = $this->_parseUrl($url);
        $host = null;
        if ($urlInfo) {
            if (isset($urlInfo['host'])) {
                $host = $urlInfo['host'];
            }
            if (isset($urlInfo['ip'])) {
                $host = $urlInfo['ip'];
            }
        }
        $host = $host !== null ? $host : 'N/A';

        $reportByGroups = $this->_getSystemReportByGroups();
        $systemReport = array();

        foreach ($reportByGroups as $groups) {
            if (empty($groups['reports'])) {
                continue;
            }
            $systemReport[$groups['title']] = $groups['reports'];
        }

        array_walk_recursive($systemReport, function (&$item) {
            $item = preg_replace('~\\033\[[^m]+m~', '', $item);
        });
        $result = $this->callApi('transfer_report', array(
            'report_data'       => serialize($systemReport),
            'report_version'    => self::REPORT_CLASS_VERSION,
            'report_flags'      => '-' . implode(' -', array_keys((array) $this->_args)),
            'magento_version'   => $this->_magentoEdition . ' ' . $this->_magentoVersion,
            'client_host'       => $host,
            'server_time'       => date('r'),
            'jira_ticket'       => isset($this->_properties['jira_ticket']) ? $this->_properties['jira_ticket'] : ''
        ));

        $message = 'Report was successfully saved on remote server.' . "\n"
                 . 'Direct link is: ' . substr(self::REPORT_REMOTE_SERVER_URL, 0, -4) . 'r' . (int) $result;

        $this->_log(null, $message);
    }

    /**
     * Save report into HTML file
     */
    protected function _saveReportIntoHtml(array $arguments = array())
    {
        $path = $this->_getWorkingPath();
        if (!is_writeable($path)) {
            throw new Exception('Can\'t write to directory where sysreport tool resides. Sysreport HTML will not be generated.');
        }
        if (!is_array($this->_systemReport) || empty($this->_systemReport)) {
            throw new Exception('System report data is empty. Report was\'t not saved.');
        }

        $menu = $this->_generateReportHtmlMenu();
        $body = '';
        $counter = 1;
        foreach ($this->_systemReport as $reports) {
            foreach ($reports as $title => $data) {
                try {
                    $info = array();
                    if (isset($data['data']) && isset($data['header'])) {
                        $info = $this->_prepareTableData($data['data'], $data['header']);
                    }
                    $report = $header = '';
                    $dataNumber = $this->_getSysReportDataCount($data);

                    if (is_array($info) && !empty($info) && !empty($info['header']) && !empty($info['data'])) {
                        array_walk_recursive($info, function (&$item) {
                            $item = preg_replace('~\\033\[[^m]+m~', '', $item);
                        });
                        $_preparedHeader = $info['header'];
                        $_preparedData = $info['data'];
                        if (!empty($_preparedHeader)) {
                            foreach ($_preparedHeader as $text) {
                                $text = $this->_replaceLeadingSpacesWithNoneBreakSpaces(htmlspecialchars($text));
                                $header .= '<th>'
                                        . str_replace(array("\n", "\r"), '<br />', $text)
                                        . '</th>';
                            }
                        }

                        if (!empty($_preparedData)) {
                            foreach ($_preparedData as $rowId => $row) {
                                $report .= '<tr>';
                                foreach ($row as $columnId => $text) {
                                    $report .= $this->_getTableCellHtml($text, md5($title . $rowId . $columnId));
                                }
                                $report .= '</tr>';
                            }
                        }
                    } else {
                        $report .= '<tr><td>No data</td></tr>';
                    }


                    $anchor = md5($title);
                    $title = htmlspecialchars($title);
                    $expandFlag = $dataNumber > self::REPORT_HTML_NONE_COLLAPSIBLE_TABLE_DATA_COUNT;
                    $body .= "
    <a name=\"$anchor\">&nbsp;</a><br />

    <h2>$counter. $title: ($dataNumber)"
        . ($expandFlag ? ' | <a href="javascript:void(0);" onclick="toggleTableData(this, \'table_'
        . $anchor
        . '\')">Expand</a>' : '')
        . "</h2>
    <div id=\"table_$anchor\" style=\"" . ($expandFlag ? 'display: none' : '') . "\">
        <table class=\"report-table\">
            <thead>
                <tr>
                    $header
                </tr>
            </thead>
            <tbody>
                $report
            </tbody>
        </table>
    </div>
";
                    $counter++;
                } catch (Exception $e) {
                    $this->_log($e);
                }
            }
        }

        $url = 'unknown';
        if ($this->_readConnection) {
            $url = Mage::getStoreConfig('web/secure/base_url', Mage_Core_Model_App::ADMIN_STORE_ID);
        }
        $urlInfo = $this->_parseUrl($url);
        $host = null;
        if ($urlInfo) {
            if (isset($urlInfo['host'])) {
                $host = $urlInfo['host'];
            }
            if (isset($urlInfo['ip'])) {
                $host = $urlInfo['ip'];
            }
        }
        $hostForTitle = $host !== null ? $host : 'N/A';
        $hostForTitle = htmlspecialchars($hostForTitle);
        $reportVersion = self::REPORT_CLASS_VERSION;
        $dateCreated = date('Y-m-d H:i:s');
        $title = '<h1 id="report-title">Magento System Report for <span>'
            . $hostForTitle . '</span> on ' . $dateCreated . '</h1>';

        $html = <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Magento System Report for $hostForTitle</title>
    <style type="text/css">
        html {
            width: 100%;
        }
        body {
            line-height:1.0em;
            font: 13px/1.5em Arial,Helvetica,sans-serif;
        }
        table {
            border-collapse: collapse;
            margin:0;
            padding:0
        }
        th {
            text-align: center;
            font-weight: bold;
            padding: 10px;
            border: 1px solid black;
            background-color: #7eadd9
            }
        td {
            text-align: left;
            padding: 7px;
            border: 1px solid black;
            vertical-align: top
        }
        h1, h2, h3, h4 {
            margin-bottom:.5em; line-height:1.4em;
        }
        h2 {
            font-size:1.7em
        }
        h3 {
            margin-bottom:.5em; color:#253033; font-size:1.25em
        }
        h4 {
            margin-bottom:.6em; color:#494848; font-size:1.05em
        }
        h5 {
            font-size:1.05em
        }
        h6 {
            font-size:1em
        }
        h1 a, h1 a:hover,
        h2 a, h2 a:hover,
        h3 a, h3 a:hover,
        h4 a, h4 a:hover {
            font-weight:normal
        }
        .flag-yes, .flag-processing, .flag-no {
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            color: #FFFFFF
        }
        .flag-yes {
            background-color: #00BD39;
        }
        .flag-processing {
            background-color: #FF9640;
        }
        .flag-no {
            background-color: #FF4040;
        }
        .selected-row, tr:hover td {
            background-color: #FFE7A1;
            color: #000000
        }
        .report-cell-text {
            display: none;
        }
        a, a:visited {
            color: #0044ff
        }
        a:hover {
            background-color: #FFE7A1;
            color: #000000
        }
        h1#report-title span {
            color: #205791
        }
        div#copyright {
            width: 100%;
            text-align: left;
            color: #6a6a6a;
            font-size: 80%
        }
        .file-path {
            color: #6a6a6a;
            font-size: 80%
        }
        .diff-negative {
            color: #FF4040;
            font-weight: bold;
        }
        .diff-positive {
            color: #00BD39;
            font-weight: bold;
        }

        /* Menu */
        #report-menu:after {
            clear: both;
            content: ".";
            display: block;
            font-size: 0;
            height: 0;
            line-height: 0;
            overflow: hidden;
        }
        #report-menu {
            background: none repeat scroll 0 0 #97c8f5;
            left: 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 100;
            border-bottom: 1px solid #6a6a6a
        }
        #report-menu ul {
            margin: 0;
            padding:0;
            list-style:none;
        }
        #report-menu ul li  {
            margin:0;
            position:relative;
            height:30px;
            line-height:30px;
        }
        #report-menu ul li a, span.report-menu-root-item {
            display: block;
            text-decoration: none;
            color: #000;
            padding: 0 5px;
            border-left: none;
            background-color: #97c8f5;
        }
        span.report-menu-root-item:hover {
            cursor: pointer;
            background-color: #FFE7A1;
        }
        #report-menu > ul > li {
            float: left;
            height: 30px;
            line-height: 30px;
        }
        #report-menu li > ul {
            visibility:hidden;
            width: 280px;
            position: absolute;
            top:0;
            border-left: 1px solid #000;
        }
        #report-menu > ul > li > ul {
            top:30px;
            left:0;
        }
        #report-menu a:hover{
            background-color: #FFE7A1;
        }
        #report-menu li:hover > ul {
            visibility:visible;
        }
        * html #report-menu ul li { float: left; height: 1%; }
        * html #report-menu ul li a { height: 1%; }
        a.no-data-link {
            color: #597aa3 !important;
        }
        a#top-link {
            background-color: #FFE7A1 !important;
            font-weight: bold !important
        }
    </style>
    <script type="text/javascript">
        function toggleTableData(element, tableId)
        {
            var tableDiv = document.getElementById(tableId);
            if (tableDiv.style.display == 'none') {
                tableDiv.style.display = 'block';
                element.firstChild.data = 'Collapse';
            } else {
                tableDiv.style.display = 'none';
                element.firstChild.data = 'Expand';
            }
        }

        function showFullText(cellId)
        {
            var cellObject = document.getElementById(cellId);
            var fullTextDiv = cellObject.getElementsByClassName('report-cell-text')[0];
            cellObject.innerHTML = fullTextDiv.innerHTML;

        }

        window.onload = function()
        {
            var rows = document.getElementsByTagName('tr');
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                row.onclick = function () {
                    var tds = this.childNodes;
                    for (var j = 0; j < tds.length; j++) {
                        if (tds[j].className.indexOf('selected-row') !=-1) {
                            tds[j].className = tds[j].className.replace('selected-row', '');
                        } else {
                            tds[j].className += ' selected-row';
                        }
                    }
                }
            }
        }
    </script>
</head>
<body>
    <a name="top">&nbsp;</a><br />
    $menu
    $title
    $body
    <br />
    <br />
    <div id="copyright">v$reportVersion</div>
</body>
</html>
HTML;
        $filename = 'sysreport-' . date('Y-m-d_H-i-s') . ($host !== null ? '_' . $urlInfo['host'] : '') . '.html';
        $writtenBytes = file_put_contents($path . $filename, $html);
        $this->_log(null,
            ($writtenBytes !== false
            ? 'File "' . $filename . '" was successfully generated.'
            : 'File "' . $filename . '" wasn\'t generated!')
        );

        return $writtenBytes !== false;
    }

    /**
     * Retrieve system report data count
     *
     * @param array $data
     * @return int
     */
    protected function _getSysReportDataCount($data)
    {
        $dataNumber = 0;
        if (isset($data['data']) && is_array($data['data'])) {
            if (isset($data['count']) && $data['count'] > 0) {
                $dataNumber = $data['count'];
            } else {
                $dataNumber = sizeof($data['data']);
            }
        }

        return $dataNumber;
    }

    /**
     * Generate report HTML horizontal drop-down menu
     *
     * @return string
     */
    protected function _generateReportHtmlMenu()
    {
        $html = '<div id="report-menu"><ul>';
        $reportByGroups = $this->_getSystemReportByGroups();

        foreach ($reportByGroups as $group) {
            if (empty($group['reports'])) {
                continue;
            }
            $group['title'] = preg_replace('~\\033\[[^m]+m~', '', $group['title']);
            $html .= '<li><span class="report-menu-root-item">' . htmlspecialchars($group['title']) . '</span><ul>';

            foreach ($group['reports'] as $title => $data) {
                $title = preg_replace('~\\033\[[^m]+m~', '', $title);
                $dataNumber = $this->_getSysReportDataCount($data);

                $html .= '<li><a href="#' . md5($title) . '"' . (!$dataNumber ? ' class="no-data-link"' : '') . '>'
                    . htmlspecialchars($title)
                    . ($dataNumber > 0 ? ' (' . $dataNumber . ')' : ' (no data)')
                    . '</a></li>';
            }

            $html .= '</ul></li>';
        }

        $html .= '<li><a href="#top" id="top-link">Top</a></li>';
        $html .= '</ul></div>';

        return $html;
    }

    /**
     * Retrieve commands list by specified group
     *
     * @param string $group
     *
     * @return array
     */
    protected function _getCommandsListByGroup($group)
    {
        $commands = array();
        if (!array_key_exists($group, $this->_commandGroups)) {
            return $commands;
        }
        foreach ($this->_supportedCommands as $data) {
            if (isset($data['group']) && $data['group'] == $group) {
                $commands[] = $data['cmd'];
            }
        }

        return $commands;
    }

    /**
     * Retrieve system report results collected by by belonging groups
     *
     * @return array
     */
    protected function _getSystemReportByGroups()
    {
        if ($this->_systemReportByGroups !== null) {
            return $this->_systemReportByGroups;
        }
        $this->_systemReportByGroups = $this->_commandGroups;

        foreach ($this->_systemReport as $cmd => $reports) {
            if (!empty($this->_supportedCommands[$cmd]['group'])
                && !empty($this->_systemReportByGroups[$this->_supportedCommands[$cmd]['group']])
            ) {
                $group = $this->_supportedCommands[$cmd]['group'];
                if (!isset($this->_systemReportByGroups[$group]['reports'])) {
                    $this->_systemReportByGroups[$group]['reports'] = array();
                }
                $this->_systemReportByGroups[$group]['reports'] =
                    array_merge($this->_systemReportByGroups[$group]['reports'], $reports);
            } else {
                $group = 'other';
                if (!isset($this->_systemReportByGroups[$group]['reports'])) {
                    $this->_systemReportByGroups[$group]['reports'] = array();
                }
                $this->_systemReportByGroups[$group]['reports'] =
                    array_merge($this->_systemReportByGroups[$group]['reports'], $reports);
            }
        }

        uasort($this->_systemReportByGroups, array(__CLASS__, 'commandPriorityCompare'));

        return $this->_systemReportByGroups;
    }

    /**
     * Get table cell css class depending on its specific value
     * Used in HTML format report
     *
     * @param mixed $value
     *
     * @return null|string
     */
    protected function _getCellClassByValue($value)
    {
        $yesValues = array('Yes', 'Enabled', 'Ready', 'Exists', 'success');
        $processValues = array('Processing', 'Invalidated', 'running', 'pending', 'Scheduled');
        $noValues = array('No', 'Disabled', 'Reindex Required', 'Missing', 'error');

        $class = null;
        if ((in_array($value, $yesValues) && !empty($value))) {
            $class = 'flag-yes';
        } else if (in_array($value, $processValues) && !empty($value)) {
            $class = 'flag-processing';
        } else if (in_array($value, $noValues) && !empty($value)) {
            $class = 'flag-no';
        }

        return $class;
    }

    /**
     * Replace the leading spaces with &nbsp; with same number of times
     *
     * @param string $text
     *
     * @return string
     */
    protected function _replaceLeadingSpacesWithNoneBreakSpaces($text)
    {
        $originalLength = strlen($text);
        $text = ltrim($text, ' ');
        $newLength = strlen($text);
        return str_repeat('&nbsp;', $originalLength - $newLength) . $text;
    }

    /**
     * Replace special {text} constructions with styled HTML
     *
     * @param $string
     *
     * @return string|bool
     */
    protected function _prepareHtmlForFilePathStrings($string)
    {
        $string = preg_replace('~\{\{([^}]+)\}\}~is', '[[[\1]]]', $string);
        $string = preg_replace('~\{([^{}]+)\}~is', '<span class="file-path">\1</span>', $string);
        return preg_replace('~\[\[\[([^]]+)\]\]\]~is', '{{\1}}', $string);
    }

    /**
     * Replace special (diff: +-<digit>) constructions with styled HTML
     *
     * @param $string
     *
     * @return string|bool
     */
    protected function _prepareHtmlForDiffStrings($string)
    {
        $string = preg_replace('~\(diff: (\-[^\)]+)\)~is', '<span class="diff-negative">(\1)</span>', $string);
        return preg_replace('~\(diff: (\+[^\)]+)\)~is', '<span class="diff-positive">(\1)</span>', $string);
    }

    /**
     * Normalize, format and construct HTML table cell text
     *
     * @param string $rawText
     * @param string $cellId
     *
     * @return string
     */
    protected function _getTableCellHtml($rawText, $cellId)
    {
        $class = $this->_getCellClassByValue($rawText);
        $text = htmlspecialchars($rawText);
        $text = $this->_replaceLeadingSpacesWithNoneBreakSpaces($text);
        $text = $this->_prepareHtmlForFilePathStrings($text);
        $text = $this->_prepareHtmlForDiffStrings($text);
        $text = str_replace(array("\n", "\r"), '<br />', $text);

        $maxLength = self::REPORT_HTML_NONE_COLLAPSIBLE_TABLE_CELL_STRING_LENGTH;
        $isTextLengthMustBeCut = strlen($rawText) > $maxLength;
        if ($isTextLengthMustBeCut) {
            $fullText = substr($text, 0, $maxLength);
            $fullText .= '<a href="javascript:void(0)" onclick="showFullText(\'cell_' . $cellId . '\')"> ... More</a>';
            $fullText .='<div class="report-cell-text">' . $text . '</div>';

            $text = $fullText;
        }

        $html = '<td'. ($isTextLengthMustBeCut ? ' id="cell_' . $cellId . '"' : '') .
            ($class !== null ? ' class="' . $class . '"' : '') .'>'. $text . '</td>';

        return $html;
    }

    /**
     * Generate CLI formatted table (like MySQL)
     *
     * @param array $data
     * @param array $header
     * @param int $maxColWidth
     * @param bool $addNumberColumn
     *
     * @return bool|null|string
     */
    protected function _generateCLITable(array $data, $header = array(),
                                         $maxColWidth = self::TABLE_DATA_COLUMN_MAXIMUM_WIDTH, $addNumberColumn = false
    ) {
        $colNum = 0;
        $table = '';
        // Notice: here while creating output table data appended and prepended space are
        // not took into account when calculating column sizes

        $info = $this->_prepareTableData($data, $header, $maxColWidth, $addNumberColumn);
        if (!is_array($info) || empty($info)) {
            return false;
        }

        $_columnSizes = $info['column_sizes'];
        $_preparedHeader = $info['header'];
        $_preparedData = $info['data'];

        /**
         * Generating CLI table data
         */
        foreach ($_columnSizes as $size) {
            $table .= '+' . str_repeat('-', $size + 2);
        }
        $table .= '+' . "\n";

        foreach ($_preparedHeader as $_column) {
            $colSize = strlen(preg_replace('~\\033\[[^m]+m~', '', $_column));
            if ($colSize > $_columnSizes[$colNum]) {
                $_column = substr($_column, 0, $_columnSizes[$colNum] - 3) . '...';
            } else {
                $_column = $_column . str_repeat(' ', $_columnSizes[$colNum] - $colSize);
            }
            $table .= '| ' . $_column . ' ';
            $colNum++;
        }

        if (!empty($_preparedHeader)) {
            $table .= '|' . "\n";

            foreach ($_columnSizes as $size) {
                $table .= '+' . str_repeat('-', $size + 2);
            }
            $table .= '+' . "\n";
        }

        $splitColumnData = array();
        foreach ($_preparedData as $row) {
            $colNum = 0;
            foreach ($row as $_column) {
                $_splitColumnData = $this->_strSplit($_column, self::TABLE_DATA_COLUMN_MAXIMUM_WIDTH);
                if (sizeof($_splitColumnData) > 1) {
                    $splitColumnData[$colNum] = $_splitColumnData;
                }
                $_column = $_splitColumnData[0];
                $colSize = strlen(preg_replace('~\\033\[[^m]+m~', '', $_column));
                $_column = $_column . str_repeat(' ', $_columnSizes[$colNum] - $colSize);

                $table .= '| ' . $_column . ' ';
                $colNum++;
            }
            $table .= '|' . "\n";

            // Multiply rows to display correctly multi line column data
            if (!is_array($splitColumnData) || empty($splitColumnData)) {
                continue;
            }
            // Determine maximum rows that must be output in multi line mode
            $maxMultiLineRows = 0;
            foreach ($splitColumnData as $columnMultiLineData) {
                $multiLineDataSize = sizeof($columnMultiLineData);
                if ($multiLineDataSize > $maxMultiLineRows) {
                    $maxMultiLineRows = $multiLineDataSize;
                }
            }
            // Multiply rows with relative multi line data
            for ($i = 1; $i < $maxMultiLineRows; $i++) {
                foreach ($_columnSizes as $index => $size) {
                    if (isset($splitColumnData[$index][$i])) {
                        $table .= '| ' . $splitColumnData[$index][$i]
                            . str_repeat(
                                ' ',
                                $size - strlen(
                                    preg_replace('~\\033\[[^m]+m~', '', $splitColumnData[$index][$i])
                                )
                            ) . ' ';
                    } else {
                        $table .= '|' . str_repeat(' ', $size + 2);
                    }
                }
                $table .= '|' . "\n";
            }
            $splitColumnData = array();
        }
        foreach ($_columnSizes as $size) {
            $table .= '+' . str_repeat('-', $size + 2);
        }
        $table .= '+' . "\n";

        return $table;
    }

    /**
     * Split the given string by a regular expression and by length
     *
     * @param string $string
     * @param int $splitLength
     * @param string $splitDelimiter
     *
     * @return array
     */
    protected function _strSplit($string, $splitLength, $splitDelimiter = "\n\r")
    {
        $results = array();
        $_pregSplit = preg_split("~[" . preg_quote($splitDelimiter, '~') . "]+~", $string);
        foreach ($_pregSplit as $_line) {
            $_strSplit = str_split($_line, $splitLength);
            $results = array_merge($results, $_strSplit);
        }

        return $results;
    }

    /**
     * Prepare system report data for output in CLI or HTML format
     *
     * @param array $data
     * @param array $header
     * @param int $maxColWidth
     * @param bool $addNumberColumn
     *
     * @return array|null
     * @throws Exception
     */
    protected function _prepareTableData(array $data, $header = array(),
                                         $maxColWidth = self::TABLE_DATA_COLUMN_MAXIMUM_WIDTH, $addNumberColumn = false
    ) {
        if (empty($data)) {
            return null;
        }

        $_columnSizes = $_preparedHeader = array();
        $_maxColNum = $colNum = 0;

        /**
         * Prepare header if applicable
         * Save header columns sizes
         */
        if (!empty($header)) {
            if ($addNumberColumn) {
                array_unshift($header, '#');
            }

            foreach ($header as $key => $column) {
                $colNum++;
                $_trimmedColumn = trim($column);

                // If maximum column number limit reached then set last column as "And more..." string
                if ($colNum == self::TABLE_DATA_COLUMN_MAXIMUM_COUNT_FOR_OUTPUT + 1) {
                    $column = 'And more...';
                }
                // If column data is not empty string then use it as column title
                // Otherwise "Column N" string will be used as title
                elseif (!is_null($column) && !is_bool($column) && empty($_trimmedColumn)) {
                    $column = 'Column ' . $colNum;
                }

                $column = $this->_prepareColumnData($column);
                // Set initial column sizes
                $_columnSizes[$colNum - 1] = strlen(preg_replace('~\\033\[[^m]+m~', '', $column));
                $_preparedHeader[$key] = $column;

                // If maximum column number limit reached then stop further row data processing
                if ($colNum == self::TABLE_DATA_COLUMN_MAXIMUM_COUNT_FOR_OUTPUT + 1) {
                    break;
                }
            }
            $_maxColNum = $colNum;
        }

        $_preparedData = array();
        $dataRowsCount = sizeof($data);
        $detectedSingleRowDataMode = $detectedNormalRowDataMode = $detectedMaximumDataLimit = false;
        $singleRowColNum = 0;

        /**
         * Data validation and preparation
         * Collect column data sizes
         */
        for ($rowIndex = 0; $rowIndex < $dataRowsCount; $rowIndex++) {
            $origRow = $data[$rowIndex];
            // If requested add column with row number. Applicable only for multi row mode
            if ($addNumberColumn && is_array($origRow)) {
                array_unshift($origRow, $rowIndex + 1);
            }
            $newRow = array();
            $colNum = 0;

            if (is_array($origRow)) {
                if ($detectedSingleRowDataMode === true) {
                    throw new Exception('Detected Single Row Mode but data is corrupted.');
                }

                $detectedNormalRowDataMode = true;

                // If maximum row count limit reached then set last row as "And more..." string
                if ($rowIndex == self::TABLE_DATA_ROW_MAXIMUM_COUNT_FOR_OUTPUT) {
                    $origRow = array('And more...');
                    $detectedMaximumDataLimit = true;
                }

                foreach ($origRow as $key => $column) {
                    $colNum++;
                    // If maximum column number limit reached then set last column as "And more..." string
                    if ($colNum == self::TABLE_DATA_COLUMN_MAXIMUM_COUNT_FOR_OUTPUT + 1) {
                        $column = 'And more...';
                    }

                    $column = $this->_prepareColumnData($column);
                    $newRow[$key] = $column;

                    // Detect maximum column data length, take into account multi line columns that will be split
                    $length = 0;
                    $_pregSplit = preg_split("~[\n\r]+~", $column);
                    foreach ($_pregSplit as $_line) {
                        $_length = strlen(preg_replace('~\\033\[[^m]+m~', '', $_line));
                        if ($_length > $length) {
                            $length = $_length;
                        }
                    }

                    if ((isset($_columnSizes[$colNum - 1]) && $_columnSizes[$colNum - 1] < $length)
                        || !isset($_columnSizes[$colNum - 1])
                    ) {
                        $_columnSizes[$colNum - 1] = $length;
                    }

                    // If maximum column number limit reached then stop further row data processing
                    if ($colNum == self::TABLE_DATA_COLUMN_MAXIMUM_COUNT_FOR_OUTPUT + 1) {
                        break;
                    }
                }

                // Detect maximum column number in one row
                if ($colNum > $_maxColNum) {
                    $_maxColNum = $colNum;
                }
                $_preparedData[$rowIndex] = $newRow;

            } else {
                if ($detectedNormalRowDataMode) {
                    throw new Exception('Detected Single Row Mode but data is corrupted.');
                }

                $detectedSingleRowDataMode = true;

                if ($rowIndex == self::TABLE_DATA_COLUMN_MAXIMUM_COUNT_FOR_OUTPUT) {
                    $origRow = 'And more...';
                    $detectedMaximumDataLimit = true;
                }

                $column =  $this->_prepareColumnData($origRow);
                $_preparedData[0][] = $column;

                // Detect maximum column data length
                $length = strlen($column);
                if ((isset($_columnSizes[$singleRowColNum]) && $_columnSizes[$singleRowColNum] < $length)
                    || !isset($_columnSizes[$singleRowColNum])
                ) {
                    $_columnSizes[$singleRowColNum] = $length;
                }

                $singleRowColNum++;
            }

            // If maximum row count limit reached then stop further data processing
            if ($detectedMaximumDataLimit) {
                break;
            }
        }

        // If data was retrieved as single row then make sure that maximum column number detection has last update
        if ($singleRowColNum > $_maxColNum) {
            $_maxColNum = $singleRowColNum;
        }

        /**
         * Header array normalization
         * Save maximum column sizes
         */
        if (!empty($_preparedHeader)) {
            $_headerColCount = sizeof($_preparedHeader);
            $colNum = $_headerColCount + 1;
            if ($_headerColCount < $_maxColNum) {
                for ($counter = 0; $counter < ($_maxColNum - $_headerColCount); $counter++) {
                    $_column = $this->_prepareColumnData('Column ' . $colNum);
                    $_preparedHeader[] = $_column;
                    $length = strlen(preg_replace('~\\033\[[^m]+m~', '', $_column));
                    if ((isset($_columnSizes[$colNum - 1]) && $_columnSizes[$colNum - 1] < $length)
                        || !isset($_columnSizes[$colNum - 1])
                    ) {
                        $_columnSizes[$colNum - 1] = $length;
                    }
                    $colNum++;
                }
            }
        }

        /**
         * Data array normalization
         * Save maximum column sizes
         */
        if ($detectedSingleRowDataMode) {
            if ($singleRowColNum < $_maxColNum) {
                $colNum = $singleRowColNum + 1;
                for ($counter = 0; $counter < ($_maxColNum - $singleRowColNum); $counter++) {
                    $_column = $this->_prepareColumnData('');
                    $_preparedData[0][] = $_column;

                    // Only if header wasn't normalized/specified
                    if (empty($_preparedHeader)) {
                        $_columnSizes[$colNum - 1] = strlen(preg_replace('~\\033\[[^m]+m~', '', $_column));
                    }

                    $colNum++;
                }
            }
        } else {
            foreach ($_preparedData as &$row) {
                $_dataRowColCount = sizeof($row);
                $colNum = $_dataRowColCount + 1;
                if ($_dataRowColCount < $_maxColNum) {
                    for ($counter = 0; $counter < ($_maxColNum - $_dataRowColCount); $counter++) {
                        $_column = $this->_prepareColumnData('');
                        $row[] = $_column;

                        // Only if header wasn't normalized/specified
                        if (empty($_preparedHeader)) {
                            // Detect maximum column data length
                            $length = strlen(preg_replace('~\\033\[[^m]+m~', '', $_column));
                            if (isset($_columnSizes[$colNum - 1]) && $_columnSizes[$colNum - 1] < $length) {
                                $_columnSizes[$colNum - 1] = $length;
                            }
                        }

                        $colNum++;
                    }
                }
            }
        }
        unset($row);

        /*
         * Normalization of maximum column sizes
         */
        if (!empty($maxColWidth) && $maxColWidth > 0) {
            $maxColWidth = $maxColWidth < self::TABLE_DATA_COLUMN_MINIMUM_WIDTH
                ? self::TABLE_DATA_COLUMN_MINIMUM_WIDTH
                : $maxColWidth;
        }

        foreach ($_columnSizes as &$size) {
            if ((!empty($maxColWidth) && $maxColWidth > 0)) {
                if ($size > $maxColWidth) {
                    $size = $maxColWidth;
                }
            }
        }

        return array('column_sizes' => $_columnSizes, 'header' => $_preparedHeader, 'data' => $_preparedData);
    }

    /**
     * Convert table column data into readable format
     *
     * @param mixed $data
     *
     * @return string
     */
    protected function _prepareColumnData($data)
    {
        if (is_null($data)) {
            $data = 'null';
        } elseif (is_bool($data)) {
            $data = $data ? 'true' : 'false';
        } elseif (is_object($data)) {
            $data = 'Object ' . get_class($data);
        } elseif (is_array($data)) {
            $data = 'array(' . sizeof($data) . ')';
        } elseif (is_numeric($data)) {
            // as is
        } elseif (is_string($data)) {
            if (empty($data)) {
                $data = '';
            }
        } else {
            $data = (string)$data;
        }

        $cellClass = $this->_getCellClassByValue($data);
        $textColor = null;
        switch ($cellClass) {
            case 'flag-yes':
                $textColor = 'green';
                break;
            case 'flag-no':
                $textColor = 'red';
                break;
            case 'flag-processing':
                $textColor = 'yellow';
                break;
            default:
                break;
        }
        if ($textColor !== null) {
            $data = $this->_formatCLIStyle($data, $textColor, null, array('bold'));
        }
        return $data;
    }

    /**
     * Clean files generated by sysreport tool
     *
     * @param array $arguments
     */
    protected function _cleanReportFiles(array $arguments = array())
    {
        $path = $this->_getWorkingPath();
        $removedFiles = array();

        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != '.' && $entry != '..' && $entry != basename(__FILE__)
                    && (in_array(pathinfo($entry, PATHINFO_EXTENSION), array('local', 'ref', 'wiki'))
                        || strpos($entry, 'sysreport-') === 0
                        || $entry == self::REPORT_MERGED_CONFIG_FILE_NAME
                    )
                    && is_file($path . $entry)
                ) {
                    if (unlink($path . $entry)) {
                        $removedFiles[] = $entry;
                    }
                }
            }
            closedir($handle);
        }

        $logDir = Mage::getBaseDir('log') . DIRECTORY_SEPARATOR;
        if (file_exists($logDir . self::REPORT_LOG_FILE) && unlink($logDir . self::REPORT_LOG_FILE)) {
            $removedFiles[] = self::REPORT_LOG_FILE;
        }
        $count = sizeof($removedFiles);

        echo "\n" . ($count
            ? 'Removed ' . $count . ' files: ' . "\n" . print_r($removedFiles, true)
            : 'No one file was removed!');

        if (isset($arguments['f'])) {
            echo "\n" . 'System Report tool itself ' .
                (unlink(__FILE__) ? 'was removed as well.' : 'wsn\'t removed!') . "\n";
        }
    }

    ################################################
    ###                 FILES                    ###
    ################################################

    /**
     * Generate corrupted core files report
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateCorruptedCoreFilesData(array $arguments = array())
    {
        $checkSumData   = $this->_getFilesCheckSumData(isset($arguments['f']));
        $referenceData  = $checkSumData['reference_data'];
        $localData      = $checkSumData['local_data'];

        $newLocalFiles  = array_diff_key($localData, $referenceData);
        $difference     = array_diff_assoc($localData, $newLocalFiles);
        $difference     = array_diff_assoc($difference, $referenceData);
        $_data          = array_keys($difference);
        $systemReport = $data = array();

        foreach ($_data as $file) {
            $data[] = array($file);
        }

        $systemReport['Modified Core Files'] = array(
            'header' => array('File'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate missing core files report
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateMissingCoreFilesData(array $arguments = array())
    {
        $checkSumData     = $this->_getFilesCheckSumData(isset($arguments['f']));
        $referenceData    = $checkSumData['reference_data'];
        $localData        = $checkSumData['local_data'];

        $absentLocalFiles = array_diff_key($referenceData, $localData);
        $_data            = array_keys($absentLocalFiles);
        $systemReport = $data = array();

        foreach ($_data as $file) {
            $data[] = array($file);
        }

        $systemReport['Missing Core Files'] = array(
            'header' => array('File'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate new local files report
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateNewLocalFilesData(array $arguments = array())
    {
        $checkSumData   = $this->_getFilesCheckSumData(isset($arguments['f']));
        $referenceData  = $checkSumData['reference_data'];
        $localData      = $checkSumData['local_data'];

        $newLocalFiles  = array_diff_key($localData, $referenceData);
        $data = $firstElements = array();
        $filesByPriority = array(
            'app' . DS . 'code' . DS => array(),
            'app' . DS . 'design' . DS => array(),
            'lib' . DS => array(),
            'js' . DS => array(),
            'skin' . DS => array(),
            '__other__files__' => array(),
        );
        $maxFilesToDisplayPerPath = floor(self::TABLE_DATA_ROW_MAXIMUM_COUNT_FOR_OUTPUT / sizeof($filesByPriority));
        foreach ($newLocalFiles as $fileName => $sum) {
            $prioritized = false;
            foreach ($filesByPriority as $path => $files) {
                if (sizeof($filesByPriority[$path]) >= $maxFilesToDisplayPerPath) {
                    continue;
                }
                if (substr($fileName, 0, strlen($path)) == $path) {
                    $filesByPriority[$path][] = $fileName;
                    $prioritized = true;
                    break;
                }
            }
            if (!$prioritized) {
                $filesByPriority['__other__files__'][] = $fileName;
            }
        }

        foreach ($filesByPriority as $files) {
            foreach ($files as $file) {
                $data[] = array($file);
            }
        }

        $systemReport = array();
        $systemReport['New Files'] = array(
            'header' => array('File'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Get files check sum data
     *
     * @param bool $foreRegenerateLocalData
     *
     * @return array
     * @throws Exception
     */
    protected function _getFilesCheckSumData($foreRegenerateLocalData = false)
    {
        $edition        = strtolower($this->_magentoEdition);
        $referenceFile  = sprintf(self::REPORT_FILES_CHECK_SUM_REF_DATA_FILE_MASK, $edition, $this->_magentoVersion);
        $localFile      = sprintf(self::REPORT_FILES_CHECK_SUM_LOCAL_DATA_FILE_MASK, $edition, $this->_magentoVersion);
        $path           = $this->_getWorkingPath();

        if (!is_readable($path . $referenceFile)) {
            $this->_log(null, 'Requesting ' . $referenceFile . ' from remote server...');
            $result = $this->callApi('get_files_check_sum_snapshot', array(
                'edition' => $edition,
                'version' => $this->_magentoVersion
            ));
            if ($result) {
                $writtenBytes = file_put_contents($path . $referenceFile, $result);;
                $this->_log(null,
                    ($writtenBytes !== false
                        ? 'File "' . $referenceFile . '" was successfully received from remote server.'
                        : 'File "' . $referenceFile . '" wasn\'t received from remote server!')
                );
            }

            if (!is_readable($path . $referenceFile)) {
                throw new Exception(
                    $referenceFile . ' doesn\'t exist or it is not readable. Checking files can\'t be performed.'
                );
            }
        }
        if (!file_exists($path . $localFile) || $foreRegenerateLocalData) {
            $this->_generateFilesCheckSumData();
        }
        if (!is_readable($path . $localFile)) {
            throw new Exception(
                $localFile . ' wasn\'t generated or it is not readable. Checking files can\'t be performed.'
            );
        }

        $referenceData = unserialize(file_get_contents($path . $referenceFile));
        $localData     = unserialize(file_get_contents($path . $localFile));
        if (!is_array($referenceData) || !is_array($localData)
            || !array_key_exists('file_hashes', $referenceData) || !array_key_exists('file_hashes', $localData)
        ) {
            throw new Exception(
                'Sha1 hash data is corrupted. Checking files can\'t be performed.'
            );
        }

        return array('reference_data' => $referenceData['file_hashes'], 'local_data' => $localData['file_hashes']);
    }

    /**
     * Generate files sha1 data file
     *
     * @param array $arguments
     *
     * @return bool
     * @throws Exception
     */
    protected function _generateFilesCheckSumData(array $arguments = array())
    {
        $path = $this->_getWorkingPath();
        if (!is_writable($path)) {
            throw new Exception(
                'Can\'t write to directory where sysreport tool resides. Files check sum data wasn\'t generated.'
            );
        }

        $result = $this->_getRecursiveCheckSum($this->_getRootPath(), true);
        $filesNumber = sizeof($result);

        $this->_log(null, 'Got sha1 for "' . $filesNumber . '" files.');

        $edition = strtolower($this->_magentoEdition);
        $filename = sprintf(self::REPORT_FILES_CHECK_SUM_LOCAL_DATA_FILE_MASK, $edition, $this->_magentoVersion);
        $writtenBytes = file_put_contents($path . $filename, serialize(array('file_hashes' => $result)));
        $this->_log(null,
            ($writtenBytes !== false
                ? 'File "' . $filename . '" was successfully generated.'
                : 'File "' . $filename . '" wasn\'t generated!')
        );

        return $writtenBytes !== false;
    }

    /**
     * Get files check sum recursively
     *
     * @param string $directory
     * @param bool $resetStaticData
     *
     * @return array
     */
    protected function _getRecursiveCheckSum($directory, $resetStaticData = false)
    {
        static $filesNumber = 0;
        if ($resetStaticData) {
            $filesNumber = 0;
        }
        $result = array();
        $iterator = new DirectoryIterator($directory);
        $rootDirectory = $this->_getRootPath();

        /** @var $file SplFileInfo */
        foreach ($iterator as $file) {
            $fileName = $file->getFilename();
            $filePath = $file->getPathname();
            $relativePath = str_replace($rootDirectory, '', $filePath);

            if ($fileName == 'Thumbs.db'
                || $relativePath == 'app' . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'local.xml'
                || $relativePath == 'app' . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'modules' .
                    DIRECTORY_SEPARATOR . 'XEnterprise_Enabler.xml'
                || in_array($fileName, array('.', '..', '.git', '.svn', '.gitignore', '.idea'))
                || (substr($relativePath, 0, 1) == '.' && $fileName != '.htaccess' && $fileName != '.htaccess.sample')
                // All files that are not in app/, lib/, js/, shell/, skin/ directories (except root files)
                || (strpos($relativePath, 'app' . DIRECTORY_SEPARATOR) !== 0
                    && strpos($relativePath, 'js' . DIRECTORY_SEPARATOR) !== 0
                    && strpos($relativePath, 'lib' . DIRECTORY_SEPARATOR) !== 0
                    && strpos($relativePath, 'shell' . DIRECTORY_SEPARATOR) !== 0
                    && strpos($relativePath, 'skin' . DIRECTORY_SEPARATOR) !== 0
                    && $relativePath != $fileName)
            ) {
                continue;
            }

            if ($file->isFile()) {
                // Convert path to Unix style (because checking files method is using Unix style paths)
                $relativePath = str_replace('\\', '/', $relativePath);
                $result[$relativePath] = is_readable($filePath) ? sha1_file($filePath) : '0';

                if ($this->_canOutputProgress()) {
                    if ($filesNumber == 0) {
                        echo "\n" . 0;
                    }
                    $filesNumber++;
                    if ($filesNumber % 300 == 0) {
                        echo '=';
                    }
                    if ($filesNumber % 1500 == 0) {
                        echo $filesNumber;
                    }
                }
            } else if ($file->isDir()) {
                $_result = (array) $this->_getRecursiveCheckSum($filePath);
                $result = array_merge($result, $_result);
            }
        }

        return $result;
    }

    /**
     * Generate provided patches report
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateProvidedPatchesData(array $arguments = array())
    {
        clearstatcache();
        $appliedPatchesData = $systemReport = array();

        $appliedPatchesListFile = Mage::getBaseDir('etc') . DIRECTORY_SEPARATOR
            . self::REPORT_APPLIED_PATCHES_LIST_FILE_NAME;
        if (is_file($appliedPatchesListFile) && is_readable($appliedPatchesListFile)
            && $this->_getFileSize($appliedPatchesListFile) < self::REPORT_APPLIED_PATCHES_LIST_FILE_MAX_SIZE
        ) {
            try {
                $appliedListData = file($appliedPatchesListFile);
                foreach ($appliedListData as $line) {
                    $data = explode('|', $line);
                    $data = array_map('trim', $data);
                    if (sizeof($data) < 7) {
                        continue;
                    }
                    $appliedPatchesData[] = array(
                        $data[0],
                        $data[1],
                        $data[3],
                        $data[2],
                        isset($data[7]) ? 'Yes' : 'No',
                        $data[4],
                    );
                }
            } catch (Exception $e) {
                $this->_log($e);
            }
        }

        $systemReport['Applied Solutions List'] = array(
            'header' => array('Date', 'Solution', 'Solution Version', 'Magento Version', 'Reversion', 'Commit'),
            'data' => $appliedPatchesData
        );

        $baseDir = $this->_getRootPath();
        $filesList = $this->_getFilesList($baseDir, 1, true, array(), '^.*\.patch$');
        $filesList = array_merge(
            $filesList, $this->_getFilesList($baseDir, 1, self::REPORT_FILE_LIST_FILES, array(), '^PATCH.+\.sh$')
        );
        $baseDirNameLength = strlen($baseDir);
        $data = array();

        foreach ($filesList as $file) {
            $data[] = array(
                substr($file, $baseDirNameLength),
                $this->_formatBytes($this->_getFileSize($file), 3, 'IEC'),
                date('r', filemtime($file))
            );
        }

        $systemReport['Patch Files List'] = array(
            'header' => array('Patch', 'Size', 'Last Update'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate files permissions report
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateFilePermissionsData(array $arguments = array())
    {
        clearstatcache();
        $baseDir = $this->_getRootPath();
        $openDirList = array(
            'app',
            'app' . DIRECTORY_SEPARATOR . 'etc',
            'media',
            'shell',
            'var',
            'var' . DIRECTORY_SEPARATOR . 'locks',
            'var' . DIRECTORY_SEPARATOR . 'log',
        );
        foreach ($openDirList as &$directory) {
            $directory = $baseDir . $directory;
        }

        $filesList = $this->_getFilesList($baseDir, 2, self::REPORT_FILE_LIST_ALL, $openDirList);
        $baseDirNameLength = strlen($baseDir);
        $rootFiles = $restFiles = array();
        foreach ($filesList as $file) {
            $fileParts = explode('/', substr($file, $baseDirNameLength));
            if (is_file($file) && sizeof($fileParts) == 1) {
                $rootFiles[] = $file;
            } else {
                $restFiles[] = $file;
            }
        }
        $filesList = array_merge($restFiles, $rootFiles);

        $data = array();
        $everyFilesNumber = ceil(count($filesList) / 50);

        $filesNumber = 0;
        $directoryFilesCounter = array();
        foreach ($filesList as $file) {
            try {
                if ($this->_canOutputProgress()) {
                    if ($filesNumber == 0) {
                        echo "\n" . 0;
                    }
                    $filesNumber++;
                    if ($filesNumber % $everyFilesNumber == 0) {
                        echo '=';
                    }
                    if ($filesNumber % ($everyFilesNumber * 5) == 0) {
                        echo $filesNumber;
                    }
                }

                $fileName       = substr($file, $baseDirNameLength);
                $path           = dirname($fileName);
                $fileParts      = explode('/', $fileName);
                $fileName       = array_pop($fileParts);
                $pathPartsSize  = sizeof($fileParts);
                $fileName       = str_repeat('    ', $pathPartsSize) . $fileName .
                    (is_dir($file) ? DIRECTORY_SEPARATOR : '');

                if (!isset($directoryFilesCounter[$path])) {
                    $directoryFilesCounter[$path] = 0;
                }
                $directoryFilesCounter[$path]++;
                if ($directoryFilesCounter[$path] > self::TABLE_DATA_PERMISSIONS_REPORT_MAX_FILES_PER_DIRECTORY
                    && $path != '.' && $path != '..'
                ) {
                    continue;
                }

                if ($this->_isLink($file)) {
                    $fileName = $this->_formatCLIStyle(
                        $fileName . ' -> ' . $this->_readlink($file), 'cyan', null, array('bold')
                    );
                }
                if (is_dir($file)) {
                    $fileName = $this->_formatCLIStyle($fileName, 'yellow', null, array('bold'));
                }
                $data[] = array(
                    $fileName,
                    $this->_parsePermissions(fileperms($file)),
                    $this->_getFileOwner($file),
                    is_file($file)
                        ? $this->_formatBytes($this->_getFileSize($file), 3, 'IEC')
                        : $this->_formatBytes($this->_getDirSize($file), 3, 'IEC'),
                    date('r', filemtime($file))
                );
            } catch (Exception $e) {
                $this->_log($e);
            }
        }

        $systemReport = array();
        $systemReport['Files Permissions'] = array(
            'header' => array('File', 'Permissions', 'Owner', 'Size', 'Last Update'),
            'data' => $data
        );

        return $systemReport;
    }

    ################################################
    ###                  DATABASE                ###
    ################################################

    /**
     * Generate DB corrupted tables report
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateCorruptedCoreDbTablesData(array $arguments = array())
    {
        $tablesReport = $this->_getDbStructureData('tables', isset($arguments['f']));
        $tablesReport  = $this->_compareTablesStructure($tablesReport['local_data'], $tablesReport['reference_data']);
        $systemReport  = array();

        $systemReport['Modified Core Tables'] = array(
            'header' => array('Name', 'Missing Data', 'New Data', 'Corrupted Data'),
            'data' => $tablesReport['corrupted'],
        );

        return $systemReport;
    }

    /**
     * Generate DB missing tables report
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateMissingCoreDbTablesData(array $arguments = array())
    {
        $tablesReport = $this->_getDbStructureData('tables', isset($arguments['f']));
        $tablesReport = $this->_compareTablesStructure($tablesReport['local_data'], $tablesReport['reference_data']);
        $systemReport = array();

        $systemReport['Missing Core Tables'] = array(
            'header' => array('Name'),
            'data' => $tablesReport['missing']
        );

        return $systemReport;
    }

    /**
     * Generate DB new tables report
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateNewDbTablesData(array $arguments = array())
    {
        $tablesReport = $this->_getDbStructureData('tables', isset($arguments['f']));
        $tablesReport = $this->_compareTablesStructure($tablesReport['local_data'], $tablesReport['reference_data']);
        $systemReport = array();

        $systemReport['New Tables'] = array(
            'header' => array('Name'),
            'data' => $tablesReport['new']
        );

        return $systemReport;
    }

    /**
     * Generate stored functions and procedures list for current DB
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateDbRoutinesListData(array $arguments = array())
    {
        $routines = $data = array();
        try {
            $routines = $this->_getMySQLRoutinesList();
        } catch (Exception $e) {
            $this->_log($e);
        }

        if ($routines) {
            foreach ($routines as $name => $routine) {
                $data[] = array($name, $routine['type'], $routine['comment']);
            }
        }

        $systemReport = array();
        $systemReport['DB Routines List'] = array(
            'header' => array('Name', 'Type', 'Comment'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate missing stored functions and procedures list for current DB
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateMissingDbRoutinesData(array $arguments = array())
    {
        $structure      = $this->_getDbStructureData('routines', isset($arguments['f']));
        $routines       = array_diff_key($structure['reference_data'], $structure['local_data']);
        $systemReport   = $data = array();

        if ($routines) {
            foreach ($routines as $name => $routine) {
                $data[] = array($name, $routine['type'], $routine['comment']);
            }
        }

        $systemReport['Missing DB Routines List'] = array(
            'header' => array('Name', 'Type', 'Comment'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate missing stored functions and procedures list for current DB
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateNewDbRoutinesData(array $arguments = array())
    {
        $structure      = $this->_getDbStructureData('routines', isset($arguments['f']));
        $routines       = array_diff_key($structure['local_data'], $structure['reference_data']);
        $systemReport   = $data = array();

        if ($routines) {
            foreach ($routines as $name => $routine) {
                $data[] = array($name, $routine['type'], $routine['comment']);
            }
        }

        $systemReport['New DB Routines List'] = array(
            'header' => array('Name', 'Type', 'Comment'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate triggers list for current DB
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateDbTriggersListData(array $arguments = array())
    {
        $triggers = $data = array();
        try {
            $triggers = $this->_getMySQLTriggersList();
        } catch (Exception $e) {
            $this->_log($e);
        }

        if ($triggers) {
            foreach ($triggers as $name => $trigger) {
                $data[] = array($name, $trigger['comment']);
            }
        }

        $systemReport = array();
        $systemReport['DB Triggers List'] = array(
            'header' => array('Name', 'Comment'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate missing triggers list for current DB
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateMissingDbTriggersData(array $arguments = array())
    {
        $structure      = $this->_getDbStructureData('triggers', isset($arguments['f']));
        $triggers       = array_diff_key($structure['reference_data'], $structure['local_data']);
        $systemReport   = $data = array();

        if ($triggers) {
            foreach ($triggers as $name => $trigger) {
                $data[] = array($name, $trigger['comment']);
            }
        }

        $systemReport['Missing DB Triggers List'] = array(
            'header' => array('Name', 'Comment'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate new triggers list for current DB
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateNewDbTriggersData(array $arguments = array())
    {
        $structure      = $this->_getDbStructureData('triggers', isset($arguments['f']));
        $triggers       = array_diff_key($structure['local_data'], $structure['reference_data']);
        $systemReport   = $data = array();

        if ($triggers) {
            foreach ($triggers as $name => $trigger) {
                $data[] = array($name, $trigger['comment']);
            }
        }

        $systemReport['New DB Triggers List'] = array(
            'header' => array('Name', 'Comment'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Compare local tables structure to reference (clean installation) and retrieve report
     *
     * @param array $localTablesStructure
     * @param array $referenceTablesStructure
     *
     * @return array
     */
    protected function _compareTablesStructure(array $localTablesStructure, array $referenceTablesStructure)
    {
        $corruptedLocalTablesReport = $missingTablesReport = $newTablesReport = array();

        // Collecting tables that are exist in local installation but not exist on clean native magento - New Tables
        $_newTablesReport = array_diff(array_keys($localTablesStructure), array_keys($referenceTablesStructure));
        foreach ($_newTablesReport as $table) {
            $newTablesReport[] = array($table);
        }

        // Collecting differences in tables structures
        foreach ($referenceTablesStructure as $table => $tableProp) {
            try {
                if (!isset($localTablesStructure[$table])) {
                    $missingTablesReport[] = array($table);
                } else {
                    $data = array();
                    $tableProp['keys_structure'] = array();
                    $tableProp['constraints_structure'] = array();
                    $localTablesStructure[$table]['keys_structure'] = array();
                    $localTablesStructure[$table]['constraints_structure'] = array();

                    // Compare columns
                    // Missing
                    $list = array_diff_key($tableProp['fields'], $localTablesStructure[$table]['fields']);
                    if ($list) {
                        $data[0][] = 'Columns: ' . join(', ', array_keys($list));
                    }
                    // New
                    $list = array_diff_key($localTablesStructure[$table]['fields'], $tableProp['fields']);
                    if ($list) {
                        $data[1][] = 'Columns: ' . join(', ', array_keys($list));
                    }

                    /**
                     * Compare indexes
                     */
                    // Prepare keys structure info for reference and local tables
                    foreach ($tableProp['keys'] as $keyInfo) {
                        $keyStructureHash = md5($keyInfo['type'] . join(':', $keyInfo['fields']));
                        $tableProp['keys_structure'][$keyStructureHash] = $keyInfo['name'];
                    }
                    foreach ($localTablesStructure[$table]['keys'] as $keyInfo) {
                        $keyStructureHash = md5($keyInfo['type'] . join(':', $keyInfo['fields']));
                        $localTablesStructure[$table]['keys_structure'][$keyStructureHash] = $keyInfo['name'];
                    }
                    $compareInfo = $this->_compareKeysStructure(
                        $localTablesStructure[$table]['keys_structure'], $tableProp['keys_structure']
                    );
                    if (!empty($compareInfo['missing'])) {
                        $data[0][] = 'Keys: ' . join(', ', $compareInfo['missing']);
                    }
                    if (!empty($compareInfo['new'])) {
                        $data[1][] = 'Keys: ' . join(', ', $compareInfo['new']);
                    }
                    if (!empty($compareInfo['corrupted'])) {
                        $data[2][] = 'Keys: ' . join(', ', $compareInfo['corrupted']);
                    }

                    /**
                     * Compare foreign keys
                     */
                    // Prepare constraints structure info for reference and local tables
                    foreach ($tableProp['constraints'] as $keyInfo) {
                        $keyStructureHash = md5($keyInfo['ref_db'] . $keyInfo['pri_table'] . $keyInfo['pri_field'] .
                            $keyInfo['ref_table'] . $keyInfo['ref_field'] . $keyInfo['on_delete'] .
                            $keyInfo['on_update']
                        );
                        $tableProp['constraints_structure'][$keyStructureHash] = $keyInfo['fk_name'];
                    }
                    foreach ($localTablesStructure[$table]['constraints'] as $keyInfo) {
                        $keyStructureHash = md5($keyInfo['ref_db'] . $keyInfo['pri_table'] . $keyInfo['pri_field'] .
                            $keyInfo['ref_table'] . $keyInfo['ref_field'] . $keyInfo['on_delete'] .
                            $keyInfo['on_update']
                        );
                        $localTablesStructure[$table]['constraints_structure'][$keyStructureHash] = $keyInfo['fk_name'];
                    }
                    $compareInfo = $this->_compareKeysStructure(
                        $localTablesStructure[$table]['constraints_structure'], $tableProp['constraints_structure']
                    );
                    if (!empty($compareInfo['missing'])) {
                        $data[0][] = 'Constraints: ' . join(', ', $compareInfo['missing']);
                    }
                    if (!empty($compareInfo['new'])) {
                        $data[1][] = 'Constraints: ' . join(', ', $compareInfo['new']);
                    }
                    if (!empty($compareInfo['corrupted'])) {
                        $data[2][] = 'Constraints: ' . join(', ', $compareInfo['corrupted']);
                    }

                    // Check charset
                    if ($tableProp['charset'] != $localTablesStructure[$table]['charset']) {
                        $info = 'Local table charset is "' . $localTablesStructure[$table]['charset'] .
                            '", but must be "' . $tableProp['charset'] .
                            '" (collate "' . $tableProp['collate'] . '")';
                        $data[2][] = $info;
                    }

                    // Check storage
                    if ($tableProp['engine'] != $localTablesStructure[$table]['engine']) {
                        $info = 'Local table storage engine type is "' . $localTablesStructure[$table]['engine'] .
                            '", but must be "' . $tableProp['engine'] . '"';
                        $data[2][] = $info;
                    }

                    if ($data) {
                        // Determine maximum size of data for each column. This number will represent how many rows
                        // must be generated in report for one DB table
                        $dataCount = 0;
                        for ($i = 0; $i < 3; $i++) {
                            if (!isset($data[$i])) {
                                continue;
                            }
                            $size = sizeof($data[$i]);
                            if ($size > $dataCount) {
                                $dataCount = $size;
                            }
                        }

                        for ($i = 0; $i < $dataCount; $i++) {
                            $corruptedLocalTablesReport[] = array(
                                $i == 0 ? $table : '',
                                isset($data[0][$i]) ? $data[0][$i] : '',
                                isset($data[1][$i]) ? $data[1][$i] : '',
                                isset($data[2][$i]) ? $data[2][$i] : '',
                            );
                        }
                    }
                }
            } catch (Exception $e) {
                $this->_log($e);
            }
        }

        return array(
            'new'       => $newTablesReport,
            'missing'   => $missingTablesReport,
            'corrupted' => $corruptedLocalTablesReport
        );
    }

    /**
     * Compare table keys by structure
     *
     * So if key names are different but they have same structure, that means that they are equal.
     * Such approach is used because client's DB can contain table prefixes that make sometimes keys to be renamed, so
     * keys name length will be limited to 64 characters (at least after EE 1.11.0.0)
     *
     * @param array $localKeysStructure
     * @param array $referenceKeysStructure
     * @return array
     */
    protected function _compareKeysStructure(array $localKeysStructure, array $referenceKeysStructure)
    {
        // Keys that are exist in local table but not exist in reference table, so they can be new
        $maybeNew    = array_diff_key($localKeysStructure, $referenceKeysStructure);
        // Keys that are exist in reference table but not exist in local table, so they can be missing
        $maybeMissing = array_diff_key($referenceKeysStructure, $localKeysStructure);
        // Same keys by name that are exist in both local and reference tables, but they can be different by structure
        $sameNames   = array_intersect(array_values($maybeMissing), array_values($maybeNew));

        // array_merge() used to reset indices
        // Actually new keys
        $new         = array_merge(array_diff(array_values($maybeNew), $sameNames), array());
        // Actually missing keys
        $missing     = array_merge(array_diff(array_values($maybeMissing), $sameNames), array());
        // Corrupted keys by structure
        $corrupted   = array_merge(array_intersect($sameNames, array_values($maybeNew)), array());

        return array('missing' => $missing, 'new' => $new, 'corrupted' => $corrupted);
    }

    /**
     * Generate DB tables and views information.
     * Tables information contains: Name, Engine, Rows count and table Size (data length + index length), Collation
     * Views information contains: Name
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateDbTablesStatusData(array $arguments = array())
    {
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. Count data can\'t be retrieved.');
        }

        $connection = $this->_readConnection;
        $dbName = $this->_getMagentoDBName();
        $data = $myIsamData = array();
        $totalSize = $totalRows = null;

        try {
            $info = $connection->fetchAll("
                SELECT
                    TABLE_NAME AS `table_name`,
                    ENGINE AS `engine`,
                    TABLE_ROWS AS `rows`,
                    (DATA_LENGTH + INDEX_LENGTH) AS `size`,
                    TABLE_COLLATION AS `collation`,
                    TABLE_TYPE AS `type`,
                    CREATE_TIME as `create_time`,
                    UPDATE_TIME as `update_time`,
                    TABLE_COMMENT as `comment`
                FROM information_schema.TABLES
                WHERE TABLES.TABLE_SCHEMA = '$dbName'
                      AND (TABLES.TABLE_TYPE = 'BASE TABLE' OR TABLES.TABLE_TYPE = 'VIEW')
                ORDER BY `size` DESC, TABLE_ROWS DESC
            ");

            if ($info) {
                $totalSize = $totalRows = $counter = 0;
                foreach ($info as $_data) {
                    if ($counter < self::TABLE_DATA_ROW_MAXIMUM_COUNT_FOR_OUTPUT - 2) {
                        if ($_data['type'] == 'BASE TABLE') {
                            $collectedData = array(
                                $_data['table_name'],
                                $_data['engine'],
                                $_data['rows'],
                                $this->_formatBytes($_data['size'], 3, 'IEC'),
                                $_data['create_time'],
                                $_data['update_time'],
                                $_data['collation'],
                                $_data['comment'],
                            );
                            $data[] = $collectedData;
                            if ($_data['engine'] == 'MyISAM') {
                                $myIsamData[] = $collectedData;
                            }
                        } else {
                            $data[] = array($_data['table_name'], '[VIEW]', 'n/a', 'n/a', 'n/a', 'n/a', 'n/a', 'n/a');
                        }
                    }
                    $totalSize += $_data['size'];
                    $totalRows += $_data['rows'];
                    $counter++;
                    if ($counter == self::TABLE_DATA_ROW_MAXIMUM_COUNT_FOR_OUTPUT - 2) {
                        $data[] = array('And more...');
                    }
                }
            }
        } catch (Exception $e) {
            $this->_log($e);
        }

        if ($totalSize !== null && $totalRows !== null) {
            $data[] = array('TOTALS: ', '', $totalRows, $this->_formatBytes($totalSize, 3, 'IEC'), '', '', '', '');
        }

        $systemReport = array();
        $systemReport['DB Tables Status'] = array(
            'header' => array('Name', 'Engine', '~ Rows', '~ Size', 'Create Time', 'Update Time','Collation','Comment'),
            'data' => $data
        );
        $systemReport['DB MyISAM Tables Status'] = array(
            'header' => array('Name', 'Engine', '~ Rows', '~ Size', 'Create Time', 'Update Time','Collation','Comment'),
            'data' => $myIsamData
        );

        return $systemReport;
    }

    /**
     * Retrieve DB structure and data information by specified type
     * Available types: all, tables, triggers, routines, eav_attributes
     *
     * @param string $type
     * @param bool $forceRegenerateLocalSnapshot
     *
     * @return array
     *
     * @throws Exception
     */
    protected function _getDbStructureData($type = 'all', $forceRegenerateLocalSnapshot = false)
    {
        static $referenceData = null, $localData = null;
        if ($referenceData === null || $localData === null || $forceRegenerateLocalSnapshot) {
            $edition      = strtolower($this->_magentoEdition);
            $referenceFile= sprintf(self::REPORT_DB_STRUCTURE_SNAPSHOT_REF_FILE_MASK, $edition, $this->_magentoVersion);
            $localFile  = sprintf(self::REPORT_DB_STRUCTURE_SNAPSHOT_LOCAL_FILE_MASK, $edition, $this->_magentoVersion);
            $path         = $this->_getWorkingPath();

            if (!is_readable($path . $referenceFile)) {
                $this->_log(null, 'Requesting ' . $referenceFile . ' from remote server...');
                $result = $this->callApi('get_db_structure_snapshot', array(
                    'edition' => $edition,
                    'version' => $this->_magentoVersion
                ));
                if ($result) {
                    $writtenBytes = file_put_contents($path . $referenceFile, $result);;
                    $this->_log(null,
                        ($writtenBytes !== false
                            ? 'File "' . $referenceFile . '" was successfully received from remote server.'
                            : 'File "' . $referenceFile . '" wasn\'t received from remote server!')
                    );
                }

                if (!is_readable($path . $referenceFile)) {
                    throw new Exception(
                        $referenceFile . ' doesn\'t exist or it is not readable. DB structure data can\'t be retrieved.'
                    );
                }
            }
            if (!file_exists($path . $localFile) || $forceRegenerateLocalSnapshot) {
                $this->_generateDbDataAndStructureSnapshot();
            }
            if (!is_readable($path . $localFile)) {
                throw new Exception(
                    $localFile . ' wasn\'t generated or it is not readable. DB structure data can\'t be retrieved.'
                );
            }

            $referenceData = unserialize(file_get_contents($path . $referenceFile));
            $localData     = unserialize(file_get_contents($path . $localFile));
        }

        if (!is_array($referenceData) || !is_array($localData)) {
            throw new Exception('DB snapshot data is corrupted. DB structure data can\'t be retrieved.');
        }

        $type = in_array($type, array('tables', 'triggers', 'routines', 'eav_attributes')) ? $type : 'all';

        if ($type == 'all') {
            return array('reference_data' => $referenceData, 'local_data' => $localData);
        }
        if ($type == 'eav_attributes') {
            if (!array_key_exists('data', $referenceData) || !array_key_exists('data', $localData)) {
                throw new Exception('DB snapshot data is corrupted. DB structure data can\'t be retrieved.');
            }
            $referenceData = $referenceData['data'];
            $localData     = $localData['data'];
        }

        if (!array_key_exists($type, $referenceData) || !array_key_exists($type, $localData)) {
            throw new Exception('DB snapshot data is corrupted. DB structure data can\'t be retrieved.');
        }

        return array('reference_data' => $referenceData[$type], 'local_data' => $localData[$type]);
    }

    /**
     * Generate DB structure and data consistency snapshot
     *
     * @param array $arguments
     *
     * @return bool
     * @throws Exception
     */
    protected function _generateDbDataAndStructureSnapshot(array $arguments = array())
    {
        $path = $this->_getWorkingPath();
        if (!is_writable($path)) {
            throw new Exception(
                'Cant\'t write to directory where sysreport tool resides. DB snapshot file will not be generated.'
            );
        }

        $tables        = $this->_getMySQLTablesList();
        $triggers      = $this->_getMySQLTriggersList();
        $routines      = $this->_getMySQLRoutinesList();
        $eavAttributes = $this->_getEavAttributes('all');

        $structureData = array(
            'tables'    => $tables,
            'triggers'  => $triggers,
            'routines'  => $routines,
            'data'      => array('eav_attributes' => $eavAttributes)
        );
        $this->_log(null, 'Got data for "' . sizeof($tables) . '" tables.');
        $this->_log(null, 'Got data for "' . sizeof($triggers) . '" triggers.');
        $this->_log(null, 'Got data for "' . sizeof($routines) . '" routines.');
        $this->_log(null, 'Got data for "' . sizeof($eavAttributes) . '" eav attributes.');

        $edition = strtolower($this->_magentoEdition);
        $filename = sprintf(self::REPORT_DB_STRUCTURE_SNAPSHOT_LOCAL_FILE_MASK, $edition, $this->_magentoVersion);
        $writtenBytes = file_put_contents($path . $filename, serialize($structureData));
        $this->_log(null,
            ($writtenBytes !== false
                ? 'File "' . $filename . '" was successfully generated.'
                : 'File "' . $filename . '" wasn\'t generated!')
        );

        return $writtenBytes !== false;
    }

    /**
     * Collect MySQL triggers
     *
     * @return array
     *
     * @throws Exception
     */
    protected function _getMySQLTriggersList()
    {
        static $data = array();
        if (!empty($data)) {
            return $data;
        }
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. Routines list can\'t be retrieved.');
        }

        $connection = $this->_readConnection;
        $dbName = $this->_getMagentoDBName();

        $triggers = $connection->fetchAll("
            SELECT TRIGGER_NAME AS `name`,
                    CONCAT('On ', EVENT_MANIPULATION, ': ', EVENT_OBJECT_TABLE) AS `comment`
            FROM information_schema.TRIGGERS
            WHERE EVENT_OBJECT_SCHEMA = '$dbName'
        ");

        if (!$triggers) {
            return $data;
        }

        foreach ($triggers as $trigger) {
            $data[$trigger['name']] = array('comment' => $trigger['comment']);
        }

        return $data;
    }

    /**
     * Collect stored MySQL procedures and functions
     *
     * @return array
     *
     * @throws Exception
     */
    protected function _getMySQLRoutinesList()
    {
        static $data = array();
        if (!empty($data)) {
            return $data;
        }
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. Routines list can\'t be retrieved.');
        }

        $connection = $this->_readConnection;
        $dbName = $this->_getMagentoDBName();

        $routines = $connection->fetchAll("
            SELECT ROUTINE_NAME AS `name`,
                   ROUTINE_TYPE AS `type`,
                   IF(DTD_IDENTIFIER IS NOT NULL, CONCAT('Returns: ', DTD_IDENTIFIER), '') AS `comment`
            FROM information_schema.ROUTINES
            WHERE ROUTINE_SCHEMA = '$dbName'
            ORDER BY 2,1
        ");

        if (!$routines) {
            return $data;
        }

        foreach ($routines as $routine) {
            $data[$routine['name']] = array('type' => $routine['type'], 'comment' => $routine['comment']);
        }

        return $data;
    }

    /**
     * Retrieve MySQL tables list for current DB.
     * List contains full tables structure.
     *
     * @return array
     *
     * @throws Exception
     */
    protected function _getMySQLTablesList()
    {
        static $structureData = array();
        if (!empty($structureData)) {
            return $structureData;
        }
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. MySQL tables structure cannot be collected.');
        }

        $tablePrefix = (string) Mage::getConfig()->getTablePrefix();
        $tables = $this->_readConnection->fetchAll('SHOW TABLES');
        $tablesNumber = sizeof($tables);

        for ($rowIndex = 0; $rowIndex < $tablesNumber; $rowIndex++) {
            try {
                $originalTableName = $tables[$rowIndex];
                $tableName = substr(current($originalTableName), strlen($tablePrefix));
                $structureData[$tableName] = $this->_getTableProperties($tablePrefix . $tableName);
            } catch (Exception $e) {
                $this->_log($e);
            }
            if ($this->_canOutputProgress()) {
                if ($rowIndex == 0) {
                    echo "\n" . 0;
                }
                if (($rowIndex + 1) % 15 == 0) {
                    echo '=';
                }
                if (($rowIndex + 1) % 50 == 0) {
                    echo $rowIndex;
                }
            }
        }

        return $structureData;
    }

    /**
     * Collect DB table structure data
     *
     * @param $table
     * @return array
     * @throws Exception
     */
    protected function _getTableProperties($table)
    {
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. DB table properties can\'t be retrieved.');
        }

        $tablePrefix = (string)Mage::getConfig()->getTablePrefix();
        $prefixLength = strlen($tablePrefix);
        $tableProp = array(
            'fields'      => array(),
            'keys'        => array(),
            'constraints' => array(),
            'engine'      => 'MYISAM',
            'charset'     => 'utf8',
            'collate'     => null,
            'create_sql'  => null
        );

        $connection = $this->_readConnection;

        // collect fields
        $columnsInfo = $connection->fetchAll("SHOW FULL COLUMNS FROM `{$table}`");
        foreach ($columnsInfo as $field) {
            $tableProp['fields'][$field['Field']] = array(
                'type'      => $field['Type'],
                'is_null'   => strtoupper($field['Null']) == 'YES' ? true : false,
                'default'   => $field['Default'],
                'extra'     => $field['Extra'],
                'collation' => $field['Collation'],
            );
        }

        // create sql
        $createSql = $connection->fetchAll("SHOW CREATE TABLE `{$table}`");
        $tableProp['create_sql'] = $createSql[0]['Create Table'];

        // collect keys
        $regExp  = '#(PRIMARY|UNIQUE|FULLTEXT|FOREIGN)?\s+KEY\s+(`[^`]+` )?(\([^\)]+\))#';
        $matches = array();
        preg_match_all($regExp, $tableProp['create_sql'], $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            if (isset($match[1]) && $match[1] == 'PRIMARY') {
                $keyName = 'PRIMARY';
            }
            elseif (isset($match[1]) && $match[1] == 'FOREIGN') {
                continue;
            }
            else {
                $keyName = strtoupper(substr($match[2], 1, -2));
            }
            $fields = $fieldsMatches = array();
            preg_match_all("#`([^`]+)`#", $match[3], $fieldsMatches, PREG_SET_ORDER);
            foreach ($fieldsMatches as $field) {
                $fields[] = $field[1];
            }

            $tableProp['keys'][$keyName] = array(
                'type'   => !empty($match[1]) ? $match[1] : 'INDEX',
                'name'   => $keyName,
                'fields' => $fields
            );
        }

        // collect CONSTRAINT
        $regExp  = '#,\s+CONSTRAINT `([^`]*)` FOREIGN KEY \(`([^`]*)`\) '
            . 'REFERENCES (`[^`]*\.)?`([^`]*)` \(`([^`]*)`\)'
            . '( ON DELETE (RESTRICT|CASCADE|SET NULL|NO ACTION))?'
            . '( ON UPDATE (RESTRICT|CASCADE|SET NULL|NO ACTION))?#';
        $matches = array();
        preg_match_all($regExp, $tableProp['create_sql'], $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $keyName = strtoupper($match[1]);
            $tableProp['constraints'][$keyName] = array(
                'fk_name'   => $keyName,
                'ref_db'    => isset($match[3]) ? $match[3] : null,
                'pri_table' => substr($table, $prefixLength),
                'pri_field' => $match[2],
                'ref_table' => substr($match[4], $prefixLength),
                'ref_field' => $match[5],
                'on_delete' => isset($match[6]) ? $match[7] : '',
                'on_update' => isset($match[8]) ? $match[9] : ''
            );
        }

        // engine
        $regExp = "#(ENGINE|TYPE)="
            . "(MEMORY|HEAP|INNODB|MYISAM|ISAM|BLACKHOLE|BDB|BERKELEYDB|MRG_MYISAM|ARCHIVE|CSV|EXAMPLE)"
            . "#i";
        $match  = array();
        if (preg_match($regExp, $tableProp['create_sql'], $match)) {
            $tableProp['engine'] = strtoupper($match[2]);
        }

        //charset
        $regExp = "#DEFAULT CHARSET=([a-z0-9]+)( COLLATE=([a-z0-9_]+))?#i";
        $match  = array();
        if (preg_match($regExp, $tableProp['create_sql'], $match)) {
            $tableProp['charset'] = strtolower($match[1]);
            if (isset($match[3])) {
                $tableProp['collate'] = $match[3];
            }
        }

        return $tableProp;
    }

    /**
     * Retrieve Magento default DB name from config
     *
     * @return string
     */
    protected function _getMagentoDBName()
    {
        static $dbName = null;
        if ($dbName === null) {
            $dbName = (string) Mage::getConfig()->getNode('global/resources/default_setup/connection/dbname');
        }

        return $dbName;
    }

    ################################################
    ###                  OTHER                   ###
    ################################################

    /**
     * Generate Magento config file merged_config.xml
     *
     * @param array $arguments
     *
     * @throws Exception
     */
    protected function _generateMergedConfigFile(array $arguments = array())
    {
        $path = $this->_getWorkingPath();
        if (!is_writable($path)) {
            throw new Exception('Cant\'t write to directory where sysreport tool resides. Magento merged config file will not be generated.');
        }

        $filename = self::REPORT_MERGED_CONFIG_FILE_NAME;
        Mage::app()->getConfig()->getNode()->asNiceXml($path . $filename);
        $fileExists = file_exists($path . $filename);
        $this->_log(null,
            ($fileExists !== false
                ? 'File "' . $filename . '" was successfully generated.'
                : 'File "' . $filename . '" wasn\'t generated!')
        );
    }

    /**
     * Generate all events report
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateAllEventsData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['All Global Events'] = array(
            'header' => array('Event Name', 'Observer Class', 'Method'),
            'data' => $this->_getEvents('global')
        );

        $systemReport['All Admin Events'] = array(
            'header' => array('Event Name', 'Observer Class', 'Method'),
            'data' => $this->_getEvents('adminhtml')
        );

        return $systemReport;
    }

    /**
     * Generate core events report
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateCoreEventsData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Core Global Events'] = array(
            'header' => array('Event Name', 'Observer Class', 'Method'),
            'data' => $this->_getEvents('global', 'core')
        );

        $systemReport['Core Admin Events'] = array(
            'header' => array('Event Name', 'Observer Class', 'Method'),
            'data' => $this->_getEvents('adminhtml', 'core')
        );

        return $systemReport;
    }

    /**
     * Generate enterprise events report
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateEnterpriseEventsData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Enterprise Global Events'] = array(
            'header' => array('Event Name', 'Observer Class', 'Method'),
            'data' => $this->_getEvents('global', 'enterprise')
        );

        $systemReport['Enterprise Admin Events'] = array(
            'header' => array('Event Name', 'Observer Class', 'Method'),
            'data' => $this->_getEvents('adminhtml', 'enterprise')
        );

        return $systemReport;
    }

    /**
     * Generate custom events report
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateCustomEventsData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Custom Global Events'] = array(
            'header' => array('Event Name', 'Observer Class', 'Method'),
            'data' => $this->_getEvents('global', 'custom')
        );

        $systemReport['Custom Admin Events'] = array(
            'header' => array('Event Name', 'Observer Class', 'Method'),
            'data' => $this->_getEvents('adminhtml', 'custom')
        );

        return $systemReport;
    }

    /**
     * Get configured events in the system by scope and type
     *
     * @param string $scope
     * @param string $type
     *
     * @return array
     */
    protected function _getEvents($scope, $type = 'all')
    {
        $scope = $scope == 'adminhtml' || $scope == 'global' ? $scope : 'global';
        $type = !in_array($type, array('all', 'core', 'enterprise', 'custom')) ? 'all' : $type;
        $data = $eventsData = array();
        $coreNamespaces = array('Mage', 'Zend');

        $events = Mage::app()->getConfig()->getNode($scope . '/events');
        if (!$events) {
            return array();
        }
        $suffix = 0;
        foreach ($events->children() as $event) {
            foreach ($event->observers->children() as $info) {
                $class = $info->class ? (string)$info->class : $info->getClassName();
                $className = Mage::getConfig()->getModelClassName($class);
                if ($type != 'all') {
                    $nameSpace = substr($className, 0, strpos($className, '_'));
                    $_className = str_replace($nameSpace . '_', '', $className);
                    $module = $nameSpace . '_' . substr($_className, 0, strpos($_className, '_'));
                }  else {
                    $nameSpace = '';
                    $module = '';
                }
                if (($type == 'core' && !in_array($nameSpace, $coreNamespaces)
                        && !in_array($module, $this->_additionalCoreModules['community']))
                    || ($type == 'custom' && (in_array($nameSpace, $coreNamespaces) || $nameSpace == 'Enterprise'
                        || in_array($module, $this->_additionalCoreModules['community'])))
                    || ($type == 'enterprise' && $nameSpace != 'Enterprise')
                ) {
                    continue;
                }

                $classPath = $this->_getClassPath($className, $this->_getModuleCodePoolByClassName($className));
                $arrayKey = $eventName = $event->getName();
                if (isset($eventsData[$eventName])) {
                    $arrayKey .= '_' . (++$suffix);
                }

                $eventsData[$arrayKey] = array(
                    $eventName,
                    $this->_formatCLIStyle($className, 'yellow', null, array('bold')) . "\n"
                    . '    {' . $classPath . '}',
                    (string)$info->method
                );
            }
        }

        ksort($eventsData);
        foreach ($eventsData as $_data) {
            $data[] = $_data;
        }

        return $data;
    }

    /**
     * Generate class rewrite conflicts
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateClassRewriteConflictsData(array $arguments = array())
    {
        $modules = Mage::app()->getConfig()->getNode('modules')->children();
        $data = $systemReport = $_conflicts = $_rewrites = array();

        foreach ($modules as $modName => $module) {
            $configFile = $this->_getModulePath(
                $modName,
                $this->_getModuleCodePoolByClassName($modName)
            );
            $configFile .= 'etc' . DS . 'config.xml';

            try {
                $config = new Mage_Core_Model_Config_Base($configFile);
            } catch (Exception $e) {
                //
            }

            if (!isset($config)) {
                continue;
            }
            $classes = $config->getXpath('global/*/*/rewrite');
            if (!$classes) {
                continue;
            }
            /** @var $element Mage_Core_Model_Config_Element */
            foreach ($classes as $element) {
                //module node
                $moduleNode = $element->getParent();
                //scope node (models|blocks|helpers)
                $scopeNode = $moduleNode->getParent();
                //scope name
                $scopeName = $scopeNode->getName();
                if (!in_array($scopeName, array('models', 'blocks', 'helpers'))) {
                    continue;
                }
                /** @var $rewrite Mage_Core_Model_Config_Element */
                foreach ($element as $rewrite) {
                    $_rewriteFactoryName = $element->getParent()->getName() . '/' . $rewrite->getName();
                    if (!array_key_exists($_rewriteFactoryName, $_rewrites)) {
                        $_rewrites[$_rewriteFactoryName] = array(
                            'pool' => (string)$module->codePool,
                            'rewrite' => trim($rewrite),
                            'is_active' => $this->_isModuleActiveByClassName($modName),
                        );
                    } else {
                        if (!array_key_exists($_rewriteFactoryName, $_conflicts)) {
                            $_conflicts[$_rewriteFactoryName][] = $_rewrites[$_rewriteFactoryName];
                        }
                        $_conflicts[$_rewriteFactoryName][] = array(
                            'pool' => (string)$module->codePool,
                            'rewrite' => trim($rewrite),
                            'is_active' => $this->_isModuleActiveByClassName($modName),
                        );
                    }
                }
            }
            unset($config);
        }

        if ($_conflicts) {
            foreach ($_conflicts as $factoryName => $conflicts) {
                foreach ($conflicts as $conflict) {
                    $data[] = array(
                        $factoryName,
                        $this->_formatCLIStyle($conflict['rewrite'], 'yellow', null, array('bold')) . "\n" .
                        '    {' . $this->_getClassPath($conflict['rewrite'], $conflict['pool']) . '}',
                        $conflict['is_active'] ? 'Yes' : 'No',
                    );
                }
            }
        }

        $systemReport['Class Rewrite Conflicts'] = array(
            'header' => array('Factory Name', 'Class', 'Is Active'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate classes rewrite data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateClassRewritesData(array $arguments = array())
    {
        //global/models|blocks|helpers/module/rewrite/mage_class/to_new_class
        $systemReport = $rewrites = array();
        $classes = Mage::app()->getConfig()->getNode('global')->xpath('.//*/*/rewrite');
        if (!$classes) {
            $systemReport['Active Class Rewrites'] = array(
                'header' => array('Original Class', 'New Class', 'Type'),
                'data' => array()
            );

            return $systemReport;
        }
        /** @var $element Mage_Core_Model_Config_Element */
        foreach ($classes as $element) {
            //module node
            $moduleNode = $element->getParent();
            //scope node (models|blocks|helpers)
            $scopeNode = $moduleNode->getParent();
            //scope name
            $scopeName = $scopeNode->getName();
            if (!in_array($scopeName, array('models', 'blocks', 'helpers'))) {
                continue;
            }
            $deprecatedNode = $scopeNode->xpath('.//*/deprecatedNode[text()="' . $moduleNode->getName() . '"]');
            /** @var $rewrite Mage_Core_Model_Config_Element */
            foreach ($element as $rewrite) {
                // By default and in most cases in each scope of each module there is <class> node
                // which specifies class name pattern (e.g.: Mage_Adminhtml_Model)
                if (!empty($moduleNode->class)) {
                    $originalClass = $moduleNode->class . '_' . uc_words($rewrite->getName());
                }
                // But sometimes it's not specified, for ex.: deprecated resource model names, not defined helper name
                // Case when <deprecatedNode> is in use
                else if ($deprecatedNode && !empty($deprecatedNode[0]->getParent()->class)) {
                    $originalClass = trim($deprecatedNode[0]->getParent()->class) . '_' . uc_words($rewrite->getName());
                }
                // Otherwise specifically for certain scope try to resolve original class name
                else {
                    $combinedFactoryClassName = $moduleNode->getName() . '_' . $rewrite->getName();
                    switch ($scopeName) {
                        case 'helpers':
                            $originalClass = Mage::getConfig()->getHelperClassName($combinedFactoryClassName);
                            break;
                        default:
                            $originalClass = $combinedFactoryClassName;
                            break;
                    }
                }

                $newClass = trim($rewrite);
                // Resolve new (rewrite to) class name if it was specified in factory format
                if (sizeof(explode('/', $newClass)) == 2) {
                    switch ($scopeName) {
                        case 'models':
                            $newClass = Mage::getConfig()->getModelClassName($newClass);
                            break;
                        case 'blocks':
                            $newClass = Mage::getConfig()->getBlockClassName($newClass);
                            break;
                        case 'helpers':
                            $newClass = Mage::getConfig()->getHelperClassName($newClass);
                            break;
                        default:
                            break;
                    }
                }

                // Retrieve code pool information
                $originalCodePool = $this->_getModuleCodePoolByClassName($originalClass);
                $newCodePool = $this->_getModuleCodePoolByClassName($newClass);

                $rewrites[$scopeName][] = array(
                    'original_class' => $originalClass,
                    'original_code_pool' => $originalCodePool,
                    'new_class' => $newClass,
                    'new_code_pool' => $newCodePool,
                );
            }
        }

        $data = array();
        if (!empty($rewrites)) {
            foreach ($rewrites as $type => $rewrite) {
                foreach ($rewrite as $item) {
                    $data[] = array(
                        $this->_formatCLIStyle($item['original_class'], 'yellow', null, array('bold')) . "\n" .
                        (!empty($item['original_code_pool'])
                            ? '    {' . $this->_getClassPath($item['original_class'], $item['original_code_pool']) . '}'
                            : ''
                        ),
                        $this->_formatCLIStyle($item['new_class'], 'yellow', null, array('bold')) . "\n" .
                        (!empty($item['new_code_pool'])
                            ? '    {' . $this->_getClassPath($item['new_class'], $item['new_code_pool']) . '}'
                            : ''
                        ),
                        substr($type, 0, -1),
                    );
                }
            }
        }

        $systemReport['Active Class Rewrites'] = array(
            'header' => array('Original Class', 'New Class', 'Type'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate relative path to class file by its name and code pool
     *
     * @param string $className
     * @param string $codePool
     *
     * @return string
     */
    protected function _getClassPath($className, $codePool)
    {
        if (empty($className) || $className == 'n/a') {
            return '';
        }
        return 'app' . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . $codePool . DIRECTORY_SEPARATOR
               . implode(DIRECTORY_SEPARATOR, explode('_', $className)) . '.php';
    }

    /**
     * Get module code pool by specified class name
     *
     * @param string $className
     *
     * @return string
     */
    protected function _getModuleCodePoolByClassName($className)
    {
        $moduleConfig = $this->_getModuleConfigByClassName($className);
        if (!empty($moduleConfig)) {
            return $moduleConfig['code_pool'];
        }

        return 'n/a';
    }

    /**
     * Get module code pool by specified class name
     *
     * @param string $className
     *
     * @return bool
     */
    protected function _isModuleActiveByClassName($className)
    {
        $moduleConfig = $this->_getModuleConfigByClassName($className);
        if (!empty($moduleConfig)) {
            return (bool)$moduleConfig['is_active'];
        }

        return false;
    }

    /**
     * Get module config (active, codePool, version) by specified class name
     *
     * @param string $className
     *
     * @return string
     */
    protected function _getModuleConfigByClassName($className)
    {
        static $config = array();
        $result = array();
        $_classParts = explode('_', $className);
        if (is_array($_classParts) && isset($_classParts[0]) && isset($_classParts[1])) {
            $module = $_classParts[0] . '_' . $_classParts[1];
            if (array_key_exists($module, $config)) {
                $result = $config[$module];
            } else {
                $moduleConfig = Mage::app()->getConfig()->getNode('modules')->$module;
                if ($moduleConfig) {
                    $config[$module] = array(
                        'is_active' => strtolower((string) $moduleConfig->active) == 'true',
                        'code_pool' => (string) $moduleConfig->codePool,
                        'version'   => (string) $moduleConfig->version,
                    );
                    $result = $config[$module];
                }
            }
        }

        return $result;
    }

    /**
     * Generate core php file rewrites data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateFileRewritesData(array $arguments = array())
    {
        /**
         * Magento autoloader loads classes in this sequence:
         * 1. Try to find class in "local" code pool
         * 2. If not found, try to find class in "community" code pool
         * 3. If not found, try to find class in "code" code pool
         * 4. If not found, try to find class in "lib" code pool
         *
         * "local" and "community" - are "custom" code pools. That is why rewritten files will be searched there.
         *
         * But collecting custom files must be done in reverse sequence withing "custom" code pools, because
         * if autoloader finds file - it doesn't try to find it further down through rest of code pools - it is obvious.
         * That is why $customCodePools contains "custom" code pools in reverse sequence to default autoloader load
         * sequence.
         */
        $customCodePools = array(
            'community' => $this->_getRootPath() . 'app' . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR .
                'community' . DIRECTORY_SEPARATOR,
            'local' => $this->_getRootPath() . 'app' . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR .
                'local' . DIRECTORY_SEPARATOR,
        );

        /**
         * Core code pools are set in same sequence here as they watched by loader by default
         */
        $coreCodePools = array(
            'core' => $this->_getRootPath() . 'app' . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR .
                'core' . DIRECTORY_SEPARATOR,
            'lib' => $this->_getRootPath() . 'lib' . DIRECTORY_SEPARATOR,
        );

        // Collecting "custom" php files
        $files = $customFiles = array();
        foreach ($customCodePools as $pool => $poolDirectory) {
            if (!file_exists($poolDirectory) || !is_dir($poolDirectory)) {
                continue;
            }
            try {
                $directory = new RecursiveDirectoryIterator($poolDirectory);
                $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
                $files = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
            } catch (Exception $e) {
                $this->_log($e);
            }
            foreach ($files as $file) {
                $filePath = $file[0];
                $relativePath = str_replace($poolDirectory, '', $filePath);
                $customFiles[$relativePath] = $pool;
            }
        }

        $_rewriteFilesCache = $rewritesData = array();
        foreach ($customFiles as $relativePath => $pool) {
            foreach ($coreCodePools as $corePool => $poolDirectory) {
                $coreFile = $poolDirectory . $relativePath;
                // If file exists in core code pool then remember only occurrence in code pool which goes first in
                // load sequence
                if (file_exists($coreFile) && !isset($_rewriteFilesCache[$relativePath])) {
                    $rewritesData[] = array(
                        'app' . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR .
                            $pool . DIRECTORY_SEPARATOR . $relativePath,
                        $corePool,
                        $pool
                    );
                    $_rewriteFilesCache[$relativePath] = true;
                }
            }
        }

        $systemReport = array();
        $systemReport['File Rewrites'] = array(
            'header' => array('Core File', 'Core Pool', 'Custom Pool'),
            'data' => $rewritesData
        );

        return $systemReport;
    }

    /**
     * Generate controllers rewrite data
     *
     * Example of configuration to parse:
     * <global>
     *   <routers>
     *     <core_module>
     *       <rewrite>
     *         <core_controller>
     *           <to>new_route/new_controller</to>
     *           <override_actions>true</override_actions>
     *           <actions>
     *             <core_action><to>new_module/new_controller/new_action</core_action>
     *           </actions>
     *         <core_controller>
     *       </rewrite>
     *     </core_module>
     *   </routers>
     * </global>
     *
     * This will override:
     * 1. core_module/core_controller/core_action to new_module/new_controller/new_action
     * 2. all other actions of core_module/core_controller to new_module/new_controller
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateControllerRewritesData(array $arguments = array())
    {
        $systemReport = $rewritesData = array();
        $routers = Mage::app()->getConfig()->getNode('global')->xpath('.//routers/*/rewrite');
        if (!$routers) {
            $systemReport['Controller Rewrites'] = array(
                'header' => array('Core Controller', 'Core Action(s)', 'Custom Controller', 'Custom Action(s)'),
                'data' => array()
            );

            return $systemReport;
        }
        /** @var $rewrites Mage_Core_Model_Config_Element */
        foreach ($routers as $rewrites) {
            $coreFrontName = $rewrites->getParent()->getName();
            /** @var $rewrite Mage_Core_Model_Config_Element */
            foreach ($rewrites as $rewrite) {
                $coreController = $rewrite->getName();
                if (!empty($rewrite->to)) {
                    $rewriteInfo = explode('/', (string)$rewrite->to);
                    if (sizeof($rewriteInfo) !== 2 || empty($rewriteInfo[0]) || empty($rewriteInfo[1])) {
                        continue;
                    }
                    $rewritesData[] = $this->_getControllerRewriteData(
                        $coreFrontName, $coreController, '*',
                        $rewriteInfo[0], $rewriteInfo[1], '*'
                    );
                }
                if (!empty($rewrite->actions)) {
                    /** @var $action Mage_Core_Model_Config_Element */
                    foreach ($rewrite->actions->children() as $action) {
                        if (empty($action->to)) {
                            continue;
                        }
                        $rewriteInfo = explode('/', (string)$action->to);
                        if (sizeof($rewriteInfo) !== 3
                            || empty($rewriteInfo[0]) || empty($rewriteInfo[1]) || empty($rewriteInfo[2])
                        ) {
                            continue;
                        }
                        $rewritesData[] = $this->_getControllerRewriteData(
                            $coreFrontName, $coreController, $action->getName(),
                            $rewriteInfo[0], $rewriteInfo[1], $rewriteInfo[2]
                        );
                    }
                }
            }
        }

        $systemReport['Controller Rewrites'] = array(
            'header' => array('Core Controller', 'Core Action(s)', 'Custom Controller', 'Custom Action(s)'),
            'data' => $rewritesData
        );

        return $systemReport;
    }

    /**
     * Generate controller rewrite data
     *
     * @param string $coreFrontName
     * @param string $coreController
     * @param string $coreAction
     * @param string $customFrontName
     * @param string $customController
     * @param string $customAction
     *
     * @return array
     */
    protected function _getControllerRewriteData($coreFrontName, $coreController, $coreAction, $customFrontName,
        $customController, $customAction
    ) {
        $coreModule = $this->_getRealModuleNameByFrontName($coreFrontName);
        if ($coreModule) {
            $coreClass = $this->_getControllerClassName($coreModule, $coreController);
            $coreClass .= ' [' . $this->_getModuleCodePoolByClassName($coreClass) . ']';
            $coreClass .= "\n" . '{' . $this->_getControllerFileName($coreModule, $coreController) . '}';
        } else {
            $coreClass = $coreFrontName . '/' . $coreController;
        }

        $customModule = $this->_getRealModuleNameByFrontName($customFrontName);
        if ($customModule) {
            $customClass = $this->_getControllerClassName($customModule, $customController);
            $customClass .= ' [' . $this->_getModuleCodePoolByClassName($customClass) . ']';
            $customClass .= "\n" . '{' . $this->_getControllerFileName($customModule, $customController) . '}';
        } else {
            $customClass = $customFrontName . '/' . $customController;
        }

        return array($coreClass, $coreAction, $customClass, $customAction);
    }

    /**
     * Get real module name by controller front name
     *
     * @param string $frontName
     * @return bool|string
     */
    protected function _getRealModuleNameByFrontName($frontName)
    {
        /** @var $frontendNameNode Mage_Core_Model_Config_Element */
        $frontendNameNode = Mage::app()->getConfig()->getNode('frontend')
            ->xpath('.//routers/*/*/frontName[text()="' . $frontName . '"]');
        if (!$frontendNameNode) {
            return false;
        }
        if ($frontendNameNode[0]->getParent() && !empty($frontendNameNode[0]->getParent()->module)) {
            return (string)$frontendNameNode[0]->getParent()->module;
        }

        return false;
    }

    /**
     * Get controller file name by it's name and module
     *
     * @param string $realModule
     * @param string $controller
     * @return mixed|string
     */
    protected function _getControllerFileName($realModule, $controller)
    {
        $parts = explode('_', $realModule);
        $realModule = implode('_', array_splice($parts, 0, 2));
        $file = Mage::getModuleDir('controllers', $realModule);
        $file = str_replace($this->_getRootPath(), '', $file);
        if (count($parts)) {
            $file .= DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts);
        }
        $file .= DIRECTORY_SEPARATOR . uc_words($controller, DIRECTORY_SEPARATOR) . 'Controller.php';

        return $file;
    }

    /**
     * Get controller class name by it's name and module
     *
     * @param string $realModule
     * @param string $controller
     * @return string
     */
    protected function _getControllerClassName($realModule, $controller)
    {
        $class = $realModule.'_'.uc_words($controller).'Controller';
        return $class;
    }

    /**
     * Generate routers rewrite data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateRouterRewritesData(array $arguments = array())
    {
        $systemReport = array();
        $rewrites = Mage::getConfig()->getNode('global/rewrite');
        if (!$rewrites) {
            $systemReport['Router Rewrites'] = array(
                'header' => array('From', 'To'),
                'data' => array()
            );
            return $systemReport;
        }

        $rewritesData = array();
        foreach ($rewrites->children() as $rewrite) {
            $from = (string)$rewrite->from;
            $to = (string)$rewrite->to;
            if (empty($from) || empty($to)) {
                continue;
            }
            $rewritesData[] = array($from, $to);
        }

        $systemReport['Router Rewrites'] = array(
            'header' => array('From', 'To'),
            'data' => $rewritesData
        );

        return $systemReport;
    }

    /**
     * Generate Magento version data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateMagentoVersionData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Magento Version'] = array(
            'header' => array('Version'),
            'data' => array($this->_magentoEdition . ' ' . $this->_magentoVersion)
        );

        return $systemReport;
    }

    /**
     * Generate sysreport tool version data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateToolVersionData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Tool Version'] = array(
            'header' => array('Version'),
            'data' => array($this->getVersion())
        );

        return $systemReport;
    }

    /**
     * Generate Magento version data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateToolSupportedMagentoVersionsData(array $arguments = array())
    {
        $systemReport = $data = $header = array();
        $maxRows = $row = $column = 0;
        foreach ($this->_supportedMagentoVersions as $edition => $versions) {
            $header[] = strtoupper($edition);
            foreach ($versions as $version) {
                if (!array_key_exists($row, $data)) {
                    $data[$row] = array($column => $version);
                } else {
                    $data[$row][$column] = $version;
                }
                $row++;
            }
            if ($maxRows > $row) {
                for ($i = $row; $i < $maxRows; $i++) {
                    $data[$i][$column] = '';
                }
            }
            if ($row > $maxRows) {
                $maxRows = $row;
            }
            $row = 0;
            $column++;
        }
        $systemReport['Supported Magento Versions'] = array(
            'header' => $header,
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate installed all modules data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateAllModulesData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['All Modules List'] = array(
            'header' => array(
                'Module', 'Code Pool', 'Config Version', 'DB Version', 'DB Data Version', 'Output', 'Enabled'
            ),
            'data' => $this->_getModules('all')
        );

        return $systemReport;
    }

    /**
     * Generate installed core modules data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateCoreModulesData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Core Modules List'] = array(
            'header' => array(
                'Module', 'Code Pool', 'Config Version', 'DB Version', 'DB Data Version', 'Output', 'Enabled'
            ),
            'data' => $this->_getModules('core')
        );

        return $systemReport;
    }

    /**
     * Generate installed enterprise modules data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateEnterpriseModulesData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Enterprise Modules List'] = array(
            'header' => array(
                'Module', 'Code Pool', 'Config Version', 'DB Version', 'DB Data Version', 'Output', 'Enabled'
            ),
            'data' => $this->_getModules('enterprise')
        );

        return $systemReport;
    }

    /**
     * Generate installed custom modules data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateCustomModulesData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Custom Modules List'] = array(
            'header' => array(
                'Module', 'Code Pool', 'Config Version', 'DB Version', 'DB Data Version', 'Output', 'Enabled'
            ),
            'data' => $this->_getModules('custom')
        );

        return $systemReport;
    }

    /**
     * Generate disabled and installed modules data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateDisabledModulesData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Disabled Modules List'] = array(
            'header' => array(
                'Module', 'Code Pool', 'Config Version', 'DB Version', 'DB Data Version', 'Output', 'Enabled'
            ),
            'data' => $this->_getModules(isset($arguments['scope']) ? $arguments['scope'] : 'all', true)
        );

        return $systemReport;
    }

    /**
     * Collect installed modules information by scope
     *
     * @param string $scope
     * @param bool $disabledOnly
     *
     * @return array
     * @throws Exception
     */
    protected function _getModules($scope = 'all', $disabledOnly = false)
    {
        /**
         * Collect modules DB versions
         */
        $dbVersions = array();
        try {
            if (!$this->_readConnection) {
                throw new Exception('Cant\'t connect to DB. Modules DB Version data can\'t be retrieved.');
            }

            $info = $this->_readConnection->fetchAll("SELECT * FROM `{$this->_getTableName('core/resource')}`");
            foreach ($info as $_moduleInfo) {
                $setupNode = Mage::app()->getConfig()->getNode('global/resources')->$_moduleInfo['code'];
                if ($setupNode) {
                    $moduleName = (string)$setupNode->setup->module;
                    $dbVersions[$moduleName] = array(
                        'version' => $_moduleInfo['version'],
                        'data_version' => $_moduleInfo['data_version']
                    );
                }
            }
        } catch (Exception $e) {
            $this->_log($e);
        }

        $scope = !in_array($scope, array('all', 'core', 'enterprise', 'custom')) ? 'all' : $scope;

        $modulesData = array();
        $coreNamespaces = array_flip($this->_coreNamespaces);
        unset($coreNamespaces['Enterprise']);
        $coreNamespaces = array_flip($coreNamespaces);
        $additionalCoreModules = $this->_additionalCoreModules['community'];

        $modules = Mage::app()->getConfig()->getNode('modules');
        if (!$modules) {
            return array();
        }

        /**
         * Collect modules config files to determine if module disabled
         */
        clearstatcache();
        $codeDir = Mage::getBaseDir('code') . DIRECTORY_SEPARATOR;
        $moduleToConfigFileMap = $this->_getModulesConfigFileMap();
        if (empty($moduleToConfigFileMap)) {
            $this->_log(null, 'Can\'t determine if modules enabled/disabled because none of config files can be read.');
        }

        /**
         * Generate modules data list
         */
        foreach ($modules->children() as $module => $info) {
            if ($scope != 'all') {
                $nameSpace = substr($module, 0, strpos($module, '_'));
            } else {
                $nameSpace = '';
            }
            $codePool = (string)$info->codePool;
            if (($scope == 'core' && !in_array($nameSpace, $coreNamespaces)
                && !in_array($module, $additionalCoreModules))
                || ($scope == 'custom' &&
                    (
                        in_array($nameSpace, $coreNamespaces)
                        || $nameSpace == 'Enterprise'
                        || (
                            isset($this->_additionalCoreModules[$codePool])
                                && in_array($module, $this->_additionalCoreModules[$codePool])
                           )
                    )
                   )
                || ($scope == 'enterprise' && $nameSpace != 'Enterprise')
            ) {
                continue;
            }

            $modulePath = $codeDir . $codePool . DIRECTORY_SEPARATOR .
                str_replace('_', DIRECTORY_SEPARATOR, $module) . DIRECTORY_SEPARATOR;
            $moduleExists = is_dir($modulePath);
            $moduleEnabled = isset($moduleToConfigFileMap[$module]);
            if (isset($moduleToConfigFileMap[$module])) {
                $configData = is_readable($moduleToConfigFileMap[$module])
                    ? file_get_contents($moduleToConfigFileMap[$module])
                    : '';
                $searchPattern =
                    '<' . preg_quote($module) . '>\s+' .
                        '.*<active>([^<]+)</active>.+' .
                    '</' . preg_quote($module) . '>';
                if (preg_match('~' . $searchPattern . '~s', $configData, $matches)) {
                    $moduleEnabled = (bool)in_array($matches[1], array('1', 'true'));
                }
            }

            if ($disabledOnly && $moduleExists && $moduleEnabled) {
                continue;
            }

            $modulesData[] = array(
                $module . "\n" . '{' . $this->_getModulePath($module, $codePool) . '}',
                $codePool,
                (string)$info->version ? (string)$info->version : 'n/a',
                isset($dbVersions[$module]) ? $dbVersions[$module]['version'] : 'n/a',
                isset($dbVersions[$module]) ? $dbVersions[$module]['data_version'] : 'n/a',
                !$this->_readConnection
                    ? 'n/a'
                    : (Mage::getStoreConfigFlag('advanced/modules_disable_output/' . $module) ? 'No' : 'Yes'),
                empty($moduleToConfigFileMap) ? 'n/a' : ($moduleExists && $moduleEnabled ? 'Yes' : 'No')
            );
        }

        return $modulesData;
    }

    /**
     * Generate relative path to module directory by its name and code pool
     *
     * @param string $moduleName
     * @param string $codePool
     *
     * @return string
     */
    protected function _getModulePath($moduleName, $codePool)
    {
        return 'app' . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . $codePool . DIRECTORY_SEPARATOR
        . implode(DIRECTORY_SEPARATOR, explode('_', $moduleName)) . DIRECTORY_SEPARATOR;
    }

    /**
     * Generate count data information
     *
     * Supported counting for:
     * Stores, Tax Rules, Customers, Customer Attributes, Customer Segments, Orders, Categories, Products,
     * Product Attributes, URL Rewrites, Shopping Cart Price Rules, Catalog Price Rules, CMS Pages, Banners,
     * Log Visitors, Log Visitors Online, Log URLs, Log Quotes, Log Customers
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateCountData(array $arguments = array())
    {
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. Count data can\'t be retrieved.');
        }

        $connection = $this->_readConnection;
        $dataCount = array();

        // Stores number
        try {
            $info = $connection->fetchAll("SELECT COUNT(1) as cnt FROM `{$this->_getTableName('core/store')}`");
            $dataCount[] = array('Stores', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Tax Rules number
        try {
            $info = $connection->fetchAll(
                "SELECT COUNT(1) as cnt FROM `{$this->_getTableName('tax/tax_calculation_rule')}`"
            );
            $dataCount[] = array('Tax Rules', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Customers number
        try {
            $info = $connection->fetchAll("SELECT COUNT(1) as cnt FROM `{$this->_getTableName('customer/entity')}`");
            $dataCount[] = array('Customers', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
        } catch (Exception $e) {
            $this->_log($e);
        }

        $count = sizeof($dataCount);

        // Customer Attributes number
        try {
            $_info = $this->_getAttributesCount('customer');
            foreach ($_info as $_infoEntry) {
                $dataCount[] = $_infoEntry;
            }
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Customer Address Attributes number
        try {
            $_info = $this->_getAttributesCount('customer_address');
            foreach ($_info as $_infoEntry) {
                $dataCount[] = $_infoEntry;
            }
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Customer Segments
        try {
            if ($this->_properties['is_enterprise_mode']) {
                $info = $connection->fetchAll(
                    "SELECT `is_active` FROM `{$this->_getTableName('enterprise_customersegment/segment')}`"
                );
                if ($info) {
                    $counter = 0;
                    foreach ($info as $_data) {
                        if ($_data['is_active']) {
                            $counter++;
                        }
                    }
                    $dataCount[] = array('Customer Segments', sizeof($info), 'Active Segments: ' . $counter);
                } else {
                    $dataCount[] = array('Customer Segments', 0);
                }
                $count++;
            }
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Orders number
        try {
            $info = $connection->fetchAll("SELECT COUNT(1) as cnt FROM `{$this->_getTableName('sales/order')}`");
            $dataCount[] = array('Sales Orders', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Categories number
        try {
            $info = $connection->fetchAll("SELECT COUNT(1) as cnt FROM `{$this->_getTableName('catalog/category')}`");
            $dataCount[] = array('Categories', isset($info[0]['cnt']) ? --$info[0]['cnt'] : 0);
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Category Attributes number
        try {
            $_info = $this->_getAttributesCount('category');
            foreach ($_info as $_infoEntry) {
                $dataCount[] = $_infoEntry;
            }
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Products number
        try {
            $info = $connection->fetchAll("
                SELECT COUNT(1) as cnt, `type_id` FROM `{$this->_getTableName('catalog/product')}` GROUP BY `type_id`
            ");
            if ($info) {
                $counter = 0;
                $extra = '';
                foreach ($info as $_data) {
                    $counter += $_data['cnt'];
                    $extra .= $_data['type_id'] . ': ' . $_data['cnt'] . '; ';
                }
                $dataCount[] = array('Products', $counter, 'Product Types: ' . $extra);
            } else {
                $dataCount[] = array('Products', 0);
            }
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Product Attributes number
        try {
            $_info = $this->_getAttributesCount('product');
            foreach ($_info as $_infoEntry) {
                $dataCount[] = $_infoEntry;
            }
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Product Attributes Flat Table Row Size
        try {
            $_info = $this->_getProductAttributesRowSizeForFlatTable();
            $dataCount[] = array(
                'Product Attributes Flat Table Row Size',
                $_info > 0 ? $this->_formatBytes($_info) : 'n/a',
                $_info . ' bytes'
            );
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Shopping Cart Price Rules number
        try {
            $info = $connection->fetchAll("SELECT COUNT(1) as cnt FROM `{$this->_getTableName('salesrule/rule')}`");
            $dataCount[] = array('Shopping Cart Price Rules', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Catalog Price Rules number
        try {
            $info = $connection->fetchAll("SELECT COUNT(1) as cnt FROM `{$this->_getTableName('catalogrule/rule')}`");
            $dataCount[] = array('Catalog Price Rules', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Target Rules (Rule-Based Relations) number
        try {
            if ($this->_properties['is_enterprise_mode']) {
                $info = $connection->fetchAll("
                SELECT COUNT(1) as cnt FROM `{$this->_getTableName('enterprise_targetrule/rule')}`
            ");
                $dataCount[] = array('Target Rules', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
                $count++;
            }
        } catch (Exception $e) {
            $this->_log($e);
        }

        // CMS Pages number
        try {
            $info = $connection->fetchAll("SELECT COUNT(1) as cnt FROM `{$this->_getTableName('cms/page')}`");
            $dataCount[] = array('CMS Pages', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Banners number
        try {
            if ($this->_properties['is_enterprise_mode']) {
                $info = $connection->fetchAll("
                SELECT COUNT(1) as cnt FROM `{$this->_getTableName('enterprise_banner/banner')}`
            ");
                $dataCount[] = array('Banners', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
                $count++;
            }
        } catch (Exception $e) {
            $this->_log($e);
        }

        // URL Rewrites number
        try {
            $urlRewriteTable = $this->_getTableName('core/url_rewrite');
            if ((version_compare($this->_magentoVersion, '1.13.0.0', '>=') && $this->_magentoEdition == 'EE')) {
                $urlRewriteTable = $this->_getTableName('enterprise_urlrewrite/url_rewrite');
            }
            $info = $connection->fetchAll("SELECT COUNT(1) as cnt FROM `{$urlRewriteTable}`");
            $dataCount[] = array('URL Rewrites', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // URL Redirects number
        if ((version_compare($this->_magentoVersion, '1.13.0.0', '>=') && $this->_magentoEdition == 'EE')) {
            try {
                $info = $connection->fetchAll(
                    "SELECT COUNT(1) as cnt FROM `{$this->_getTableName('enterprise_urlrewrite/redirect')}`"
                );
                $dataCount[] = array('URL Redirects', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
                $count++;
            } catch (Exception $e) {
                $this->_log($e);
            }
        }

        // Core Cache records
        try {
            $info = $connection->fetchAll("SELECT COUNT(1) as cnt FROM `{$this->_getTableName('core/cache')}`");
            $dataCount[] = array('Core Cache Records', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Core Cache Tag records
        try {
            $info = $connection->fetchAll("SELECT COUNT(1) as cnt FROM `{$this->_getTableName('core/cache_tag')}`");
            $dataCount[] = array('Core Cache Tags', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Log Visitors number
        try {
            $info = $connection->fetchAll("SELECT COUNT(1) as cnt FROM `{$this->_getTableName('log/visitor')}`");
            $dataCount[] = array('Log Visitors', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Log Visitors Online number
        try {
            $info = $connection->fetchAll("SELECT COUNT(1) as cnt FROM `{$this->_getTableName('log/visitor_online')}`");
            $dataCount[] = array('Log Visitors Online', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Log URLs number
        try {
            $info = $connection->fetchAll("SELECT COUNT(1) as cnt FROM `{$this->_getTableName('log/url_table')}`");
            $dataCount[] = array('Log URLs', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Log Quotes number
        try {
            $info = $connection->fetchAll("SELECT COUNT(1) as cnt FROM `{$this->_getTableName('log/quote_table')}`");
            $dataCount[] = array('Log Quotes', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Log Customers number
        try {
            $info = $connection->fetchAll("SELECT COUNT(1) as cnt FROM `{$this->_getTableName('log/customer')}`");
            $dataCount[] = array('Log Customers', isset($info[0]['cnt']) ? $info[0]['cnt'] : 0);
            $count++;
        } catch (Exception $e) {
            $this->_log($e);
        }

        $systemReport = array();
        $systemReport['Data Count'] = array(
            'header' => array('Entity', 'Count', 'Extra'),
            'data' => $dataCount,
            'count' => $count
        );

        return $systemReport;
    }

    /**
     * Collect catalog attributes information
     *
     * @param string $type
     * @return array
     *
     * @throws Exception
     */
    protected function _getAttributesCount($type = 'product')
    {
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. Count data can\'t be retrieved.');
        }

        $connection = $this->_readConnection;
        $entityTypeCode = null;
        switch ($type) {
            case 'customer':
                $title = 'Customer Attributes';
                $entityTypeCode = $type;
                $flagColumns = array(
                    'main_table.`is_system`',
                    'main_table.`is_visible`',
                );
                if ($this->_magentoEdition != 'CE') {
                    $flagColumns[] = 'main_table.`is_used_for_customer_segment`';
                }
                $eavMainTable = $this->_getTableName('customer/eav_attribute');
                break;
            case 'customer_address':
                $title = 'Customer Address Attributes';
                $entityTypeCode = $type;
                $flagColumns = array(
                    'main_table.`is_system`',
                    'main_table.`is_visible`',
                );
                if ($this->_magentoEdition != 'CE') {
                    $flagColumns[] = 'main_table.`is_used_for_customer_segment`';
                }
                $eavMainTable = $this->_getTableName('customer/eav_attribute');
                break;
            case 'category':
                $title = 'Category Attributes';
                $entityTypeCode = Mage_Catalog_Model_Category::ENTITY;
                $flagColumns = array(
                    'main_table.`is_visible_on_front`',
                    'main_table.`is_used_for_promo_rules`'
                );
                $eavMainTable = $this->_getTableName('catalog/eav_attribute');
                break;
            case 'product':
                $title = 'Product Attributes';
                $entityTypeCode = Mage_Catalog_Model_Product::ENTITY;
                $flagColumns = array(
                    'main_table.`is_visible_on_front`',
                    'main_table.`is_searchable`',
                    'main_table.`is_filterable`',
                    'main_table.`is_used_for_promo_rules`'
                );
                $eavMainTable = $this->_getTableName('catalog/eav_attribute');
                break;
            default:
                throw new Exception(
                    '_getAttributesInfo() doesn\'t support specified attributes entity type: "' . (string)$type . '".'
                    . ' Count data can\'t be retrieved.'
                );
                break;
        }

        $result = array();
        $entityTypeId = (int)Mage::getSingleton('eav/config')->getEntityType($entityTypeCode)->getId();
        $flagColumns = implode(', ', $flagColumns);
        $info = $connection->fetchAll("
            SELECT ea.`backend_type`, ea.`is_user_defined`, {$flagColumns}
            FROM `{$eavMainTable}` `main_table`
            INNER JOIN `{$this->_getTableName('eav/attribute')}` ea ON
                (ea.`attribute_id` = main_table.`attribute_id` AND ea.`entity_type_id` = '{$entityTypeId}')
        ");
        if ($info) {
            $_byType = $_extra = array();
            foreach ($info as $_data) {
                foreach ($_data as $key => $data) {
                    if ($key == 'backend_type') {
                        if (!isset($_byType[$_data[$key]])) {
                            $_byType[$_data[$key]] = 0;
                        }
                        $_byType[$_data[$key]]++;
                    } else {
                        if (!isset($_extra[$key])) {
                            $_extra[$key] = 0;
                        }
                        if ($_data[$key]) {
                            $_extra[$key]++;
                        }
                    }
                }
            }
            $extra1 = $extra2 = '';
            foreach ($_extra as $key => $num) {
                $extra1 .= $key . ': ' . $num . '; ';
            }
            foreach ($_byType as $key => $num) {
                $extra2 .= $key . ': ' . $num . '; ';
            }
            $result[] = array($title, sizeof($info), 'Attributes Flags: ' . $extra1);
            $result[] = array('', '', 'Attributes Types: ' . $extra2);
        } else {
            $result[] = array($title, 0);
        }

        return $result;
    }

    /**
     * Calculate approximately the size of table row if using flat functionality based on product attributes list
     *
     * @return int
     *
     * @throws Exception
     */
    protected function _getProductAttributesRowSizeForFlatTable()
    {
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. Count data can\'t be retrieved.');
        }

        $connection = $this->_readConnection;
        $entityTypeId = (int)Mage::getSingleton('eav/config')
            ->getEntityType(Mage_Catalog_Model_Product::ENTITY)->getId();
        $info = $connection->fetchAll("
            SELECT ea.`backend_type`
            FROM `{$this->_getTableName('catalog/eav_attribute')}` `main_table`
            INNER JOIN `{$this->_getTableName('eav/attribute')}` ea ON
                (ea.`attribute_id` = main_table.`attribute_id` AND ea.`entity_type_id` = '{$entityTypeId}')
        ");

        /**
         * Dynamic EAV attributes
         *
         * @see http://dev.mysql.com/doc/refman/5.0/en/storage-requirements.html
         */
        $typeSizes = array(
            'varchar'   => (255 + 1) * 3,
            'int'       => 4,
            'datetime'  => 8,
            'decimal'   => 4 + 2, // because decimal type = DECIMAL(12, 4)
        );
        $result = 0;
        if (!$info) {
            return false;
        }
        $_byType = array();
        foreach ($info as $_data) {
            if ($_data['backend_type'] == 'static') {
                continue;
            }
            if (!isset($_byType[$_data['backend_type']])) {
                $_byType[$_data['backend_type']] = 0;
            }
            $_byType[$_data['backend_type']]++;
        }
        foreach ($_byType as $type => $count) {
            if (isset($typeSizes[$type])) {
                $result += $typeSizes[$type] * $count;
            }
        }

        /**
         * Static product entity attributes
         *
         * @see http://dev.mysql.com/doc/refman/5.0/en/storage-requirements.html
         */
        $typeSizes = array(
            'tinyint'   => 1,
            'smallint'  => 2,
            'mediumint' => 3,
            'int'       => 4,
            'integer'   => 4,
            'bigint'    => 8,
            'float'     => 4,
            'double'    => 8,
            'real'      => 8,
            'date'      => 3,
            'time'      => 3,
            'datetime'  => 8,
            'timestamp' => 4,
            'year'      => 1,
        );
        $describe = $connection->describeTable($this->_getTableName('catalog/product'));
        if (empty($describe) || !is_array($describe)) {
            return false;
        }
        foreach ($describe as $column) {
            if (isset($typeSizes[$column['DATA_TYPE']])) {
                $result += $typeSizes[$column['DATA_TYPE']];
            } else if ($column['DATA_TYPE'] == 'varchar') {
                $result += ($column['LENGTH'] + 1) * 3;
            } else if ($column['DATA_TYPE'] == 'decimal') {
                $leftOver = $column['PRECISION'] - floor($column['PRECISION'] / 9) * 9;
                $result += floor($column['PRECISION'] / 9) * 4 + ceil($leftOver / 2);
            }
        }

        return (int)$result;
    }

    /**
     * Generate major configuration data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateConfigurationData(array $arguments = array())
    {
        // Supported configurations
        $configPaths = array(
            // url, STORE VIEW
            array('path' => 'web/secure/base_url', 'name' => 'Base Secured URL'),
            // url, STORE VIEW
            array('path' => 'web/unsecure/base_url', 'name' => 'Base Unsecured URL'),
            // text, WEBSITE
            array('path' => 'currency/options/base', 'name' => 'Base Currency'),
            // 1, STORE VIEW
            array('path' => 'dev/log/active', 'name' => 'Enable Log', 'enabled_flag' => true),
            // 1, GLOBAL
            array('path' => 'system/log/enabled', 'name' => 'Log Tables Cleaning', 'enabled_flag' => true),
            // 1, STORE VIEW
            array('path' => 'dev/js/merge_files', 'name' => 'Merge JavaScript Files', 'enabled_flag' => true),
            // 1, STORE VIEW
            array('path' => 'dev/css/merge_css_files', 'name' => 'Merge CSS Files', 'enabled_flag' => true),
            // 1, GLOBAL
            array('path' => 'admin/security/use_form_key', 'name' => 'Add Secret Key to URLs', 'enabled_flag' => true),
            // 1, GLOBAL
            array(
                'path' => 'catalog/frontend/flat_catalog_category',
                'name' => 'Flat Catalog Category',
                'enabled_flag' => true
            ),
            // 1, GLOBAL
            array(
                'path' => 'catalog/frontend/flat_catalog_product',
                'name' => 'Flat Catalog Product',
                'enabled_flag' => true
            ),
            // 1, WEBSITE
            array('path' => 'tax/weee/enable', 'name' => 'Fixed Product Taxes (FPT)', 'enabled_flag' => true),
            // 1, GLOBAL
            array('path' => 'compiler', 'name' => 'Compilation', 'enabled_flag' => true),
            // 1, GLOBAL
            array('path' => 'maintenance_mode', 'name' => 'Maintenance Mode', 'enabled_flag' => true),
        );
        if ($this->_properties['is_enterprise_mode']) {
            // 1, GLOBAL
            $configPaths[] = array(
                'path' => 'solr_engine',
                'name' => 'Solr Search',
                'enabled_flag' => true
            );
        }
        $configPaths = array_merge($configPaths, array(
            // 1, GLOBAL
            array('path' => 'catalog/search/engine', 'name' => 'Search Engine'),
            // custom
            array('path' => 'table_prefix', 'name' => 'DB Table Prefix'),
            // text, STORE VIEW
            array('path' => 'web/cookie/cookie_lifetime', 'name' => 'Cookie Lifetime'),
            // text, STORE VIEW
            array('path' => 'web/cookie/cookie_path', 'name' => 'Cookie Path'),
            // text, STORE VIEW
            array('path' => 'web/cookie/cookie_domain', 'name' => 'Cookie Domain'),
            // 1, STORE VIEW
            array('path' => 'web/cookie/cookie_httponly', 'name' => 'Use HTTP Only', 'enabled_flag' => true),
            // 1, WEBSITE
            array('path' => 'web/cookie/cookie_restriction', 'name' =>'Cookie Restriction Mode','enabled_flag' => true),
            // 1, GLOBAL
            array(
                'path' => 'web/session/use_remote_addr',
                'name' => 'Validate REMOTE_ADDR',
                'enabled_flag' => true
            ),
            // 1, GLOBAL
            array(
                'path' => 'web/session/use_http_via',
                'name' => 'Validate HTTP_VIA',
                'enabled_flag' => true
            ),
            // 1, GLOBAL
            array(
                'path' => 'web/session/use_http_x_forwarded_for',
                'name' => 'Validate HTTP_X_FORWARDED_FOR',
                'enabled_flag' => true
            ),
            // 1, GLOBAL
            array(
                'path' => 'web/session/use_http_user_agent',
                'name' => 'Validate HTTP_USER_AGENT',
                'enabled_flag' => true
            ),
            // 1, WEBSITE
            array('path' => 'web/session/use_frontend_sid', 'name' => 'Use SID on Frontend', 'enabled_flag' => true),
        ));
        if ($this->_properties['is_enterprise_mode']) {
            // 1, STORE VIEW
            $configPaths[] = array(
                'path' => 'system/page_crawl/enable',
                'name' => 'Full Page Cache Crawler',
                'enabled_flag' => true
            );
            // 1, GLOBAL
            $configPaths[] = array(
                'path' => 'customer/enterprise_customersegment/is_enabeld',
                'name' => 'Customer Segment Functionality',
                'enabled_flag' => true
            );
        }

        $data = array();
        $configData = $this->_getConfigValues($configPaths);
        foreach ($configData as $info) {
            $data[] = array(
                $info['name'],
                $info['enabled'],
                $info['value'],
                $info['scope']
            );
        }

        $systemReport = array();
        $systemReport['Configuration'] = array(
            'header' => array('Name', 'Enabled', 'Value', 'Scope'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate app/etc/local.xml configuration data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateEtcLocalXmlData(array $arguments = array())
    {
        $data = array();
        $xmlFile = Mage::getBaseDir('etc') . DIRECTORY_SEPARATOR . 'local.xml';
        $parsedXmlData = $this->_loadAndParseXmlConfigFile($xmlFile);
        foreach ($parsedXmlData as $key => $value) {
            $data[] = array($key, $value);
        }

        $systemReport = array();
        $systemReport['Data from app/etc/local.xml'] = array(
            'header' => array('Path', 'Value'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate app/etc/config.xml configuration data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateEtcConfigXmlData(array $arguments = array())
    {
        $data = array();
        $xmlFile = Mage::getBaseDir('etc') . DIRECTORY_SEPARATOR . 'config.xml';
        $parsedXmlData = $this->_loadAndParseXmlConfigFile($xmlFile);
        foreach ($parsedXmlData as $key => $value) {
            $data[] = array($key, $value);
        }

        $systemReport = array();
        $systemReport['Data from app/etc/config.xml'] = array(
            'header' => array('Path', 'Value'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate app/etc/enterprise.xml configuration data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateEtcEnterpriseXmlData(array $arguments = array())
    {
        $data = array();
        $xmlFile = Mage::getBaseDir('etc') . DIRECTORY_SEPARATOR . 'enterprise.xml';
        $parsedXmlData = $this->_loadAndParseXmlConfigFile($xmlFile);
        foreach ($parsedXmlData as $key => $value) {
            $data[] = array($key, $value);
        }

        $systemReport = array();
        $systemReport['Data from app/etc/enterprise.xml'] = array(
            'header' => array('Path', 'Value'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Load and parse local.xml config file
     *
     * @param $file
     *
     * @return array
     */
    protected function _loadAndParseXmlConfigFile($file)
    {
        static $parsedXmlData = array();
        if (!isset($parsedXmlData[$file])) {
            if (!is_readable($file)) {
                return array();
            }
            $xmlObject = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOCDATA);
            $parsedXmlData[$file] = $this->_parseXmlObject($xmlObject);
        }

        return $parsedXmlData[$file];
    }

    /**
     * Parse XML tree
     *
     * @param SimpleXMLElement $xmlObject
     * @param bool $resetStaticData
     *
     * @return array
     */
    protected function _parseXmlObject($xmlObject, $resetStaticData = true)
    {
        static $nodeLevel = 0;
        if ($resetStaticData) {
            $nodeLevel = 0;
        }
        $data = array();
        $indent = str_repeat(' ', $nodeLevel * 4);
        /** @var $value SimpleXMLElement */
        foreach ($xmlObject as $key => $value) {
            if (sizeof($value->children()) > 0) {
                $data[$indent . '<' . $key . '>'] = '';
                $nodeLevel++;
                $data = array_merge($data, $this->_parseXmlObject($value, false));
            } else {
                $data[$indent . '<' . $key . '>'] = in_array($key, $this->_xmlConfigRestrictedFields)
                    ? '****'
                    : (string) $value;
            }
        }
        $nodeLevel--;

        return $data;
    }

    /**
     * Generate Shipping Methods information
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateShippingMethodsData(array $arguments = array())
    {
        $data = $configPaths = array();
        $methods = Mage::app()->getConfig()->getNode('default/carriers');
        if ($methods) {
            foreach ($methods->children() as $code => $info) {
                if ((string)$code == 'googlecheckout') {
                    $codes = array(
                        'checkout_shipping_merchant' => 'Google Checkout Shipping - Merchant Calculated',
                        'checkout_shipping_carrier'  => 'Google Checkout Shipping - Carrier Calculated',
                        'checkout_shipping_flatrate' => 'Google Checkout Shipping - Flat Rate',
                        'checkout_shipping_virtual'  => 'Google Checkout Shipping - Digital Delivery',
                    );
                    foreach ($codes as $_code => $title) {
                        $configPaths[] = array(
                            'path' => 'google/' . $_code . '/active',
                            'name' => $title,
                            'enabled_flag' => true,
                            'extra' => array(
                                'code' => $_code,
                                'title' => $title,
                                'name' => ''
                            )
                        );
                    }
                    continue;
                }
                $configPaths[] = array(
                    'path' => 'carriers/' . $code . '/active',
                    'name' => (string)$info->title ? (string)$info->title : $code,
                    'enabled_flag' => true,
                    'extra' => array(
                        'code' => (string)$code,
                        'title' => (string)$info->title,
                        'name' => (string)$info->name
                    )
                );
            }
        }
        $configData = $this->_getConfigValues($configPaths);
        foreach ($configData as $path) {
            $data[] = array(
                $path['extra']['code'],
                $path['extra']['name'],
                $path['extra']['title'],
                $path['enabled'],
                $path['scope']
            );
        }

        $systemReport = array();
        $systemReport['Shipping Methods'] = array(
            'header' => array('Code', 'Name', 'Title', 'Enabled', 'Scope'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate Payment Methods information
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generatePaymentMethodsData(array $arguments = array())
    {
        $methods = Mage::app()->getConfig()->getNode('default/payment');
        $firstMethods = $nextMethods = $methodsConfig = array();
        if (!$methods) {
            return array();
        }

        foreach ($methods->children() as $code => $info) {
            if (substr($code, 0, 8) == 'pbridge_' && $code != 'pbridge_ogone_direct') {
                continue;
            }
            $scopes = array();

            $group = (string)$info->group;
            if (!$group && $code != 'authorizenet') {
                $group = substr($code, 0, 7) == 'pbridge' ? 'pbridge' : '';
                $group = $group ? $group : ($info->using_pbridge ? 'pbridge' : '');
            }

            $path = 'payment/' . $code . '/active';
            if ($code == 'googlecheckout') {
                $path = 'google/checkout/active';
            }
            $isEnabledValues = $this->_getConfigValues(
                array(
                    array(
                        'path' => $path,
                        'name' => (string)$code,
                        'enabled_flag' => true,
                    )
                )
            );
            foreach ($isEnabledValues as $value) {
                $scopes[] = $value['scope'];
                $isEnabledValues[$value['scope']] = $value;
            }
            $viaPBridgeValues = $this->_getConfigValues(
                array(
                    array(
                        'path' => 'payment/' . $code . '/using_pbridge',
                        'name' => (string)$code,
                        'enabled_flag' => true,
                    )
                )
            );
            foreach ($viaPBridgeValues as $value) {
                $scopes[] = $value['scope'];
                $viaPBridgeValues[$value['scope']] = $value;
            }
            $methodsConfig = array(
                'code' => (string)$code,
                'title' => (string)$info->title,
                'group' => $group,
                'enabled' => $isEnabledValues,
                'viapbridge' => $viaPBridgeValues,
                'scopes' => array_merge(array_unique($scopes), array()),
            );

            if ($group == '' || $group == 'offline' || $group == 'pbridge') {
                $firstMethods[] = $methodsConfig;
            } else {
                $nextMethods[] = $methodsConfig;
            }
        }

        $methodsConfig = array_merge($firstMethods, $nextMethods);
        $data = array();
        foreach ($methodsConfig as $config) {
            foreach ($config['scopes'] as $scope) {
                $data[] = array(
                    $config['code'],
                    $config['group'],
                    $config['title'],
                    isset($config['enabled'][$scope]['enabled'])
                        ? $config['enabled'][$scope]['enabled']
                        : $config['enabled']['[Default]']['enabled'],
                    isset($config['viapbridge'][$scope]['enabled'])
                        ? $config['viapbridge'][$scope]['enabled']
                        : $config['viapbridge']['[Default]']['enabled'],
                    $scope,
                );
            }
        }

        $systemReport = array();
        $systemReport['Payment Methods'] = array(
            'header' => array('Code', 'Group', 'Title', 'Enabled', 'VIA PBridge', 'Scope'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate Payments Functionality Matrix
     * Data will be collected only in PHP >= 5.3.0, because required method ReflectionProperty::setAccessible()
     * was implemented in PHP 5.3.0
     *
     * Supported Payments Info:
     * - Code
     * - Name
     * - Group
     * - Is Gateway
     * - Can Void
     * - Is Used For Checkout
     * - Is Used For Multishipping
     * - Capture Online
     * - Partial Capture Online
     * - Refund Online
     * - Partial Refund Online
     * - Capture Offline
     * - Partial Capture Offline
     * - Refund Offline
     * - Partial Refund Offline
     *
     * @param array $arguments
     *
     * @throws Exception
     * @return array
     */
    protected function _generatePaymentsFunctionalityMatrixData(array $arguments = array())
    {
        $data = array();
        /**
         * ReflectionProperty::setAccessible was implemented in PHP 5.3.0
         *
         * @link http://de2.php.net/manual/en/reflectionproperty.setaccessible.php
         */
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            throw new Exception(
                'ReflectionProperty::setAccessible is required for data collection, ' .
                 'but it was implemented in PHP 5.3.0; your PHP version is ' . PHP_VERSION . ".\n" .
                 'Payments Functionality Matrix is not available.'
            );
        }

        $methods = Mage::app()->getConfig()->getNode('default/payment');
        if ($methods) {
            $methods = $methods->children();
            foreach ($methods as $code => $info) {
                try {
                    $name = (string)$info->title ? (string)$info->title : 'n/a';
                    $group = (string)$info->group;
                    if (!$group && $code != 'authorizenet') {
                        $group = substr($code, 0, 7) == 'pbridge' ? 'pbridge' : '';
                        $group = $group ? $group : ($info->using_pbridge ? 'pbridge' : '');
                    }

                    if (substr($code, 0, 8) == 'pbridge_' && $code != 'pbridge_ogone_direct') {
                        continue;
                    }

                    /** @var $paymentHelper Mage_Payment_Helper_Data */
                    $paymentHelper = Mage::helper('payment');
                    $payment = $paymentHelper->getMethodInstance($code);
                    // Try to define if method proxies through Payment Bridge,
                    // if yes then collect data for PB payment method
                    if (!$payment) {
                        $pBridgePaymentMethodCode = 'pbridge_' . $code;
                        $payment = $paymentHelper->getMethodInstance($pBridgePaymentMethodCode);
                    }

                    if (!$payment) {
                        $data[] = array(
                            $code, $name, $group,
                            'n/a', 'n/a', 'n/a', 'n/a', 'n/a', 'n/a', 'n/a', 'n/a', 'n/a', 'n/a', 'n/a', 'n/a'
                        );
                        continue;
                    }
                    $reflectionPayment = new ReflectionObject($payment);

                    $isGateway = $reflectionPayment->getProperty('_isGateway');
                    $isGateway->setAccessible(true);

                    $canVoid = $reflectionPayment->getProperty('_canVoid');
                    $canVoid->setAccessible(true);
                    $canUseCheckout = $reflectionPayment->getProperty('_canUseCheckout');
                    $canUseCheckout->setAccessible(true);
                    $canUseForMultishipping = $reflectionPayment->getProperty('_canUseForMultishipping');
                    $canUseForMultishipping->setAccessible(true);

                    $canCapture = $reflectionPayment->getProperty('_canCapture');
                    $canCapture->setAccessible(true);
                    $canCapturePartial = $reflectionPayment->getProperty('_canCapturePartial');
                    $canCapturePartial->setAccessible(true);


                    $canRefund = $reflectionPayment->getProperty('_canRefund');
                    $canRefund->setAccessible(true);
                    $canRefundInvoicePartial = $reflectionPayment->getProperty('_canRefundInvoicePartial');
                    $canRefundInvoicePartial->setAccessible(true);

                    $data[] = array(
                        $code,
                        $name,
                        $group,
                        $isGateway->getValue($payment) ? 'Yes' : 'No',
                        $canVoid->getValue($payment) ? 'Yes' : 'No',
                        $canUseCheckout->getValue($payment) ? 'Yes' : 'No',
                        $canUseForMultishipping->getValue($payment) ? 'Yes' : 'No',

                        $canCapture->getValue($payment) ? 'Yes' : 'No',
                        $canCapture->getValue($payment) && $canCapturePartial->getValue($payment) ? 'Yes' : 'No',
                        $canRefund->getValue($payment) ? 'Yes' : 'No',
                        $canRefund->getValue($payment) && $canRefundInvoicePartial->getValue($payment) ? 'Yes' : 'No',

                        'Yes',
                        $canCapture->getValue($payment) && $canCapturePartial->getValue($payment) ? 'Yes' : 'No',
                        'Yes',
                        $canRefund->getValue($payment) && $canRefundInvoicePartial->getValue($payment) ? 'Yes' : 'No',
                    );
                } catch (Exception $e) {
                    $this->_log($e);
                }
            }
        }

        $systemReport = array();
        $systemReport['Payments Functionality Matrix'] = array(
            'header' => array(
                'Code',
                'Title',
                'Group',
                'Is Gateway',
                'Void',
                'For Checkout',
                'For Multishipping',
                'Capture Online',
                'Partial Capture Online',
                'Refund Online',
                'Partial Refund Online',
                'Capture Offline',
                'Partial Capture Offline',
                'Refund Offline',
                'Partial Refund Offline'
            ),
            'data' => $data
        );

        if (isset($arguments['wiki'])) {
            $path = $this->_getWorkingPath();
            if (!is_writable($path)) {
                $this->_log(null, 'Can\'t write to directory where sysreport tool resides. Wiki file will not be generated.');
            }
            $wikiContents = 'h3. ' . $this->_magentoEdition . ' ' . $this->_magentoVersion . ' (' . sizeof($data) . ')'
                            . "\n";
            $wikiContents .= '{table-plus:enableHighlighting=true|enableSorting=true|sortIcon=true}';
            $wikiContents .= "\n";
            $wikiContents .= '|| '.implode(' || ', $systemReport['Payments Functionality Matrix']['header']).'||';
            $wikiContents .= "\n";
            foreach ($data as $row) {
                foreach ($row as $cell) {
                    $wikiContents .= '| ' . ($cell == 'Yes' || $cell == 'No' ? ($cell == 'Yes' ? '(+)' : '(-)') : $cell)
                                  . ' ';
                }
                $wikiContents .= "|\n";
            }
            $wikiContents .= "{table-plus}\n\n";
            $edition = strtolower($this->_magentoEdition);
            $filename = sprintf(self::REPORT_PAYMENTS_FUNCTIONALITY_WIKI_FILE_MASK, $edition, $this->_magentoVersion);
            $writtenBytes = file_put_contents($path . $filename, $wikiContents, FILE_APPEND);
            $this->_log(null,
                ($writtenBytes !== false
                    ? 'File "' . $filename . '" was successfully generated.'
                    : 'File "' . $filename . '" wasn\'t generated!')
            );
        }

        return $systemReport;
    }

    /**
     * Collect configuration values by specified config paths
     *
     * @param array $configPaths
     *
     * @return array
     *
     * @throws Exception
     */
    protected function _getConfigValues(array $configPaths)
    {
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. Config data for stores can\'t be retrieved.');
        }
        $configData = array();
        $stores = Mage::app()->getStores();

        foreach ($configPaths as $info) {
            try {
                $_configData = $this->_prepareConfigData($info);
                if (!is_null($_configData)) {
                    $configData[] = $_configData;
                    continue;
                }

                $originalDefaultValue = Mage::getStoreConfig($info['path'], Mage_Core_Model_App::DISTRO_STORE_ID);
                $originalDefaultValue = $this->_prepareConfigValue($info, $originalDefaultValue);
                $configData[] = array(
                    'name' => $info['name'],
                    'enabled' => (isset($info['enabled_flag']) ? ($originalDefaultValue ? 'Yes' : 'No') : ''),
                    'value' => isset($info['enabled_flag']) ? '' : $originalDefaultValue,
                    'scope' => '[Default]',
                    'extra' => isset($info['extra']) ? $info['extra'] : null
                );

                // Then determine values which are different from default one
                /** @var $store Mage_Core_Model_Store */
                foreach ($stores as $store) {
                    $value = Mage::getStoreConfig($info['path'], $store);
                    $value = $this->_prepareConfigValue($info, $value);
                    if ($value == $originalDefaultValue) {
                        continue;
                    }
                    $configData[] = array(
                        'name' => $info['name'],
                        'enabled' => (isset($info['enabled_flag']) ? ($value ? 'Yes' : 'No') : ''),
                        'value' => isset($info['enabled_flag']) ? '' : $value,
                        'scope' => '['. $store->getWebsite()->getName() . '] -> ['
                            . $store->getGroup()->getName()   . '] -> [' . $store->getName() . ']',
                        'extra' => isset($info['extra']) ? $info['extra'] : null
                    );
                }
            } catch (Exception $e) {
                $this->_log($e);
            }
        }

        return $configData;
    }

    /**
     * Prepare config value
     *
     * @param array $configInfo
     * @param string $value
     *
     * @return string
     */
    protected function _prepareConfigValue($configInfo, $value)
    {
        if (substr($configInfo['path'], 0, 7) == 'design/' && empty($value)) {
            if ($configInfo['path'] == 'design/package/name') {
                $value = Mage_Core_Model_Design_Package::BASE_PACKAGE;
            } else {
                $value = Mage_Core_Model_Design_Package::DEFAULT_THEME;
            }
        }

        if ($configInfo['path'] == 'catalog/search/engine') {
            switch ($value) {
                case 'catalogsearch/fulltext_engine':
                    $value = 'MySQL Fulltext';
                    break;
                case 'enterprise_search/engine':
                    $value = 'Solr';
                    break;
                default:
                    break;

            }
        }

        return $value;
    }

    /**
     * Prepare config data
     *
     * @param array $configInfo
     * @return array|null
     */
    protected function _prepareConfigData($configInfo)
    {
        if ($configInfo['path'] == 'compiler') {
            $enabled = $this->_isCompilerEnabled() ? 'Yes' : 'No';
            return array(
                'name' => $configInfo['name'],
                'enabled' => $enabled,
                'value' => '',
                'scope' => '[Default]',
                'extra' => null
            );
        }

        if ($configInfo['path'] == 'table_prefix') {
            return array(
                'name' => $configInfo['name'],
                'enabled' => '',
                'value' => (string) Mage::getConfig()->getTablePrefix(),
                'scope' => '[Default]',
                'extra' => null
            );
        }

        if ($configInfo['path'] == 'solr_engine') {
            $value = Mage::getStoreConfig('catalog/search/engine', Mage_Core_Model_App::ADMIN_STORE_ID);
            return array(
                'name' => $configInfo['name'],
                'enabled' => $value == 'enterprise_search/engine' ? 'Yes' : 'No',
                'value' => '',
                'scope' => '[Default]',
                'extra' => null
            );
        }

        if ($configInfo['path'] == 'maintenance_mode') {
            $maintenanceFile = $this->_getRootPath() . self::REPORT_MAINTENANCE_MODE_FLAG_FILE_NAME;
            $value = is_file($maintenanceFile);
            return array(
                'name' => $configInfo['name'],
                'enabled' => $value ? 'Yes' : 'No',
                'value' => '',
                'scope' => '[Default]',
                'extra' => null
            );
        }

        return null;
    }

    /**
     * Generate log files entries count and log files sizes
     * Generate top messages and their last occurrence dates
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateLogFilesData(array $arguments = array())
    {
        $systemReport     = array();
        $logDir           = Mage::getBaseDir('log') . DIRECTORY_SEPARATOR;
        $directoryHandler = opendir($logDir);
        $data             = $exceptions = $sysMessages = $currentSystemMessages = $currentExceptionMessages = array();
        $logFileDetected  = false;
        $filesCount       = 0;
        $currentDate      = date('Y-m-d');
        $systemLogFile    = Mage::getStoreConfig('dev/log/file');
        $exceptionLogFile = Mage::getStoreConfig('dev/log/exception_file');

        if ($directoryHandler) {
            clearstatcache();
            while (($entry = readdir($directoryHandler)) !== false
                    && $filesCount <= self::TABLE_DATA_ROW_MAXIMUM_COUNT_FOR_OUTPUT
            ) {
                $file = $logDir . $entry;

                // Take into account only files with "log" extension
                if (!is_file($file) || substr($entry, strrpos($entry, '.') + 1) != 'log') {
                    continue;
                }

                $logEntriesNumber = 0;
                $fileSize = $this->_getFileSize($file);

                $exceptionStarted = $exceptionEnded = false;
                $exceptionMessage = $exceptionStack = '';

                // If file is not too big then calculate log entries number
                if ($fileSize <= self::MAX_FILE_SIZE_TO_OPEN_FOR_LOG_ENTRIES_CALC && is_readable($file)) {
                    $lines = 0;
                    // To use just small portion of memory fgets() must be used
                    $handle = fopen($file, 'r');
                    while (!feof($handle)) {
                        // But sometimes file can contain long one line which can be very huge,
                        // so defend against such case by reading just 4 KB of data per line
                        $line = fgets($handle, 4096);
                        // This is regular expression for Zend produced log file entries
                        // like 2012-05-11T06:04:45+00:00 ERR (3): ...
                        $matched = (int)preg_match('~^[-0-9]+T[:0-9]+[-+][:0-9]+[^\(]+\([^\)]+\).+$~im', $line);
                        $logEntriesNumber += $matched;

                        // Collect system log messages
                        if ($entry == $systemLogFile
                            && preg_match(
                                '~^([-0-9]+)T([:0-9]+)([-+][:0-9]+)[^\(]+\([^\)]+\):\s(.+)$~im', $line, $matches
                            )
                        ) {
                            $lastDate = $matches[1] . ', ' . $matches[2] . ' [' . $matches[3] . ']';
                            if (!isset($sysMessages[$matches[4]])) {
                                $sysMessages[$matches[4]] = array('count' => 1, 'last_occurrence_date' => $lastDate);
                            } else {
                                $sysMessages[$matches[4]]['count']++;
                                $sysMessages[$matches[4]]['last_occurrence_date'] = $lastDate;
                            }

                            if ($matches[1] == $currentDate) {
                                if (!isset($currentSystemMessages[$matches[4]])) {
                                    $currentSystemMessages[$matches[4]] = array(
                                        'count' => 1,
                                        'last_occurrence_date' => $lastDate
                                    );
                                } else {
                                    $currentSystemMessages[$matches[4]]['count']++;
                                    $currentSystemMessages[$matches[4]]['last_occurrence_date'] = $lastDate;
                                }
                            }
                        }

                        // Collect exception log messages
                        if ($entry == $exceptionLogFile) {
                            // Record date
                            if ($exceptionStarted === false
                                && preg_match('~^([-0-9]+)T([:0-9]+)([-+][:0-9]+)[^\(]+\([^\)]+\).+$~im',$line,$matches)
                            ) {
                                $exceptionStarted = true;
                                $lastDate = $matches[1] . ', ' . $matches[2] . ' [' . $matches[3] . ']';
                                $_exceptionDatePart = $matches[1];
                            }

                            // Record message
                            if ($exceptionStarted === true && $exceptionMessage === ''
                                && (preg_match('~^exception (.+)$~im', $line, $matches)
                                    ||
                                    preg_match('~Exception message:\s*(.+)$~im', $line, $matches)
                                )
                            ) {
                                $exceptionMessage = $matches[1];
                            }

                            // Record exception end flag
                            if ($exceptionEnded === false && $exceptionStarted === true
                                && preg_match('~^\#[0-9]+ \{main\}$~im', $line)
                            ) {
                                $exceptionEnded = true;
                            }

                            // Record stack trace
                            if ($exceptionEnded !== true && $exceptionMessage !== ''
                                && !preg_match('~^(?:Stack trace|Trace)\:\s*$~im', $line)
                                && !preg_match('~^exception .+$~im', $line)
                                && !preg_match('~Exception message:\s*(.+)$~im', $line)
                            ) {
                                $exceptionStack .= $line;
                            }

                            // Add exception data
                            if ($exceptionStarted === true && $exceptionEnded === true) {
                                if (!isset($exceptions[$exceptionMessage])) {
                                    $exceptions[$exceptionMessage] = array(
                                        'count' => 1,
                                        'last_occurrence_date' => $lastDate,
                                        'exception_stack' => $exceptionStack
                                    );
                                } else {
                                    $exceptions[$exceptionMessage]['count']++;
                                    $exceptions[$exceptionMessage]['last_occurrence_date'] = $lastDate;
                                    $exceptions[$exceptionMessage]['exception_stack'] = $exceptionStack;
                                }

                                if ($_exceptionDatePart == $currentDate) {
                                    if (!isset($currentExceptionMessages[$exceptionMessage])) {
                                        $currentExceptionMessages[$exceptionMessage] = array(
                                            'count' => 1,
                                            'last_occurrence_date' => $lastDate,
                                            'exception_stack' => $exceptionStack
                                        );
                                    } else {
                                        $currentExceptionMessages[$exceptionMessage]['count']++;
                                        $currentExceptionMessages[$exceptionMessage]['last_occurrence_date']=$lastDate;
                                        $currentExceptionMessages[$exceptionMessage]['exception_stack']=$exceptionStack;
                                    }
                                }

                                $exceptionStarted = $exceptionEnded = false;
                                $exceptionMessage = $exceptionStack = '';
                            }
                        }

                        // For long files output progress
                        if ($this->_canOutputProgress()) {
                            if ($lines % 50000 == 0) {
                                if (!$logFileDetected) {
                                    $logFileDetected = true;
                                    echo "\n";
                                }
                                echo '=';
                            }
                            if (substr_count($line, "\n") || substr_count($line, "\r")) {
                                $lines++;
                            }
                        }
                    }
                    fclose($handle);
                }

                $entriesNumber = $logEntriesNumber;
                if ($fileSize > self::MAX_FILE_SIZE_TO_OPEN_FOR_LOG_ENTRIES_CALC) {
                    $entriesNumber = 'File is too big';
                }
                if (!is_readable($file)) {
                    $entriesNumber = 'File is not readable';
                }

                $data[] = array(
                    $entry,
                    $this->_formatBytes($fileSize, 3, 'IEC'),
                    $entriesNumber,
                    date('r', filemtime($file))
                );
                $filesCount++;
            }
            closedir($directoryHandler);
        }

        // Log Files
        $systemReport['Log Files'] = array(
            'header' => array('File', 'Size', 'Log Entries', 'Last Update'),
            'data' => $data
        );

        // Top System Messages
        $systemReport['Top System Messages'] = array(
            'header' => array('Count', 'Message', 'Last Occurrence'),
            'data' => $this->_prepareSystemMessagesReportData($sysMessages),
        );

        // Today's Top System Messages
        $systemReport['Today\'s Top System Messages'] = array(
            'header' => array('Count', 'Message', 'Last Occurrence'),
            'data' => $this->_prepareSystemMessagesReportData($currentSystemMessages),
        );

        // Top Exception Messages
        $systemReport['Top Exception Messages'] = array(
            'header' => array('Count', 'Message', 'Stack Trace', 'Last Occurrence'),
            'data' => $this->_prepareExceptionMessagesReportData($exceptions),
        );

        // Today's Top Exception Messages
        $systemReport['Today\'s Top Exception Messages'] = array(
            'header' => array('Count', 'Message', 'Stack Trace', 'Last Occurrence'),
            'data' => $this->_prepareExceptionMessagesReportData($currentExceptionMessages),
        );

        return $systemReport;
    }

    /**
     * Sort and prepare top system messages data for report
     *
     * @param array $messagesData
     *
     * @return array
     */
    protected function _prepareSystemMessagesReportData($messagesData)
    {
        $data = array();
        if (empty($messagesData)) {
            return $data;
        }

        $counts = array();
        foreach ($messagesData as $key => $messageData) {
            $counts[$key]  = $messageData['count'];
        }

        array_multisort($counts, SORT_DESC, $messagesData);

        $i = 0;
        foreach ($messagesData as $message => $messageData) {
            if ($i == self::TOP_SYSTEM_LOG_MESSAGES_NUMBER_TO_REPORT) {
                break;
            }
            $data[] = array(
                $messageData['count'],
                $message,
                $messageData['last_occurrence_date']
            );
            $i++;
        }

        return $data;
    }

    /**
     * Sort and prepare top system messages data for report
     *
     * @param array $messagesData
     * @return array
     */
    protected function _prepareExceptionMessagesReportData($messagesData)
    {
        $data = array();
        if (empty($messagesData)) {
            return $data;
        }
        $counts = array();
        foreach ($messagesData as $key => $messageData) {
            $counts[$key]  = $messageData['count'];
        }

        array_multisort($counts, SORT_DESC, $messagesData);

        $i = 0;
        foreach ($messagesData as $message => $messageData) {
            if ($i == self::TOP_EXCEPTION_LOG_MESSAGES_NUMBER_TO_REPORT) {
                break;
            }
            $data[] = array(
                $messageData['count'],
                $message,
                $messageData['exception_stack'],
                $messageData['last_occurrence_date']
            );
            $i++;
        }

        return $data;
    }

    /**
     * Generate server environment information such as:
     * - OS Version
     * - Apache version
     * - Apache Loaded Modules
     * - PHP Version
     * - PHP Loaded Modules
     * - PHP Major Configuration Values
     * - MySQL Server Version (retrieved from DB adapter)
     * - MySQL Supported Engines (took into account only enabled engines)
     * - MySQL Databases Present
     * - MySQL Plugins
     * - MySQL Major Global Variables
     *
     * Current method uses cURL request to sysreport.php tool (web mode only for phpinfo).
     * Sometimes it is not allowed or not applicable to request sysreport.php from outside. In this case information
     * will be not complete.
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateEnvironmentData(array $arguments = array())
    {
        $data = array();
        $count = 0;
        try {
            $phpInfo = $this->_tryGetPhpInfo();
        } catch (Exception $e) {
            $this->_log($e);
            $phpInfo = null;
        }
        if ($phpInfo === null) {
            $this->_log(null, 'So Environment Information will not be fully collected.');
        }

        if (is_array($phpInfo) && !empty($phpInfo)) {
            if (isset($phpInfo['General']['System'])) {
                $data[] = array('OS Information', $phpInfo['General']['System']);
                $count++;
            }
            if (isset($phpInfo['apache2handler']['Apache Version'])) {
                $data[] = array('Apache Version', $phpInfo['apache2handler']['Apache Version']);
                $count++;
            }
            if (isset($phpInfo['Apache Environment']['DOCUMENT_ROOT'])) {
                $data[] = array('Document Root', $phpInfo['Apache Environment']['DOCUMENT_ROOT']);
                $count++;
            } else if (isset($phpInfo['PHP Variables']['_SERVER["DOCUMENT_ROOT"]'])) {
                $data[] = array('Document Root', $phpInfo['PHP Variables']['_SERVER["DOCUMENT_ROOT"]']);
                $count++;
            }
            if (isset($phpInfo['Apache Environment']['SERVER_ADDR'])
                && isset($phpInfo['Apache Environment']['SERVER_PORT'])
            ) {
                $data[] = array(
                    'Server Address',
                    $phpInfo['Apache Environment']['SERVER_ADDR'] . ':' . $phpInfo['Apache Environment']['SERVER_PORT']
                );
                $count++;
            } else if (isset($phpInfo['PHP Variables']['_SERVER["SERVER_ADDR"]'])
                       && isset($phpInfo['PHP Variables']['_SERVER["SERVER_PORT"]'])
            ) {
                $data[] = array(
                    'Server Address',
                    $phpInfo['PHP Variables']['_SERVER["SERVER_ADDR"]'] . ':' .
                    $phpInfo['PHP Variables']['_SERVER["SERVER_PORT"]']
                );
                $count++;
            }
            if (isset($phpInfo['Apache Environment']['REMOTE_ADDR'])
                && isset($phpInfo['Apache Environment']['REMOTE_PORT'])
            ) {
                $data[] = array(
                    'Remote Address',
                    $phpInfo['Apache Environment']['REMOTE_ADDR'] . ':' . $phpInfo['Apache Environment']['REMOTE_PORT']
                );
                $count++;
            } else if (isset($phpInfo['PHP Variables']['_SERVER["REMOTE_ADDR"]'])
                && isset($phpInfo['PHP Variables']['_SERVER["REMOTE_PORT"]'])
            ) {
                $data[] = array(
                    'Remote Address',
                    $phpInfo['PHP Variables']['_SERVER["REMOTE_ADDR"]'] . ':' .
                        $phpInfo['PHP Variables']['_SERVER["REMOTE_PORT"]']
                );
                $count++;
            }
        }
        // Apache Loaded Modules
        if (is_array($phpInfo) && !empty($phpInfo) && isset($phpInfo['apache2handler']['Loaded Modules'])) {
            $modulesInfo = '';
            $modules = explode(' ', $phpInfo['apache2handler']['Loaded Modules']);
            foreach ($modules as $module) {
                $modulesInfo .= $module . "\n";
            }
            $data[] = array('Apache Loaded Modules', trim($modulesInfo));
            $count++;
        }

        try {
            // DB (MySQL) Server Version
            if (!$this->_readConnection) {
                throw new Exception('Cant\'t connect to DB. MySQL version can\'t be retrieved.');
            }

            $data[] = array('MySQL Server Version', $this->_readConnection->getServerVersion());
        } catch (Exception $e) {
            $this->_log($e);
            $data[] = array('MySQL Server Version', 'n/a');
        }
        $count++;

        // MySQL Enabled and Supported Engines
        try {
            if (!$this->_readConnection) {
                throw new Exception('Cant\'t connect to DB. MySQL supported engines list can\'t be retrieved.');
            }

            $engines = $this->_readConnection->fetchAll('SHOW ENGINES');
            $supportedEngines = '';

            if ($engines) {
                foreach ($engines as $engine) {
                    if ($engine['Support'] != 'NO' && $engine['Engine'] != 'DISABLED') {
                        $supportedEngines .= $engine['Engine'] . '; ';
                    }
                }
            }
            $data[] = array('MySQL Supported Engines', $supportedEngines);
            unset($engines, $supportedEngines);
        } catch (Exception $e) {
            $this->_log($e);
            $data[] = array('MySQL Supported Engines', 'n/a');
        }
        $count++;

        // MySQL Databases amount
        try {
            if (!$this->_readConnection) {
                throw new Exception('Cant\'t connect to DB. Database number can\'t be collected.');
            }

            $databases = $this->_readConnection->fetchAll('SHOW DATABASES');
            $dbNumber = $databases ? sizeof($databases) : 0;
            $data[] = array('MySQL Databases Present', $dbNumber);
            unset($databases);
        } catch (Exception $e) {
            $this->_log($e);
            $data[] = array('MySQL Databases Present', 'n/a');
        }
        $count++;

        // MySQL Configuration
        $importantConfig = array(
            'datadir',
            'default_storage_engine',
            'general_log',
            'general_log_file',
            'innodb_buffer_pool_size',
            'innodb_io_capacity',
            'innodb_log_file_size',
            'innodb_thread_concurrency',
            'innodb_flush_log_at_trx_commit',
            'innodb_open_files',
            'join_buffer_size',
            'key_buffer_size',
            'max_allowed_packet',
            'max_connect_errors',
            'max_connections',
            'max_heap_table_size',
            'query_cache_size',
            'query_cache_limit',
            'read_buffer_size',
            'skip_name_resolve',
            'slow_query_log',
            'slow_query_log_file',
            'sync_binlog',
            'table_open_cache',
            'tmp_table_size',
            'wait_timeout',
            'version',
        );
        $maxSettingNameLength = 0;
        foreach ($importantConfig as $settingName) {
            $length = strlen($settingName);
            if ($length > $maxSettingNameLength) {
                $maxSettingNameLength = $length;
            }
        }
        try {
            if (!$this->_readConnection) {
                throw new Exception('Cant\'t connect to DB. MySQL config settings can\'t be collected.');
            }

            $variables = $this->_readConnection->fetchAssoc('SHOW GLOBAL VARIABLES');
            if ($variables) {
                $configuration = '';
                foreach ($variables as $variable) {
                    if (!in_array($variable['Variable_name'], $importantConfig)) {
                        continue;
                    }
                    if (substr($variable['Variable_name'], -4) == 'size') {
                        $variable['Value'] = $this->_formatBytes($variable['Value'], 3, 'IEC');
                    }
                    $indent = str_repeat(' ', $maxSettingNameLength - strlen($variable['Variable_name']) + 4);
                    $configuration .= $variable['Variable_name'] . $indent . ' => "' . $variable['Value'] . '"' . "\n";
                }
                $data[] = array('MySQL Configuration', trim($configuration));
            } else {
                $data[] = array('MySQL Configuration', 'n/a');
            }
            unset($variables);
        } catch (Exception $e) {
            $this->_log($e);
            $data[] = array('MySQL Configuration', 'n/a');
        }
        $count++;

        // MySQL Plugins
        try {
            if (!$this->_readConnection) {
                throw new Exception('Cant\'t connect to DB. MySQL plugins list can\'t be retrieved.');
            }

            $plugins = $this->_readConnection->fetchAssoc('SHOW PLUGINS');
            $installedPlugins = '';

            if ($plugins) {
                foreach ($plugins as $plugin) {
                    $installedPlugins .= ($plugin['Status'] == 'DISABLED' ? '-disabled- ' : '') .
                        $plugin['Name'] . "\n";
                }
            }
            $data[] = array('MySQL Plugins', trim($installedPlugins));
            unset($plugins, $installedPlugins);
        } catch (Exception $e) {
            $this->_log($e);
            $data[] = array('MySQL Plugins', 'n/a');
        }
        $count++;

        // PHP Version
        $data[] = array('PHP Version', PHP_VERSION);
        $count++;

        if (is_array($phpInfo) && !empty($phpInfo)) {
            if (isset($phpInfo['General']['Loaded Configuration File'])) {
                $data[] = array('PHP Loaded Config File', $phpInfo['General']['Loaded Configuration File']);
                $count++;
            }
            if (isset($phpInfo['General']['Additional .ini files parsed'])) {
                $data[] =array('PHP Additional .ini files parsed', $phpInfo['General']['Additional .ini files parsed']);
                $count++;
            }
        }

        // PHP Important Config Settings
        $importantConfig = array(
            'memory_limit',
            'register_globals',
            'safe_mode',
            'upload_max_filesize',
            'post_max_size',
            'allow_url_fopen',
            'default_charset',
            'error_log',
            'error_reporting',
            'extension_dir',
            'file_uploads',
            'upload_tmp_dir',
            'log_errors',
            'magic_quotes_gpc',
            'max_execution_time',
            'max_file_uploads',
            'max_input_time',
            'max_input_vars',
        );
        $maxSettingNameLength = 0;
        foreach ($importantConfig as $settingName) {
            $length = strlen($settingName);
            if ($length > $maxSettingNameLength) {
                $maxSettingNameLength = $length;
            }
        }
        if (is_array($phpInfo) && !empty($phpInfo)) {
            $coreEntry = isset($phpInfo['Core'])
                       ? $phpInfo['Core']
                       : (isset($phpInfo['PHP Core']) ? $phpInfo['PHP Core'] : null);
            if ($coreEntry !== null) {
                $configuration = '';
                foreach ($coreEntry as $key => $info) {
                    if (in_array($key, $importantConfig)) {
                        $indent = str_repeat(' ', $maxSettingNameLength - strlen($key) + 4);
                        $configuration .= $key . $indent . ' => Local = "' . $info['local'] .
                                                  '", Master = "' . $info['master'] . '"' . "\n";
                    }
                }
                $data[] = array('PHP Configuration', trim($configuration));
                $count++;
            }
        } else {
            $iniValues = ini_get_all();
            if (!empty($iniValues) && is_array($iniValues)) {
                $configuration = '';
                foreach ($iniValues as $key => $info) {
                    if (in_array($key, $importantConfig)) {
                        $configuration .= $key . ' => Local = "' . $info['local_value'] .
                            '", Master = "' . $info['global_value']. '"' . "\n";
                    }
                }
                $data[] = array('PHP Configuration', $configuration);
                $count++;
            }
        }

        try {
            /**
             * PHP Loaded Modules
             */
            $defaultPhpInfoCategories = array(
                'General',
                'apache2handler',
                'Apache Environment',
                'PHP Core',
                'Core',
                'HTTP Headers Information',
                'Environment',
                'PHP Variables'
            );
            if (is_array($phpInfo) && !empty($phpInfo)) {
                $modulesInfo = '';
                foreach ($phpInfo as $module => $info) {
                    if (!in_array($module, $defaultPhpInfoCategories)) {
                        // Collect additional information for required modules by Magento
                        switch ($module) {
                            case 'curl':
                                if (isset($info['cURL Information'])) {
                                    $module .= ' [' . $info['cURL Information'] . ']';
                                }
                                break;
                            case 'dom':
                                if (isset($info['libxml Version'])) {
                                    $module .= ' [' . $info['libxml Version'] . ']';
                                }
                                break;
                            case 'gd':
                                if (isset($info['GD Version'])) {
                                    $module .= ' [' . $info['GD Version'] . ']';
                                }
                                break;
                            case 'iconv':
                                if (isset($info['iconv library version'])) {
                                    $module .= ' [' . $info['iconv library version'] . ']';
                                }
                                break;
                            case 'mcrypt':
                                if (isset($info['Version'])) {
                                    $module .= ' [' . $info['Version'] . ']';
                                }
                                break;
                            case 'pdo_mysql':
                                if (isset($info['Client API version'])) {
                                    $module .= ' [' . $info['Client API version'] . ']';
                                } else if (isset($info['PDO Driver for MySQL, client library version'])) {
                                    $module .= ' [' . $info['PDO Driver for MySQL, client library version'] . ']';
                                }
                                break;
                            case 'SimpleXML':
                                if (isset($info['Revision'])) {
                                    $module .= ' [' . $info['Revision'] . ']';
                                }
                                break;
                            case 'soap':
                            case 'hash':
                            default:
                                $module .= phpversion($module) ? ' [' . phpversion($module) . ']' : '';
                                break;
                        }
                        $modulesInfo .= $module . "\n";
                    }
                }
                $data[] = array('PHP Loaded Modules', trim($modulesInfo));
                $count++;
            } else {
                $modules = get_loaded_extensions();
                if (is_array($modules) && !empty($modules)) {
                    $modules = array_map('strtolower', $modules);
                    sort($modules);
                    $modulesInfo = '';
                    foreach ($modules as $module) {
                        $modulesInfo .= $module . (phpversion($module) ? ' [' . phpversion($module) . ']' : '') . "\n";
                    }
                    $data[] = array('PHP Loaded Modules', trim($modulesInfo));
                    $count++;
                }
            }
        } catch (Exception $e) {
            $this->_log($e);
            $data[] = array('PHP Loaded Modules', 'n/a');
        }

        $systemReport = array();
        $systemReport['Environment Information'] = array(
            'header' => array('Parameter', 'Value'),
            'data' => $data,
            'count' => $count
        );

        return $systemReport;
    }

    /**
     * Generate MySQL status information.
     * Additionally generate MySQL status after 10 seconds delay to see the difference.
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateMysqlStatusData(array $arguments = array())
    {
        // MySQL Status
        $data = array();
        $importantConfig = array(
            'Aborted_clients',
            'Aborted_connects',
            'Com_select',
            'Connections',
            'Created_tmp_disk_tables',
            'Created_tmp_files',
            'Created_tmp_tables',
            'Handler_read_rnd_next',
            'Innodb_buffer_pool_read_requests',
            'Innodb_buffer_pool_write_requests',
            'Innodb_log_waits',
            'Innodb_log_write_requests',
            'Innodb_log_writes',
            'Open_files',
            'Open_streams',
            'Open_table_definitions',
            'Open_tables',
            'Opened_files',
            'Opened_table_definitions',
            'Opened_tables',
            'Qcache_lowmem_prunes',
            'Select_full_join',
            'Select_full_range_join',
            'Select_range',
            'Select_range_check',
            'Select_scan',
            'Slow_queries',
            'Slave_running',
            'Sort_range',
            'Sort_rows',
            'Sort_scan',
            'Table_locks_immediate',
            'Table_locks_waited',
            'Threads_cached',
            'Threads_connected',
            'Threads_created',
            'Threads_running',
        );
        try {
            if (!$this->_readConnection) {
                throw new Exception('Cant\'t connect to DB. MySQL Status data can\'t be collected.');
            }

            $variables = $this->_readConnection->fetchPairs('SHOW GLOBAL STATUS');
            $this->_log(null, '10 seconds wait time to collect MySQL status data...');

            if ($this->_canOutputProgress()) {
                echo "\n[0";
                for ($i = 1; $i <= 20; $i++) {
                    echo '=';
                    if ($i == 10) {
                        echo '5';
                    }
                    if ($i == 20) {
                        echo '10]';
                    }
                    usleep(500000);
                }
            }

            $variablesAfter10Sec = $this->_readConnection->fetchPairs('SHOW GLOBAL STATUS');
            if ($variables && $variablesAfter10Sec) {
                foreach ($variables as $name => $value) {
                    if (!in_array($name, $importantConfig)) {
                        continue;
                    }
                    $valueAfter10Sec = 'n/a';
                    if (isset($variablesAfter10Sec[$name])) {
                        $difference = '';
                        if (is_numeric($variablesAfter10Sec[$name])) {
                            $difference = $variablesAfter10Sec[$name] - $value;
                            if ($difference != 0) {
                                $difference = ' (diff: ' . ($difference > 0 ? '+' : '') . $difference . ')';
                            } else {
                                $difference = '';
                            }
                        }
                        $valueAfter10Sec = $variablesAfter10Sec[$name] . $difference;
                    }
                    $data[] = array($name, $value, $valueAfter10Sec);
                }
            }
            unset($variables, $variablesAfter10Sec);
        } catch (Exception $e) {
            $this->_log($e);
        }

        $systemReport = array();
        $systemReport['MySQL Status'] = array(
            'header' => array('Variable', 'Value', 'Value after 10 sec'),
            'data' => $data,
        );

        return $systemReport;
    }

    /**
     * Convert phpinfo() HTML output into array and output it in serialized format
     *
     * @link http://www.php.net/manual/en/function.phpinfo.php#106862
     *
     * @return array
     */
    static public function collectPHPInfo()
    {
        ob_start();
        phpinfo(INFO_ALL);
        $info = array();
        $infoLines = explode("\n", strip_tags(ob_get_clean(), '<tr><td><h2>'));

        $category = 'General';
        foreach($infoLines as $line) {
            if (preg_match('~<h2>(.*)</h2>~', $line, $title)) {
                $category = $title[1];
            }

            if (preg_match('~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~', $line, $value)) {
                $info[$category][trim($value[1])] = trim($value[2]);
            } else if(preg_match(
                '~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~',
                $line,
                $value
            )
            ) {
                $info[$category][trim($value[1])] = array('local' => $value[2], 'master' => $value[3]);
            }
        }

        echo serialize($info);
    }

    /**
     * Try get and unserialize phpinfo through web request
     * In case of unserialization error try recursively get phpinfo again, but using direct IP address to current server
     *
     * @param array $params
     * @param bool $tryWebIp
     *
     * @uses Mage_Shell_SystemReport::_getLocalWebIpList()
     * @uses Mage_Shell_SystemReport::_tryGetPhpInfoByRequest()
     *
     * @return array|null
     */
    protected function _tryGetPhpInfo($params = array(), $tryWebIp = true)
    {
        try {
            $phpInfo = $this->_tryGetPhpInfoByRequest($params);
            if ($phpInfo === null) {
                return $phpInfo;
            }
            $phpInfo = unserialize($phpInfo);
        } catch (Exception $e) {
            $phpInfo = null;

            /**
             * If unserialization failed then current script can be installed at web node which is under load balancer.
             * In this case sysreport script can\'t be call by URL, but by direct IP.
             * So local web IP addresses must be checked.
             */
            if (preg_match('~Notice\: unserialize\(\)\: Error at offset.+~i', $e->getMessage()) && $tryWebIp) {
                $this->_log(null, 'Started checking local IP list for phpinfo.');
                $webIpList = $this->_getLocalWebIpList();
                $ipCount = sizeof($webIpList);
                $ipCount = $ipCount > self::REPORT_ENVIRONMENT_DATA_MAX_WEB_LOCAL_IP_NUMBER_TO_CHECK
                                    ? self::REPORT_ENVIRONMENT_DATA_MAX_WEB_LOCAL_IP_NUMBER_TO_CHECK : $ipCount;
                for ($i = 0; $i < $ipCount; $i++) {
                    $params['host_ip'] = $webIpList[$i];
                    $this->_log(null, 'Trying to get phpinfo from IP ' . $webIpList[$i]);
                    $phpInfo = $this->_tryGetPhpInfo($params, false);
                    if ($phpInfo !== null) {
                        return $phpInfo;
                    }
                }
            } else {
                $this->_log($e);
            }
        }

        return $phpInfo;
    }

    /**
     * Try get phpinfo using specific cUrl request to sysreport tool itself
     * In case of HTTP Authorization error give ability to enter HTTP Auth credentials
     *
     * @param array $params
     *
     * @return string|null
     */
    protected function _tryGetPhpInfoByRequest($params = array())
    {
        static $httpAuthTries = 1;
        try {
            $params['post'] = array('phpinfo' => 1);
            $sysreportFile = $this->_getRootPath() . basename(__FILE__);
            $sysreportWasCopied = false;
            if (!file_exists($sysreportFile)) {
                $sysreportWasCopied = copy(dirname(__FILE__) .DIRECTORY_SEPARATOR . basename(__FILE__), $sysreportFile);
            }
            $sysreportToolUrl = Mage::getStoreConfig('web/unsecure/base_url', Mage_Core_Model_App::ADMIN_STORE_ID)
                . basename(__FILE__);
            $phpInfo = $this->curlRequest($sysreportToolUrl, $params);
            if ($sysreportWasCopied) {
                unlink($sysreportFile);
            }
        } catch (Exception $e) {
            $this->_log($e);
            $phpInfo = null;
            // Case when Base URL is under HTTP Authentication
            if ($e->getCode() == 401 && $httpAuthTries <= self::REPORT_ENVIRONMENT_DATA_HTTP_AUTH_MAX_TRIES_NUMBER) {
                $this->_log(null, 'Attempt #' . $httpAuthTries . "\n");
                $httpAuthTries++;
                $user = $password = null;
                echo 'Enter HTTP Auth username: ';
                while (empty($user)) {
                    $user = trim(fgets(STDIN));
                }
                $shell = $this->_getUnixShell();
                echo 'Enter HTTP Auth password: ';
                if (false !== $shell) {
                    $readCmd = $shell === 'csh' ? 'set mypassword = $<' : 'read -r mypassword';
                    $command = sprintf(
                        "/usr/bin/env %s -c 'stty -echo; %s; stty echo; echo \$mypassword'",
                        $shell,
                        $readCmd
                    );
                    $password = rtrim(shell_exec($command));
                } else {
                    while ($password === null) {
                        $password = trim(fgets(STDIN));
                    }
                }
                $params['httpauth'] = $user . ':' . $password;

                return $this->_tryGetPhpInfoByRequest($params);
            }
            // In case 404 error trigger unserialize() notice by providing wrong data, so it can be caught and
            // local IP addresses can be checked
            else if ($e->getCode() == 404) {
                $phpInfo = '-';
            }
        }

        // Reset http authentication tries counter, so other requests can be processed in case of HTTP 401 error
        $httpAuthTries = 1;

        return $phpInfo;
    }

    /**
     * Collect local Web (with listen port "80") IP list
     *
     * Method used when collecting environment data. And this method is only applicable for Linux-based systems
     *
     * @return array
     * @throws Exception
     */
    protected function _getLocalWebIpList()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            throw new Exception('IP list collecting is not supported on Windows OS.');
        }
        if ((!file_exists('/proc/net/tcp') && !file_exists('/proc/net/tcp6'))
            || (!is_readable('/proc/net/tcp') && !is_readable('/proc/net/tcp6'))
        ) {
            throw new Exception('IP list was not found or is not readable.');
        }
        $ipList = array();

        /**
         * IPv4 list
         */
        $ipv4List = file_get_contents('/proc/net/tcp');
        if ($ipv4List) {
            $ipv4List = explode("\n", $ipv4List);
            foreach ($ipv4List as &$record) {
                $record = preg_split('~[\s]+~', $record, -1, PREG_SPLIT_NO_EMPTY);
            }
        }
        foreach ($ipv4List as $info) {
            // Remote socket "00000000:0000" means this is listen local socket (local IP) which is need to be collected
            if ($info && isset($info[1]) && isset($info[2]) && $info[2] == '00000000:0000') {
                $ipData = explode(':', $info[1]);
                // Collect IPs only with port "80"
                if (sizeof($ipData) == 2 && $ipData[1] == '0050') {
                    $ip = $this->_reversedHex2ip($ipData[0]);
                    if ($ip == '0.0.0.0') {
                        $ip = '127.0.0.1';
                    }
                    if (!in_array($ip, $ipList)) {
                        $ipList[] = $ip;
                    }
                }
            }
        }
        /**
         * IPv4 addresses must be collected, but sometimes TCP configuration also/only exists in IPv6 list
         */
        $ipv6List = file_get_contents('/proc/net/tcp6');
        if ($ipv6List) {
            $ipv6List = explode("\n", $ipv6List);
            foreach ($ipv6List as &$_record) {
                $_record = preg_split('~[\s]+~', $_record, -1, PREG_SPLIT_NO_EMPTY);
            }
        }
        foreach ($ipv6List as $info) {
            // Remote socket "00000000000000000000000000000000:0000" means this is listen local socket (local IP)
            // which is need to be collected
            if ($info && isset($info[1]) && isset($info[2]) && $info[2] == '00000000000000000000000000000000:0000') {
                $ipData = explode(':', $info[1]);
                // Usually IPv6 listening 80 port looks like :::80 (the same as "0.0.0.0:80" in Ipv4 format)
                // and since we work with IPv4 only collect address "127.0.0.1" and stop further lookup
                if (sizeof($ipData) == 2 && $ipData[1] == '0050') {
                    $ip = '127.0.0.1';
                    if (!in_array($ip, $ipList)) {
                        $ipList[] = $ip;
                    }
                    break;
                }
            }
        }

        return $ipList;
    }

    /**
     * Convert reversed and hexed IP to normal IP address
     *
     * @param string $reverseHex
     *
     * @return string
     */
    protected function _reversedHex2ip($reverseHex)
    {
        $reverseIp = long2ip(hexdec($reverseHex));
        return implode('.', array_reverse(explode('.', $reverseIp)));
    }

    /**
     * Return a valid Unix shell
     *
     * @return string|bool
     */
    protected static function _getUnixShell()
    {
        $shell = false;
        if (self::is_function_exist('shell_exec') && file_exists('/usr/bin/env')) {
            // handle other OSs with bash/zsh/ksh/csh if available to hide the answer
            $test = "/usr/bin/env %s -c 'echo OK' 2> /dev/null";
            foreach (array('bash', 'zsh', 'ksh', 'csh') as $sh) {
                if ('OK' === rtrim(shell_exec(sprintf($test, $sh)))) {
                    $shell = $sh;
                    break;
                }
            }
        }

        return $shell;
    }

    /**
     * Determine if the given function has been defined and not disabled
     *
     * @param string $functionName
     * @return bool
     */
    protected static function is_function_exist($functionName)
    {
        $disabledFuncs = '';
        if (extension_loaded('suhosin')) {
            $disabledFuncs = ini_get('suhosin.executor.func.blacklist');
        }
        $disabledFuncs .= ',' . ini_get('disable_functions');
        $disabledFuncs = explode(',', $disabledFuncs);
        $disabledFuncs = array_map('trim', $disabledFuncs);
        $disabledFuncs = array_map('strtolower', $disabledFuncs);

        return (function_exists($functionName) === true && array_search($functionName, $disabledFuncs) === false);
    }


    /**
     * Generate Cache Status information
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateCacheStatusData(array $arguments = array())
    {
        $invalidated = Mage::app()->getCacheInstance()->getInvalidatedTypes();
        $cacheTypes = Mage::app()->getCacheInstance()->getTypes();
        $data = array();
        /** @var $type Varien_Object */
        foreach ($cacheTypes as $typeName => $type) {
            $data[] = array(
                $type->getCacheType(),
                isset($invalidated[$type->getId()]) ? 'Invalidated' : ($type->getStatus() ? 'Enabled' : 'Disabled'),
                $typeName,
                $type->getTags(),
                $type->getDescription()
            );
        }

        $systemReport = array();
        $systemReport['Cache Status'] = array(
            'header' => array('Cache', 'Status', 'Type', 'Associated Tags', 'Description'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate Index Status Information
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateIndexStatusData(array $arguments = array())
    {
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. Index status can\'t be retrieved.');
        }

        if ((version_compare($this->_magentoVersion, '1.11.2.0', '<') && $this->_magentoEdition == 'EE')
            || (version_compare($this->_magentoVersion, '1.12.0.0', '<') && $this->_magentoEdition == 'PE')
            || (version_compare($this->_magentoVersion, '1.6.2.0', '<') && $this->_magentoEdition == 'CE')
        ) {
            $refactoredIndexers = false;
            $header = array('Index', 'Status', 'Updated At', 'Mode', 'Description');
        } else {
            $refactoredIndexers = true;
            $header = array('Index', 'Status', 'Update Required', 'Updated At', 'Mode', 'Is Visible', 'Description');
        }

        if ((version_compare($this->_magentoVersion, '1.13.0.0', '>=') && $this->_magentoEdition == 'EE')) {
            $newEnterpriseIndexers = true;
            /** @var $indexer Enterprise_Index_Model_Indexer */
            $indexer = Mage::getSingleton('enterprise_index/indexer');
            /** @var $processModel Enterprise_Index_Model_Process */
            $processModel = Mage::getSingleton('enterprise_index/process');
        } else {
            $newEnterpriseIndexers = false;
            /** @var $indexer Mage_Index_Model_Indexer */
            $indexer = Mage::getSingleton('index/indexer');
            /** @var $processModel Mage_Index_Model_Process */
            $processModel = Mage::getSingleton('index/process');
        }
        $processStatuses = $processModel->getStatusesOptions();
        $processModes = $processModel->getModesOptions();
        /** @var $collection  Enterprise_Index_Model_Resource_Process_Collection */
        $collection = $indexer->getProcessesCollection();

        $data = array();
        /** @var $item Enterprise_Index_Model_Process */
        foreach ($collection as $item) {
            try {
                if (!$newEnterpriseIndexers || ($newEnterpriseIndexers && !$item->isEnterpriseProcess())) {
                    $status = $item->isLocked() ? Mage_Index_Model_Process::STATUS_RUNNING : $item->getStatus();
                } else {
                    $status = $item->getStatus();
                }
                $status = isset($processStatuses[$status]) ? $processStatuses[$status] : $status;
                $mode = isset($processModes[$item->getMode()]) ? $processModes[$item->getMode()] : $item->getMode();
                $updateRequired = '';
                if ($refactoredIndexers) {
                    $updateRequired = $item->getUnprocessedEventsCollection()->count() > 0 ? 'Yes' : 'No';
                }
                if ($newEnterpriseIndexers) {
                    $updateRequired = !$item->isEnterpriseProcess()
                        ? $item->getUnprocessedEventsCollection()->count() > 0 ? 'Yes' : 'No'
                        : '';
                }
                if (is_null($mode)) {
                    $mode = '';
                }
                $name = $item->getIndexer()->getName();
                if (empty($name)) {
                    $name = $item->getIndexerCode();
                }

                if ($refactoredIndexers || $newEnterpriseIndexers) {
                    $data[] = array(
                        $name,
                        $status,
                        $updateRequired,
                        $item->getEndedAt() ? $item->getEndedAt() : 'Never',
                        $mode,
                        $item->getIndexer()->isVisible() ? 'Yes' : 'No',
                        $item->getIndexer()->getDescription()
                    );
                } else {
                    $data[] = array(
                        $name,
                        $status,
                        $item->getEndedAt() ? $item->getEndedAt() : 'Never',
                        $mode,
                        $item->getIndexer()->getDescription()
                    );
                }
            } catch (Exception $e) {
                $this->_log($e);
            }
        }

        $systemReport = array();
        $systemReport['Index Status'] = array(
            'header' => $header,
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate Compiler Status Information
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateCompilerStatusData(array $arguments = array())
    {
        $data = array();

        /** @var $compiler Mage_Compiler_Model_Process */
        $compiler = Mage::getModel('compiler/process');
        $data[] = array(
            $this->_isCompilerEnabled() ? 'Enabled' : 'Disabled',
            $compiler->getCollectedFilesCount() > 0 ? 'Compiled' : 'Not Compiled',
            $compiler->getCollectedFilesCount(),
            $compiler->getCompiledFilesCount()
        );

        $systemReport = array();
        $systemReport['Compiler Status'] = array(
            'header' => array('Status', 'State', 'Files Count', 'Scopes Count'),
            'data' => $data
        );
        return $systemReport;
    }

    /**
     * Determine if compiler configured as Enabled
     *
     * @return bool
     */
    protected function _isCompilerEnabled()
    {
        try {
            $compilerConfig = $this->_getRootPath() . 'includes' . DIRECTORY_SEPARATOR . 'config.php';
            if (file_exists($compilerConfig)) {
                include_once $compilerConfig;
            }
        } catch (Exception $e) {
            $this->_log($e);
        }
        return defined('COMPILER_INCLUDE_PATH');
    }

    /**
     * Generate Cron Schedules List
     * Can be filtered by cron job code, status and ID
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateCronSchedulesData(array $arguments = array())
    {
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. Cron schedule status can\'t be retrieved.');
        }

        $code = $status = $id = null;
        if (isset($arguments['code']) && !empty($arguments['code'])) {
            $code = $arguments['code'];
        }
        if (isset($arguments['status']) && !empty($arguments['status'])) {
            $status = $arguments['status'];
        }
        if (isset($arguments['id']) && !empty($arguments['id'])) {
            $id = $arguments['id'];
        }
        $systemReport = $data = array();
        $count = 0;

        // If ID specified, them output full cron schedule information
        if (!is_null($id)) {
            try {
                /** @var $schedule Mage_Cron_Model_Schedule */
                $schedule = Mage::getModel('cron/schedule')->load((int)$id);
                if ($schedule->getId()) {
                    $count++;
                    $data = array(
                        $schedule->getId(),
                        $schedule->getJobCode(),
                        $schedule->getStatus(),
                        $schedule->getMessages(),
                        $schedule->getCreatedAt(),
                        $schedule->getScheduledAt(),
                        $schedule->getExecutedAt(),
                        $schedule->getFinishedAt(),
                    );
                }
            } catch (Exception $e) {
                $this->_log($e);
            }
            $systemReport['Cron Schedule Info'] = array(
                'header' => array(
                    'Schedule Id',
                    'Job Code',
                    'Status',
                    'Messages',
                    'Created At',
                    'Scheduled At',
                    'Executed At',
                    'Finished At'
                ),
                'data' => $data,
                'count' => $count
            );
        } else {
            $cronSchedules = array();
            $collection = null;
            try {
                /** @var $collection Mage_Cron_Model_Resource_Schedule_Collection */
                $collection = Mage::getModel('cron/schedule')->getCollection();
                if (!is_null($code)) {
                    $collection->addFieldToFilter('job_code', $code);
                }
                if (!is_null($status)) {
                    $collection->addFieldToFilter('status', $status);
                }
            } catch (Exception $e) {
                $this->_log($e);
            }
            if ($collection) {
                $cronSchedules = $collection->load();
            }
            /** @var $schedule Mage_Cron_Model_Schedule */
            foreach ($cronSchedules as $schedule) {
                try {
                    $data[] = array(
                        $schedule->getId(),
                        $schedule->getJobCode(),
                        $schedule->getStatus(),
                        $schedule->getCreatedAt(),
                        $schedule->getScheduledAt(),
                        $schedule->getExecutedAt(),
                        $schedule->getFinishedAt(),
                    );
                } catch (Exception $e) {
                    $this->_log($e);
                }
            }
            $systemReport['Cron Schedules List'] = array(
                'header' => array(
                    'Schedule Id', 'Job Code', 'Status', 'Created At', 'Scheduled At', 'Executed At', 'Finished At'
                ),
                'data' => $data
            );
        }

        return $systemReport;
    }

    /**
     * Generate Cron Status Information
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateCronStatusData(array $arguments = array())
    {
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. Cron schedule status can\'t be retrieved.');
        }
        // Cron status by status code
        $systemReport = $data = array();
        try {
            $info = $this->_readConnection->fetchAll("
                SELECT COUNT( * ) AS `cnt` , `status`
                FROM `{$this->_getTableName('cron/schedule')}`
                GROUP BY `status`
                ORDER BY `status`
            ");

            if ($info) {
                foreach ($info as $_data) {
                    $data[] = array($_data['status'], $_data['cnt']);
                }
            }

            $systemReport['Cron Schedules by status code'] = array(
                'header' => array(
                    'Status Code', 'Count'
                ),
                'data' => $data
            );
        } catch (Exception $e) {
            $this->_log($e);
        }

        // Cron status by job code
        $data = array();
        try {
            $info = $this->_readConnection->fetchAll("
                SELECT COUNT( * ) AS `cnt` , `job_code`
                FROM `{$this->_getTableName('cron/schedule')}`
                GROUP BY `job_code`
                ORDER BY `job_code`
            ");

            if ($info) {
                foreach ($info as $_data) {
                    $data[] = array($_data['job_code'], $_data['cnt']);
                }
            }

            $systemReport['Cron Schedules by job code'] = array(
                'header' => array(
                    'Job Code', 'Count'
                ),
                'data' => $data
            );
        } catch (Exception $e) {
            $this->_log($e);
        }

        return $systemReport;
    }
    /**
     * Generate the most frequent error per each cron job code
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateCronErrorsData(array $arguments = array())
    {
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. Cron schedule status can\'t be retrieved.');
        }
        $collection = null;
        $cronSchedules = array();
        try {
            /** @var $collection Mage_Cron_Model_Resource_Schedule_Collection */
            $collection = Mage::getModel('cron/schedule')->getCollection();
            $collection->addFieldToFilter('status', Mage_Cron_Model_Schedule::STATUS_ERROR);
        } catch (Exception $e) {
            $this->_log($e);
        }
        if ($collection) {
            $cronSchedules = $collection->load();
        }

        $jobs = $data = array();
        /** @var $schedule Mage_Cron_Model_Schedule */
        foreach ($cronSchedules as $schedule) {
            try {
                $jobData = array(
                    $schedule->getId(),
                    $schedule->getJobCode(),
                    $schedule->getMessages(),
                    1,
                    $schedule->getCreatedAt(),
                    $schedule->getScheduledAt(),
                    $schedule->getExecutedAt(),
                    $schedule->getFinishedAt(),
                );
                // Calculate error message occurrence rate
                if (preg_match('~^exception (.+)$~im', $schedule->getMessages(), $matches)) {
                    $exceptionMessage = $matches[1];
                    if (empty($jobs[$schedule->getJobCode()][$exceptionMessage])) {
                        $jobs[$schedule->getJobCode()][$exceptionMessage]['cnt'] = 1;
                    } else {
                        $jobs[$schedule->getJobCode()][$exceptionMessage]['cnt']++;
                    }
                    $jobs[$schedule->getJobCode()][$exceptionMessage]['data'] = $jobData;
                } else {
                    $data[] = $jobData;
                }
            } catch (Exception $e) {
                $this->_log($e);
            }
        }

        foreach ($jobs as $messages) {
            $counts = array();
            foreach ($messages as $messageData) {
                $counts[] = $messageData['cnt'];
            }
            array_multisort($counts, SORT_DESC, $messages);
            $topMessage = current($messages);
            $topMessage['data'][3] = $topMessage['cnt'];
            $data[] = $topMessage['data'];
        }

        $systemReport = array();
        $systemReport['Errors in Cron Schedules Queue'] = array(
            'header' => array(
                'Schedule Id', 'Job Code', 'Error', 'Count', 'Created At', 'Scheduled At', 'Executed At', 'Finished At'
            ),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate all cron jobs information
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateAllCronJobsData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['All Global Cron Jobs'] = array(
            'header' => array('Job Code', 'Cron Expression', 'Run Class', 'Run Method'),
            'data' => $this->_getCronJobs('global')
        );

        $systemReport['All Configurable Cron Jobs'] = array(
            'header' => array('Job Code', 'Cron Expression', 'Run Class', 'Run Method'),
            'data' => $this->_getCronJobs('configurable')
        );

        return $systemReport;
    }

    /**
     * Generate core cron jobs information
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateCoreCronJobsData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Core Global Cron Jobs'] = array(
            'header' => array('Job Code', 'Cron Expression', 'Run Class', 'Run Method'),
            'data' => $this->_getCronJobs('global', 'core')
        );

        $systemReport['Core Configurable Cron Jobs'] = array(
            'header' => array('Job Code', 'Cron Expression', 'Run Class', 'Run Method'),
            'data' => $this->_getCronJobs('configurable', 'core')
        );

        return $systemReport;
    }

    /**
     * Generate enterprise cron jobs information
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateEnterpriseCronJobsData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Enterprise Global Cron Jobs'] = array(
            'header' => array('Job Code', 'Cron Expression', 'Run Class', 'Run Method'),
            'data' => $this->_getCronJobs('global', 'enterprise')
        );

        $systemReport['Enterprise Configurable Cron Jobs'] = array(
            'header' => array('Job Code', 'Cron Expression', 'Run Class', 'Run Method'),
            'data' => $this->_getCronJobs('configurable', 'enterprise')
        );

        return $systemReport;
    }

    /**
     * Generate custom cron jobs information
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateCustomCronJobsData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Custom Global Cron Jobs'] = array(
            'header' => array('Job Code', 'Cron Expression', 'Run Class', 'Run Method'),
            'data' => $this->_getCronJobs('global', 'custom')
        );

        $systemReport['Custom Configurable Cron Jobs'] = array(
            'header' => array('Job Code', 'Cron Expression', 'Run Class', 'Run Method'),
            'data' => $this->_getCronJobs('configurable', 'custom')
        );

        return $systemReport;
    }

    /**
     * Collect Cron Jobs by specified scope and type
     *
     * @param string $scope
     * @param string $type
     *
     * @return array
     */
    protected function _getCronJobs($scope, $type = 'all')
    {
        $scope = $scope == 'configurable' || $scope == 'global' ? $scope : 'global';
        $type = !in_array($type, array('all', 'core', 'enterprise', 'custom')) ? 'all' : $type;
        $jobsData = array();
        $coreNamespaces = array('Mage', 'Zend');
        $data = array();

        $jobs = Mage::getConfig()->getNode(($scope == 'configurable' ? 'default/' : '') . 'crontab/jobs');
        if (!($jobs instanceof Mage_Core_Model_Config_Element)) {
            return $data;
        }
        foreach ($jobs->children() as $jobCode => $jobConfig) {
            $runClass = $runMethod = $cronExpr = 'n/a';
            if ($jobConfig) {
                if ($jobConfig->run && $jobConfig->run->model) {
                    $modelName = (string)$jobConfig->run->model;
                    if (preg_match(Mage_Cron_Model_Observer::REGEX_RUN_MODEL, $modelName, $run)) {
                        $runClass = Mage::app()->getConfig()->getModelClassName($run[1]);
                        $runMethod = $run[2];
                    }
                }

                if ($jobConfig->schedule && $jobConfig->schedule->config_path) {
                    $cronExpr = Mage::getStoreConfig((string)$jobConfig->schedule->config_path);
                }
                if ($cronExpr == 'n/a' && $jobConfig->schedule && $jobConfig->schedule->cron_expr) {
                    $cronExpr = (string)$jobConfig->schedule->cron_expr;
                }
                if (!$cronExpr) {
                    $cronExpr = 'n/a';
                }
            }

            if ($runClass != 'n/a') {
                if ($type != 'all') {
                    $nameSpace = substr($runClass, 0, strpos($runClass, '_'));
                    $_className = str_replace($nameSpace . '_', '', $runClass);
                    $module = $nameSpace . '_' . substr($_className, 0, strpos($_className, '_'));
                }  else {
                    $module = '';
                    $nameSpace = '';
                }
                if (($type == 'core' && !in_array($nameSpace, $coreNamespaces)
                    && !in_array($module, $this->_additionalCoreModules['community']))
                    || ($type == 'custom' && (in_array($nameSpace, $coreNamespaces) || $nameSpace == 'Enterprise'
                        || in_array($module, $this->_additionalCoreModules['community'])))
                    || ($type == 'enterprise' && $nameSpace != 'Enterprise')
                ) {
                    continue;
                }
            }

            $classPath = $this->_getClassPath($runClass, $this->_getModuleCodePoolByClassName($runClass));
            $runClass = $this->_formatCLIStyle($runClass, 'yellow', null, array('bold'));
            $jobsData[$jobCode] = array($jobCode, $cronExpr, $runClass . "\n" . '    {' . $classPath . '}', $runMethod);
        }
        ksort($jobsData);

        foreach ($jobsData as $_data) {
            $data[] = $_data;
        }

        return $data;
    }

    /**
     * Get FPC data by specified cache ID
     *
     * @param array $arguments
     *
     * @throws Exception
     */
    protected function _generateFpcData(array $arguments = array())
    {
        $arguments['cache_id'] = $arguments['fpc'];
        if (!$this->_properties['is_enterprise_mode']) {
            throw new Exception('Full Page Cache is not supported in none EE instance.');
        }
        /** @var $cacheInstance Mage_Core_Model_Cache */
        if (version_compare($this->_magentoVersion, '1.11.0.0', '<')) {
            $cacheInstance = Mage::app()->getCacheInstance();
        } else {
            $cacheInstance = Enterprise_PageCache_Model_Cache::getCacheInstance();
        }

        if (!isset($arguments['cache_id']) || $arguments['cache_id'] == '') {
            throw new Exception('Cache ID is required to be specified.');
        }
        if (!$cacheInstance->getFrontend()->test($arguments['cache_id'])) {
            throw new Exception('FPC Data for cache ID "' . $arguments['cache_id'] . '" is not exist or expired.');
        }

        $data = $cacheInstance->load($arguments['cache_id']);
        if (isset($arguments['uncompress']) && function_exists('gzuncompress')) {
            $data = @gzuncompress($data);
        } else if (isset($arguments['unserialize'])) {
            $data = @unserialize($data);
            if ($data) {
                $data = print_r($data, true);
            }
        } else if (isset($arguments['unjson'])) {
            $data = @json_decode($data);
            if ($data) {
                $data = print_r($data, true);
            }
        }

        $data = "\n\n" . $arguments['cache_id'] . ":\n" . $data;

        if (isset($arguments['into']) && $arguments['into']) {
            if (!$this->_checkFileName($arguments['into'])) {
                throw new Exception(
                    'Cant\'t save FPC data into specified file, file name is incorrect.'
                );
            }

            $path = $this->_getWorkingPath();
            if (!is_writable($path)) {
                throw new Exception(
                    'Cant\'t write to directory where sysreport tool resides. FPC data will not be saved into specified file.'
                );
            }

            $writtenBytes = file_put_contents($path . $arguments['into'], $data);
            $this->_log(
                null,
                ($writtenBytes !== false
                    ? 'FPC Data File "' . $arguments['into'] . '" for cache ID "' . $arguments['cache_id']
                    . '" was successfully generated'
                    : 'FPC Data File "' . $arguments['into'] . '" for cache ID "' . $arguments['cache_id']
                    . '" wasn\'t generated'
                )
            );
        } else {
            echo $data . "\n";
        }
    }

    /**
     * Generate websites tree
     * Collect detailed and most useful information about all websites, stores and store views
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateWebsitesTreeData(array $arguments = array())
    {
        $data = array();

        try {
            $websites = Mage::app()->getWebsites();
            $categories = $this->_getRootCategories();

            /** @var Mage_Core_Model_Website $website */
            foreach ($websites as $websiteId => $website) {
                $name = $website->getName() . ($website->getIsDefault()  ? ' [*] ' : '');
                $data[] = array(
                    $websiteId,
                    $name,
                    $website->getCode(),
                    'website',
                    ''
                );
                $defaultStoreId = $website->getDefaultGroupId();
                $stores = $website->getGroups();
                /** @var Mage_Core_Model_Store_Group $store */
                foreach ($stores as $storeId => $store) {
                    $name = '    ' . $store->getName() . ($defaultStoreId == $storeId  ? ' [*]' : '');
                    $data[] = array(
                        $storeId,
                        $name,
                        '',
                        'store',
                        isset($categories[$store->getRootCategoryId()])
                            ? $categories[$store->getRootCategoryId()]
                            : 'n/a'
                    );
                    $defaultStoreViewId = $store->getDefaultStoreId();
                    $storeViews = $store->getStores();
                    /** @var Mage_Core_Model_Store $storeView */
                    foreach ($storeViews as $storeViewId => $storeView) {
                        $name = '        '
                             . (!$storeView->getIsActive() ? '-disabled- ' : '')
                              . $storeView->getName()
                              . ($defaultStoreViewId == $storeViewId  ? ' [*]' : '');
                        $data[] = array(
                            $storeId,
                            $name,
                            $storeView->getCode(),
                            'store view',
                            ''
                        );
                    }
                }
            }
        } catch (Exception $e) {
            $this->_log($e);
        }

        $systemReport = array();
        $systemReport['Websites Tree'] = array(
            'header' => array('ID', 'Name', 'Code', 'Type', 'Root Category'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate websites list with default store and default store view
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateWebsitesData(array $arguments = array())
    {
        $data = array();

        try {
            $websites = Mage::app()->getWebsites();
            /** @var Mage_Core_Model_Website $website */
            foreach ($websites as $id => $website) {
                $defaultStore = $website->getDefaultGroup();
                $defaultStoreView = $website->getDefaultStore();
                $data[] = array(
                    $id,
                    $website->getName(),
                    $website->getCode(),
                    $website->getIsDefault() ? 'Yes' : 'No',
                    $defaultStore
                        ? $defaultStore->getName() . ' {ID:' . $defaultStore->getId() . '}'
                        : 'n/a',
                    $defaultStoreView
                        ? $defaultStoreView->getName() . ' {ID:' . $defaultStoreView->getId() . '}'
                        : 'n/a'
                );
            }
        } catch (Exception $e) {
            $this->_log($e);
        }

        $systemReport = array();
        $systemReport['Websites List'] = array(
            'header' => array('ID', 'Name', 'Code', 'Is Default', 'Default Store', 'Default Store View'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate stores list with root category and default store view
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateStoresData(array $arguments = array())
    {
        $data = array();

        try {
            $stores = Mage::app()->getGroups();
            $categories = $this->_getRootCategories();

            /** @var Mage_Core_Model_Store_Group $store */
            foreach ($stores as $id => $store) {
                $defaultStoreView = $store->getDefaultStore();
                $data[] = array(
                    $id,
                    $store->getName(),
                    (
                      isset($categories[$store->getRootCategoryId()]) ? $categories[$store->getRootCategoryId()] : 'n/a'
                    )
                    . ' {ID:' . $store->getRootCategoryId() . '}',
                    $defaultStoreView
                        ? $defaultStoreView->getName() . ' {ID:' . $defaultStoreView->getId() . '}'
                        : 'n/a'
                );
            }
        } catch (Exception $e) {
            $this->_log($e);
        }

        $systemReport = array();
        $systemReport['Stores List'] = array(
            'header' => array('ID', 'Name', 'Root Category', 'Default Store View'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Collect store root categories
     *
     * @return array
     */
    protected function _getRootCategories()
    {
        /** @var Mage_Catalog_Model_Resource_Category_Collection $collection */
        $categoryCollection = Mage::getResourceModel('catalog/category_collection');
        $categoryCollection->addAttributeToSelect('name')
            ->addFieldToFilter('path', array('neq' => '1'))
            ->addFieldToFilter('level', array('lteq' => '1'))
            ->load();

        $categories = array();
        foreach ($categoryCollection as $category) {
            $categories[$category->getId()] = $category->getName();
        }

        return $categories;
    }

    /**
     * Generate store views list with sore
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateStoreViewsData(array $arguments = array())
    {
        $data = array();

        try {
            $storeViews = Mage::app()->getStores();
            /** @var Mage_Core_Model_Store $storeView */
            foreach ($storeViews as $id => $storeView) {
                $defaultStore = $storeView->getGroup();
                $data[] = array(
                    $id,
                    $storeView->getName(),
                    $storeView->getCode(),
                    $storeView->getIsActive() ? 'Yes' : 'No',
                    $defaultStore
                        ? $defaultStore->getName() . ' {ID:' . $defaultStore->getId() . '}'
                        : 'n/a'
                );
            }
        } catch (Exception $e) {
            $this->_log($e);
        }

        $systemReport = array();
        $systemReport['Store Views List'] = array(
            'header' => array('ID', 'Name', 'Code', 'Enabled', 'Store'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate design themes config data
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateDesignThemeConfigData(array $arguments = array())
    {
        $configPaths = array(
           // text, STORE VIEW
            array('path' => 'design/package/name', 'name' => 'Current Package Name'),
            // text, STORE VIEW
            array('path' => 'design/theme/default', 'name' => 'Default Theme'),
            // text, STORE VIEW
            array('path' => 'design/theme/locale', 'name' => 'Translations Theme'),
            // text, STORE VIEW
            array('path' => 'design/theme/layout', 'name' => 'Layouts Theme'),
            // text, STORE VIEW
            array('path' => 'design/theme/template', 'name' => 'Templates Theme'),
            // text, STORE VIEW
            array('path' => 'design/theme/skin', 'name' => 'Skin (Images / CSS)'),
        );
        $data = array();
        try {
            $data = array();
            $configData = $this->_getConfigValues($configPaths);
            foreach ($configData as $info) {
                $data[] = array(
                    $info['name'],
                    $info['value'],
                    $info['scope']
                );
            }
        } catch (Exception $e) {
            $this->_log($e);
        }

        $systemReport = array();
        $systemReport['Design Themes Config'] = array(
            'header' => array('Name', 'Value', 'Scope'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Generate design themes list
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateDesignThemeListData(array $arguments = array())
    {
        $systemReport = array();
        try {
            $reports = $this->_getDesignList();
            foreach ($reports as $reportName => $data) {
                $reportName = ucwords($reportName);
                $systemReport[$reportName . ' Themes List'] = array(
                    'header' => array('Name', 'Type'),
                    'data' => $data
                );
            }
        } catch (Exception $e) {
            $this->_log($e);
        }

        return $systemReport;
    }

    /**
     * Generate skins list
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _generateDesignSkinsListData(array $arguments = array())
    {
        $systemReport = array();
        try {
            $reports = $this->_getDesignList('skin');
            foreach ($reports as $reportName => $data) {
                if (empty($data)) {
                    continue;
                }
                $reportName = ucwords($reportName);
                $systemReport[$reportName . ' Skins List'] = array(
                    'header' => array('Name', 'Type'),
                    'data' => $data
                );
            }
        } catch (Exception $e) {
            $this->_log($e);
        }

        return $systemReport;
    }

    /**
     * Collect themes or skins list with default mark for frontend area
     *
     * @param string $type
     *
     * @return array
     */
    protected function _getDesignList($type = 'design')
    {
        $type = $type == 'design' || $type == 'skin' ? $type : 'design';
        $configInfo = array('path' => 'design/package/name');
        $defaultPackage = Mage::getStoreConfig($configInfo['path'], Mage_Core_Model_App::DISTRO_STORE_ID);
        $defaultPackage = $this->_prepareConfigValue($configInfo, $defaultPackage);

        if ($type == 'design') {
            $configInfo = array('path' => 'design/theme/default');
        } else {
            $configInfo = array('path' => 'design/theme/skin');
        }
        $defaultDesign = Mage::getStoreConfig($configInfo['path'], Mage_Core_Model_App::DISTRO_STORE_ID);
        $defaultDesign = $this->_prepareConfigValue($configInfo, $defaultDesign);

        $designDirectory = Mage::getBaseDir($type) . DIRECTORY_SEPARATOR;
        $entries = $this->_getFilesList($designDirectory, 2, self::REPORT_FILE_LIST_DIRS);
        $results = array();
        foreach ($entries as $entry) {
            $entry = substr($entry, strlen($designDirectory));
            if (preg_match('~\..+~', $entry)) {
                continue;
            }
            $parts = explode(DIRECTORY_SEPARATOR, $entry);
            $partsSize = sizeof($parts);
            if ($partsSize == 1) {
                $results[$parts[0]] = array();
            } else if ($partsSize == 2) {
                $name = $parts[1];
                if ($parts[0] == 'frontend') {
                    $name = $defaultPackage == $name ? $name . ' [*]' : $name;
                }
                $results[$parts[0]][] = array($name, 'package');
            } else if ($partsSize == 3) {
                $name = $parts[2];
                if ($parts[0] == 'frontend' && $defaultPackage == $parts[1]) {
                    $name = $defaultDesign == $name ? $name . ' [*]' : $name;
                }
                $results[$parts[0]][] = array(
                    '    ' . $name,
                    $type == 'design' ? 'theme' : 'skin'
                );
            }
        }
        ksort($results);

        return $results;
    }

    /**
     * Disable custom modules on config level and hard rewrites
     *
     * @param array $arguments
     *
     * @return array
     */
    protected function _disableCustomModules(array $arguments = array())
    {
        $arguments['modules'] = array_filter(
            array_map('trim', explode(',', !empty($arguments['dcm']) ? $arguments['dcm'] : ''))
        );
        if (empty($arguments['modules'])) {
            $this->_log(null, 'Nothing to disable.');
        }

        /**
         * 1. Validate specified modules
         */
        $validModules = null;
        if (!in_array('*', $arguments['modules'])) {
            $modulesToValidate = array();
            foreach ($arguments['modules'] as $module) {
                if (!preg_match('~([a-z0-9]+)\_((?:[a-z0-9]+|\*))$~i', $module, $matches)) {
                    continue;
                }
                if (isset($modulesToValidate[$matches[1]])) {
                    if ($matches[2] == '*') {
                        $modulesToValidate[$matches[1]] = array($matches[2]);
                    }
                    if (in_array('*', $modulesToValidate[$matches[1]])
                        || in_array($matches[2], $modulesToValidate[$matches[1]])
                    ) {
                        continue;
                    }
                }
                $modulesToValidate[$matches[1]][] = $matches[2];
            }
        } else {
            $modulesToValidate['*'] = array('*');
        }

        $pools = array_flip($this->_allowedCodePools);
        if (isset($arguments['localonly'])) {
            unset($pools['community']);
        }
        if (isset($arguments['communityonly'])) {
            unset($pools['local']);
        }
        if (empty($pools)) {
            $pools = $this->_allowedCodePools;
        } else {
            $pools = array_flip($pools);
        }
        $validModules = $this->_validateModules($modulesToValidate, $pools, $forEnabling = isset($arguments['enable']));

        /**
         * 2. Disable modules
         */
        $data = array();
        if (!empty($validModules)) {
            foreach ($validModules as $poolNSModule => $pathInfo) {
                $moduleInfo = explode('/', $poolNSModule);
                $codePool = $moduleInfo[0];
                $nsModuleName = $moduleInfo[1];
                $moduleInfo = explode('_', $moduleInfo[1]);
                $moduleName = $moduleInfo[1];
                $result = null;
                try {
                    $activeValue = isset($arguments['enable']) ? 'true' : 'false';
                    if (isset($pathInfo['config_path'])) {
                        $configData = file_get_contents($pathInfo['config_path']);
                        $searchPattern =
                            '<(' . preg_quote($nsModuleName) . ')>(\s+)' .
                                '(.*<active>)(?:true|1|false|0)(</active>.+)' .
                            '</' . preg_quote($nsModuleName) . '>';
                        $configData = preg_replace(
                            '~' . $searchPattern . '~s',
                            "<\${1}>\${2}\${3}$activeValue\${4}</\${1}>",
                            $configData
                        );
                        $result = (bool)file_put_contents($pathInfo['config_path'], $configData);
                    }
                    if (is_null($result) || $result === true) {
                        if (isset($arguments['enable'])) {
                            $modulePath = str_replace(
                                self::DCM_DISABLED_MODULE_DIRECTORY_PREFIX, '', $pathInfo['module_path']
                            );
                        } else {
                            $modulePath = substr($pathInfo['module_path'], 0, -strlen($moduleName) - 1) .
                                self::DCM_DISABLED_MODULE_DIRECTORY_PREFIX . $moduleName;
                        }
                        $result = (bool)rename($pathInfo['module_path'], $modulePath);
                    }
                } catch (Exception $e) {
                    $this->_log($e);
                }
                $data[] = array(
                    $nsModuleName,
                    $codePool,
                    $result !== true ? 'Fail' : (isset($arguments['enable']) ? 'Enabled' : 'Disabled')
                );
            }
        }

        /**
         * 3. Flush cache if requested
         */
        $systemReport = array();
        $systemReport['Disabled Modules'] = array(
            'header' => array('Module', 'Code Pool', 'Status'),
            'data' => $data
        );

        if (isset($arguments['cleancache'])) {
            $systemReport = array_merge($systemReport, $this->_controlCache(array('flush' => true)));
        }

        return $systemReport;
    }

    /**
     * Check specified namespaces and modules for existence and ability to be disabled
     * as hard rewrite or/and on config level
     *
     * @param array $namespaceToModulesMap
     * @param array $pools
     * @param bool $forEnabling
     *
     * @return array
     * @throws Exception
     */
    protected function _validateModules($namespaceToModulesMap, $pools=array('local','community'), $forEnabling = false)
    {
        if (!is_array($namespaceToModulesMap) || empty($namespaceToModulesMap)) {
            return array();
        }
        clearstatcache();

        $codeDir       = Mage::getBaseDir('code') . DIRECTORY_SEPARATOR;
        $etcModulesDir = Mage::getBaseDir('etc')  . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR;

        $additionalCoreModules = array();
        foreach ($this->_additionalCoreModules as $_modules) {
            $additionalCoreModules = array_merge($additionalCoreModules, $_modules);
        }

        /**
         * 1. Collect code pools to work in
         */
        $pools = !is_array($pools) ? array($pools) : (empty($pools) ? $this->_allowedCodePools : $pools);
        $originalPoolNames = array();
        foreach ($pools as $poolIndex => &$pool) {
            if (!in_array($pool, $this->_allowedCodePools)) {
                unset($pools[$poolIndex]);
            } elseif (is_dir($codeDir . $pool)) {
                $originalPoolNames[] = $pool;
                $pool = $codeDir . $pool . DIRECTORY_SEPARATOR;
            }
        }
        if (empty($pools)) {
            $this->_log(null, 'None of the specified code pools exists.');
            return array();
        }

        /**
         * 2. Check if only hard rewrites (modules) must be disabled
         */
        $disableOnlyHardRewrite = true;
        foreach ($namespaceToModulesMap as $namespace => $modules) {
            // In all modules mode this flag must be set to false
            if ($namespace == '*') {
                $disableOnlyHardRewrite = false;
                break;
            }
            if (in_array($namespace, $this->_coreNamespaces)) {
                continue;
            }
            foreach ($modules as $module) {
                foreach ($originalPoolNames as $poolName) {
                    if (!in_array($namespace . '_' . $module, $this->_additionalCoreModules[$poolName])) {
                        $disableOnlyHardRewrite = false;
                        break 3;
                    }
                }
            }
        }

        /**
         * 3. Check if "app/etc/modules" directory can be used at all.
         *    Create module => config_file map
         */
        if (!$disableOnlyHardRewrite && !is_writable($etcModulesDir)) {
            throw new Exception(
                '"app/etc/modules" directory is not writable or not exists. Not all specified modules can be disabled.'
                . "\nIn this case specify only hard rewrite modules to disable."
            );
        } else {
            $moduleToConfigFileMap = $this->_getModulesConfigFileMap();
        }

        /**
         * 4. Build up modules list to be disabled
         */
        $modulesListToDisable = array();
        $allModulesFlag = false;
        // For every namespace
        foreach ($namespaceToModulesMap as $namespaceName => $modules) {
            if ($namespaceName == '*') {
                $allModulesFlag = true;
            }
            // Each module
            foreach ($modules as $moduleName) {
                // Must be checked for existence in every requested code pool
                foreach ($pools as $poolPath) {
                    $namespacePath = $poolPath . $namespaceName . DIRECTORY_SEPARATOR;
                    // ==========================================================
                    $path = $namespacePath . ($moduleName == '*' ? '[!_]*' : $moduleName);
                    if ($forEnabling) {
                        $path = $namespacePath . self::DCM_DISABLED_MODULE_DIRECTORY_PREFIX . $moduleName;
                    }
                    $existingModules = glob($path, GLOB_ONLYDIR | GLOB_MARK);
                    // ==========================================================
                    if (empty($existingModules)) {
                        continue;
                    }
                    // Connect every existing module that must be disabled to config file where it should be turned off
                    foreach ($existingModules as $modulePath) {
                        // In all modules mode namespace is "*" but we need to determine namespace per each module
                        // as well as module name must be also determined according to existing modules
                        if ($allModulesFlag) {
                            // Magic =)
                            $existingModuleName = substr(
                                $modulePath,
                                strrpos(substr($modulePath, 0, -1), DIRECTORY_SEPARATOR) + 1,
                                -1
                            );
                            $realNamespaceName = substr($modulePath, 0, -strlen($existingModuleName) - 2);
                            $realNamespaceName = substr(
                                $realNamespaceName,
                                strrpos(substr($realNamespaceName, 0, -1), DIRECTORY_SEPARATOR) + 1
                            );
                        } else {
                            $existingModuleName = substr($modulePath, strlen($namespacePath), -1);
                            $realNamespaceName = $namespaceName;
                        }
                        if ($forEnabling) {
                            $existingModuleName = str_replace(
                                self::DCM_DISABLED_MODULE_DIRECTORY_PREFIX, '', $existingModuleName
                            );
                        }
                        $nsModule = $realNamespaceName . '_' . $existingModuleName;
                        $poolName = substr($poolPath, strlen($codeDir), -1);
                        // Do not collect additional core modules that can exist for example in "community" code poo;
                        if (in_array($nsModule, $this->_additionalCoreModules[$poolName])) {
                            continue;
                        }
                        // If module doesn't belong to core modules
                        if (!in_array($realNamespaceName, $this->_coreNamespaces)
                            && !in_array($nsModule, $additionalCoreModules)
                        ) {
                            // Add this module to list only if correspondence config file ready to be updated for it
                            if (array_key_exists($nsModule, $moduleToConfigFileMap)) {
                                $modulesListToDisable[$poolName . '/' . $nsModule] = array(
                                    'module_path' => $modulePath,
                                    'config_path' => $moduleToConfigFileMap[$nsModule]
                                );
                            }
                        }
                        // Otherwise add module as rewrite module to be disabled
                        else {
                            $modulesListToDisable[$poolName . '/' . $nsModule] = array('module_path' => $modulePath);
                        }
                    }
                }
            }
        }

        return $modulesListToDisable;
    }

    /**
     * Generate module => config_file map
     * Files being collected in app/etc/modules directory
     *
     * @return array
     */
    protected function _getModulesConfigFileMap()
    {
        $etcModulesDir = Mage::getBaseDir('etc')  . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR;
        if (!is_readable($etcModulesDir)) {
            return array();
        }

        $moduleToConfigFileMap = array();
        $modulesConfigFiles = $this->_getFilesList($etcModulesDir, 1, self::REPORT_FILE_LIST_FILES, array(), '^.*\.xml$');
        foreach ($modulesConfigFiles as $configFile) {
            // If config file is not possible to read then it will be skipped
            if (!is_readable($configFile)
                || $this->_getFileSize($configFile) > self::MODULE_CONFIG_FILE_MAX_SIZE
            ) {
                continue;
            }
            $configData = file_get_contents($configFile);
            preg_match_all('~<([a-z0-9]+)\_([a-z0-9]+)>~i', $configData, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                /*
                 * Note: each module can be defined only once in any config, so it will be defined only for
                 * one code pool
                 */
                $moduleToConfigFileMap[$match[1] . '_' . $match[2]] = $configFile;
            }
        }

        return $moduleToConfigFileMap;
    }

    /**
     * Generate category duplicates by URL key
     *
     * @param array $arguments
     *
     * @throws Exception
     * @return array
     */
    protected function _generateCategoryDuplicates(array $arguments = array())
    {
        $systemReport = array();

        $systemReport['Duplicate Categories By URL Key'] = array(
            'header' => array('ID', 'URL key', 'Name', 'Store'),
            'data' => $this->_getDuplicateUrlKeys('category'),
        );

        return $systemReport;
    }

    /**
     * Generate corrupted categories data
     *
     * @param array $arguments
     *
     * @throws Exception
     * @return array
     */
    protected function _generateCorruptedCategoriesData(array $arguments = array())
    {
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. Corrupted categories data can\'t be retrieved.');
        }
        $connection = $this->_readConnection;
        $data = $systemReport = array();

        try {
            $expected = $connection->fetchAll(
                "SELECT c.entity_id,
                        COUNT(c2.children_count) as `children_count`,
                        (LENGTH(c.path) - LENGTH(REPLACE(c.path,'/',''))) as `level`
                FROM `{$this->_getTableName('catalog/category')}` c
                LEFT JOIN `{$this->_getTableName('catalog/category')}` c2 ON c2.path like CONCAT(c.path,'/%')
                GROUP BY c.path"
            );
            $_expected = $_actual = array();
            foreach ($expected as $row) {
                $_expected[$row['entity_id']] = array(
                    'children_count' => $row['children_count'],
                    'level' => $row['level'],
                );
            }
            $actual = $connection->fetchAll(
                "SELECT `entity_id`, `children_count`, `level`
                FROM `{$this->_getTableName('catalog/category')}`"
            );
            foreach ($actual as $row) {
                $_actual[$row['entity_id']] = array(
                    'children_count' => $row['children_count'],
                        'level' => $row['level'],
                );
            }
            foreach ($_actual as $entityId => $_data) {
                $actualChildrenCount = $_data['children_count'];
                $actualLevel = $_data['level'];
                if (!array_key_exists($entityId, $_expected)) {
                    $data[] = array($entityId, 'n/a', $actualChildrenCount, 'n/a', $actualLevel);
                    continue;
                }
                $expectedChildrenCount = $_expected[$entityId]['children_count'];
                $expectedLevel = $_expected[$entityId]['level'];
                if ($actualChildrenCount == $expectedChildrenCount && $actualLevel == $expectedLevel) {
                    continue;
                }

                $difference = $actualChildrenCount - $expectedChildrenCount;
                if ($difference != 0) {
                    $difference = ' (diff: ' . ($difference > 0 ? '+' : '') . $difference . ')';
                } else {
                    $difference = '';
                }
                $actualChildrenCount .= $difference;

                $difference = $actualLevel - $expectedLevel;
                if ($difference != 0) {
                    $difference = ' (diff: ' . ($difference > 0 ? '+' : '') . $difference . ')';
                } else {
                    $difference = '';
                }
                $actualLevel .= $difference;

                $data[] = array(
                    $entityId,
                    $expectedChildrenCount,
                    $actualChildrenCount,
                    $expectedLevel,
                    $actualLevel
                );
            }

            $systemReport['Corrupted Categories Data'] = array(
                'header' => array(
                    'ID',
                    'Expected Children Count',
                    'Actual Children Count',
                    'Expected Level',
                    'Actual Level',
                ),
                'data' => $data
            );
        } catch (Exception $e) {
            $this->_log($e);
        }

        return $systemReport;
    }

    /**
     * Generate product duplicates by sku and URL key
     *
     * @param array $arguments
     *
     * @throws Exception
     * @return array
     */
    protected function _generateProductDuplicates(array $arguments = array())
    {
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. Product duplicates data can\'t be retrieved.');
        }
        $connection = $this->_readConnection;
        $data = $systemReport = array();

        $systemReport['Duplicate Products By URL Key'] = array(
            'header' => array('ID', 'URL key', 'Name', 'Store'),
            'data' => $this->_getDuplicateUrlKeys('product'),
        );

        try {
            $entityTypeCode = Mage_Catalog_Model_Product::ENTITY;
            $entityTypeId = (int)Mage::getSingleton('eav/config')->getEntityType($entityTypeCode)->getId();

            $entityTable  = $this->_getTableName('catalog/product');
            $varCharTable  = $this->_getTableName(array('catalog/product', 'varchar'));

            $nameAttributeId = (int)$connection->fetchOne(
                "SELECT `attribute_id`
                    FROM `{$this->_getTableName('eav/attribute')}`
                    WHERE `attribute_code` = 'name' AND `entity_type_id` = {$entityTypeId}"
            );

            $info = $connection->fetchAll(
                "SELECT COUNT(1) AS `cnt`, `sku`
                FROM `{$entityTable}`
                GROUP BY `sku` HAVING `cnt` > 1 ORDER BY `cnt` DESC, `entity_id`"
            );
            foreach ($info as $row) {
                $entities = $connection->fetchAll(
                    "SELECT `e`.`entity_id`, `n`.`value` as `name`, `e`.`sku`
                        FROM `{$entityTable}` e
                        LEFT JOIN `{$varCharTable}` n
                            ON `e`.`entity_id` = `n`.`entity_id` AND `n`.attribute_id = {$nameAttributeId}
                        WHERE " . $connection->quoteInto('`e`.`sku` = ?', $row['sku'])
                );
                foreach ($entities as $entity) {
                    $data[] = array(
                        $entity['entity_id'],
                        $row['sku'],
                        $entity['name'],
                    );
                }
            }

            $systemReport['Duplicate Products By SKU'] = array(
                'header' => array('ID', 'SKU', 'Name',),
                'data' => $data,
            );
        } catch (Exception $e) {
            $this->_log($e);
        }

        return $systemReport;
    }

    /**
     * Generate order duplicates by Increment ID
     *
     * @param array $arguments
     *
     * @throws Exception
     * @return array
     */
    protected function _generateOrderDuplicates(array $arguments = array())
    {
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. Order duplicates data can\'t be retrieved.');
        }
        $connection = $this->_readConnection;
        $data = $systemReport = array();

        try {
            $entityTable  = $this->_getTableName('sales/order');

            $info = $connection->fetchAll(
                "SELECT COUNT(1) AS `cnt`, `increment_id`
                FROM `{$entityTable}`
                GROUP BY `increment_id` HAVING `cnt` > 1 ORDER BY `cnt` DESC, `entity_id`"
            );
            foreach ($info as $row) {
                $entities = $connection->fetchAll(
                    "SELECT `e`.`entity_id`, `e`.`store_id` , `e`.`customer_id`, `e`.`increment_id`, `e`.`created_at`,
                            `s`.`name` as `store_name`
                    FROM `{$entityTable}` e
                    LEFT JOIN `{$this->_getTableName('core/store')}` s USING(store_id)
                    WHERE " . $connection->quoteInto('`e`.`increment_id` = ?', $row['increment_id'])
                );

                foreach ($entities as $entity) {
                    $data[] = array(
                        $entity['entity_id'],
                        $row['increment_id'],
                        $entity['store_name'] . ' {ID:' . $entity['store_id'] . '}',
                        $entity['created_at'],
                        $entity['customer_id'],
                    );
                }
            }

            $systemReport['Duplicate Orders By Increment ID'] = array(
                'header' => array('ID', 'Increment ID', 'Store', 'Created At', 'Customer ID'),
                'data' => $data,
            );
        } catch (Exception $e) {
            $this->_log($e);
        }

        return $systemReport;
    }

    /**
     * Generate user duplicates by email
     *
     * @param array $arguments
     *
     * @throws Exception
     * @return array
     */
    protected function _generateUserDuplicates(array $arguments = array())
    {
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. User duplicates data can\'t be retrieved.');
        }
        $connection = $this->_readConnection;
        $data = $systemReport = array();

        try {
            $entityTable  = $this->_getTableName('customer/entity');

            $info = $connection->fetchAll(
                "SELECT COUNT(1) AS `cnt`, `email`
                FROM `{$entityTable}`
                GROUP BY `email` HAVING `cnt` > 1 ORDER BY `cnt` DESC, `entity_id`"
            );
            foreach ($info as $row) {
                $entities = $connection->fetchAll(
                    "SELECT `e`.`entity_id`, `e`.`email`, `e`.`website_id`, `e`.`created_at`,
                            `w`.`name` as `website_name`
                    FROM `{$entityTable}` e
                    LEFT JOIN `{$this->_getTableName('core/website')}` w USING(website_id)
                    WHERE " . $connection->quoteInto('`e`.`email` = ?', $row['email'])
                );

                foreach ($entities as $entity) {
                    $data[] = array(
                        $entity['entity_id'],
                        $row['email'],
                        $entity['website_name'] . ' {ID:' . $entity['website_id'] . '}',
                        $entity['created_at'],
                    );
                }
            }

            $systemReport['Duplicate Users By Email'] = array(
                'header' => array('ID', 'Email', 'Website', 'Created At'),
                'data' => $data,
            );
        } catch (Exception $e) {
            $this->_log($e);
        }

        return $systemReport;
    }

    /**
     * Collect duplicate URL keys for specified entity type
     *
     * @param string $entityType
     *
     * @return array
     *
     * @throws Exception
     */
    protected function _getDuplicateUrlKeys($entityType)
    {
        if (!$this->_readConnection) {
            throw new Exception('Cant\'t connect to DB. Duplicate URL keys data can\'t be retrieved.');
        }
        $connection = $this->_readConnection;
        $data = array();

        switch ($entityType) {
            case 'product':
                $entityTypeCode = Mage_Catalog_Model_Product::ENTITY;
                $table = 'catalog/product';
                break;
            case 'category':
                $entityTypeCode = Mage_Catalog_Model_Category::ENTITY;
                $table = 'catalog/category';
                break;
            default:
                throw new Exception('Unsupported entity type: "' . (string)$entityType . '"');
        }

        try {
            $entityTypeId = (int)Mage::getSingleton('eav/config')->getEntityType($entityTypeCode)->getId();
            $nameAttributeId = (int)$connection->fetchOne(
                "SELECT `attribute_id`
                FROM `{$this->_getTableName('eav/attribute')}`
                WHERE `attribute_code` = 'name' AND `entity_type_id` = {$entityTypeId}"
            );
            $urlKeyAttributeId = (int)$connection->fetchOne(
                "SELECT `attribute_id`
                FROM `{$this->_getTableName('eav/attribute')}`
                WHERE `attribute_code` = 'url_key' AND `entity_type_id` = {$entityTypeId}"
            );

            $urlKeyTable = $varCharTable = $this->_getTableName(array($table, 'varchar'));
            if ((version_compare($this->_magentoVersion, '1.13.0.0', '>=') && $this->_magentoEdition == 'EE')) {
                $urlKeyTable  = $this->_getTableName(array($table, 'url_key'));
            }

            $info = $connection->fetchAll(
                "SELECT COUNT(1) AS `cnt`, `value`
                FROM `{$urlKeyTable}`
                WHERE `attribute_id` = {$urlKeyAttributeId}
                GROUP BY `value` HAVING `cnt` > 1 ORDER BY `cnt` DESC, `entity_id`"
            );
            foreach ($info as $row) {
                $entities = $connection->fetchAll(
                    "SELECT `u`.`entity_id`, `n`.`value` as `name`, `u`.`store_id`, `s`.`name` as `store_name`
                    FROM `{$urlKeyTable}` u
                    LEFT JOIN `{$this->_getTableName('core/store')}` s ON `u`.`store_id` = `s`.`store_id`
                    LEFT JOIN `{$varCharTable}` n
                        ON `u`.`entity_id` = `n`.`entity_id` AND
                           `u`.`store_id` = `n`.`store_id` AND
                           `n`.attribute_id = {$nameAttributeId}
                    WHERE `u`.`attribute_id` = {$urlKeyAttributeId}
                        AND " . $connection->quoteInto('`u`.`value` = ?', $row['value'])
                );
                foreach ($entities as $entity) {
                    $data[] = array(
                        $entity['entity_id'],
                        $row['value'],
                        $entity['name'],
                        $entity['store_name'] . ' {ID:' . $entity['store_id'] . '}'
                    );
                }
            }
        } catch (Exception $e) {
            $this->_log($e);
        }

        return $data;
    }

    /**
     * Control system cache: refresh, flush, disable, enable
     *
     * @param array $arguments
     *
     * @throws Exception
     *
     * @return array
     */
    protected function _controlCache(array $arguments = array())
    {
        $systemReport = $data = $inputTypes = array();
        if (!empty($arguments['enable'])) {
            $inputTypes = $arguments['enable'];
        } else if (!empty($arguments['disable'])) {
            $inputTypes = $arguments['disable'];
        } else if (!empty($arguments['refresh'])) {
            $inputTypes = $arguments['refresh'];
        }
        // Flush Magento cache storage
        else if (isset($arguments['flush'])) {
            Mage::app()->cleanCache();
            $data[] = array('Core Cache', 'Flushed');

            if ($this->_properties['is_enterprise_mode']) {
                if (version_compare($this->_magentoVersion, '1.11.0.0', '<')) {
                    Mage::app()->cleanCache(Enterprise_PageCache_Model_Processor::CACHE_TAG);
                } else {
                    Enterprise_PageCache_Model_Cache::getCacheInstance()->clean(
                        Enterprise_PageCache_Model_Processor::CACHE_TAG
                    );
                }
                $data[] = array('FPC Cache', 'Flushed');
            }

            $systemReport['Flush Cache'] = array(
                'header' => array('Cache Type', 'Result'),
                'data' => $data
            );

            return $systemReport;
        }

        $availableCacheTypes = array();
        $_availableCacheTypes = Mage::app()->getCacheInstance()->getTypes();
        foreach ($_availableCacheTypes as $type => $typeInfo) {
            $availableCacheTypes[$type] = $typeInfo->getCacheType();
        }
        unset($_availableCacheTypes);

        // Determine cache types to be affected
        $inputTypes = array_filter(
            array_map('trim', explode(',', $inputTypes))
        );

        if (!in_array('all', $inputTypes)) {
            $inputTypes = array_intersect(array_keys($availableCacheTypes), $inputTypes);
            if (empty($inputTypes)) {
                throw new Exception('Specified cache type(s) not exist(s) in the system.');
            }
        } else {
            $inputTypes = array_keys($availableCacheTypes);
        }

        $action = null;
        if (!empty($arguments['enable'])) {
            $action = 'Enable';
            $allTypes = (array)Mage::app()->useCache();

            foreach ($inputTypes as $type) {
                if (empty($allTypes[$type])) {
                    $allTypes[$type] = 1;
                    $data[] = array($availableCacheTypes[$type], 'Enabled');
                }
            }

            if (!empty($data)) {
                Mage::app()->saveUseCache($allTypes);
            }
        } else if (!empty($arguments['disable'])) {
            $action = 'Disable';
            $allTypes = (array)Mage::app()->useCache();

            foreach ($inputTypes as $type) {
                if (!empty($allTypes[$type])) {
                    $allTypes[$type] = 0;
                    $data[] = array($availableCacheTypes[$type], 'Disabled');
                    Mage::app()->getCacheInstance()->cleanType($type);
                }
            }

            if (!empty($data)) {
                Mage::app()->saveUseCache($allTypes);
            }
        } else if (!empty($arguments['refresh'])) {
            $action = 'Refresh';
            foreach ($inputTypes as $type) {
                Mage::app()->getCacheInstance()->cleanType($type);
                Mage::dispatchEvent('adminhtml_cache_refresh_type', array('type' => $type));
                $data[] = array($availableCacheTypes[$type], 'Refreshed');
            }
        }

        if ($action === null) {
            throw new Exception('No action was performed on cache.');
        }
        $systemReport[ucwords($action) . ' Cache'] = array(
            'header' => array('Cache', 'Result'),
            'data' => $data
        );

        return $systemReport;
    }

    /**
     * Turn on/off developer mode
     *
     * @param array $arguments
     * @throws Exception
     */
    protected function _setDeveloperMode(array $arguments = array())
    {
        if (!isset($arguments['dev'])
            || !in_array($arguments['dev'], array(1, 0, true, false, 'true', 'false', 'on', 'off', '1', '0'), true)
        ) {
            throw new Exception('Wrong parameter value. Must be one of these values: 1, 0, true, false, on, off.');
        }
        $enableDevMode = (bool)in_array($arguments['dev'], array(1, true, 'on', 'true', '1'), true);

        $indexFilePath = $this->_getRootPath() . 'index.php';
        if (!is_writable($indexFilePath)) {
            throw new Exception('index.php file is not writable or not exists.');
        }
        $indexFileContents = file_get_contents($indexFilePath);
        $devModeControlCode =
            "\n\n// [<--" . str_repeat('@', 50) . "\n" .
            'Mage::setIsDeveloperMode(MODE_VALUE);' . "\n" .
            'ini_set(\'display_errors\', 1);' . "\n" .
            "// " . str_repeat('@', 50) . "-->]\n\n";
        $controlCodeSearchPattern =
            "[\n\r]+// \[\<\-\-" . str_repeat('@', 50) . "[\n\r]+" .
            '.+' .
            "// " . str_repeat('@', 50) . "\-\-\>\][\n\r]+";
        $mageRunSearchPattern = '(Mage\:\:run\([^)]+\)\s*\;)';

        // If requested to remove (make index file default) dev control code
        if (isset($arguments['default'])) {
            $indexFileContents = preg_replace('~' . $controlCodeSearchPattern . '~s', "\n\n", $indexFileContents);
        }
        // Otherwise set dev control code
        else {
            $devModeControlCode = str_replace('MODE_VALUE', $enableDevMode ? 'true' : 'false', $devModeControlCode);
            // If dev control code already exists, replace it
            if (preg_match('~' . $controlCodeSearchPattern . '~s', $indexFileContents)) {
                $indexFileContents = preg_replace(
                    '~' . $controlCodeSearchPattern . '~s',
                    $devModeControlCode,
                    $indexFileContents
                );
            }
            // Otherwise replace Mage::run() code
            else {
                $indexFileContents = preg_replace(
                    '~' . $mageRunSearchPattern . '~s',
                    $devModeControlCode . "\${1}",
                    $indexFileContents
                );
            }
        }

        $result = (bool)file_put_contents($indexFilePath, $indexFileContents);
        if (isset($arguments['default'])) {
            $result = $result ? 'Reverted to default' : 'Failed';
        } else if ($enableDevMode) {
            $result = $result ? 'Enabled' : 'Failed';
        } else {
            $result = $result ? 'Disabled' : 'Failed';
        }

        $systemReport['Developer Mode'] = array(
            'header' => array('Result'),
            'data'   => array(array($result))
        );
        return $systemReport;
    }

    /**
     * Disable/Enable maintenance mode
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _setMaintenanceMode(array $arguments = array())
    {
        if (!isset($arguments['mm'])
            || !in_array($arguments['mm'], array(1, 0, true, false, 'true', 'false', 'on', 'off', '1', '0'), true)
        ) {
            throw new Exception('Wrong parameter value. Must be one of these values: 1, 0, true, false, on, off.');
        }
        $enableMaintenanceMode = (bool)in_array($arguments['mm'], array(1, true, 'on', 'true', '1'), true);

        if (!is_writable($this->_getRootPath())) {
            throw new Exception('Magento root directory is not writable.');
        }
        $maintenanceFile = $this->_getRootPath() . self::REPORT_MAINTENANCE_MODE_FLAG_FILE_NAME;

        if ($enableMaintenanceMode) {
            $result = (bool)file_put_contents($maintenanceFile, '1');
            $result = $result ? 'Enabled' : 'Failed';
        } else {
            $result = unlink($maintenanceFile);
            $result = $result ? 'Disabled' : 'Failed';
        }

        $systemReport = array();
        $systemReport['Maintenance Mode'] = array(
            'header' => array('Result'),
            'data'   => array(array($result))
        );

        return $systemReport;
    }

    /**
     * Generate shell command to connect to current Magento DB using mysql shell
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateMysqlShellCommandData(array $arguments = array())
    {
        $dbConfig = $this->_readConnection->getConfig();
        $hostName = !empty($dbConfig['unix_socket']) ? $dbConfig['unix_socket']
            : (!empty($dbConfig['host']) ? $dbConfig['host'] : 'localhost');

        $cmd = 'mysql -h' . $hostName . ' -u' . $dbConfig['username'] . ' -p ' . $dbConfig['dbname'];

        $systemReport = array();
        $systemReport['MySQL Shell Command'] = array(
            'header' => array('Command'),
            'data'   => array(array($cmd))
        );

        return $systemReport;
    }

    /**
     * Generate all entity types list
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateAllEntityTypesData(array $arguments = array())
    {
        $data = array();
        $types = Mage::getModel('eav/entity_type')
            ->getResourceCollection()
            ->load();
        $types = !$types ? array() : $types;
        /** @var $type Mage_Eav_Model_Entity_Type */
        foreach ($types as $type) {
            $entityTable = $type->getEntityTable();
            try {
                $entityTable = $this->_getTableName($entityTable);
            } catch (Exception $e) {
                //
            }

            $additionalAttrTable = $type->getAdditionalAttributeTable();
            try {
                $additionalAttrTable = $this->_getTableName($additionalAttrTable);
            } catch (Exception $e) {
                //
            }

            try {
                $data[] = array(
                    $type->getId(),
                    $type->getEntityTypeCode(),
                    $this->_generateModelClassValueByModelFactoryName($type->getEntityModel()),
                    $this->_generateModelClassValueByModelFactoryName($type->getAttributeModel()),
                    $this->_generateModelClassValueByModelFactoryName($type->getIncrementModel()),
                    $entityTable,
                    $additionalAttrTable,
                );
            } catch (Exception $e) {
                $this->_log($e);
            }
        }

        $systemReport = array();
        $systemReport['Entity Types'] = array(
            'header' => array(
                'ID', 'Code', 'Model', 'Attribute Model', 'Increment Model', 'Main Table', 'Additional Attribute Table',
            ),
            'data'   => $data
        );

        return $systemReport;
    }

    /**
     * Generate all EAV attributes list
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateAllEavAttributesData(array $arguments = array())
    {
        $data = $attributes = $systemReport = array();
        $attributes = $this->_getEavAttributes('all');
        foreach ($attributes as $attrId => $attrData) {
            $data[] = array(
                $attrId,
                $attrData['code'] . "\n" .
                '{frontend: ' . $attrData['frontend_type'] . ', backend: ' . $attrData['backend_type'] . '}',
                $attrData['is_user_defined'] ? 'Yes' : 'No',
                $attrData['entity_type_code'] ? $attrData['entity_type_code'] : 'n/a',
                $this->_generateModelClassValueByModelFactoryName($attrData['source_model']),
                $this->_generateModelClassValueByModelFactoryName($attrData['backend_model']),
                $this->_generateModelClassValueByModelFactoryName($attrData['frontend_model']),
            );
        }

        $systemReport['All Eav Attributes'] = array(
            'header' => array(
                'ID', 'Code', 'User Defined', 'Entity Type Code', 'Source Model', 'Backend Model', 'Frontend Model'
            ),
            'data'   => $data
        );

        return $systemReport;
    }

    /**
     * Generate new EAV attributes list
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateNewEavAttributesData(array $arguments = array())
    {
        $systemReport   = $data = array();

        $systemReport['New Eav Attributes'] = array(
            'header' => array('ID', 'Code', 'User Defined', 'Source Model', 'Backend Model', 'Frontend Model'),
            'data'   => $this->_prepareAttributesListAsReportData('new')
        );

        return $systemReport;
    }

    /**
     * Generate user defined EAV attributes list
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateUserDefinedEavAttributesData(array $arguments = array())
    {
        $systemReport = array();

        $systemReport['User Defined Eav Attributes'] = array(
            'header' => array('ID', 'Code', 'Entity Type Code', 'Source Model', 'Backend Model', 'Frontend Model'),
            'data'   => $this->_prepareAttributesListAsReportData('user_defined')
        );

        return $systemReport;
    }

    /**
     * Generate category EAV attributes list
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateCategoryEavAttributesData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Category Eav Attributes'] = array(
            'header' => array('ID', 'Code', 'User Defined', 'Source Model', 'Backend Model', 'Frontend Model'),
            'data'   => $this->_prepareAttributesListAsReportData('category'),
        );

        return $systemReport;
    }

    /**
     * Generate product EAV attributes list
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateProductEavAttributesData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Product Eav Attributes'] = array(
            'header' => array('ID', 'Code', 'User Defined', 'Source Model', 'Backend Model', 'Frontend Model'),
            'data'   => $this->_prepareAttributesListAsReportData('product'),
        );

        return $systemReport;
    }

    /**
     * Generate customer EAV attributes list
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateCustomerEavAttributesData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Customer Eav Attributes'] = array(
            'header' => array('ID', 'Code', 'User Defined', 'Source Model', 'Backend Model', 'Frontend Model'),
            'data'   => $this->_prepareAttributesListAsReportData('customer'),
        );

        return $systemReport;
    }

    /**
     * Generate customer address EAV attributes list
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateCustomerAddressEavAttributesData(array $arguments = array())
    {
        $systemReport = array();
        $systemReport['Customer Address Eav Attributes'] = array(
            'header' => array('ID', 'Code', 'User Defined', 'Source Model', 'Backend Model', 'Frontend Model'),
            'data'   => $this->_prepareAttributesListAsReportData('customer_address'),
        );

        return $systemReport;
    }

    /**
     * Generate customer EAV attributes list
     *
     * @param array $arguments
     *
     * @return array
     * @throws Exception
     */
    protected function _generateRmaItemEavAttributesData(array $arguments = array())
    {
        $systemReport = array();
        if ($this->_magentoEdition != 'EE'
            || ($this->_magentoEdition == 'EE' && version_compare($this->_magentoVersion, '1.11.0.0', '<'))
        ) {
            return $systemReport;
        }

        $systemReport['Rma Item Eav Attributes'] = array(
            'header' => array('ID', 'Code', 'User Defined', 'Source Model', 'Backend Model', 'Frontend Model'),
            'data'   => $this->_prepareAttributesListAsReportData('rma_item'),
        );

        return $systemReport;
    }

    /**
     * Decorate attributes list data and prepare as report format
     *
     * @param string $type
     * @return array
     */
    protected function _prepareAttributesListAsReportData($type)
    {
        $data = $attributes = array();
        $attributes = $this->_getEavAttributes($type);
        foreach ($attributes as $attrId => $attrData) {
            $_data = array(
                $attrId,
                $attrData['code'] . "\n" .
                '{frontend: ' . $attrData['frontend_type'] . ', backend: ' . $attrData['backend_type'] . '}',
            );
            if ($type == 'user_defined') {
                $_data[] = $attrData['entity_type_code'] ? $attrData['entity_type_code'] : 'n/a';
            } else {
                $_data[] = $attrData['is_user_defined'] ? 'Yes' : 'No';
            }
            $_data[] = $this->_generateModelClassValueByModelFactoryName($attrData['source_model']);
            $_data[] = $this->_generateModelClassValueByModelFactoryName($attrData['backend_model']);
            $_data[] = $this->_generateModelClassValueByModelFactoryName($attrData['frontend_model']);
            $data[]  = $_data;
        }

        return $data;
    }

    /**
     * Get eav attributes list by specified attributes type (group)
     * Available types: all, new, user_defined, rma_item, category, product, customer, customer_address
     *
     * Return format:
     * Array (
     *     attr_id => Array (
     *          'id' => attr_id,
     *          'code' => attr_code,
     *          'entity_type_code' => entity_type_code,
     *          'is_user_defined' => is_user_defined,
     *          'frontend_type' => frontend_type,
     *          'backend_type' => backend_type,
     *          'source_model' => source_model,
     *          'backend_model' => backend_model,
     *          'frontend_model' => frontend_model
     *      )
     * )
     *
     * @param string $type
     *
     * @return array
     */
    protected function _getEavAttributes($type = 'all')
    {
        $data = array();
        if ($type == 'rma_item' && ($this->_magentoEdition != 'EE'
            || $this->_magentoEdition == 'EE' && version_compare($this->_magentoVersion, '1.11.0.0', '<'))
        ) {
            return $data;
        }

        if ($type == 'new') {
            $structure = $this->_getDbStructureData('eav_attributes', isset($arguments['f']));

            return array_diff_key($structure['local_data'], $structure['reference_data']);
        }

        /** @var Mage_Eav_Model_Resource_Attribute_Collection $attributes */
        $attributes = Mage::getModel('eav/entity_attribute')
            ->getResourceCollection()
            ->setOrder('attribute_code', Varien_Data_Collection::SORT_ORDER_ASC);

        switch ($type) {
            case 'user_defined':
                $attributes->addFieldToFilter('is_user_defined', 1);
                break;
            case 'category':
            case 'product':
            case 'rma_item':
            case 'customer':
            case 'customer_address':
                $entityCode = $type;
                if ($type == 'product') {
                    $entityCode = Mage_Catalog_Model_Product::ENTITY;
                }
                if ($type == 'category') {
                    $entityCode = Mage_Catalog_Model_Category::ENTITY;
                }
                if ($type == 'rma_item') {
                    $entityCode = Enterprise_Rma_Model_Item::ENTITY;
                }
                /** @var null|Mage_Eav_Model_Entity_Type $entityType */
                $entityType = null;
                try {
                    $entityType = Mage::getSingleton('eav/config')->getEntityType($entityCode);
                } catch (Exception $e) {
                    //
                }
                if ($entityType) {
                    $attributes->addFieldToFilter('entity_type_id', (int)$entityType->getId());
                }
                break;
            case 'all':
            default:
                break;
        }

        $attributes->load();
        $attributes = !$attributes ? array() : $attributes;

        /** @var $attribute Mage_Eav_Model_Entity_Attribute */
        foreach ($attributes as $attribute) {
            /** @var null|Mage_Eav_Model_Entity_Type $entityType */
            $entityType = null;
            try {
                $entityType = $attribute->getEntityType();
            } catch (Exception $e) {
                //
            }

            try {
                $data[$attribute->getId()] = array(
                    'id'                => $attribute->getId(),
                    'code'              => $attribute->getAttributeCode(),
                    'entity_type_code'  => $entityType ? $entityType->getEntityTypeCode() : null,
                    'is_user_defined'   => (bool)$attribute->getIsUserDefined(),
                    'frontend_type'     => $attribute->getFrontendInput(),
                    'backend_type'      => $attribute->getBackendType(),
                    'source_model'      => $attribute->getSourceModel(),
                    'backend_model'     => $attribute->getBackendModel(),
                    'frontend_model'    => $attribute->getFrontendModel(),
                );
            } catch (Exception $e) {
                $this->_log($e);
            }
        }

        return $data;
    }

    /**
     * Generate class name and class file path by specified factory model name
     *
     * @param string $model
     * @return string
     */
    protected function _generateModelClassValueByModelFactoryName($model)
    {
        $model = (string)$model;
        if (empty($model)) {
            return '';
        }
        $className = Mage::getConfig()->getModelClassName($model);
        $classPath = $this->_getClassPath(
            $className,
            $this->_getModuleCodePoolByClassName($className)
        );

        $className = $this->_formatCLIStyle($className, 'yellow', null, array('bold'));
        return $className . "\n" . '    {' . $classPath . '}';
    }

    /**
     * Collect files list in specified directory recursively according to specified nesting level
     *
     * @param string $directory
     * @param int $nestLevel
     * @param int $listMode
     * @param array $openOnlyDirs array of directory paths to browse in only
     * @param string|null $fileMask REGEXP
     * @param bool $resetStaticData
     *
     * @return array
     */
    protected function _getFilesList($directory, $nestLevel = 1, $listMode = self::REPORT_FILE_LIST_FILES,
                                     $openOnlyDirs = array(), $fileMask = null, $resetStaticData = true
    ) {
        if (substr($directory, -1, 1) != DIRECTORY_SEPARATOR) {
            $directory .= DIRECTORY_SEPARATOR;
        }
        $directoryHandler = opendir($directory);
        $data = array();
        static $currentLevel = 0;
        if ($resetStaticData) {
            $currentLevel = 0;
        }

        if ($directoryHandler) {
            while (($entry = readdir($directoryHandler)) !== false) {
                $file = $directory . $entry;
                if (
                    ($listMode == self::REPORT_FILE_LIST_ALL
                        || ($listMode == self::REPORT_FILE_LIST_FILES && is_file($file))
                        || ($listMode == self::REPORT_FILE_LIST_DIRS && is_dir($file))
                    )
                    && (empty($fileMask) || preg_match('~' . $fileMask . '~', $entry))
                    && $entry != '.' && $entry != '..'
                ) {
                    $data[] = $file;
                }

                if ($entry != '.' && $entry != '..' && is_dir($file)) {
                    if ((!empty($openOnlyDirs) && is_array($openOnlyDirs) && in_array($file, $openOnlyDirs))
                        || empty($openOnlyDirs)
                    ) {
                        if ($currentLevel < $nestLevel) {
                            $currentLevel++;
                            $data = array_merge(
                                $data,
                                $this->_getFilesList(
                                    $file, $nestLevel, $listMode, $openOnlyDirs, $fileMask, false
                                )
                            );
                        }
                    }
                }
            }
            $currentLevel--;
            closedir($directoryHandler);
        }

        sort($data);
        return $data;
    }

    /**
     * Retrieve directory size recursively
     *
     * @param string $directory
     *
     * @throws Exception
     *
     * @return int
     */
    protected function _getDirSize($directory)
    {
        $size = 0;
        try {
            /** @var $iterator RecursiveIteratorIterator */
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
            /** @var $file SplFileInfo */
            foreach ($iterator as $file) {
                $size += $file->getSize();
            }
        } catch (Exception $e) {
            $this->_log($e);
        }

        return $size;
    }

    /**
     * Determine if given file is link. It is "Windows OS" compatible.
     *
     * @param string $filename
     *
     * @return bool
     */
    protected function _isLink($filename)
    {
        if (is_link($filename)) {
            return true;
        }
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (strtolower($ext) == 'lnk') {
            return ($this->_readlink($filename) ? true : false);
        }

        return false;
    }

    /**
     * Read link. It is "Windows OS" compatible.
     *
     * @param string $filename
     *
     * @return bool|string
     */
    protected function _readlink($filename)
    {
        if (file_exists($filename)) {
            if (is_link($filename)) {
                return readlink($filename);
            }
            if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
                return false;
            }
            if (!is_readable($filename)) {
                return false;
            }
            // Get file content
            $handle = fopen($filename, "rb");
            $buffer = array();
            while (!feof($handle)) {
                $buffer[] = fread($handle, 1);
            }
            fclose($handle);

            // Test magic value and GUID
            if (count($buffer) < 20) {
                return false;
            }
            if ($buffer[0] != 'L') {
                return false;
            }

            if ((ord($buffer[4]) != 0x01) ||
                (ord($buffer[5]) != 0x14) ||
                (ord($buffer[6]) != 0x02) ||
                (ord($buffer[7]) != 0x00) ||
                (ord($buffer[8]) != 0x00) ||
                (ord($buffer[9]) != 0x00) ||
                (ord($buffer[10]) != 0x00) ||
                (ord($buffer[11]) != 0x00) ||
                (ord($buffer[12]) != 0xC0) ||
                (ord($buffer[13]) != 0x00) ||
                (ord($buffer[14]) != 0x00) ||
                (ord($buffer[15]) != 0x00) ||
                (ord($buffer[16]) != 0x00) ||
                (ord($buffer[17]) != 0x00) ||
                (ord($buffer[18]) != 0x00) ||
                (ord($buffer[19]) != 0x46)
            ) {
                return false;
            }

            $i = 20;
            if (count($buffer) < ($i + 4)) {
                return false;
            }

            $flags = ord($buffer[$i]);
            $flags = $flags | (ord($buffer[++$i]) << 8);
            $flags = $flags | (ord($buffer[++$i]) << 16);
            $flags = $flags | (ord($buffer[++$i]) << 24);

            $hasShellItemIdList = ($flags & 0x00000001) ? true : false;
            $pointsToFileOrDir  = ($flags & 0x00000002) ? true : false;

            if (!$pointsToFileOrDir) {
                return false;
            }

            $a = 0;
            if ($hasShellItemIdList) {
                $i = 76;
                if (count($buffer) < ($i + 2)) {
                    return false;
                }
                $a = ord($buffer[$i]);
                $a = $a | (ord($buffer[++$i]) << 8);

            }

            $i = 78 + 4 + $a;
            if (count($buffer) < ($i + 4)) {
                return false;
            }

            $b = ord($buffer[$i]);
            $b = $b | (ord($buffer[++$i]) << 8);
            $b = $b | (ord($buffer[++$i]) << 16);
            $b = $b | (ord($buffer[++$i]) << 24);

            $i = 78 + $a + $b;
            if (count($buffer) < ($i + 4)) {
                return false;
            }

            $c = ord($buffer[$i]);
            $c = $c | (ord($buffer[++$i]) << 8);
            $c = $c | (ord($buffer[++$i]) << 16);
            $c = $c | (ord($buffer[++$i]) << 24);

            $i = 78 + $a + $b + $c;
            if (count($buffer) < ($i +1)) {
                return false;
            }

            $linkedTarget = "";
            $bufSize = sizeof($buffer);
            for (;$i < $bufSize; ++$i) {
                if (!ord($buffer[$i])) {
                    break;
                }
                $linkedTarget .= $buffer[$i];
            }

            if (empty($linkedTarget)) {
                return false;
            }

            return $linkedTarget;
        }

        return false;
    }

    /**
     * Get file owner
     *
     * @param string $filename
     *
     * @return string
     */
    protected function _getFileOwner($filename)
    {
        if (!function_exists('posix_getpwuid')) {
            return 'unknown';
        }

        $owner     = posix_getpwuid(fileowner($filename));
        $groupinfo = posix_getgrnam(filegroup($filename));
        $groupinfo = $groupinfo ? $groupinfo : filegroup($filename);

        return $owner['name'] . ' / ' . $groupinfo;
    }

    /**
     * Convert integer permissions format into human readable one
     *
     * @param integer $mode
     *
     * @return string
     */
    protected function _parsePermissions($mode)
    {
        /* FIFO pipe */
        if ($mode & 0x1000) {
            $type = 'p';
        }
        /* Character special */
        else if ($mode & 0x2000) {
            $type ='c';
        }
        /* Directory */
        else if ($mode & 0x4000) {
            $type ='d';
        }
        /* Block special */
        else if ($mode & 0x6000) {
            $type ='b';
        }
        /* Regular */
        else if ($mode & 0x8000) {
            $type ='-';
        }
        /* Symbolic Link */
        else if ($mode & 0xA000) {
            $type ='l';
        }
        /* Socket */
        else if ($mode & 0xC000) {
            $type ='s';
        }
        /* Unknown */
        else {
            $type ='u';
        }

        /* Determine permissions */
        $owner['read']      = ($mode & 00400) ? 'r' : '-';
        $owner['write']     = ($mode & 00200) ? 'w' : '-';
        $owner['execute']   = ($mode & 00100) ? 'x' : '-';
        $group['read']      = ($mode & 00040) ? 'r' : '-';
        $group['write']     = ($mode & 00020) ? 'w' : '-';
        $group['execute']   = ($mode & 00010) ? 'x' : '-';
        $world['read']      = ($mode & 00004) ? 'r' : '-';
        $world['write']     = ($mode & 00002) ? 'w' : '-';
        $world['execute']   = ($mode & 00001) ? 'x' : '-';

        /* Adjust for SUID, SGID and sticky bit */
        if ($mode & 0x800) {
            $owner['execute'] = ($owner['execute']=='x') ? 's' : 'S';
        }
        if ($mode & 0x400) {
            $group['execute'] = ($group['execute']=='x') ? 's' : 'S';
        }
        if ($mode & 0x200) {
            $world['execute'] = ($world['execute']=='x') ? 't' : 'T';
        }

        $s = sprintf('%1s', $type);
        $s .= sprintf('%1s%1s%1s', $owner['read'], $owner['write'], $owner['execute']);
        $s .= sprintf('%1s%1s%1s', $group['read'], $group['write'], $group['execute']);
        $s .= sprintf('%1s%1s%1s', $world['read'], $world['write'], $world['execute']);

        return trim($s);
    }

    /**
     * Check if specified filename is correct
     *
     * @param string $filename
     *
     * @return bool
     */
    protected function _checkFileName($filename)
    {
        //("\\", '/', ':', '*', '?', '"', '<', '>', '|', "\t", "\n", "\0", "\x0B")
        $incorrectChars = array(92,47,58,42,63,34,60,62,124,9,10,0,11);
        $trimmedName = trim($filename);
        if (empty($trimmedName) && $trimmedName != '0'){
            return false;
        }
        $chars = count_chars($filename, 1);
        $size = sizeof($incorrectChars);
        for ($i = 0; $i < $size; $i++){
            if (array_key_exists($incorrectChars[$i], $chars)){
                return false;
            }
        }

        return true;
    }

    /**
     * Retrieve previous key of specified array if applicable
     *
     * @param array $array
     *
     * @param $key
     *
     * @return bool|int|string
     */
    protected function _getPreviousKeyFromArray(array $array, $key)
    {
        $prev = false;
        foreach ($array as $k => $v) {
            if ($k == $key) {
                return $prev;
            }
            $prev = $k;
        }

        return $prev;
    }

    /**
     * Determine size of specified file
     * Applicable for all files, also those files what have size > 4 GB at Windows
     *
     * @param $file
     * @link http://www.php.net/manual/en/function.filesize.php#104101
     *
     * @return float
     */
    protected function _getFileSize($file)
    {
        if (class_exists('COM', false) && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            try {
                $filesystem = new COM('Scripting.FileSystemObject');
                $file = $filesystem->GetFile(realpath($file));
                $size = $file->Size();
                if (!ctype_digit($size)) {
                    return null;
                }
            } catch (Exception $e) {
                $size = null;
            }
        } else {
            $size = filesize($file);
        }
        if ($size < 0 || $size === false) {
            return null;
        }

        return $size;
    }

    /**
     * Format specified bits|bytes to human readable string
     *
     * @param int $val
     * @param int $digits how match digits must be used when round result
     * @param string $mode SI'|'IEC': if SI, then division factor will be 1000, other way - 1024
     * @param string $bB 'b'|'B': if b, then result will be in bits, other way in bytes
     *
     * @return string
     */
    protected function _formatBytes($val, $digits = 3, $mode = 'SI', $bB = 'B')
    {
        $iec = array('', 'k', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y');
        $si = array('', 'Ki', 'Mi', 'Gi', 'Ti', 'Pi', 'Ei', 'Zi', 'Yi');
        $nums = 9;
        $mode = strtoupper((string)$mode);
        $mode = $mode != 'SI' && $mode != 'IEC' ? 'SI' : $mode;
        if ($mode == 'SI') {
            $factor  = 1000;
            $symbols = $si;
        }
        else {
            $factor  = 1024;
            $symbols = $iec;
        }
        if ($bB == 'b'){
            $val *= 8;
        }
        else {
            $bB = 'B';
        }
        for ($i=0; $i < $nums - 1 && $val >= $factor; $i++) {
            $val /= $factor;
        }
        $p = strpos($val, '.');
        if ($p !== false && $p > $digits) {
            $val = round($val);
        } else if($p !== false) {
            $val = round($val, $digits - $p);
        }

        return round($val, $digits) . ' ' . $symbols[$i] . $bB;
    }

    /**
     * Parse and validate FTP, RSYNC, FILE, HTTP or HTTPS url and return its components
     * More convenient then parse_url()
     *
     * Potential keys within this array are:
     *     scheme - e.g. http
     *     host
     *     tld
     *     port
     *     user
     *     pass
     *     path
     *     query - after the question mark ?
     *     fragment - after the hashmark #
     *
     * @param  string $url
     *
     * @return array|bool
     */
    protected function _parseUrl($url)
    {
        $url   = (string)$url;
        $parts = $result = array();

        if(preg_match('/\A
                #scheme
                (?:(rsync|ftp|file|https?):\/\/)?
                #userinfo
                (?:
                    ([-0-9_\x41-\x5A\x61-\x7A\xA5\xA8\xAA\xAF\xB2\xB3\xB4\xB8\xBA\xBF-\xFF.,\'@$*^=%:&amp;~+?#"()\[\]]+)@
                )?
                #host or ip
                (?:
                    #ip
                    ((?:(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.){3}(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5]))
                    |
                    #host
                    (
                        (?:[-0-9_\x41-\x5A\x61-\x7A\xA5\xA8\xAA\xAF\xB2\xB3\xB4\xB8\xBA\xBF-\xFF]+\.)+
                        #tld
                        ([\x41-\x5A\x61-\x7A]{2,8})?
                        |
                        [-0-9_\x41-\x5A\x61-\x7A\xA5\xA8\xAA\xAF\xB2\xB3\xB4\xB8\xBA\xBF-\xFF]+
                    )
                )
                #port
                (?:
                    :(0|[1-9][0-9]?[0-9]?[0-9]?|[1-5][0-9][0-9][0-9][0-9]|6[0-4][0-9][0-9][0-9]|65[0-4][0-9][0-9]|655[0-2][0-9]|6553[0-5])
                )?
                #path
                (?:
                    \/([-0-9_\x41-\x5A\x61-\x7A\xA5\xA8\xAA\xAF\xB2\xB3\xB4\xB8\xBA\xBF-\xFF.,\'@$*^=%:;\/~+"()\[\]]+)?
                )?
                #query
                (?:
                    \?([-0-9_\x41-\x5A\x61-\x7A\xA5\xA8\xAA\xAF\xB2\xB3\xB4\xB8\xBA\xBF-\xFF.,\'@$*^=%:&amp;\/~+?"()\[\]]+)+
                )?
                #fragment
                (?:
                    \#([-0-9_\x41-\x5A\x61-\x7A\xA5\xA8\xAA\xAF\xB2\xB3\xB4\xB8\xBA\xBF-\xFF.,\'@$*^=%:&amp;\/~+?#"()\[\]]+)
                )?\Z/x', $url, $parts)
        ){
            $result['url'] = $parts[0];
            if (!empty($parts[1])){
                $result['scheme'] = $parts[1];
            }
            if (!empty($parts[2])){
                $userinfo = explode(':', $parts[2], 2);
                $result['user'] = $userinfo[0];
                if (!empty($userinfo[1])) {
                    $result['pass'] = $userinfo[1];
                }
            }
            if (!empty($parts[3])){
                $result['ip'] = $parts[3];
            }
            if (!empty($parts[4])){
                $result['host'] = $parts[4];
            }
            if (!empty($parts[5])){
                $result['tld'] = $parts[5];
            }
            if (!empty($parts[6])){
                $result['port'] = $parts[6];
            }
            if (!empty($parts[7])){
                $result['path'] = $parts[7];
            }
            if (!empty($parts[8])){
                $result['query'] = $parts[8];
            }
            if (!empty($parts[9])){
                $result['fragment'] = $parts[9];
            }

            return $result;
        }
        return false;
    }

    /**
     * Serialize multidimensional arrays or objects inside post data
     *
     * When passing CURLOPT_POSTFIELDS a url-encoded string in order to
     * use Content-Type: application/x-www-form-urlencoded, you can pass a string directly
     * rather than passing the string in an array
     *
     * The array used to set the POST fields must only contain scalar values.
     * Multidimensional arrays or objects lacking a __toString implementation will cause Curl to error.
     *
     * @param array|string $data
     * @return array|string
     */
    protected function _preparePostData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $info) {
                if (is_array($info)) {
                    $data[$key] = serialize($info);
                }
            }
        }
        return $data;
    }

    /**
     * Prevent termination if requested php information
     */
    protected function _validate()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            throw new Exception('This script cannot be run from Browser. This is the shell script.');
        }
    }

    /**
     * Get Magento Root path (with directory separator in the end)
     *
     * @return string
     */
    protected function _getRootPath()
    {
        if (is_null($this->_rootPath)) {
            if (isset($_SERVER['argv'][1]) && is_dir($_SERVER['argv'][1])) {
                $this->_rootPath = $_SERVER['argv'][1];
            } else {
                $this->_rootPath = getcwd() . DIRECTORY_SEPARATOR;
            }
            if (substr($this->_rootPath, -1, 1) != DIRECTORY_SEPARATOR) {
                $this->_rootPath .= DIRECTORY_SEPARATOR;
            }
            if (!is_file($this->_rootPath . 'app' . DIRECTORY_SEPARATOR . 'Mage.php')) {
                exit(
                    'Either you specified a wrong Magento directory or you\'re running the tool from a non-Magento directory.' . "\n" .
                    'Put sysreport tool script into Magento root directory or specify this directory following next example: ' .
                    'php sysreport.php [MAGENTO_ROOT_PATH] [OPTIONS]' . "\n"
                );
            }
        }
        return $this->_rootPath;
    }

    /**
     * Retrieve current working path.
     * Used for files generated by sysreport tool
     *
     * @return string
     */
    protected function _getWorkingPath()
    {
        return dirname(__FILE__) . DS;
    }

    /**
     * Parse .htaccess file and apply php settings to shell script
     *
     */
    protected function _applyPhpVariables()
    {
        $htaccess = $this->_getRootPath() . '.htaccess';
        if (is_file($htaccess)) {
            // parse htaccess file
            $data = file_get_contents($htaccess);
            $matches = array();
            preg_match_all('#^\s+?php_value\s+([a-z_]+)\s+(.+)$#siUm', $data, $matches, PREG_SET_ORDER);
            if ($matches) {
                foreach ($matches as $match) {
                    @ini_set($match[1], str_replace("\r", '', $match[2]));
                }
            }
            preg_match_all('#^\s+?php_flag\s+([a-z_]+)\s+(.+)$#siUm', $data, $matches, PREG_SET_ORDER);
            if ($matches) {
                foreach ($matches as $match) {
                    @ini_set($match[1], str_replace("\r", '', $match[2]));
                }
            }
        }
    }

    /**
     * Parse input arguments
     *
     * @return Mage_Shell_Abstract
     */
    protected function _parseArgs()
    {
        $current = null;
        if (!isset($_SERVER['argv'])) {
            return $this;
        }
        foreach ($_SERVER['argv'] as $arg) {
            $match = array();
            if (preg_match('#^--([\w\d:_-]{1,})$#', $arg, $match) || preg_match('#^-([\w\d:_]{1,})$#', $arg, $match)) {
                $current = $match[1];
                $this->_args[$current] = true;
            } else {
                if ($current) {
                    $this->_args[$current] = $arg;
                } else if (preg_match('#^([\w\d_]{1,})$#', $arg, $match)) {
                    $this->_args[$match[1]] = true;
                }
            }
        }

        return $this;
    }

    /**
     * Check is show usage help
     *
     */
    protected function _showHelp()
    {
        if (empty($this->_args) || isset($this->_args['h']) || isset($this->_args['help'])) {
            die($this->usageHelp());
        }
    }

    /**
     * Log exception or regular message into self::REPORT_LOG_FILE file or/and output it into STDOUT
     *
     * @param null|Exception $exception
     * @param null|mixed $message
     */
    protected function _log($exception = null, $message = null)
    {
        if (!$this->_properties['do_not_log']) {
            if ($exception instanceof Exception) {
                Mage::log($exception->__toString(), Zend_Log::ERR, self::REPORT_LOG_FILE, true);
            } else {
                Mage::log($message, null, self::REPORT_LOG_FILE, true);
            }
        }
        if (!$this->_properties['is_silent_mode']) {
            if ($exception instanceof Exception) {
                $message = $exception->getMessage() . ' in ' . $exception->getFile() . ', line ' . $exception->getLine();
                if (!empty($message)) {
                    $parts = preg_split('~[\n\r]+~', $message);
                    $_spacesLength = 0;
                    foreach ($parts as $part) {
                        $_partLength = strlen($part);
                        if ($_partLength > $_spacesLength) {
                            $_spacesLength = $_partLength;
                        }
                    }
                    echo "\n". $this->_formatCLIStyle(
                        str_repeat(' ', $_spacesLength + 4), 'white', 'red', array('bold')
                    ) . "\n";
                    foreach ($parts as $part) {
                        $spacesAfter = str_repeat(' ', $_spacesLength - strlen($part) + 2);
                        echo $this->_formatCLIStyle('  ' . $part . $spacesAfter, 'white', 'red', array('bold')) . "\n";
                    }
                    echo $this->_formatCLIStyle(
                        str_repeat(' ', $_spacesLength + 4), 'white', 'red', array('bold')
                    ) . "\n";
                }
            } else {
                if (!is_string($message) && !is_numeric($message)) {
                    $message = var_export($message, true);
                }
                echo "\n" . $this->_formatCLIStyle($message, 'green', null, array('bold'));
            }
        }
    }

    /**
     * Add bash/CLI color and text style codes for specified text
     *
     * @param string $text
     * @param string $color
     * @param string $bgColor
     * @param array $textStyles
     *
     * @return string
     */
    protected function _formatCLIStyle($text, $color = null, $bgColor = null, $textStyles = array())
    {
        if (empty($text)) {
            return $text;
        }
        $styleCodes = array();
        $colors = array(
            'black'     => 30,
            'red'       => 31,
            'green'     => 32,
            'yellow'    => 33,
            'blue'      => 34,
            'purple'    => 35,
            'cyan'      => 36,
            'white'     => 37,
        );
        $bgColors = array(
            'black'     => 40,
            'red'       => 41,
            'green'     => 42,
            'yellow'    => 43,
            'blue'      => 44,
            'purple'    => 45,
            'cyan'      => 46,
            'white'     => 47,
        );
        $styles = array(
            'regular'   => 0,
            'bold'      => 1,
            'underline' => 4
        );

        if (!empty($color) && !empty($colors[$color])) {
            $styleCodes[] = $colors[$color];
        }
        if (!empty($bgColor) && !empty($bgColors[$bgColor])) {
            $styleCodes[] = $bgColors[$bgColor];
        }
        $_styles = array_intersect(array_keys($styles), $textStyles);
        foreach ($_styles as $style) {
            $styleCodes[] = $styles[$style];
        }

        if (empty($styleCodes)) {
            return $text;
        }

        return sprintf("\033[0;%sm%s\033[0m", implode(';', $styleCodes), $text);
    }

    /**
     * Check if progress information can be output
     *
     * @return bool
     */
    protected function _canOutputProgress()
    {
        return !$this->_properties['is_silent_mode'] && !$this->_properties['do_not_output_progress'];
    }
}

// If php information was requested, then perform info collection regardless standard sysreport tool behavior
if (isset($_POST['phpinfo']) && $_POST['phpinfo'] == 1) {
    Mage_Shell_SystemReport::collectPHPInfo();
} else {
    $shell = new Mage_Shell_SystemReport();
    $shell->run();
}
