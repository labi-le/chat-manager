<?php

declare(strict_types=1);

namespace Manager\Controller;

use Jawira\EmojiCatalog\Emoji;
use Manager\Models\ChatsQuery;
use Manager\Models\SimpleVKExtend;

class ActionController extends ChatController
{
    /**
     * обработка action (message\\action)
     * @param array $data
     * @return void
     */
    public static function handler(array $data): void
    {
        $type = $data['type'];
        $member_id = $data['member_id'];
        if (method_exists(self::class, $type)) self::$type($member_id);
    }

    /**
     * Пользователь присоединился к беседе по инвайт-ссылке
     * @param $id
     * @return void
     */
    private static function chat_invite_user_by_link(int $id)
    {
        self::chat_invite_user($id);
    }

    /**
     * Пользователь присоединился к беседе
     * @param $id
     */
    private static function chat_invite_user(int $id)
    {
        /**
         * Если добавили бота
         * Приветственное сообщение + предложение выдать админку
         */
        if ($id === -SimpleVKExtend::getVars('group_id')) {
            self::$vk
                ->msg("Привет! Я опенсорс чат менеджер" . Emoji::GRINNING_FACE . "\nБуду рад служить тебе ~мой повелитель|" . SimpleVKExtend::getVars('user_id') . "~")
                ->addImg('https://sun6-22.userapi.com/impg/L39hLV6_QTrYGYq5mSJf1BsVH335PTrZUC4KRw/x8_vbSEE0No.jpg?size=604x601&quality=96&proxy=1&sign=bc1a5008eee91ef7e14e685a4f9460e7&type=album')
                ->send();

            sleep(1);

            $buttons[] = self::$vk->buttonText('Я выдал админку', 'green', ['chat' => 'registration']);
            $buttons[] = self::$vk->buttonOpenLink('https://vk.com/@labile.paranoid-kak-dobavit-bota-v-besedu-i-dat-emu-prava-administratora', 'А как блин?');
            self::$vk
                ->msg("🛠 Для начала наших прекрасных отношений выдай мне права администратора")
                ->addImg('https://sun9-66.userapi.com/impg/5lLDD_qo40mfj7h--VbNcns8TnX7ov14Mkc0ww/xZtQcjToEvE.jpg?size=600x400&quality=96&proxy=1&sign=1c61751b37e889ffc011454632d19bd5&type=album')
//                ->addImg(Utils::showCat())
                ->kbd([$buttons], true)
                ->send();

        } elseif (self::$db->statusSettings(ChatsQuery::ACTION . ChatsQuery::WELCOME_MESSAGE_TEXT . ChatsQuery::ACTION) === ChatsQuery::SHOW_ACTION) {
            $welcome_msg = self::$db->showWelcomeMessage();
            if (!is_bool($welcome_msg)) self::$vk->msg($welcome_msg)->send();
        }

    }

    /**
     * Пользователь покинул беседу, либо был исключён кикнули
     * @param $id
     * @return void
     */
    private static function chat_kick_user(int $id)
    {
        if (self::$db->statusSettings(ChatsQuery::ACTION . ChatsQuery::EXIT_MESSAGE_TEXT . ChatsQuery::ACTION) === ChatsQuery::SHOW_ACTION) {
            $welcome_msg = self::$db->showExitMessage();
            if (!is_bool($welcome_msg)) self::$vk->msg($welcome_msg)->send();
        }
        self::$vk->msg("~!fn|$id~ пока-пока!")->send();
    }

    /**
     * Обновлена аватарка
     * @param $id
     */
    private static function chat_photo_update(int $id)
    {
    }

    /**
     * Удалена аватарка
     * @param $id
     */
    private static function chat_photo_remove(int $id)
    {
    }

    /**
     * Закреплено сообщение
     * @param $id
     */
    private static function chat_pin_message(int $id)
    {
    }

    /**
     * Откреплено сообщение
     * @param $id
     */
    private static function chat_unpin_message(int $id)
    {
    }

    /**
     * Сделан скриншот
     * @param $id
     */
    private static function chat_screenshot(int $id)
    {
    }
}