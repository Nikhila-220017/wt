<?php
// ================================================================
//  lib/MongoDB.php
//
//  MongoDB Document Store
//  ─────────────────────
//  • When USE_REAL_MONGO = false  → stores data as JSON files
//    (works with zero setup, perfect for development/learning)
//  • When USE_REAL_MONGO = true   → uses the real MongoDB PHP driver
//    (install: pecl install mongodb  then  composer require mongodb/mongodb)
//
//  Either way, ALL your other PHP files stay exactly the same.
// ================================================================

define('USE_REAL_MONGO',    false);           // flip to true for real MongoDB
define('MONGO_URI',         'mongodb://localhost:27017');
define('MONGO_DB',          'kumaris_store');
define('COLLECTIONS_DIR',   __DIR__ . '/../collections/');

// ── JSON File Collection (dev mode) ─────────────────────────
class FileCollection {
    private string $file;
    private array  $data;

    public function __construct(string $name) {
        $this->file = COLLECTIONS_DIR . $name . '.json';
        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([]));
        }
        $this->data = json_decode(file_get_contents($this->file), true) ?? [];
    }

    private function save(): void {
        file_put_contents($this->file, json_encode($this->data, JSON_PRETTY_PRINT));
    }

    private function generateId(): string {
        return bin2hex(random_bytes(12));   // mimics MongoDB ObjectId hex string
    }

    /** Insert one document, returns the inserted document with _id */
    public function insertOne(array $doc): array {
        $doc['_id']       = $this->generateId();
        $doc['createdAt'] = date('c');
        $this->data[]     = $doc;
        $this->save();
        return $doc;
    }

    /** Find all documents matching a filter array (equality checks) */
    public function find(array $filter = []): array {
        if (empty($filter)) return $this->data;
        return array_values(array_filter($this->data, function($doc) use ($filter) {
            foreach ($filter as $key => $val) {
                if (!isset($doc[$key]) || $doc[$key] !== $val) return false;
            }
            return true;
        }));
    }

    /** Find the first document matching a filter */
    public function findOne(array $filter): ?array {
        $results = $this->find($filter);
        return $results[0] ?? null;
    }

    /** Update the first matching document; returns updated doc or null */
    public function updateOne(array $filter, array $update): ?array {
        foreach ($this->data as &$doc) {
            $match = true;
            foreach ($filter as $k => $v) {
                if (!isset($doc[$k]) || $doc[$k] !== $v) { $match = false; break; }
            }
            if ($match) {
                if (isset($update['$set']))  $doc = array_merge($doc, $update['$set']);
                if (isset($update['$inc'])) {
                    foreach ($update['$inc'] as $k => $v) $doc[$k] = ($doc[$k] ?? 0) + $v;
                }
                $doc['updatedAt'] = date('c');
                $this->save();
                return $doc;
            }
        }
        return null;
    }

    /** Delete the first matching document; returns true on success */
    public function deleteOne(array $filter): bool {
        foreach ($this->data as $i => $doc) {
            $match = true;
            foreach ($filter as $k => $v) {
                if (!isset($doc[$k]) || $doc[$k] !== $v) { $match = false; break; }
            }
            if ($match) {
                array_splice($this->data, $i, 1);
                $this->save();
                return true;
            }
        }
        return false;
    }

    /** Delete all matching documents */
    public function deleteMany(array $filter): int {
        $before = count($this->data);
        $this->data = array_values(array_filter($this->data, function($doc) use ($filter) {
            foreach ($filter as $k => $v) {
                if (!isset($doc[$k]) || $doc[$k] !== $v) return true;
            }
            return false;
        }));
        $this->save();
        return $before - count($this->data);
    }

    /** Count documents matching a filter */
    public function countDocuments(array $filter = []): int {
        return count($this->find($filter));
    }
}

// ── Real MongoDB Collection wrapper (production mode) ────────
class MongoCollection {
    private $col;
    public function __construct(string $name) {
        // Requires: pecl install mongodb && composer require mongodb/mongodb
        $client    = new MongoDB\Client(MONGO_URI);
        $this->col = $client->selectCollection(MONGO_DB, $name);
    }
    public function insertOne(array $doc): array {
        $result = $this->col->insertOne($doc);
        $doc['_id'] = (string)$result->getInsertedId();
        return $doc;
    }
    public function find(array $filter = []): array {
        return iterator_to_array($this->col->find($filter), false);
    }
    public function findOne(array $filter): ?array {
        $doc = $this->col->findOne($filter);
        return $doc ? (array)$doc : null;
    }
    public function updateOne(array $filter, array $update): ?array {
        $this->col->updateOne($filter, $update);
        return $this->findOne($filter);
    }
    public function deleteOne(array $filter): bool {
        return $this->col->deleteOne($filter)->getDeletedCount() > 0;
    }
    public function deleteMany(array $filter): int {
        return $this->col->deleteMany($filter)->getDeletedCount();
    }
    public function countDocuments(array $filter = []): int {
        return $this->col->countDocuments($filter);
    }
}

// ── Factory — returns the right collection automatically ──────
function getCollection(string $name): FileCollection|MongoCollection {
    return USE_REAL_MONGO ? new MongoCollection($name) : new FileCollection($name);
}
