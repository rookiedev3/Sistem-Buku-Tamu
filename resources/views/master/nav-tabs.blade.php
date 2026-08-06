<div class="d-flex gap-2 border-bottom pb-3 mb-4">
    <a href="{{ route('branches.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('branches*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('branches*') ? '#006B3F' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Branches
    </a>
    
    <a href="{{ route('products.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('products*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('products*') ? '#006B3F' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Products
    </a>
    
    <a href="{{ route('lead-sources.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('lead-sources*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('lead-sources*') ? '#006B3F' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Lead Sources
    </a>
    
    <a href="{{ route('visit-purposes.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('visit-purposes*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('visit-purposes*') ? '#006B3F' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Visit Purposes
    </a>
    
    <a href="{{ route('guest-categories.index') }}" 
       class="btn btn-sm px-3 fw-semibold {{ request()->routeIs('guest-categories*') ? 'text-white' : 'text-secondary' }}" 
       style="background-color: {{ request()->routeIs('guest-categories*') ? '#006B3F' : '#f1f5f9' }}; border: none; border-radius: 10px;">
       Guest Categories
    </a>
</div>