<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MeasurementRepository::class)
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
     * @ORM\Column(type="string", length=10)
     */
    private $year;

    /**
     * relation entity Wine
     * @ORM\ManyToOne(targetEntity="App\Entity\Wine")
     * @ORM\JoinColumn(name="id_wine", referencedColumnName="id", nullable=false)
     */
    private $id_wine;

    /**
     * Relation entity Sensor
     * @ORM\ManyToOne(targetEntity="App\Entity\Sensor")
     * @ORM\JoinColumn(name="id_sensor", referencedColumnName="id", nullable=false)
     */
    private $id_sensor;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getIdSensor(): ?int
    {
        return $this->id_sensor;
    }

    public function setIdSensor(int $id_sensor): self
    {
        $this->id_sensor = $id_sensor;

        return $this;
    }

    public function getIdWine(): ?int
    {
        return $this->id_wine;
    }

    public function setIdWine(int $id_wine): self
    {
        $this->id_wine = $id_wine;

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
