<?php //declare(strict_types=1);

namespace Guideplugin\Guide\Filter;

use Guideplugin\Guide\FilterSelection\FilterSelection;

class Filter
{
    private $id;
    private $title;
    private $description;
    private $type;
    private $selection;
    private $uniqueIdentifier;
    private $inputRequired;
    private $nextOnSelect;
    private $showInProgress;
    private $index;
    private $values;
    private $isFirst;
    private $isLast;

    public function __construct(int $id, string $title, string $description, string $type, FilterSelection $selection, string $uniqueIdentifier, $inputRequired, $nextOnSelect, $showInProgress, int $index, array $values = array(), $isFirst = false, $isLast = false)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->type = $type;
        $this->selection = $selection;
        $this->uniqueIdentifier = $uniqueIdentifier;
        $this->inputRequired = $inputRequired;
        $this->nextOnSelect = $nextOnSelect;
        $this->showInProgress = $showInProgress;
        $this->index = $index;
        $this->values = $values;
        $this->isFirst = $isFirst;
        $this->isLast = $isLast;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getSelection()
    {
        return $this->selection;
    }

    /**
     * @return mixed
     */
    public function getUniqueIdentifier()
    {
        return $this->uniqueIdentifier;
    }

    /**
     * @return mixed
     */
    public function getInputRequired()
    {
        return $this->inputRequired;
    }

    /**
     * @return mixed
     */
    public function getNextOnSelect()
    {
        return $this->nextOnSelect;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @return mixed
     */
    public function getIsFirst()
    {
        return $this->isFirst;
    }

    /**
     * @return mixed
     */
    public function getIsLast()
    {
        return $this->isLast;
    }

    /**
     * @param mixed $isFirst
     *
     * @return self
     */
    public function setIsFirst($isFirst)
    {
        $this->isFirst = $isFirst;

        return $this;
    }

    /**
     * @param mixed $isLast
     *
     * @return self
     */
    public function setIsLast($isLast)
    {
        $this->isLast = $isLast;

        return $this;
    }

    /**
     * @param mixed $values
     *
     * @return self
     */
    public function setValues($values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowInProgress()
    {
        return $this->showInProgress;
    }
}
