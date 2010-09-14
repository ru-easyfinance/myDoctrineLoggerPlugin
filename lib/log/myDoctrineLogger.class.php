<?php
/**
 *
 * @package    myDoctrineLoggerPlugin
 * @subpackage log
 * @author     Svel.Sontz <svel.sontz@gmail.com>
 * @version    0.1
 */
class myDoctrineLogger extends sfLogger
{

    protected
        $dispatcher = null,
        $options = array();


    /**
     * Инициализация логгера
     */
    public function initialize(sfEventDispatcher $dispatcher, $options = array())
    {
        $this->dispatcher = $dispatcher;
        $this->options = $options;
    }


    /**
     * Заглушка
     */
    protected function doLog($message, $priority)
    {}


    /**
     * Обрабатывает событие из dispatcher
     */
    public function listenToLogEvent(sfEvent $event)
    {
        $subject = $event->getSubject();
        $parameters = $event->getParameters();

        try {
            $record = new myDoctrineLoggerEvent();
        } catch (Exception $e) {
            new sfDatabaseManager(ProjectConfiguration::getActive());
            $record = new myDoctrineLoggerEvent();
        }

        // Статус: fail, info, notice, warning
        if (isset($parameters['state'])) {
            $definition = Doctrine_Core::getTable('myDoctrineLoggerEvent')
                        ->getColumnDefinition('state');

            $states = $definition['values'];
            if (in_array($parameters['state'], $states)) {
                $record->setState($parameters['state']);
            }
        }

        // Идентификатор связанного объекта, если есть
        if (isset($parameters['object'])) {
            $record->setModelId((int) $parameters['object']);
        }

        // Идентификатор пользователя - инициатора события, если есть
        if (isset($parameters['user'])) {
            if (is_object($parameters['user']) && ($parameters['user'] instanceof Doctrine_Record)) {
                $parameters['user'] = $parameters['user']->getId();
            }
            $record->setUserId((int) $parameters['user']);
        }

        // Компонент приложения
        $record->setComponent(isset($parameters['component']) ? (string) $parameters['component'] : 'UNKNOWN');
        // Название события
        if (!isset($parameters['name'])) {
            throw new sfException('Необходимо назвать событие: заполнить параметр ["name"]');
        }
        $record->setLabel((string) $parameters['name']);

        // расшифровка результата
        if (!isset($parameters['description'])) {
            throw new sfException('Необходимо назвать событие: заполнить параметр ["description"]');
        }
        $record->setResult((string) $parameters['description']);

        // Данные события
        if(isset($parameters['env'])) {
            $record->setContext((string) $parameters['env']);
        }

        $record->save();
    }

}
