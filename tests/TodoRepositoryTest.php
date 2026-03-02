<?php

namespace Tests;

use App\TodoRepository;
use PDO;
use PHPUnit\Framework\TestCase;

class TodoRepositoryTest extends TestCase
{
    private PDO $pdo;
    private TodoRepository $repo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $this->pdo->exec('
            CREATE TABLE todos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                task TEXT NOT NULL,
                done INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');

        $this->repo = new TodoRepository($this->pdo);
    }

    public function testGetAllReturnsEmptyArrayWhenNoTodos(): void
    {
        $this->assertSame([], $this->repo->getAll());
    }

    public function testAddInsertsATodo(): void
    {
        $this->repo->add('Buy milk');

        $todos = $this->repo->getAll();
        $this->assertCount(1, $todos);
        $this->assertSame('Buy milk', $todos[0]['task']);
        $this->assertEquals(0, $todos[0]['done']);
    }

    public function testAddTrimsWhitespace(): void
    {
        $this->repo->add('  Trimmed task  ');

        $todos = $this->repo->getAll();
        $this->assertCount(1, $todos);
        $this->assertSame('Trimmed task', $todos[0]['task']);
    }

    public function testAddIgnoresEmptyString(): void
    {
        $this->repo->add('');
        $this->repo->add('   ');

        $this->assertSame([], $this->repo->getAll());
    }

    public function testToggleFlipsDoneStatus(): void
    {
        $this->repo->add('Toggle me');
        $todos = $this->repo->getAll();
        $id = (int) $todos[0]['id'];

        $this->assertEquals(0, $todos[0]['done']);

        $this->repo->toggle($id);
        $todos = $this->repo->getAll();
        $this->assertEquals(1, $todos[0]['done']);

        $this->repo->toggle($id);
        $todos = $this->repo->getAll();
        $this->assertEquals(0, $todos[0]['done']);
    }

    public function testDeleteRemovesATodo(): void
    {
        $this->repo->add('Delete me');
        $todos = $this->repo->getAll();
        $id = (int) $todos[0]['id'];

        $this->repo->delete($id);
        $this->assertSame([], $this->repo->getAll());
    }

    public function testGetAllReturnsNewestFirst(): void
    {
        $this->pdo->exec("INSERT INTO todos (task, created_at) VALUES ('First', '2025-01-01 00:00:00')");
        $this->pdo->exec("INSERT INTO todos (task, created_at) VALUES ('Second', '2025-01-02 00:00:00')");
        $this->pdo->exec("INSERT INTO todos (task, created_at) VALUES ('Third', '2025-01-03 00:00:00')");

        $todos = $this->repo->getAll();
        $this->assertSame('Third', $todos[0]['task']);
        $this->assertSame('Second', $todos[1]['task']);
        $this->assertSame('First', $todos[2]['task']);
    }
}
