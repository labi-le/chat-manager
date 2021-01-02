<?php

namespace labile\bot;

trait Commands
{
    public function list(): array
    {
        return [

            [
                'text' => ['[|котика', '[|котиков', '[|кот'],
                'method' => ['_cat']
            ],

            [
                'text' => ['кончить', 'кон4ить'],
                'method' => ['_kon4']
            ],

            [
                'text' => ['блин', 'капец', 'блять', 'пиздец', 'ебать', 'елки иголки', 'екарный бабай'],
                'method' => ['_blin']
            ],

            [
                'text' => ['|фубар'],
                'method' => ['_foobar']
            ],

            [
                'text' => ['hi'],
                'method' => ['_hiMessage']
            ],

        ];
    }

    protected function _hiMessage()
    {
        $this->msg('привет ~кожанный~')->send();
    }

    protected function pr()
    {
        $this->msg(print_r($this->request('users.get', ['user_ids' => $this->getVars('user_id'), 'fields' => 'photo_id' ]), true))->send();
    }

    /*
     * Котиков int
     * return котики
     */
    protected function _cat()
    {
        $count = intval(Utils::textWithoutPrefix($this->getVars()['text_lower']));

        if ($count > 10 or $count <= 0) {
            $this->msg("Отинь многа котикаф либо их ваще нет!!!")->send();
        } else {

            $cat = [];
            $smile = str_repeat('🐈', $count);

            for ($i = 0; $i < $count; $i++) {
                $cat[] = json_decode(file_get_contents('https://aws.random.cat/meow'));
            }

            $this->msg($smile)->addImg($cat)->send();
        }
    }

    protected function _keyboard()
    {
        $kb[] = $this->buttonText('1', 'white', null);
        $kb[] = $this->buttonText('2', 'red', null);
        $kb[] = $this->buttonText('3', 'blue', null);

        $this->msg('you popal to gay pride')->kbd([$kb], true)->send();
    }

    protected function вагина()
    {
        $array_vagina = [
            'Сидел я в МДК', 'листал картинки про кота~', 'И был доволен всем',
            'Но вдруг стряслась беда', 'Опухли два яйца', 'Мне нужно трахаца',
            'Но у меня прыщи', 'Как классно, что есть ты!', 'Ведь ты! Ты лежала в магазине',
            'Между членом и вагиной', 'И меня к тебе манило', 'Твоё тело из резины',
            'Полные глаза любви', 'И широко раскрытый рот', 'Девушка моей мечты',
            'Плачу лишь раз - всю жизнь дает!', 'В ПИЗДУ И В РОТ!', 'Плачу лишь раз - всю жизнь дает!',
            'Йе - йе - йе...'
        ];

        foreach($array_vagina as $word){
            $this->request('messages.setActivity', ['peer_id' => $this->getVars()['id'], 'type' => 'typing']);
            sleep(2);
            $this->msg($word)->send();
        }
    }

    protected function _say()
    {
        $word = Utils::textWithoutPrefix($this->getVars()['text_lower']); //получить все подстроки кроме первой
        $this->msg($word)->send();
    }

    //todo сделать викторину

    protected function heyo()
    {
        $this->msg('Heyooo')->send();
    }

    protected function _kon4()
    {
        function kon4($direct_url): string
        {
            return 'http://www.lunach.ru/?cum=&url=' . urlencode($direct_url) . '&tpl=vk';
        }

        $photos = $this->getVars()['attachments']['photo'] ?? false;

        if (!$photos) $this->msg('а где фотка???')->send(); else {
            $img = [];
            foreach ($photos as $photo) {
                $img[] = kon4($photo['url']);
            }

            $this->msg()->img($img)->send();
        }


    }

    protected function _blin()
    {
        $img =
            [
                'https://sun9-9.userapi.com/impg/OXEYAB9A45etB9ZWDa-pd-g6tEvjSosgu6JHyQ/pLC71Zu9sUU.jpg?size=1080x1080&quality=96&proxy=1&sign=91dd401c3d043cf036f8587cbc2d5c89&type=album',
                'https://sun9-68.userapi.com/impg/TvfweW24n8ffknPNPcWsuQqiuffC8VTJFBMrJw/3EwRxpnZe1o.jpg?size=1280x1166&quality=96&proxy=1&sign=e22852dfdf69127ae922d637e841fc38&type=album',
                'https://sun9-7.userapi.com/impg/kccsB2njnNlCOoTDTLpoQKXXQ86sJKUwNnwE-Q/3VGkoOt451Y.jpg?size=300x168&quality=96&proxy=1&sign=fdc0e0e717db356fd5673d0790ced491&type=album',
                'https://sun9-30.userapi.com/impg/ZhvR6WdVHVrKJTEjlNdZ0EgNBDrsSbJEkPX4XA/BtUz52CKjLk.jpg?size=750x469&quality=96&proxy=1&sign=a74aa824c6d37d7994ac3644d043e800&type=album',
                'https://sun9-11.userapi.com/impg/LSMF9Pss1FuIFltdQF-4EIaJKDDUG1f7LV6zDQ/CavZ0LDdCVw.jpg?size=544x545&quality=96&proxy=1&sign=fd2290f11bcad84663dcd5e757956e36&type=album',
                'https://sun9-57.userapi.com/impg/NFOceDXdCAVnRyZAbx4vFpVZGNYq6iLf25xRPw/dCN7IZdW2zA.jpg?size=750x418&quality=96&proxy=1&sign=3869cf76472d2f79f7f1dffbf4b8380a&type=album',
                'https://sun6-21.userapi.com/impg/T9ncP03FusPMZtXIAGgbm0DKzmFW2qbZAWRfmQ/f-t_JK35SNw.jpg?size=1080x861&quality=96&proxy=1&sign=c5eed31e85902f0275b12742b74535b0&type=album',
                'https://sun9-39.userapi.com/impg/pFLF-mzb15bxuXsJtNiZnfFnvYx3HXe2PFFB9w/F2kHjpmxPQU.jpg?size=824x547&quality=96&proxy=1&sign=d6859209b691c1dae7f35c89c77a5f88&type=album',
                'https://sun9-30.userapi.com/impg/GfgHgHxMFu03kI5T6iT23xPTDjeBiGh2O9f7ZA/hFuB8_7QNho.jpg?size=331x305&quality=96&proxy=1&sign=7ccfe77c023dda8a5dcc6eea4be97428&type=album',
                'https://sun9-27.userapi.com/impg/jnrPJoZdRNhIFfe-DyzufdwfIQbJY7ebcEnmbA/iFkW76zMR3w.jpg?size=300x300&quality=96&proxy=1&sign=c2fef313a24d3c6b80ddd3116999975e&type=album',
                'https://sun9-16.userapi.com/impg/F3RCcs_qHrAW2Co9bO3ulp7vD0IS06o2fhWO5w/kmnFf1VBq9c.jpg?size=730x417&quality=96&proxy=1&sign=ae0e7bb6bbf550d5bf02886ae02eade8&type=album',
                'https://sun9-36.userapi.com/impg/y7ulsFDaL2uw7eUlH-2zfkTEx3TjChr7HFVuEQ/Nj6MzkEVyDA.jpg?size=1080x895&quality=96&proxy=1&sign=78e332976645511a1e6cfd6ed7fe7b81&type=album',
                'https://sun9-59.userapi.com/impg/mra0So896rUeLNf5kYvB6iL61W-vIhn70G97yw/Qr_9Ssjpxik.jpg?size=700x400&quality=96&proxy=1&sign=d6eb8cabbd44c9d8e44ea57be7467c2e&type=album',
                'https://sun9-71.userapi.com/impg/uB0vq32E_JZdiGF7hC7D0kJUWcjeDu-PJ_6Jjw/QSKcgkyPpOo.jpg?size=720x486&quality=96&proxy=1&sign=5f0258c43a810c692b605ceef03de272&type=album',
                'https://sun9-7.userapi.com/impg/54Hm68h5zhDne_4VFo5G-Dew40tglJLvZiwZJw/SnsQ1CuVmYM.jpg?size=1080x776&quality=96&proxy=1&sign=b55dac04af4125232231c3c1e2e0ec2f&type=album',
                'https://sun9-15.userapi.com/impg/W-SU4O2rj34AjSVTaYKWDB2uRCCbYm4gnPplkw/sIm2IjaUMi4.jpg?size=388x375&quality=96&proxy=1&sign=8a9fcab7a42f6e6c88ab22863b7e72c7&type=album',
                'https://sun9-48.userapi.com/impg/oq7n1m04LetAZZDgNX4xTmW9SPvZw1Y6PqPuFA/zY3mD94A1HE.jpg?size=720x456&quality=96&proxy=1&sign=574ef396acf77df78de5b7003a8d1ae6&type=album'
            ];

        $blin = array_rand(array_flip($img));

        $this->msg()
            ->img($blin)
            ->forward()
            ->send();
    }
}
