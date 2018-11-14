<?php
declare(strict_types=1);

namespace Ciebit\Stories\Builders;

use DateTime;
use Exception;
use Ciebit\Stories\Builders\Builder;
use Ciebit\Stories\Status;
use Ciebit\Stories\Story;
use Ciebit\Stories\LanguageReference;

class FromArray implements Builder
{
    private $data; #:array

    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function build(): Story
    {
        if (
            ! is_array($this->data) OR
            ! isset($this->data['title'])
        ) {
            throw new Exception('ciebit.stories.builders.invalid', 3);
        }

        $story = new Story($this->data['title'], Status::DRAFT());

        isset($this->data['datetime'])
        && $story->setDateTime(new DateTime($this->data['datetime']));

        isset($this->data['id'])
        && $story->setId((int) $this->data['id']);

        isset($this->data['body'])
        && $story->setBody((string) $this->data['body']);

        isset($this->data['summary'])
        && $story->setSummary((string) $this->data['summary']);

        isset($this->data['uri'])
        && $story->setUri((string) $this->data['uri']);

        isset($this->data['views'])
        && $story->setViews((int) $this->data['views']);

        isset($this->data['language'])
        && $story->setLanguage($this->data['language']);
        
        isset($this->data['status'])
        && $story->setStatus(new Status((int) $this->data['status']));
        
        if (isset($this->data['languages_references'])) {
            $languageReferences = json_decode($this->data['languages_references'], true);
            foreach ($languageReferences as $languageCode => $id) {
                $story->addLanguageReference(new LanguageReference($languageCode, $id));
            }
        }

        return $story;
    }
}
