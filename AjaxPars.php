<?php
namespace Indeximstudio\AjaxPars;


if (!defined('MODX_BASE_PATH')) {
    die('What are you doing? Get out of here!');
}

/**
 * Class AjaxPars
 * @package Indeximstudio\AjaxPars
 * release 2.0.1
 *
 * @property string $id уникальный номер парсинга
 * @property integer $delay задержка в мс
 * @property string $ajaxPath ссылка на обработчик формы
 * @property array $data массив для передачи данных для работы в момет создания екземпляра класса
 * @property integer $flow потоки (сколько раз выполнить метод getAction за одну итерацию)
 * @property boolean $start
 * @property integer $countIterations сколько раз должен повториться скрипт
 * @property integer $debug Режим отладки (вывод информации в консоль)
 * 0 - отключен, 1 - краткая информация, 2 - полная информация
 */
abstract class AjaxPars
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var int
     */
    public $delay = 1000;
    /**
     * @var string
     */
    public $ajaxPath = '';
    /**
     * @var array
     */
    public $data = [];
    /**
     * @var int
     */
    public $flow = 1;
    /**
     * @var bool
     */
    public $start;
    /**
     * @var integer
     */
    public $debug = 0;
    /**
     * @var int
     */
    private $countIterations;

    /**
     * AjaxPars constructor.
     * @param string $id
     */
    function __construct($id)
    {
        if (is_string($id) && trim($id) != '') {
            $this->id = $id;
            return $this;
        }
        return false;
    }

    /**
     * Получает количество итераций
     * @return integer
     */
    abstract protected function getCountIterations();

    /**
     * Выполнение действий с полученными данными
     * @return string
     */
    abstract protected function getAction();

    /**
     * Создается в дочернем классе для получения необходимой информации о нем
     * Передает его имя и путь к файлу в этот класс, чтоб занести в сессию
     *
     * ВНИМАНИЕ!!!
     * Если вам нужно расширить функционал етого метода в дочернем классе,
     * не забудьте вызвать метод transferThisFileInfo()
     * и передать в него константы __CLASS__ и __FILE__
     */
    abstract protected function communicationWithParent();

    /**
     * Вычисляет сколько процентов осталось до конца программы
     * @return integer|float
     */
    protected function getProcessPercent()
    {
        if ($this->countIterations > 0) {
            $this->setSessionParam(
                'current',
                $this->getSessionParam('current') + 1
            );
            $this->setSessionParam(
                'percent',
                $this->getSessionParam('current') * 100 / $this->countIterations
            );
            $percent = number_format(
                $this->getSessionParam('percent'),
                2,
                '.',
                ''
            );
        } else {
            $percent = 100;
        }
        return $percent;
    }

    /**
     * Вычисляет и выводит данные о текущей операции
     * @return string
     */
    protected function ajaxTime()
    {
        $time_start = ($this->getSessionParam('start_time') !== false) ? $this->getSessionParam('start_time') : 0;
        $ostalos = $this->countIterations - $this->getSessionParam('current');

        if ($time_start == 0) {
            $this->setSessionParam('time', []);
            $ostalos_time_min = '';
        } else {
            if (is_array($this->getSessionParam('time')) && count($this->getSessionParam('time')) > 5) {
                $this->setSessionParam('time', []);
            }
            $time_stop = microtime(TRUE);
            $time = $time_stop - $time_start;

            $_SESSION['parsing'][$this->id]['time'][] = $time;
            $sr = 0;
            if (is_array($this->getSessionParam('time'))) {
                $sr = array_sum($this->getSessionParam('time')) / count($this->getSessionParam('time'));
            }

            $ostalos_time = ($ostalos * $sr) / $this->flow;
            $ostalos_time_min = sprintf(
                '%02d:%02d:%02d',
                $ostalos_time / 3600,
                ($ostalos_time % 3600) / 60,
                ($ostalos_time % 3600) % 60
            );
        }
        $this->setSessionParam('start_time', microtime(true));

        return '<br>items left/total= ' . $ostalos . '/' . $this->countIterations . '<br>
                    time left = ' . $ostalos_time_min . ' <br>
                    current = ' . ($this->getSessionParam('current') + $this->flow) . ' ';
    }

    /**
     * Сохраняет параметры в сессию
     */
    public function setParams()
    {
        $this->sessionClear();
        $this->setDebug();

        $this->setSessionParam('on', true);
        $this->setSessionParam('current', 0);
        $this->setSessionParam('start_time', 0);
        $this->setSessionParam('data', $this->data);
        $this->setSessionParam('flow', $this->flow);

        $this->setSessionParam(
            'count_iterations',
            $this->getCountIterationsWp()
        );

        if (trim($this->ajaxPath) == '') {
            $this->communicationWithParent();
            $this->ajaxPath = $this->genDefaultAjaxFilePath();
        }
    }

    /**
     * остановка парсинга
     */
    public function StopPars()
    {
        $this->setSessionParam('on', false);
    }

    public function sessionClear()
    {
        unset($_SESSION['parsing'][$this->id]);
    }

    /**
     * получаем данные и возвращем их через json
     * @return string
     */
    public function getValueJson()
    {
        $this->data = $this->getSessionParam('data');
        $this->flow = $this->getSessionParam('flow');
        $this->start = $this->getSessionParam('start_time');
        $this->countIterations = $this->getSessionParam('count_iterations');

        $data['current'] = $this->getSessionParam('current');
        $data['process_info'] = $this->ajaxTime();
        for ($x = 0; $x < $this->flow; $x++) {
            $data['process_message'] = $this->getAction();
            if ($this->getSessionParam('on')) {
                $data['percent'] = $this->getProcessPercent();
            } else {
                $data['percent'] = 100;
            }
        }
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * вывод полосы прогресса
     * @return string
     */
    public function getProgress()
    {
        $p = '
           
                <div class="form-group" >
                    <label for="categories" > Прогрес</label >
                    <div class="progress" id = "status_bar_item_' . $this->id . '" style = "display:none;" >
                        <div class="progress-bar progress-bar-striped active" role = "progressbar" aria - valuenow = "0" aria - valuemin = "0" aria - valuemax = "100" style = "width:0" ></div >
                    </div >
                </div >
                <br />
                <div class="form-group" id = "statys_' . $this->id . '" ></div >
                <div class="form-group" >
                    <pre class="pre-scrollable"><code id = "test_' . $this->id . '" ></code ></pre>
                </div>
            
        ';
        return $p;
    }

    /**
     * Формирует и возвращает JavaScript c Ajax запросом
     * @return string
     */
    public function getScript()
    {
        $write_debug = $this->getDebug();
        return "
            <script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js'></script>
            <script type='text/javascript'>
            $(function() {
                var form = '#form" . $this->id . "';
                $(form).submit(function(e) {
                    doAjax" . $this->id . "();
                    e.preventDefault(); 
                });
                
                function doAjax" . $this->id . "() {
                    setTimeout(function() {
                        var ajax = $.ajax({
                            type: 'POST',
                            url: location.origin + '" . $this->ajaxPath . "',
                            data: $(form).serialize(),
                            success: function (sample) {
                                " . $write_debug . "
                                try {
                                    obj = jQuery.parseJSON(sample);
                                    $('#statys_" . $this->id . "').html(obj.process_info);
                                    $('#test_" . $this->id . "').html(obj.process_message);
                                    if (obj.percent >= 100) {
                                        obj.percent = 100;
                                    }
                                    $('#status_bar_item_" . $this->id . "').show();
                                    $('#status_bar_item_" . $this->id . " .progress-bar').attr('aria-valuenow', obj.percent + '%');
                                    $('#status_bar_item_" . $this->id . " .progress-bar').width(obj.percent + '%');
                                    $('#status_bar_item_" . $this->id . " .progress-bar').html(obj.percent + '%');
                            
                                    if (obj.percent >= 100) {
                                        obj.percent = 100;
                                        $('#status_bar_item_" . $this->id . " .progress-bar').removeClass('progress-bar-striped active');
                                        ajax.abort();
                                    } else {
                                        doAjax" . $this->id . "();
                                    }
                                } catch (err) {
                                    doAjax" . $this->id . "();
                                }
                            },
                            error: function (sample) {
                                console.log('error " . $this->id . " '.sample);
                                doAjax" . $this->id . "();
                            }
                        });
                    }, " . $this->delay . ");
                }
            });
            </script>
        ";
    }

    /**
     * Получает имя дочернего класса и путь к файлу с ним и заносит в базу
     * @param string $class_name
     * @param string $class_path
     */
    protected function transferThisFileInfo($class_name, $class_path)
    {
        $this->setSessionParam('className', $class_name);
        $this->setSessionParam('classPath', $this->replaceReverseSlashes($class_path));
    }

    /**
     * Заменяет обратные слеши на простые
     * Они не нравятся JavaScript
     * @param string $str
     * @return string
     */
    protected function replaceReverseSlashes($str)
    {
        return str_replace('\\', '/', $str);
    }

    /**
     * Формирует путь к стандартному файлу ajax относительно корня админки
     * @return string
     */
    private function genDefaultAjaxFilePath()
    {
        return '/' . stristr($this->replaceReverseSlashes(__DIR__), 'assets') . '/' . 'ajax.php?id=' . $this->id . '&modx_manager_path=' . MODX_BASE_PATH . 'index.php';
    }

    /**
     * Получает количество итераций
     * @return bool|int getCountIterations
     */
    private function getCountIterationsWp()
    {
        // Если есть в сесси - получаем
        if ($this->getSessionParam('count_iterations') > 0) {
            $count_iterations = $this->getSessionParam('count_iterations');
            // иначе получаем количество в дочернем классе и заносим в сессию
        } else {
            $count_iterations = $this->getCountIterations();
            $this->setSessionParam('count_iterations', $count_iterations);
        }
        return $count_iterations;
    }

    /**
     * Заносит данные в сессию
     * @param string $key
     * @param mixed $value
     */
    protected function setSessionParam($key, $value)
    {
        $_SESSION['parsing'][$this->id][$key] = $value;
    }

    protected function getSessionParam($key)
    {
        return (isset($_SESSION['parsing'][$this->id][$key]))
        ? $_SESSION['parsing'][$this->id][$key]
            : false;
    }

    /**
     * Устанавливает параметр отладки
     */
    private function setDebug()
    {
        switch ($this->debug) {
            case 0:
                $this->debug = 0;
                break;
            case 1:
                $this->debug = 1;
                break;
            default:
                $this->debug = 0;
        }
    }

    /**
     * В javascript подставляет тип вывода информации в консоли
     * @return string
     */
    private function getDebug()
    {
        switch ($this->debug) {
            case 0:
                return '';
                break;
            case 1:
                return "console.log('(" . $this->id . ") ' + sample);";
                break;
            default:
                return '';
        }
    }
}
