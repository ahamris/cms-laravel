<x-layouts.admin title="Activity Log Details">
    <div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-info-circle text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Activity Log Details</h2>
                <p>View detailed information about this activity</p>
            </div>
        </div>
        <a href="{{ route('admin.activity-log.index') }}" 
           class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Back to List</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Activity Information --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Activity Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-700">Activity ID:</div>
                        <div class="w-2/3">
                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-semibold">#{{ $activityLog->id }}</span>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-700">Description:</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $activityLog->description }}</div>
                    </div>

                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-700">Performed At:</div>
                        <div class="w-2/3">
                            <div class="text-sm text-gray-900">{{ $activityLog->performed_at->locale('nl')->isoFormat('DD MMM YYYY HH:mm:ss') }}</div>
                            <div class="text-xs text-gray-500 mt-1">({{ $activityLog->performed_at->diffForHumans() }})</div>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-700">User:</div>
                        <div class="w-2/3">
                            <div class="text-sm font-medium text-gray-900">{{ $activityLog->user_name }}</div>
                            @if($activityLog->user_id)
                                <div class="text-xs text-gray-500 mt-1">User ID: {{ $activityLog->user_id }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-700">User Type:</div>
                        <div class="w-2/3">
                            @if($activityLog->user_type == 'admin')
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Admin</span>
                            @elseif($activityLog->user_type == 'system')
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">System</span>
                            @elseif($activityLog->user_type == 'user')
                                <span class="bg-primary/10 text-primary px-2 py-1 rounded-full text-xs font-semibold">User</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-semibold">{{ ucfirst($activityLog->user_type) }}</span>
                            @endif
                        </div>
                    </div>

                    @if($activityLog->subject_type)
                        <div class="flex">
                            <div class="w-1/3 text-sm font-medium text-gray-700">Subject Type:</div>
                            <div class="w-2/3">
                                <div class="bg-gray-100 text-gray-800 px-2 py-1 rounded font-mono text-xs inline-block">{{ $activityLog->subject_type }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ class_basename($activityLog->subject_type) }}</div>
                            </div>
                        </div>

                        @if($activityLog->subject_id)
                            <div class="flex">
                                <div class="w-1/3 text-sm font-medium text-gray-700">Subject ID:</div>
                                <div class="w-2/3">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">{{ $activityLog->subject_id }}</span>
                                </div>
                            </div>
                        @endif

                        @if($activityLog->subject)
                            <div class="flex">
                                <div class="w-1/3 text-sm font-medium text-gray-700">Subject Details:</div>
                                <div class="w-2/3">
                                    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-3 py-2 rounded-md text-xs">
                                        <strong class="font-semibold">{{ class_basename($activityLog->subject_type) }}:</strong>
                                        @if(isset($activityLog->subject->title))
                                            {{ $activityLog->subject->title }}
                                        @elseif(isset($activityLog->subject->name))
                                            {{ $activityLog->subject->name }}
                                        @else
                                            ID #{{ $activityLog->subject->id }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Related User Information --}}
            @if($activityLog->user)
                <div class="bg-gray-50/50 rounded-md border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">User Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex">
                            <div class="w-1/3 text-sm font-medium text-gray-700">Name:</div>
                            <div class="w-2/3 text-sm text-gray-900">{{ $activityLog->user->name }}</div>
                        </div>

                        <div class="flex">
                            <div class="w-1/3 text-sm font-medium text-gray-700">Email:</div>
                            <div class="w-2/3 text-sm text-gray-900">{{ $activityLog->user->email }}</div>
                        </div>

                        @if(method_exists($activityLog->user, 'isAdmin'))
                            <div class="flex">
                                <div class="w-1/3 text-sm font-medium text-gray-700">Role:</div>
                                <div class="w-2/3">
                                    @if($activityLog->user->isAdmin())
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Administrator</span>
                                    @else
                                        <span class="bg-primary/10 text-primary px-2 py-1 rounded-full text-xs font-semibold">User</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Metadata --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Metadata</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <div class="text-xs text-gray-500 uppercase mb-1">Created At</div>
                        <div class="text-sm text-gray-900">{{ $activityLog->created_at->locale('nl')->isoFormat('DD MMM YYYY HH:mm:ss') }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500 uppercase mb-1">Updated At</div>
                        <div class="text-sm text-gray-900">{{ $activityLog->updated_at->locale('nl')->isoFormat('DD MMM YYYY HH:mm:ss') }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500 uppercase mb-1">Database ID</div>
                        <div class="bg-gray-100 text-gray-800 px-2 py-1 rounded font-mono text-xs inline-block">{{ $activityLog->id }}</div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-2">
                    <a href="{{ route('admin.activity-log.index') }}" 
                       class="w-full bg-gray-500 text-white px-4 py-2 rounded-md text-sm flex items-center justify-center space-x-2">
                        <i class="fas fa-list"></i>
                        <span>View All Logs</span>
                    </a>

                    @if($activityLog->user_id)
                        <a href="{{ route('admin.activity-log.index', ['user_id' => $activityLog->user_id]) }}" 
                           class="w-full bg-blue-500 text-white px-4 py-2 rounded-md text-sm flex items-center justify-center space-x-2">
                            <i class="fas fa-user"></i>
                            <span>View User's Activities</span>
                        </a>
                    @endif

                    @if($activityLog->subject_type && $activityLog->subject_id)
                        <a href="{{ route('admin.activity-log.index', ['search' => class_basename($activityLog->subject_type) . ' ID #' . $activityLog->subject_id]) }}" 
                           class="w-full bg-primary text-white px-4 py-2 rounded-md text-sm flex items-center justify-center space-x-2">
                            <i class="fas fa-search"></i>
                            <span>View Subject Activities</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Timeline</h3>
                </div>
                <div class="p-6">
                    <div class="relative pl-8 space-y-6">
                        <div class="relative">
                            <div class="absolute left-[-32px] top-0 w-6 h-6 bg-primary rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-white text-xs"></i>
                            </div>
                            <div class="absolute left-[-26px] top-6 bottom-[-24px] w-0.5 bg-gray-200"></div>
                            <div>
                                <div class="text-xs text-gray-500 uppercase mb-1">Performed</div>
                                <div class="text-sm text-gray-900">{{ $activityLog->performed_at->diffForHumans() }}</div>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="absolute left-[-32px] top-0 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-database text-white text-xs"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 uppercase mb-1">Logged</div>
                                <div class="text-sm text-gray-900">{{ $activityLog->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.admin>
