@if ($paginator->hasPages())
    <div class="flex mt-8 border-t pt-4">
        <div class="flex-1 text-left">
            @if ($paginator->onFirstPage())
                <eye-btn-link link="#" :disabled=true color="bg-brand" icon='@eyewitness_svg('double-left', 'svgcolor-white h-4 w-4')'>Previous</eye-btn-link>
            @else
                <eye-btn-link link="{{ $paginator->previousPageUrl() }}" :disabled=false color="bg-brand" icon='@eyewitness_svg('double-left', 'svgcolor-white h-4 w-4')'>Previous</eye-btn-link>
            @endif
        </div>
        <div class="flex-1 text-right">
            @if ($paginator->hasMorePages())
                <eye-btn-link link="{{ $paginator->nextPageUrl() }}" :disabled=false color="bg-brand" icon='@eyewitness_svg('double-right', 'svgcolor-white h-4 w-4')'>Load More</eye-btn-link>
            @else
                <eye-btn-link link="#" :disabled=true color="bg-brand" icon='@eyewitness_svg('double-right', 'svgcolor-white h-4 w-4')'>Load More</eye-btn-link>
            @endif
        </div>
    </div>
@endif
