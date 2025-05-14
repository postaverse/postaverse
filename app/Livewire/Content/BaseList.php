<?php

namespace App\Livewire\Content;

use Livewire\Component;
use Livewire\WithPagination;

abstract class BaseList extends Component
{
    use WithPagination;

    // Return the fully qualified model class
    abstract protected function getModel(): string;

    // Return relationships to eager load
    protected function getRelations(): array
    {
        return [];
    }

    // Return pagination size
    protected function getPerPage(): int
    {
        return 32;
    }

    // Apply any additional query filters
    protected function applyFilters($query)
    {
        return $query;
    }

    // Return the view name for rendering
    abstract protected function view(): string;

    // Return the variable name to use in the view
    abstract protected function getVariableName(): string;

    public function render()
    {
        $modelClass = $this->getModel();
        $query = $modelClass::with($this->getRelations());
        $query = $this->applyFilters($query);
        $paginator = $query->latest()->paginate($this->getPerPage());

        // Transform items if needed
        $collection = $paginator->getCollection()->transform(function ($item) {
            return $this->transformItem($item);
        });
        $paginator->setCollection($collection);

        return view($this->view(), [
            $this->getVariableName() => $paginator,
        ])->layout('layouts.app');
    }

    // Hook for transforming each item before rendering
    protected function transformItem($item)
    {
        return $item;
    }
}
