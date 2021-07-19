<?php //declare(strict_types=1);

namespace Guideplugin\Guide\Facet;

class Facet
{
    private $id;
    private $title;
    private $slug;
    private $type;
    private $source;

    public function __construct(int $id, string $title, string $slug, string $type, string $source)
    {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->type = $type;
        $this->source = $source;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }
}
