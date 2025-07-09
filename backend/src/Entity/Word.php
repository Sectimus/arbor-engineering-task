<?php
declare(strict_types=1);

namespace Acme\CountUp\Entity;

use Acme\CountUp\Model\CharFrequency;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class Word
{
    public function __construct(string $term)
    {
        $this->term_length = strlen($term);
    
        foreach (new CharFrequency($term) as $char => $frequency) {   
            $property = 'l_'.$char;
            $this->{$property} = $frequency;
        }
    }

    // Again, we probably don't need the ID field here, the term field is implicitly unique.
    #[ORM\Id]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 45)]
    private string $term;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getTerm(): string
    {
        return $this->term;
    }

    public function setTerm(string $term): self
    {
        $this->term = $term;
        return $this;
    }

    // Forgive me father for I have sinned
    #[ORM\Column]
    private int $term_length=0;
    #[ORM\Column]
    private int $l_a=0;
    #[ORM\Column]
    private int $l_b=0;
    #[ORM\Column]
    private int $l_c=0;
    #[ORM\Column]
    private int $l_d=0;
    #[ORM\Column]
    private int $l_e=0;
    #[ORM\Column]
    private int $l_f=0;
    #[ORM\Column]
    private int $l_g=0;
    #[ORM\Column]
    private int $l_h=0;
    #[ORM\Column]
    private int $l_i=0;
    #[ORM\Column]
    private int $l_j=0;
    #[ORM\Column]
    private int $l_k=0;
    #[ORM\Column]
    private int $l_l=0;
    #[ORM\Column]
    private int $l_m=0;
    #[ORM\Column]
    private int $l_n=0;
    #[ORM\Column]
    private int $l_o=0;
    #[ORM\Column]
    private int $l_p=0;
    #[ORM\Column]
    private int $l_q=0;
    #[ORM\Column]
    private int $l_r=0;
    #[ORM\Column]
    private int $l_s=0;
    #[ORM\Column]
    private int $l_t=0;
    #[ORM\Column]
    private int $l_u=0;
    #[ORM\Column]
    private int $l_v=0;
    #[ORM\Column]
    private int $l_w=0;
    #[ORM\Column]
    private int $l_x=0;
    #[ORM\Column]
    private int $l_y=0;
    #[ORM\Column]
    private int $l_z=0;
    
}