<nav class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6" aria-label="Pagination">
    <div class="hidden sm:block">
        <p class="text-sm text-gray-700">
            Showing
            <span class="font-medium">{{ $paginator->getPageFrom() }}</span>
            to
            <span class="font-medium">{{ $paginator->getPageTo() }}</span>
            of
            <span class="font-medium">{{ $paginator->getTotal() }}</span>
            results
        </p>
    </div>
    <div class="flex flex-1 justify-between sm:justify-end">
        <a :if="$paginator->hasPreviousPage()" href="?page={{ $paginator->getPreviousPage() }}" class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus-visible:outline-offset-0">Previous</a>
        <a :if="$paginator->hasNextPage()" href="?page={{ $paginator->getNextPage() }}" class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus-visible:outline-offset-0">Next</a>
    </div>
</nav>
