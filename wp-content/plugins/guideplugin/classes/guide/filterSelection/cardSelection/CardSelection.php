<?php //declare(strict_types=1);

namespace Guideplugin\Guide\FilterSelection\CardSelection;

use Guideplugin\Guide\FilterSelection\FilterSelection;

class CardSelection extends FilterSelection
{

    private $type;
    private $behavior;
    private $cards;
    private $imageWidth;
    private $imageHeight;
    private $resultCount;

    public function __construct(string $type, string $behavior, array $cards, int $imageWidth, int $imageHeight, int $resultCount = null)
    {
        $this->type = $type;
        $this->behavior = $behavior;
        $this->cards = $cards;
        $this->imageWidth = $imageWidth;
        $this->imageHeight = $imageHeight;
        $this->resultCount = $resultCount;
    }

    public function deleteCard(int $index)
    {
        if (isset($this->cards[$index])) {
            unset($this->cards[$index]);
        }
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
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * @param mixed $cards
     *
     * @return self
     */
    public function setCards($cards)
    {
        $this->cards = $cards;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBehavior()
    {
        return $this->behavior;
    }

    /**
     * @return mixed
     */
    public function getImageWidth()
    {
        return $this->imageWidth;
    }

    /**
     * @return mixed
     */
    public function getImageHeight()
    {
        return $this->imageHeight;
    }

    /**
     * @return mixed
     */
    public function getResultCount()
    {
        return $this->resultCount;
    }

    /**
     * @param mixed $resultCount
     *
     * @return self
     */
    public function setResultCount($resultCount)
    {
        $this->resultCount = $resultCount;

        return $this;
    }
}
