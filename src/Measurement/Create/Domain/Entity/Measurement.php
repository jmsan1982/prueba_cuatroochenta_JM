<?php

namespace App\Measurement\Create\Domain\Entity;

use App\Sensors\Create\Domain\Entity\Sensor;
use App\Wines\GetAll\Domain\Entity\Wine;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * * @ORM\Table(name="measurement")
 */
class Measurement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Wines\GetAll\Domain\Entity\Wine", inversedBy="measurements")
     * @ORM\JoinColumn(name="id_wine", referencedColumnName="id", nullable=false)
     */
    private $wine;

    /**
     * @ORM\ManyToOne(targetEntity="App\Sensors\Create\Domain\Entity\Sensor")
     * @ORM\JoinColumn(name="id_sensor", referencedColumnName="id", nullable=false)
     */
    private $sensor;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $temperature;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $alcohol_content;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $ph;

    public function __construct(Wine $wine, Sensor $sensor, $color, $temperature, $alcohol_content, $ph)
    {
        $this->wine = $wine;
        $this->sensor = $sensor;
        $this->color = $color;
        $this->temperature = $temperature;
        $this->alcohol_content = $alcohol_content;
        $this->ph = $ph;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWine(): ?Wine
    {
        return $this->wine;
    }

    public function setWine(Wine $wine): self
    {
        $this->wine = $wine;
        return $this;
    }

    public function getSensor(): ?Sensor
    {
        return $this->sensor;
    }

    public function setSensor(Sensor $sensor): self
    {
        $this->sensor = $sensor;
        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getTemperature(): ?string
    {
        return $this->temperature;
    }

    public function setTemperature(string $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getAlcoholContent(): ?string
    {
        return $this->alcohol_content;
    }

    public function setAlcoholContent(string $alcohol_content): self
    {
        $this->alcohol_content = $alcohol_content;

        return $this;
    }

    public function getPh(): ?string
    {
        return $this->ph;
    }

    public function setPh(string $ph): self
    {
        $this->ph = $ph;

        return $this;
    }
}
