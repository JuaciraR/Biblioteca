<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Users Card -->
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h3 class="card-title">Users</h3>
                <p>Manage system users.</p>
                <div class="card-actions justify-end">
                    <button class="btn btn-primary btn-sm">View</button>
                </div>
            </div>
        </div>

        <!-- Profiles Card -->
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h3 class="card-title">Profiles</h3>
                <p>Update profiles and passwords.</p>
                <div class="card-actions justify-end">
                    <button class="btn btn-secondary btn-sm">Edit</button>
                </div>
            </div>
        </div>

        <!-- Reports Card -->
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h3 class="card-title">Reports</h3>
                <p>View system statistics.</p>
                <div class="card-actions justify-end">
                    <button class="btn btn-accent btn-sm">View</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
