<?php

namespace BacklogBundle;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyPath;

class CustomPropertyAccessor extends PropertyAccessor
{
    /**
     * @var array
     */
    private $customSets;
    /**
     * @var array
     */
    private $customGets;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $propertySets = [], array $propertyGets = [], $magicCall = false, $throwExceptionOnInvalidIndex = false)
    {
        parent::__construct($magicCall, $throwExceptionOnInvalidIndex);
        $this->customSets = $propertySets;
        $this->customGets = $propertyGets;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($objectOrArray, $propertyPath)
    {
        if (isset($this->customGets[(string) $propertyPath])) {
            $propertyPath = new PropertyPath($this->customGets[(string) $propertyPath]);
        }

        return parent::getValue($objectOrArray, $propertyPath);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue(&$objectOrArray, $propertyPath, $value)
    {
        if (isset($this->customSets[(string) $propertyPath])) {
            $propertyPath = new PropertyPath($this->customSets[(string) $propertyPath]);
        }
        parent::setValue($objectOrArray, $propertyPath, $value);
    }
}
