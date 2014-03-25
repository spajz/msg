<?php namespace Spajz\Msg;

use Config;
use Session;
use Spajz\Msg\Render;

class Msg extends \Illuminate\Support\MessageBag
{
    protected $template;
    protected $templates = array();
    protected $formats = array();
    protected $displayMode = null;
    protected $sortMessages = array();
    protected $flashMessages = array();
    protected $flashOptions = array();
    protected $flashInstant = false;

    public function __construct(array $messages = array())
    {
        parent::__construct($messages);

        $this->flashMessages = Session::get(Config::get('msg::session_key') . '.messages', array());
        $this->flashOptions = Session::get(Config::get('msg::session_key') . '.options', array());
        $this->setFormat(Config::get('msg::format'));
        $this->setTemplate(Config::get('msg::template'));
        $this->setDisplayMode(Config::get('msg::display_mode'));
        $sortMessages = Config::get('msg::sort_messages');
        $this->setSortMessages($sortMessages[0], $sortMessages[1]);
    }

    /**
     * Add a success message.
     *
     * @param  string|array $message
     * @return Spajz\Msg\Msg
     */
    public function success($message)
    {
        return $this->addMessage('success', $message);
    }

    /**
     * Show a success message.
     *
     * @param  string $format
     * @return string
     */
    public function showSuccess($format = null)
    {
        return $this->show('success', $format);
    }

    /**
     * Show a success message instantly.
     *
     * @param  string $format
     * @return string
     */
    public function showSuccessInstant($format = null)
    {
        return $this->instant('success', $format);
    }

    /**
     * Add a info message.
     *
     * @param  string|array $message
     * @return Spajz\Msg\Msg
     */
    public function info($message)
    {
        return $this->addMessage('info', $message);
    }

    /**
     * Show a info message.
     *
     * @param  string $format
     * @return string
     */
    public function showInfo($format = null)
    {
        return $this->show('info', $format);
    }

    /**
     * Show a info message instantly.
     *
     * @param  string $format
     * @return string
     */
    public function showInfoInstant($format = null)
    {
        return $this->instant('info', $format);
    }

    /**
     * Add a warning message.
     *
     * @param  string|array $message
     * @return Spajz\Msg\Msg
     */
    public function warning($message)
    {
        return $this->addMessage('warning', $message);
    }

    /**
     * Show a warning message.
     *
     * @param  string $format
     * @return string
     */
    public function showWarning($format = null)
    {
        return $this->show('warning', $format);
    }

    /**
     * Show a warning message instantly.
     *
     * @param  string $format
     * @return string
     */
    public function showWarningInstant($format = null)
    {
        return $this->instant('warning', $format);
    }

    /**
     * Add a danger message.
     *
     * @param  string|array $message
     * @return Spajz\Msg\Msg
     */
    public function danger($message)
    {
        return $this->addMessage('danger', $message);
    }

    /**
     * Show a danger message.
     *
     * @param  string $format
     * @return string
     */
    public function showDanger($format = null)
    {
        return $this->show('danger', $format);
    }

    /**
     * Show a danger message instantly.
     *
     * @param  string $format
     * @return string
     */
    public function showDangerInstant($format = null)
    {
        return $this->instant('danger', $format);
    }

    /**
     * Add a error message.
     *
     * @param  string|array $message
     * @return Spajz\Msg\Msg
     */
    public function error($message)
    {
        return $this->addMessage('error', $message);
    }

    /**
     * Show a error message.
     *
     * @param  string $format
     * @return string
     */
    public function showError($format = null)
    {
        return $this->show('error', $format);
    }

    /**
     * Show a error message instantly.
     *
     * @param  string $format
     * @return string
     */
    public function showErrorInstant($format = null)
    {
        return $this->instant('error', $format);
    }

    /**
     * Add a custom message.
     *
     * @param  string|array $key
     * @param  string|array $message
     * @return Spajz\Msg\Msg
     */
    public function custom($key, $message = null, $format = null)
    {
        if (!is_null($format) && !is_array($key)) $this->setFormats($key, $format);
        return $this->addMessage($key, $message);
    }

    /**
     * Show messages.
     *
     * @param  string|array $key
     * @param  string $format
     * @return string
     */
    public function show($key = null, $format = null)
    {
        $render = new Render();
        $render->setProperties($this->flashOptions);
        return $render->process('flash', $key, $format);
    }

    /**
     * Show messages instantly.
     *
     * @param  string|array $key
     * @param  string $format
     * @return string
     */
    public function instant($key = null, $format = null)
    {
        $render = new Render();
        $this->removeDisplayed();
        $render->setProperties(get_object_vars($this));
        return $render->process('instant', $key, $format);
    }

    /**
     * Delete messages.
     *
     * @param  string|array $key
     * @param  bool $flash
     * @return Spajz\Msg\Msg
     */
    public function delete($key, $flash = true)
    {
        $key = (array)$key;
        $this->messages = array_except($this->messages, $key);

        if ($flash) {
            $this->flashMessages = array_except($this->flashMessages, $key);
            $this->flashSession(array_except($this->getFlashSession('messages') ? : array(), $key));
        }
        return $this;
    }

    /**
     * Delete all messages.
     *
     * @param  bool $flash
     * @return Spajz\Msg\Msg
     */
    public function deleteAll($flash = true)
    {
        $this->messages = array();
        if ($flash) {
            Session::forget(Config::get('msg::session_key') . '.messages');
            $this->flashMessages = array();
        }
        return $this;
    }

    /**
     * Set display mode.
     *
     * @param  string $mode (single|group)
     * @return Spajz\Msg\Msg
     */
    public function setDisplayMode($mode)
    {
        return $this->displayMode = $mode;
    }

    /**
     * Set message format.
     *
     * @param  string|array $key
     * @param  string $format
     * @return Spajz\Msg\Msg
     */
    public function setFormats($key, $format = null)
    {
        if (is_array($key)) {
            $this->formats = array_merge($this->formats, $key);
        } else {
            $this->formats[$key] = $format;
        }
        return $this;
    }

    /**
     * Add message to messages array.
     *
     * @param  string|array $key
     * @param  string|array $format
     * @return Spajz\Msg\Msg
     */
    protected function addMessage($key, $message)
    {
        if (is_array($key)) {
            foreach ($key as $messageKey => $messages) {
                foreach ((array)$messages as $item) {
                    $this->add($messageKey, $item);
                }
            }
        } else {
            foreach ((array)$message as $item) {
                $this->add($key, $item);
            }
        }
        return $this;
    }

    /**
     * Add a message to the bag.
     *
     * @param  string $key
     * @param  string $message
     * @return Spajz\Msg\Msg
     */
    public function add($key, $message)
    {
        if ($this->isUnique($key, $message)) {
            $this->messages[$key][] = $message;
        }
        return $this;
    }

    /**
     * Set messages array.
     *
     * @return Spajz\Msg\Msg
     */
    public function setMessages($messages = array())
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * Set group format.
     *
     * @param  string|array $group
     * @param  string $format
     * @return Spajz\Msg\Msg
     */
    public function setGroupFormat($group, $format = ':message')
    {
        if (is_array($group)) {
            foreach ($group as $value) {
                $this->formats[$value] = $format;
            }
        } else {
            $this->formats[$group] = $format;
        }
        return $this;
    }

    /**
     * Set main template.
     *
     * @param  string $template
     * @param  string $format
     * @return Spajz\Msg\Msg
     */
    public function setTemplate($template, $bladeString = false)
    {
        $this->template = $bladeString ? (array)$template : $template;
        return $this;
    }

    /**
     * Set template by message key.
     *
     * @param  string|array $key
     * @param  string $template
     * @return Spajz\Msg\Msg
     */
    public function setTemplates($key, $template = null, $bladeString = false)
    {
        if (is_array($key))
            $this->templates = array_merge($this->templates, $key);
        elseif ($template)
            $this->templates[$key] = $bladeString ? (array)$template : $template;
        return $this;
    }

    /**
     * Get all templates, or template by message key.
     *
     * @param  string $key
     * @return Spajz\Msg\Msg
     */
    public function getTemplates($key = null)
    {
        if (is_null($key))
            return $this->templates;
        else
            return isset($this->templates[$key]) ? $this->templates[$key] : false;
    }

    /**
     * Delete template by message key.
     *
     * @param  string $key
     * @return Spajz\Msg\Msg
     */
    public function deleteTemplates($key = null)
    {
        if (is_null($key))
            $this->templates = array();
        elseif (isset($this->templates[$key]))
            unset($this->templates[$key]);
        return $this;
    }

    /**
     * Put data into flash session.
     *
     * @param  array $messages
     * @return void
     */
    public function flashSession($messages = null)
    {
        $this->removeDisplayed();

        $array['messages'] = $messages ? : $this->messages;
        $array['options'] = array(
                'template' => $this->template,
                'templates' => $this->templates,
                'formats' => $this->formats,
                'displayMode' => $this->displayMode,
                'sortMessages' => $this->sortMessages,
        );

        Session::flash(Config::get('msg::session_key'), $array);
    }

    /**
     * Get messages session.
     *
     * @param string $key (messages|options|displayed)
     * @return array
     */
    public function getFlashSession($key = '')
    {
        if ($key) $key = '.' . $key;
        return Session::get(Config::get('msg::session_key') . $key);
    }

    /**
     * Remove displayed instant messages.
     *
     * @return void
     */
    public function removeDisplayed()
    {
        $displayed = Config::get('msg::session_key') . '.displayed';
        if (Session::get($displayed)) {
            $messages = $this->getMessages();
            foreach (Session::get($displayed) as $key => $value) {
                if (isset($messages[$key]) && is_array($value)) {
                    foreach ($value as $msgKey) {
                        if (isset($messages[$key][$msgKey])) unset($messages[$key][$msgKey]);
                    }
                    if (empty($messages[$key])) unset($messages[$key]);
                }
            }
            $this->setMessages($messages);
            Session::forget($displayed);
        }
    }

    /**
     * Leave a instant messages for the next request.
     *
     * @param string $keep
     * @return Spajz\Msg\Msg
     */
    public function instantKeep($keep = false)
    {
        $this->flashInstant = $keep;
        return $this;
    }

    /**
     * Put messages into flash session.
     *
     * @param array $array
     * @return void
     */
    public function setFlashSession($array)
    {
        Session::flash(Config::get('msg::session_key'), $array);
    }

    /**
     * Set sort for the messages.
     *
     * @param string $by (added|message)
     * @param string $sort (asc|desc)
     * @return Spajz\Msg\Msg
     */
    public function setSortMessages($by, $sort = 'asc')
    {
        $this->sortMessages = array($by, $sort);
        return $this;
    }

    public function __call($name, $arguments)
    {
        // Custom add
        $customGroups = Config::get('msg::custom_groups');
        if (in_array($name, $customGroups)) {
            return $this->addMessage($name, $arguments[0]);
        }

        // Custom show
        $key = strtolower(substr($name, 4));
        if (starts_with($name, 'show') && isset($this->messages[$key])) {
            return $this->show($key, isset($arguments[0]) ? $arguments[0] : null);
        }

        // Custom show instant
        $key = strtolower(substr($name, 4, -7));
        if (starts_with($name, 'show') && ends_with($name, 'Instant') && isset($this->messages[$key])) {
            return $this->instant($key, isset($arguments[0]) ? $arguments[0] : null);
        }
    }

    public function __toString()
    {
        return '<pre>' . print_r($this->messages) . '</pre>';
    }


}