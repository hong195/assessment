<?php

namespace DoctrineProxies\__CG__\Domain\Model\EfficiencyAnalysis;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class EfficiencyAnalysis extends \Domain\Model\EfficiencyAnalysis\EfficiencyAnalysis implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Domain\\Model\\EfficiencyAnalysis\\EfficiencyAnalysis' . "\0" . 'id', '' . "\0" . 'Domain\\Model\\EfficiencyAnalysis\\EfficiencyAnalysis' . "\0" . 'assessments', '' . "\0" . 'Domain\\Model\\EfficiencyAnalysis\\EfficiencyAnalysis' . "\0" . 'month', '' . "\0" . 'Domain\\Model\\EfficiencyAnalysis\\EfficiencyAnalysis' . "\0" . 'scored', '' . "\0" . 'Domain\\Model\\EfficiencyAnalysis\\EfficiencyAnalysis' . "\0" . 'total', '' . "\0" . 'Domain\\Model\\EfficiencyAnalysis\\EfficiencyAnalysis' . "\0" . 'employeeId', '' . "\0" . 'Domain\\Model\\EfficiencyAnalysis\\EfficiencyAnalysis' . "\0" . 'status'];
        }

        return ['__isInitialized__', '' . "\0" . 'Domain\\Model\\EfficiencyAnalysis\\EfficiencyAnalysis' . "\0" . 'id', '' . "\0" . 'Domain\\Model\\EfficiencyAnalysis\\EfficiencyAnalysis' . "\0" . 'assessments', '' . "\0" . 'Domain\\Model\\EfficiencyAnalysis\\EfficiencyAnalysis' . "\0" . 'month', '' . "\0" . 'Domain\\Model\\EfficiencyAnalysis\\EfficiencyAnalysis' . "\0" . 'scored', '' . "\0" . 'Domain\\Model\\EfficiencyAnalysis\\EfficiencyAnalysis' . "\0" . 'total', '' . "\0" . 'Domain\\Model\\EfficiencyAnalysis\\EfficiencyAnalysis' . "\0" . 'employeeId', '' . "\0" . 'Domain\\Model\\EfficiencyAnalysis\\EfficiencyAnalysis' . "\0" . 'status'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (EfficiencyAnalysis $proxy) {
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
    public function addReview(\Domain\Model\Assessment\AssessmentId $assessmentId, \Domain\Model\Assessment\Check $check, array $criteria): \Domain\Model\Assessment\Assessment
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addReview', [$assessmentId, $check, $criteria]);

        return parent::addReview($assessmentId, $check, $criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function removeReview(\Domain\Model\Assessment\AssessmentId $reviewId)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeReview', [$reviewId]);

        return parent::removeReview($reviewId);
    }

    /**
     * {@inheritDoc}
     */
    public function editReview(\Domain\Model\Assessment\AssessmentId $reviewId, \Domain\Model\Assessment\Check $check, array $criteria)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'editReview', [$reviewId, $check, $criteria]);

        return parent::editReview($reviewId, $check, $criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function isMaxReviewsAdded(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isMaxReviewsAdded', []);

        return parent::isMaxReviewsAdded();
    }

    /**
     * {@inheritDoc}
     */
    public function isCompleted(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isCompleted', []);

        return parent::isCompleted();
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): \Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisId
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
    public function getEmployeeId(): \Domain\Model\Employee\EmployeeId
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmployeeId', []);

        return parent::getEmployeeId();
    }

    /**
     * {@inheritDoc}
     */
    public function getScored(): ?float
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getScored', []);

        return parent::getScored();
    }

    /**
     * {@inheritDoc}
     */
    public function getTotal(): ?float
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTotal', []);

        return parent::getTotal();
    }

    /**
     * {@inheritDoc}
     */
    public function getAssessmentsCount(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAssessmentsCount', []);

        return parent::getAssessmentsCount();
    }

    /**
     * {@inheritDoc}
     */
    public function getAssessments(): \Doctrine\Common\Collections\Collection
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAssessments', []);

        return parent::getAssessments();
    }

    /**
     * {@inheritDoc}
     */
    public function getMonth(): \Domain\Model\EfficiencyAnalysis\Month
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMonth', []);

        return parent::getMonth();
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus(): \Domain\Model\EfficiencyAnalysis\Status
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatus', []);

        return parent::getStatus();
    }

}