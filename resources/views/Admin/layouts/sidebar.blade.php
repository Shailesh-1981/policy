{{-- <div class="sidebar">
    <h2>Dashboard</h2>
    <a href="" class="active">All Users</a>
    <a href="">All Products</a>
    <a href="">Sign Out</a>
</div> --}}
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-80 bg-white shadow-md h-screen p-6">
        <h2 class="text-xl font-bold mb-4">Dashboard</h2>
        <ul class="space-y-2">
            <li>
                <a href="{{route('dashboard')}}" class="block p-2 rounded hover:bg-red-500  text-red-700">Home</a>
            </li>
            <li>
                <a href="{{route('policy-index')}}" class="block p-2 rounded hover:bg-red-500 text-red-700">Policies</a>
            </li>
            
            <li>
                <a href="{{route('employe-index')}}" class="block p-2 rounded hover:bg-red-500 text-red-700">Employe</a>
            </li>

        </ul>
    </aside>





