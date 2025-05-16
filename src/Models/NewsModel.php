<?php
namespace News\Models;

use News\Core\Connection;

class NewsModel
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

	public function getAllNews(): array
    {
        return $this->connection->pdoSelect("SELECT news.*, users.email AS author FROM news JOIN users ON news.user_id = users.user_id ORDER BY created_at DESC");

    }

    public function getNewById(int $id): ?array
    {
        return $this->connection->pdoSelect("SELECT * FROM news WHERE news_id = ?",[$id]);
    }

    public function createNew(int $userId, string $title, string $content, string $intro, ?string $publishAt, ?string $imagePath): int
    {
      return  $this->connection->pdoQuery(
            "INSERT INTO news (user_id, title, content, intro, publish_at, image) VALUES (?, ?, ?, ?, ?, ?)",
            [$userId, $title, $content, $intro, $publishAt, $imagePath]
        );
    }

    public function updateNew(int $id, string $title, string $content, string $intro, ?string $publishAt, ?string $imagePath = null): int
    {
        if ($imagePath) {
            return $this->connection->pdoQuery(
                "UPDATE news SET title = ?, content = ?, intro = ?, publish_at = ?, image = ? WHERE news_id = ?",
                [$title, $content, $intro, $publishAt, $imagePath, $id]
            );
        } else {
            return $this->connection->pdoQuery(
                "UPDATE news SET title = ?, content = ?, intro = ?, publish_at = ? WHERE news_id = ?",
                [$title, $content, $intro, $publishAt, $id]
            );
        }
    }

    public function deleteNew(int $id): ?int{
        return $this->connection->pdoQuery("DELETE FROM news WHERE news_id = ?", [$id]);
    }

	public function getPublishedNews(): array{
		return $this->connection->pdoSelect("SELECT news.*, users.email AS author FROM news JOIN users ON news.user_id = users.user_id WHERE publish_at IS NOT NULL AND publish_at <= NOW() ORDER BY created_at DESC");
	}

	public function getPublishedNewById(int $id): ?array{
		return $this->connection->pdoSelect("SELECT news.*, users.email AS author FROM news JOIN users ON news.user_id = users.user_id WHERE news.news_id = ? AND publish_at IS NOT NULL AND publish_at <= NOW()", [$id]);
	}
}