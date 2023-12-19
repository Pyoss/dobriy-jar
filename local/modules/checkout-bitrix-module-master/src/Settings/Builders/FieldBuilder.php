<?php

namespace TinkoffCheckout\Settings\Builders;

class FieldBuilder extends Builder
{
    private $id = '';
    private $label = '';
    private $placeholder = '';
    private $type = 'text';
    private $options = [];
    private $multiple = false;
    private $accept = "";
    private $heading = "";
    private $max = "";
    private $min = "";
    private $strictValue = false;
    private $style = '';
    private $class = '';
    private $value = null;
    private $manualValueUpdate = false;

    const TYPE_TEXT = 'text';
    const TYPE_NUMBER = 'number';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_FILE = 'file';
    const TYPE_SELECT = 'select';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_HR = 'hr';
    const TYPE_HEADING = 'heading';

    public function build()
    {
        $style          = str_replace('"', "'", $this->getStyle());
        $styleAttribute = 'style="' . $style . '"';

        $class          = $this->getClass();
        $classAttribute = 'class="' . $class . '"';

        if ($this->getType() == self::TYPE_HR) {
            $html = '<tr ' . $classAttribute . ' style="background: #cedddb;height: 1px;width: 100%;' . $style . '">';
            $html .= '<td style="padding:0"></td><td style="padding:0"></td>';
            $html .= '</tr>';

            return $html;
        }

        if ($this->getType() == self::TYPE_HEADING) {
            $heading = $this->getHeading();
            $html    = '<tr class="heading ' . $class . '" ' . $styleAttribute . '>';
            $html    .= '<td colspan="2">' . $heading . '</td></tr>';
            return $html;
        }

        $field = '<tr ' . $styleAttribute . ' ' . $classAttribute . '>';
        $field .= $this->buildLabel();
        $field .= $this->buildInput();
        $field .= '</tr>';

        return $field;
    }

    private function buildLabel()
    {
        $label = '<td style="width: 40%;">';
        $label .= '<label for="' . $this->getId() . '">' . $this->getLabel() . '</label>';
        $label .= '</td>';

        return $label;
    }

    private function buildInput()
    {
        $prefix = '<td style="width: 60%">';
        $suffix = '</td>';
        $value  = \COption::GetOptionString($this->getModuleID(), str_replace('[]', '', $this->getId()), '');
        $value  = json_decode($value) ? json_decode($value, true) : $value;

        $value = is_string($value) ? htmlspecialcharsbx($value) : $value;

        switch ($this->type) {
            case self::TYPE_SELECT:
                $multiple = $this->isMultiple() ? ' multiple ' : ' ';
                $name     = $this->isMultiple() ? $this->getId() . '[]' : $this->getId();

                $select = '<select ' . $multiple . ' name="' . $name . '">';

                foreach ($this->getOptions() as $index => $label) {
                    if ($this->isStrictValue()) {
                        $selected = is_array($value) ? in_array($index, $value) : $value === $index;
                    } else {
                        $selected = is_array($value) ? in_array($index, $value) : $value == $index;
                    }

                    $option = '<option ';
                    $option .= 'value="' . $index . '" ';
                    $option .= $selected ? 'selected ' : ' ';
                    $option .= '>';
                    $option .= $label;
                    $option .= '</option>';

                    $select .= $option;
                }

                $select .= '</select>';

                return $prefix . $select . $suffix;
            case self::TYPE_TEXTAREA:
                $textarea = '<textarea ';
                $textarea .= 'name="' . $this->getId() . '" ';
                $textarea .= 'id="' . $this->getId() . '"';
                $textarea .= 'placeholder="' . $this->getPlaceholder() . '">';
                $textarea .= $value . '</textarea>';

                return $prefix . $textarea . $suffix;
            case self::TYPE_FILE:
                $accept = $this->getAccept();

                $input = '<input type="text" ';
                $input .= 'value="' . $value . '" ';
                $input .= 'name="' . $this->getId() . '" ';
                $input .= 'id="' . $this->getId() . '"';
                $input .= 'placeholder="' . $this->getPlaceholder() . '"';
                $input .= '>';

                $input .= '<input type="file" ';
                $input .= 'name="' . $this->getId() . '_file" ';
                $input .= $accept ? "accept='$accept' " : "";
                $input .= '>';

                return $prefix . $input . $suffix;
            case self::TYPE_NUMBER:
                $min   = $this->getMin();
                $max   = $this->getMax();
                $input = '<input type="text" ';
                $input .= 'value="' . $value . '" ';
                $input .= 'name="' . $this->getId() . '" ';
                $input .= 'id="' . $this->getId() . '"';
                $input .= $min !== '' ? "min='$min'" : '';
                $input .= $max !== '' ? "max='$max'" : '';
                $input .= 'placeholder="' . $this->getPlaceholder() . '"';
                $input .= '>';

                return $prefix . $input . $suffix;
            case self::TYPE_CHECKBOX:
                $manualValue = $this->isManualValueUpdate() ? $this->getValue() : $value;

                $checked = is_array($value) && in_array($manualValue, $value) ? 'checked' : '';

                $id = $this->getId();
                $id = str_replace('[]', '', $id);
                $id = $id . time() . rand(0, 100);


                $input = '<input type="checkbox" ';
                $input .= $checked . ' ';
                $input .= 'value="' . $manualValue . '" ';
                $input .= 'name="' . $this->getId() . '" ';
                $input .= 'id="' . $id . '"';
                $input .= 'class="adm-designed-checkbox"';
                $input .= '>';

                $input .= '<label class="adm-designed-checkbox-label" for="' . $id . '" title=""></label>';

                return $prefix . $input . $suffix;
            default:
                $input = '<input type="text" ';
                $input .= 'value="' . $value . '" ';
                $input .= 'name="' . $this->getId() . '" ';
                $input .= 'id="' . $this->getId() . '"';
                $input .= 'placeholder="' . $this->getPlaceholder() . '"';
                $input .= '>';

                return $prefix . $input . $suffix;
        }

        return '';
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param mixed $placeholder
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @param $multiple
     *
     * @return void
     */
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;
    }

    /**
     * @return bool
     */
    public function isMultiple()
    {
        return $this->multiple;
    }

    /**
     * @return string
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * @param string $accept
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;
    }

    /**
     * @return string
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * @param string $heading
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;
    }

    /**
     * @return string
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param string $max
     */
    public function setMax($max)
    {
        $this->max = $max;
    }

    /**
     * @return string
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param string $min
     */
    public function setMin($min)
    {
        $this->min = $min;
    }

    /**
     * @return bool
     */
    public function isStrictValue()
    {
        return $this->strictValue;
    }

    /**
     * @param bool $strictValue
     */
    public function setStrictValue($strictValue)
    {
        $this->strictValue = $strictValue;
    }

    /**
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param string $style
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $class       = is_array($class) ? implode(' ', $class) : $class;
        $this->class = $class;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->manualValueUpdate = true;
        $this->value             = $value;
    }

    /**
     * @return bool
     */
    public function isManualValueUpdate()
    {
        return $this->manualValueUpdate;
    }
}