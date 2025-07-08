<x-base>
    <main class="w-screen h-screen bg-gradient-to-tr from-[#7fbdea] to-[#9980fc] flex items-center justify-center p-6">
        <x-card class="max-w-screen-lg bg-sky-100/20 flex-grow max-h-full overflow-y-scroll">
            <x-table class="table-fixed">
                <x-table-head>
                <tr>
                    <x-table-head-cell>Concept</x-table-head-cell>
                    <x-table-head-cell>Directe subtypen</x-table-head-cell>
                    <x-table-head-cell>Trans. subtypen</x-table-head-cell>
                </tr>
                </x-table-head>

                <x-table-body>
                    <tr :foreach="$concepts as $result">
                        <x-table-cell class="truncate">{{ $result->concept->naam }}</x-table-cell>
                        <x-table-cell class="flex flex-wrap gap-1">
                            <x-badge class="truncate" :foreach="$result->concept->subtypen as $subtype">{{ $subtype->naam }}</x-badge>
                        </x-table-cell>
                        <x-table-cell>{{ count($result->transitiveSubtypes) }}</x-table-cell>
                    </tr>
                </x-table-body>
            </x-table>

            <x-pagination :paginator="$concepts" />
        </x-card>
    </main>
</x-base>
