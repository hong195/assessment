<?php

namespace DoctrineProxies\__CG__\Domain\Model\Criterion;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Criterion extends \App\Domain\Model\Criterion\Criterion implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array<string, null> properties to be lazy loaded, indexed by property name
     */
    public static $lazyPropertiesNames = array (
);

    /**
     * @var array<string, mixed> default values of properties to be lazy loaded, with keys being the property names
     *
     * @see \Doctrine\Common\Proxy\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array (
);



    public function __construct(?\Closure $initializer = null, ?\Closure $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     *
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', '' . "\0" . 'Domain\\Model\\Criterion\\Criterion' . "\0" . 'id', '' . "\0" . 'Domain\\Model\\Criterion\\Criterion' . "\0" . 'name', '' . "\0" . 'Domain\\Model\\Criterion\\Criterion' . "\0" . 'options'];
        }

        return ['__isInitialized__', '' . "\0" . 'Domain\\Model\\Criterion\\Criterion' . "\0" . 'id', '' . "\0" . 'Domain\\Model\\Criterion\\Criterion' . "\0" . 'name', '' . "\0" . 'Domain\\Model\\Criterion\\Criterion' . "\0" . 'options'];
    }

    /**
     *
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Criterion $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy::$lazyPropertiesDefaults as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     *
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @deprecated no longer in use - generated code now relies on internal components rather than generated public API
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }


    /**
     * {@inheritDoc}
     */
    public function getOptions(): \Doctrine\Common\Collections\Collection
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOptions', []);

        return parent::getOptions();
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getName', []);

        return parent::getName();
    }

    /**
     * {@inheritDoc}
     */
    public function addOption(\App\Domain\Model\Criterion\OptionId $optionId, string $label, float $value): \App\Domain\Model\Criterion\Option
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addOption', [$optionId, $label, $value]);

        return parent::addOption($optionId, $label, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function updateOption(\App\Domain\Model\Criterion\OptionId $optionId, string $name, float $value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'updateOption', [$optionId, $name, $value]);

        return parent::updateOption($optionId, $name, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function removeOption(\App\Domain\Model\Criterion\OptionId $optionId): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeOption', [$optionId]);

        parent::removeOption($optionId);
    }

    /**
     * {@inheritDoc}
     */
    public function removeAllOptions(): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeAllOptions', []);

        parent::removeAllOptions();
    }

    /**
     * {@inheritDoc}
     */
    public function assertNameIsNotEmpty(string $name)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'assertNameIsNotEmpty', [$name]);

        return parent::assertNameIsNotEmpty($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): \App\Domain\Model\Criterion\CriterionId
    {
        if ($this->__isInitialized__ === false) {
            return  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function changeName(string $name)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'changeName', [$name]);

        return parent::changeName($name);
    }

}
