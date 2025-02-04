<?php

namespace App\Imports;

use App\Models\Fabric;
use App\Models\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FabricsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Check if the fabric title already exists
        $existingFabric = Fabric::where('title', $row['title'])->first();
        $collection = Collection::firstOrCreate(['title' => $row['collection_title']]);
        if ($existingFabric) {
            // If fabric exists, skip the import for this row
            return null;
        }

        return new Fabric([
            // 'collection_id' => $this->getCollectionId($row['collection_title']), // Convert collection title to ID
            'collection_id' => $this->getCollectionId($row['collection_title']), // Convert collection title to ID
            'title' => $row['title'],
            'threshold_price' => $row['threshold_price'],
            'image' => $row['image'] ?? null, // Handle missing image column
            'status' => strtolower($row['status']) === 'active' ? 1 : 0, // Convert Active/Inactive to 1/0
        ]);
    }

    private function getCollectionId($title)
    {
        $collection = Collection::where('title', $title)->first();
        return $collection ? $collection->id : null; // Return collection ID or null
    }
}
