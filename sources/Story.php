<?php
declare(strict_types=1);

namespace Ciebit\Stories;

use DateTime;
use Exception;
use Ciebit\Stories\Status;

class Story
{
    private $body; #:string
    private $dateTime; #:DateTime
    private $id; #:int
    private $status; #:Status
    private $summary; #:string
    private $title; #:string
    private $uri; #:string
    private $views; #:int
    private $language; #string
    private $languageReferences; #array

    public function __construct(string $title, Status $status)
    {
        $this->body = '';
        $this->dateTime = new DateTime;
        $this->id = 0;
        $this->status = $status;
        $this->summary = '';
        $this->title = $title;
        $this->uri = '';
        $this->views = 0;
        $this->language = 'pt-br';
        $this->languageReferences = [];

        $this->valid();
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getLanguageReferences(): array
    {
        return $this->languageReferences;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function setDateTime(DateTime $dateTime): self
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setStatus(Status $status): self
    {
        $this->status = $status;
        $this->valid();
        return $this;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }

    public function setViews(int $total): self
    {
        $this->views = $total;
        return $this;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function setLanguageReferences(array $languageReference): self
    {
        $this->languageReferences[] = $languageReference;
        return $this;
    }

    private function valid(): self
    {
        if (
            $this->getStatus()->getValue() !== Status::ACTIVE
            OR $this->getTitle() != ''
            OR $this->getBody() != ''
            OR $this->getUri() != ''
        ) {
            return $this;
        }

        throw new Exception('ciebit.story.invalid', 1);
    }
}
