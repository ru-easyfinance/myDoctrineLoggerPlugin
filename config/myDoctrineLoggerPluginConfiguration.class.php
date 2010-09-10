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

        if (!$this->dispatcher->hasListeners('app.activity')) {
            $this->dispatcher->connect('app.activity', array($this->myDoctrineLogger, 'listenToLogEvent'));
        }
    }

}
