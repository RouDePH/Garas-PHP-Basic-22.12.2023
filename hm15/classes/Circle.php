<?php require_once APP_DIR . "classes/Figure.php";

class Circle extends Figure
{
    private float|int $radius;

    /**
     * @throws Exception
     * Radius must be a positive number
     */
    public function __construct(float|int $radius)
    {
        if ($radius <= 0) {
            throw new Exception("Radius must be a positive number.");
        }
        $this->setRadius($radius);
    }

    public function getArea(): float|int
    {
        return M_PI * pow($this->getRadius(), 2);
    }

    public function getPerimeter(): float|int
    {
        return 2 * M_PI * $this->getRadius();
    }

    /**
     * @throws Exception
     */
    public function setRadius(float|int $radius): void
    {
        if ($radius <= 0) {
            throw new Exception("Radius must be a positive number.");
        }
        $this->radius = $radius;
    }

    public function getRadius(): float|int
    {
        return $this->radius;
    }
}