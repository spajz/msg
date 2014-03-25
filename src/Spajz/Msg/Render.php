<?php namespace Spajz\Msg;

use View;
use Config;
use Session;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;

class Render extends Msg
{
    protected $type = 'flash';
    protected $messages = array();
    protected $template;
    protected $templates = array();
    protected $formats = array();
    protected $displayMode = null;
    protected $sortMessages = array();
    protected $flashMessages = array();
    protected $flashOptions = array();
    protected $flashInstant = false;

    /**
     * Set render type.
     *
     * @param  string $type (flash|instant)
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Set class properties.
     *
     * @param  array $array
     * @return void
     */
    public function setProperties($array = array())
    {
        foreach ($array as $property => $value) {
            $this->$property = $value;
        }
    }

    /**
     * Process render.
     *
     * @param  string $type
     * @param  string $messageKey
     * @param  string $format
     * @return string $out
     */
    public function process($type, $messageKey = null, $format = null)
    {
        $out = '';

        $this->setType($type);
        $this->prepare();
        $messages = $this->messages;

        if (empty($messages)) return $out;

        if ($messageKey)
            $messageKey = (array)$messageKey;

        if (!empty($messageKey))
            $messages = array_only($messages, $messageKey);

        if (!empty($messageKey) && $format) {
            foreach ($messageKey as $key) {
                $this->formats[$key] = $format;
            }
        }

        $messages = $this->sortArrayByArray($messages);

        if ($this->displayMode == 'single') {
            foreach ($messages as $key => $messageGroup) {
                $messageGroup = $this->formatMessages($messageGroup, $key, $format);
                $template = $this->getTemplates($key) ? : $this->template;
                $this->instantDisplayed($key, $messageGroup);
                foreach ($messageGroup as $message) {
                    $message = $this->sorting((array)$message);
                    $out .= $this->makeView($template, $key, $message);
                }
            }
        } else {
            foreach ($messages as $key => $messageGroup) {
                $this->instantDisplayed($key, $messageGroup);
                $messageGroup = $this->formatMessages($messageGroup, $key, $format);
                $template = $this->getTemplates($key) ? : $this->template;
                $messageGroup = $this->sorting($messageGroup);
                $out .= $this->makeView($template, $key, $messageGroup);
            }
        }
        return $out;
    }

    /**
     * Catch instant displayed messages.
     *
     * @return void
     */
    public function instantDisplayed($key, $messages)
    {
        if ($this->type == 'instant' && !$this->flashInstant) {
            $keys = array_keys($messages);
            $current = Session::get(Config::get('msg::session_key') . '.displayed', array());
            Session::set(Config::get('msg::session_key') . '.displayed', $current + array($key => $keys));
        }
    }

    /**
     * Prepare vars.
     *
     * @return void
     */
    private function prepare()
    {
        if ($this->type == 'flash') {
            $this->messages = $this->flashMessages;
            foreach ($this->flashOptions as $key => $option) {
                $this->$key = $option;
            }
        }
    }

    /**
     * Sort messages.
     *
     * @return void
     */
    private function sorting($messages)
    {
        $sort = $this->sortMessages;
        switch ($sort[0]) {
            case 'added':
                if ($sort[1] == 'asc') {
                    ksort($messages);
                } else {
                    krsort($messages);
                }

                break;
            case 'message':
                if ($sort[1] == 'asc')
                    asort($messages);
                else
                    arsort($messages);
                break;
        }
        return $messages;
    }

    /**
     * Make view from file template or blade string.
     *
     * @param  mixed $template
     * @param  string $key
     * @param  string $format
     * @return string
     */
    private function makeView($template, $key, $messages)
    {
        if (is_array($template)) {
            return $this->blader(reset($template), array('key' => $key, 'messages' => $messages));
        } else {
            return View::make($template, array('key' => $key, 'messages' => $messages));
        }
    }

    /**
     * Format messages.
     *
     * @param  array $messageGroup
     * @param  string $key
     * @param  string $format
     * @return array $messageGroup
     */
    private function formatMessages($messageGroup, $key, $format)
    {
        $formats = array_merge(Config::get('msg::formats'), $this->formats);
        if (isset($formats[$key])) {
            $messageGroup = $this->transform($messageGroup, $this->formats[$key], $key);
        } elseif ($format) {
            $messageGroup = $this->transform($messageGroup, $format, $key);
        } else {
            $format = $this->checkFormat($format);
            $messageGroup = $this->transform($messageGroup, $format, $key);
        }
        return $messageGroup;
    }

    /**
     * Helper function for sorting array.
     *
     * @param  array $array
     * @return array
     */
    private function sortArrayByArray($array)
    {
        if (!$orderArray = Config::get('msg::sort_groups')) return $array;
        $ordered = array();
        foreach ($orderArray as $key) {
            if (array_key_exists($key, $array)) {
                $ordered[$key] = $array[$key];
                unset($array[$key]);
            }
        }
        return $ordered + $array;
    }

    /**
     * Parses and compiles strings by using Blade Template System.
     *
     * @param  string $str
     * @param  array $data
     * @return string
     */
    private function blader($str, $data = array())
    {
        $filesystemInstance = new Filesystem;
        $blade = new BladeCompiler($filesystemInstance, 'msg');
        $parsedString = $blade->compileString($str);

        ob_start() and extract($data, EXTR_SKIP);

        try {
            eval('?>' . $parsedString);
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        $str = ob_get_contents();
        ob_end_clean();

        return $str;
    }

}