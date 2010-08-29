<?php
/**
 * Конфигурация
 *
 * @package    myDoctrineLoggerPlugin
 * @subpackage config
 * @author     Svel.Sontz <svel.sontz@gmail.com>
 * @version    0.1
 */
class myDoctrineLoggerPluginConfiguration extends sfPluginConfiguration
{
    protected $myDoctrineLogger = null;

    /**
     * @see sfPluginConfiguration
     */
    public function initialize()
    {
        parent::initialize();

        $this->myDoctrineLogger = new myDoctrineLogger($this->dispatcher, array());
    }

}
