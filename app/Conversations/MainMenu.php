<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Facades\Log;

class MainMenu extends Conversation
{
    //Функция для выбора кафедры
    public function DepartmentsMenuView(){
        $idDepartament = "";
         /*
                    Получить информацию о руководстве - TEXT, ожидается array
                    SELECT id, name 
                    FROM departments
                    WHERE 1
                */

                /*
                    Создаем массив кнопок с названием кафедр
                    id - уникальный индетефикатор кафедры
                    name - назание кафедры
                */

                /*
                    $departaments_array = array();
                    foreach($query as $key =>$value){
                                array_push($departaments_array, Button::create($value)->value($key));
                            }
                */
            
                $departaments_array = array();
                $query = array(
                    '1' => "кафедра 1",
                    '2' => "кафедра 2",
                    '3' => "кафедра 3",
                    '4' => "кафедра 4",
                );//заглушка для создания кнопок кафдры

                foreach($query as $key =>$value){
                    array_push($departaments_array, Button::create($value)->value($key));
                }
                array_push($departaments_array, Button::create('Назад')->value('back'));
                $question = Question::create("Кафедры")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons($departaments_array);
            

            return $this->ask($question, function (Answer $answer) {
                if($answer->getValue()==='back'){$this->mainMenuView();}
                elseif(!empty($answer)){
                    $idDepartament = $answer->getValue();
                    Log::info($idDepartament);
                    $this->DepartmentMenuView($idDepartament);
                }
                else{
                    $this->say('Неизвестная команда введите /start чтобы вернуться в меню');
                }
            });
    }

    public function DepartmentMenuView($idDepartament){

        $department_query_array = array(
            'id' => 1,
            'name' => "kafedra takaya",
            'about' => "about kafedra",
            'history' => "history kafedra",
    );

    $departament_buttons_array = array();

    foreach($department_query_array as $key =>$value){
        if($key != 'id' and !empty($value) ){ //если это не id - его не надо отображать
                                             // и значение не пустое
        array_push($departament_buttons_array, Button::create($value)->value($key));
        }
    }
    array_push($departament_buttons_array, Button::create('Назад')->value('back')); //добавляем кнопку назад
    $question = Question::create("Кафедры")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons($departament_buttons_array);
            $cloneIdDepartament = $idDepartament;
            return $this->ask($question, function (Answer $answer) use ($cloneIdDepartament)  {
                if ($answer->isInteractiveMessageReply()) {
                    if($answer->getValue()==='back'){$this->DepartmentsMenuView();}
                    elseif (!empty($answer)){
                    $tableName = $answer->getValue(); //название колонки выбранной кафедры
                    $this->DepartmentView($cloneIdDepartament, $tableName );
                    }
                    else {
                        $this->say('Неизвестная команда введите /start чтобы вернуться в меню');
                    }
                }
                });

    

        //$this->say($idDepartament);
    }



    public function DepartmentView($idDepartament, $tableName){
        //Фукнция отображения кафедры
        $idDepartamentInt = (int)$idDepartament; //конвертируем полученный id в инт
        
        /*
            SELECT . $tableName . 
            FROM departaments
            WHERE id = . $idDepartamentInt 
        */

        $this->say('id: ' . $idDepartamentInt);
        $this->say('Table Name:' . $tableName);

        $result = "Сюда нужно поместить информацию";

        $question_main = Question::create($result)
        ->fallback('Unable to ask question')
        ->callbackId('ask_reason')
        ->addButtons([ Button::create('Назад')->value('back'),]);  

        
        return $this->ask($question_main, function (Answer $answer) use ($idDepartament){
            if ($answer->getValue() === 'back') { $this->DepartmentMenuView($idDepartament); }
            else{ $this->say('Неизвестная команда введите /start чтобы вернуться в меню');}

        });

        //DepartmentMenuView($idDepartament)


    }


    public function backToMenufunc(){
    $question_main = Question::create("Нажмите чтобы вернуться")
        ->fallback('Unable to ask question')
        ->callbackId('ask_reason')
        ->addButtons([ Button::create('Назад')->value('back'),]);  

        
        return $this->ask($question_main, function (Answer $answer) {
            if ($answer->getValue() === 'back') { $this->mainMenuView(); }
            else{ $this->say('Неизвестная команда введите /start чтобы вернуться в меню');}

        });

        

    }


    public function mainMenuView(){

        $question_main = Question::create("Главное меню")
        ->fallback('Unable to ask question')
        ->callbackId('ask_reason')
        ->addButtons([
            Button::create('О физ-техе')->value('about'),
            Button::create('Руководство')->value('leadership'),
            Button::create('Кафедры')->value('departments'),
            Button::create('Направления подготовки')->value('directions'),
            Button::create('Научная деятельность')->value('scienсу-activity'),
            Button::create('Контакты')->value('contact'),
            Button::create('Назад')->value('back'),
        ]);


    return $this->ask($question_main, function (Answer $answer) {
        if ($answer->isInteractiveMessageReply()) {
            if ($answer->getValue() === 'about') {
                
                /* 
                    Получить информацию о физ-техе - TEXT
                    SELECT aboutPhys 
                    FROM StaticData
                    WHERE 1
                
                */

                $this->say('О физтехе');
                $this->backToMenufunc();
                
            } 
            elseif ($answer->getValue() === 'leadership') {
                //Если выбрали руководство
                /* 
                    Получить информацию о руководстве - TEXT, ожидается array
                    SELECT leadership 
                    FROM StaticData
                    WHERE 1

                */

                /*
                    foreach($query as $key){
                        $attachment = new Image($key['image']); // фото руководителя
                        $name = $key['name']; // фио руководителя
                        $name = $key['name'] . $key['secondname'] . $key['patronymic'];
                        //если в таблице хранится раздельно
                        $position = $key['position']; // должность
                        $name_and_pos = 'ФИО: ' . $name . '\nДолжность' .  $position;
                        $message = OutgoingMessage::create( $name_and_pos )
                        ->withAttachment($attachment);
                         $bot->reply($message);
                    }
                */
                $this->say('Функция руководтсво');
                $this->backToMenufunc();
                
            }

            
            elseif ($answer->getValue() === 'departments') {
                //Если выбрали кафедры
                $this->DepartmentsMenuView();

            }

            elseif ($answer->getValue() === 'directions') {
                //Если выбрали напрвление подготовки
                $this->say('Направление подготовки');
                $this->backToMenufunc();

            }

            elseif ($answer->getValue() === 'scienсу-activity') {
                //Если выбрали напрвление подготовки
                $this->say('Научная деятельность');
                $this->backToMenufunc();

            }

            elseif ($answer->getValue() === 'contact') {
                //Если выбрали напрвление подготовки
                $this->say('Контакты');
                $this->backToMenufunc();

            }

            elseif ($answer->getValue() === 'back') {
                //назад   
                $this->askReason();
            }

            else {
                $this->say('Неизвестная команда введите /start чтобы вернуться в меню');
            }
        }
    });
        
    }

    // Функция стартового меню
    public function askReason()
    {
        $question = Question::create("Стартовое меню")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Главное меню')->value('main-menu'),
                Button::create('Мероприятия физ-теха')->value('activity'),
                Button::create('Новости физ-теха')->value('news'),
                Button::create('Карта физ-теха')->value('card'),
            ]);

        

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === 'main-menu') {
                    $this->mainMenuView();
                } 

                elseif ($answer->getValue() === 'activity') {

                    /* 
                    Получить информацию о мероприятиях - TEXT
                    SELECT activity 
                    FROM StaticData
                    WHERE 1
                */

                    $this->say('Мероприятия физ-теха');
                    
                } 

                elseif ($answer->getValue() === 'news') {

                    /* 
                    Получить информацию о новостях - TEXT
                    SELECT news 
                    FROM StaticData
                    WHERE 1
                */
                
                    $this->say('Новости физ-теха');
                    
                } 
                elseif ($answer->getValue() === 'card') {

                    /* 
                    Получить информацию о карте - TEXT
                    Карта физ-теха отдельная таблица
                    floor - этаж 
                    classrooms - диапазон формата 100-999 - CHAR(7)
                    SELECT news
                    FROM StaticData
                    WHERE 1
                */



                /*
                    foreach($query as $key){
                        $floor = $key['floor']; // номер этажа
                        $classrooms = $key['classrooms']; // Диапазон кабинетов
                        $floorclass = 'Этаж: ' . $floor . 'Кабинеты: ' . $classrooms;
                        $this->say($floorclass);
                    }
                */

                
                
                    $this->say('Карта физ-теха');
                    
                } 
                
                
                else {
                    $this->say('Неизвестная команда');
                }
            }
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askReason();
    }

}