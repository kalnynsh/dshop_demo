<?php
namespace shop\forms;

use yii\base\Model;
use yii\helpers\ArrayHelper;

abstract class CompositeForm extends Model
{
    /**
     * @var Model[]|array[]
     */
    private $forms = [];

    abstract protected function internalForms(): array;

    public function load($data, $formName = null): bool
    {
        $success = parent::load($data, $formName);

        foreach ($this->forms as $name => $form) {
            if (is_array($form)) {
                $success = Model::loadMultiple(
                    $form,
                    $data,
                    ($formName === null) ? null : $name
                ) && $success;
            }

            if (!is_array($form)) {
                $success = $form->load(
                    $data,
                    ($formName !== '') ? null : $name
                ) && $success;
            }
        }

        return $success;
    }

    public function validate($attributeNames = null, $clearErrors = true): bool
    {
        $parentNames = ($attributeNames !== null)
            ? array_filter((array) $attributeNames, 'is_string') : null;

        $success = parent::validate($parentNames, $clearErrors);

        foreach ($this->forms as $name => $form) {
            if (is_array($form)) {
                $success = Model::validateMultiple($form) && $success;
            }

            if (!is_array($form)) {
                $innerNames = ($attributeNames !== null)
                    ? ArrayHelper::getValue($attributeNames, $name) : null;

                $success =
                    $form->validate($innerNames ?: null, $clearErrors) && $success;
            }
        }

        return $success;
    }

    public function hasErrors($attribute = null): bool
    {
        if ($attribute !== null) {
            return parent::hasErrors($attribute);
        }

        if (parent::hasErrors()) {
            return true;
        }

        foreach ($this->forms as $form) {
            if (is_array($form)) {
                foreach ($form as $item) {
                    if ($item->hasErrors()) {
                        return true;
                    }
                }
            }

            if (!is_array($form)) {
                if ($form->hasErrors()) {
                    return true;
                }
            }
        }
    }

    public function getFirstErrors(): array
    {
        $errors = parent::getFirstErrors();

        foreach ($this->forms as $name => $form) {
            if (is_array($form)) {
                foreach ($form as $idx => $item) {
                    foreach ($item->getFirstErrors() as $attribute => $error) {
                        $errors[$name . '.' . $idx . '.' . $attribute] = $error;
                    }
                }
            }

            if (!is_array($form)) {
                foreach ($form->getFirstErrors() as $attribute => $error) {
                    $errors[$name . '.' . $attribute] = $error;
                }
            }
        }

        return $errors;
    }

    public function __get($name)
    {
        if (isset($this->forms[$name])) {
            return $this->forms[$name];
        }

        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if (in_array($name, $this->internalForms(), true)) {
            $this->forms[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    public function __isset($name)
    {
        return isset($this->forms[$name]) || parent::__isset($name);
    }
}
