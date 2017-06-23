<?php

namespace CAC\Component\ESP\Adapter;

use CAC\Component\ESP\ESPException;

trait TemplatingTrait
{

    /**
     * Find a template by name
     *
     * @param string $name
     * @param string $group
     * @throws ESPException
     */
    protected function findTemplateByName($name, $group = 'default')
    {
        if (!array_key_exists($group, $this->options['templates']) || !array_key_exists($name, $this->options['templates'][$group])) {
            throw new ESPException(sprintf("Template configuration for group %s could not be found", $group));
        }

        return $this->options['templates'][$group][$name];
    }

    /**
     * Add a new template
     *
     * @param string $name
     * @param integer $id
     * @param string $subject
     * @param string $group
     */
    public function addTemplate($name, $id, $subject, $group = 'default')
    {
        if (!array_key_exists($group, $this->options['templates'])) {
            $this->options['templates'][$group] = array();
        }

        $this->options['templates'][$group][$name] = array('id' => $id, 'subject' => $subject);
    }

    /**
     * Get a configuration option
     *
     * @param string $name
     * @param string $group
     * @throws ESPException
     * @return string
     */
    protected function getOption($name, $group = 'default', $defaultValue = null)
    {
        // Check if specific group has the option set
        if (array_key_exists($group, $this->options['options']) && isset($this->options['options'][$group][$name])) {
            return $this->options['options'][$group][$name];
        }

        // fallback to the default
        if (array_key_exists($name, $this->options)) {
            return $this->options[$name];
        }

        return $defaultValue;
    }
}
