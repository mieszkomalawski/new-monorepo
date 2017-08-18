<?php


namespace BacklogBundle;


use Symfony\Component\PropertyAccess\Exception;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

class SprintPropertyAccessor extends PropertyAccessor
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
     * @inheritDoc
     */
    public function __construct(array $propertySets = [], array $propertyGets = [], $magicCall = false, $throwExceptionOnInvalidIndex = false)
    {
        parent::__construct($magicCall, $throwExceptionOnInvalidIndex);
        $this->customSets = $propertySets;
        $this->customGets = $propertyGets;
    }

    /**
     * @inheritDoc
     */
    public function getValue($objectOrArray, $propertyPath)
    {
        if (isset($this->customGets[(string)$propertyPath])) {
            $propertyPath = new PropertyPath($this->customGets[(string)$propertyPath]);
        }
        return parent::getValue($objectOrArray, $propertyPath);
    }
    /**
     * @inheritDoc
     */
    public function setValue(&$objectOrArray, $propertyPath, $value)
    {
        if (isset($this->customSets[(string)$propertyPath])) {
            $propertyPath = new PropertyPath($this->customSets[(string)$propertyPath]);
        }
        parent::setValue($objectOrArray, $propertyPath, $value);
    }

}