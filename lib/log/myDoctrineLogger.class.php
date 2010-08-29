<?php
/**
 *
 * @package    myDoctrineLoggerPlugin
 * @subpackage log
 * @author     Svel.Sontz <svel.sontz@gmail.com>
 * @version    0.1
 */
class myDoctrineLogger
{

    protected
        $dispatcher = null,
        $options = array();


    /**
     * Конструктор
     */
    public function __construct(sfEventDispatcher $dispatcher, $options = array())
    {
        $this->initialize($dispatcher, $options);

        if (!isset($options['auto_shutdown']) || $options['auto_shutdown']) {
            register_shutdown_function(array($this, 'shutdown'));
        }
    }


    /**
     * Инициализация логгера
     */
    public function initialize(sfEventDispatcher $dispatcher, array $options = array())
    {
        $this->dispatcher = $dispatcher;
        $this->setOptions($options);

        $this->dispatcher->connect('app.activity', array($this, 'listenToLogEvent'));
    }


    /**
     *
     */
    public function log($subject, array $parameters = array())
    {
        $this->doLog($subject, $parameters);
    }


    /**
     *
     */
    protected function doLog($subject, array $parameters = array())
    {
        $log = new myDoctrineLoggerEvent();

        $log->setState($parameters['state']);
        $log->setComponent($parameters['component']);
        $log->setLabel($parameters['name']);
        $log->setResult($parameters['description']);
        $log->setContext('some');

        // TODO убрать хардкод на метод получения идентификатора пользователя
        if (sfContext::hasInstance()) {
            $context = sfContext::getInstance();
            $userId = $context->getUser()->getUserRecord()->getId();
            $log->setUserId($userId);
        }


        $log->save();
    }


    /**
     * Установить опции
     */
    protected function setOptions(array $options = array(), $reset = false)
    {
        if ($reset) {
            $this->options = array();
        }

        foreach ($options as $name => $value) {
            $this->setOption($name, $value);
        }

        return $this;
    }


    /**
     * Установить параметр
     */
    protected function setOption($name, $value)
    {
        $this->options[(string) $name] = $value;

        return $this;
    }


    /**
     * Получить параметр
     */
    protected function getOption($name)
    {
        if (array_key_exists((string) $name, $this->options)) {
            return $this->options[(string) $name];
        }

        return false;
    }


    /**
     * Проверить, существует ли параметр
     */
    protected function hasOption($name)
    {
        return array_key_exists((string) $name, $this->options);
    }


    /**
     * Действия при выключении
     */
    public function shutdown()
    {
    }


    /**
     * Обрабатывает событие из dispatcher
     */
    public function listenToLogEvent(myLogEvent $event)
    {
        $this->log($event->getSubject(), $event->getParameters());
    }

}
