<?php //declare(strict_types=1);

namespace Guideplugin\Guide\Guide;

class Guide
{

    private $id;
    private $title;
    private $description;
    private $filters;
    private $allFilters;

    public function __construct(int $id, string $title, string $description, array $filters, array $allFilters)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->filters = $filters;
        $this->allFilters = $allFilters;
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
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return mixed
     */
    public function getAllFilters()
    {
        return $this->allFilters;
    }
}
