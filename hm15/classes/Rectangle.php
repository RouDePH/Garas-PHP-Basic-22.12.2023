<?php require_once APP_DIR . "classes/Figure.php";

class Rectangle extends Figure
{

    private float|int $length;
    private float|int $width;

    /**
     * @throws Exception
     * Length and width must be positive numbers
     */
    public function __construct(float|int $length, float|int $width)
    {
        if ($length <= 0 || $width <= 0)
            throw new Exception("Length and width must be positive numbers.");
        $this->setLength($length);
        $this->setWidth($width);
    }

    public function getArea(): float|int
    {
        return $this->getLength() * $this->getWidth();
    }

    public function getPerimeter(): float|int
    {
        return 2 * ($this->getLength() + $this->getWidth());
    }

    /**
     * @throws Exception
     */
    private function setLength(float|int $length): void
    {
        if ($length <= 0)
            throw new Exception("Length must be positive number.");
        $this->length = $length;
    }

    /**
     * @throws Exception
     */
    private function setWidth(float|int $width): void
    {
        if ($width <= 0)
            throw new Exception("Width must be positive number.");
        $this->width = $width;
    }

    public function getLength(): float|int
    {
        return $this->length;
    }

    public function getWidth(): float|int
    {
        return $this->width;
    }
}