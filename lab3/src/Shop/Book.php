<?php
declare(strict_types=1);

namespace Lab3\Shop;

final class Book extends Product
{
    public function __construct(
        string $id,
        string $name,
        float $basePrice,
        private string $author,
        private int $pages,
    ) {
        parent::__construct($id, $name, $basePrice);
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getCategory(): string
    {
        return 'Книги';
    }

    public function getAttributes(): array
    {
        return [
            'Автор'     => $this->author,
            'Страниц'   => (string) $this->pages,
        ];
    }

    public function getInfo(): string
    {
        return "Книга «{$this->name}», автор {$this->author}";
    }
}
