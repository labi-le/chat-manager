<?php

namespace labile\bot;

trait Controller
{

    /**
     * Вызов типа события и передача данных
     * @param string $type
     * @param array $vars
     * @param $vk
     * @return void
     */
    protected function handler(string $type, array $vars, $vk): void
    {
        if (method_exists($this, $type)) $this->$type($vars);
    }

    /**
     * обработка action (message\\action)
     * @param array $action
     * @return void
     */
    protected function handleAction(array $action): void
    {
        $type = $action['type'];
        $member_id = $action['member_id'];

        if (method_exists($this, $type)) $this->$type($member_id);
    }

    /**
     * Поиск и выполнение команд (если нашел)
     * @param string $originalText
     * @return void
     */
    protected function commandHandler(string $originalText): void
    {
        if (method_exists($this, 'list')) {
            $list = $this->list();

            foreach ($list as $cmd) {
                if (!is_array($cmd['text'])) {
                    if (Utils::formatText($cmd['text'], $originalText)) $this->method_execute($cmd['method']);
                    break;
                }

                if (is_array($cmd['text'])) {
                    foreach ($cmd['text'] as $textFromArray) {
                        if (Utils::formatText($textFromArray, $originalText)) $this->method_execute($cmd['method']);
                        break;

                    }
                }
            }
        }
    }

    /**
     * Обработчик нажатий по клавиатуре
     * @param array $payload
     * @return void
     */
    protected function payloadHandler(array $payload): void
    {

        //todo написать обработчик кнопок типа payload и калбек кнопок

//        if (method_exists($this, 'keyboard')) {
//            $map = $this->keyboard();
//        }
    }

    /**
     * Выполнить метод\методы
     * @param array|string $methods
     * @return void
     */
    private function method_execute(array|string $methods): void
    {
        if (is_array($methods)) {
            foreach ($methods as $method) $this->$method();
        } else $this->$methods();
    }
}